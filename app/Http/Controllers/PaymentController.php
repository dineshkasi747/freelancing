<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $cart  = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('menu');
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $api = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );

        $razorpayOrder = $api->order->create([
            'amount'   => $total * 100, // paise
            'currency' => 'INR',
            'receipt'  => 'rcpt_' . time(),
        ]);

        session()->put('razorpay_order_id', $razorpayOrder['id']);
        session()->put('pending_order_data', $request->all());

        return view('payment', [
            'razorpayOrderId' => $razorpayOrder['id'],
            'razorpayKeyId'   => config('services.razorpay.key_id'),
            'total'           => $total,
            'cart'            => $cart,
            'shopName'        => Setting::get('shop_name'),
            'customerName'    => $request->customer_name,
            'customerPhone'   => $request->customer_phone,
        ]);
    }

    public function verify(Request $request)
    {
        $api = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );

        try {
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);

            // Payment verified — create order
            $cart     = session()->get('cart', []);
            $total    = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            $data     = session()->get('pending_order_data', []);

            $order = Order::create([
                'customer_name'      => $data['customer_name'],
                'customer_phone'     => $data['customer_phone'],
                'table_number'       => $data['table_number'] ?? null,
                'delivery_address'   => $data['delivery_address'] ?? null,
                'order_type'         => $data['order_type'],
                'total_amount'       => $total,
                'notes'              => $data['notes'] ?? null,
                'status'             => 'pending',
                'payment_id'         => $request->razorpay_payment_id,
                'payment_status'     => 'paid',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_item_id' => $item['id'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                ]);
            }

            // WhatsApp notification
            $this->sendWhatsApp($order, $cart);

            session()->forget(['cart', 'razorpay_order_id', 'pending_order_data']);
            return redirect()->route('order.confirmation', $order);

        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', 'Payment failed! Please try again.');
        }
    }

    private function sendWhatsApp(Order $order, array $cart)
    {
        $whatsapp = Setting::get('whatsapp_number');
        if (!$whatsapp) return;
        $items = collect($cart)->map(fn($i) =>
            "{$i['quantity']}x {$i['name']} - ₹" . ($i['price'] * $i['quantity'])
        )->implode(', ');
        $message = urlencode(
            "🔔 NEW ORDER #{$order->id} ✅ PAID\n" .
            "👤 {$order->customer_name} | 📞 {$order->customer_phone}\n" .
            "📋 {$order->order_type}\n" .
            "🍽️ {$items}\n" .
            "💰 Total: ₹{$order->total_amount}"
        );
        $url = "https://api.callmebot.com/whatsapp.php?phone={$whatsapp}&text={$message}&apikey=YOUR_API_KEY";
        @file_get_contents($url);
    }
}