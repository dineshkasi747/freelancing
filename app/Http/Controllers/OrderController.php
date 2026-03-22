<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('menu');
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('checkout', compact('cart', 'total'));
    }

    public function track($token)
    {
        $order = Order::where('tracking_token', $token)
                    ->with('orderItems.menuItem')
                    ->firstOrFail();
        $shopName = Setting::get('shop_name');
        $whatsapp = Setting::get('whatsapp_number');
        return view('order-tracking', compact('order', 'shopName', 'whatsapp'));
    }

    public function place(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_phone' => 'required|string|max:15',
            'order_type'     => 'required|in:dine_in,delivery,takeaway',
        ]);

        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
            'customer_name'    => $request->customer_name,
            'customer_phone'   => $request->customer_phone,
            'table_number'     => $request->table_number,
            'delivery_address' => $request->delivery_address,
            'order_type'       => $request->order_type,
            'total_amount'     => $total,
            'notes'            => $request->notes,
            'status'           => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'menu_item_id' => $item['id'],
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],
            ]);
        }

        // WhatsApp notification to owner
        $this->sendWhatsAppNotification($order, $cart);

        session()->forget('cart');
        return redirect()->route('order.confirmation', $order);
    }

    public function confirmation(Order $order)
    {
        $order->load('orderItems.menuItem');
        $shopName = Setting::get('shop_name');
        $whatsapp = Setting::get('whatsapp_number');
        return view('order-confirmation', compact('order', 'shopName', 'whatsapp'));
    }

    private function sendWhatsAppNotification(Order $order, array $cart)
    {
        $whatsapp = Setting::get('whatsapp_number');
        if (!$whatsapp) return;

        $items = collect($cart)->map(fn($i) =>
            "{$i['quantity']}x {$i['name']} - ₹" . ($i['price'] * $i['quantity'])
        )->implode(', ');

        $message = urlencode(
            "🔔 NEW ORDER #{$order->id}\n" .
            "👤 {$order->customer_name} | 📞 {$order->customer_phone}\n" .
            "📋 Type: {$order->order_type}\n" .
            "🍽️ {$items}\n" .
            "💰 Total: ₹{$order->total_amount}"
        );

        // CallMeBot free WhatsApp API
        $url = "https://api.callmebot.com/whatsapp.php?phone={$whatsapp}&text={$message}&apikey=YOUR_API_KEY";
        @file_get_contents($url);
    }
}