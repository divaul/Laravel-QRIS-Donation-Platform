<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }


    public function index()
    {
        return view('donation.index');
    }


    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'message' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:1000',
        ]);


        $orderId = 'DONATE-' . time() . '-' . rand(1000, 9999);

        // Prepare transaction parameters
        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$validated['amount'],
            ],
            'customer_details' => [
                'first_name' => $validated['donor_name'],
                'email' => 'donor@saweria.test',
                'phone' => '081234567890',
            ],
            'item_details' => [
                [
                    'id' => 'donation-001',
                    'price' => (int)$validated['amount'],
                    'quantity' => 1,
                    'name' => 'Donasi - ' . $validated['donor_name'],
                ]
            ],
        ];

        try {
            // Charge dengan Core API Midtrans
            $charge = CoreApi::charge($params);


            $donation = Donation::create([
                'order_id' => $orderId,
                'donor_name' => $validated['donor_name'],
                'message' => $validated['message'],
                'amount' => $validated['amount'],
                'payment_type' => 'qris',
                'transaction_status' => 'pending',
                'transaction_id' => $charge->transaction_id ?? null,
                'qr_code' => $charge->actions[0]->url ?? null, // QRIS string
                'transaction_time' => now(),
            ]);

            return view('donation.qris', [
                'donation' => $donation,
                'qris' => $charge->actions[0]->url ?? null,
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()]);
        }
    }


    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? 'accept';


            $donation = Donation::where('order_id', $orderId)->first();

            if (!$donation) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update data dari notifikasi
            $donation->transaction_id = $notification->transaction_id;
            $donation->transaction_status = $transactionStatus;
            $donation->raw_notification = $notification->getResponse();

            // Handle status berdasarkan transaction_status
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept') {
                    $donation->transaction_status = 'success';
                    $donation->settlement_time = now();
                }
            } elseif ($transactionStatus == 'pending') {
                $donation->transaction_status = 'pending';
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $donation->transaction_status = 'failed';
            }

            $donation->save();

            return response()->json(['message' => 'Notification processed'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    public function checkStatus($orderId)
    {
        $donation = Donation::where('order_id', $orderId)->first();

        if (!$donation) {
            return response()->json(['error' => 'Donation not found'], 404);
        }

        return response()->json([
            'status' => $donation->transaction_status,
            'donation' => $donation,
        ]);
    }


    public function success($orderId)
    {
        $donation = Donation::where('order_id', $orderId)->first();

        if (!$donation) {
            abort(404, 'Donasi tidak ditemukan');
        }

        return view('donation.success', compact('donation'));
    }


    public function list()
    {
        $donations = Donation::latest()->get();
        return view('donation.list', compact('donations'));
    }
}
