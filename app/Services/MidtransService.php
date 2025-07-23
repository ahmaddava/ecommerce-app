<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('services.midtrans.is_production');
        // Set sanitization on (default)
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createTransaction($order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'billing_address' => [
                    'address' => $order->shipping_address,
                ],
                'shipping_address' => [
                    'address' => $order->shipping_address,
                ]
            ],
            'item_details' => $this->getItemDetails($order),
            'enabled_payments' => ['qris', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Error creating Midtrans transaction: ' . $e->getMessage());
        }
    }

    private function getItemDetails($order)
    {
        $items = [];
        
        foreach ($order->orderItems as $item) {
            $items[] = [
                'id' => $item->product->id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }

        // Add shipping cost as separate item
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'shipping',
                'price' => $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        return $items;
    }

    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            throw new \Exception('Error getting transaction status: ' . $e->getMessage());
        }
    }

    public function handleNotification($notification)
    {
        try {
            $transaction = (object) Transaction::status($notification->order_id);
            
            $order = \App\Models\Order::where('order_number', $notification->order_id)->first();
            
            if (!$order) {
                throw new \Exception('Order not found');
            }

            if ($transaction->transaction_status == 'capture') {
                if ($transaction->fraud_status == 'challenge') {
                    $order->payment_status = 'challenge';
                } else if ($transaction->fraud_status == 'accept') {
                    $order->payment_status = 'paid';
                }
            } else if ($transaction->transaction_status == 'settlement') {
                $order->payment_status = 'paid';
            } else if ($transaction->transaction_status == 'pending') {
                $order->payment_status = 'pending';
            } else if ($transaction->transaction_status == 'deny') {
                $order->payment_status = 'failed';
            } else if ($transaction->transaction_status == 'expire') {
                $order->payment_status = 'expired';
            } else if ($transaction->transaction_status == 'cancel') {
                $order->payment_status = 'cancelled';
            }

            $order->payment_reference = $transaction->transaction_id;
            $order->save();

            return $order;
        } catch (\Exception $e) {
            throw new \Exception('Error handling notification: ' . $e->getMessage());
        }
    }
}

