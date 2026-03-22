<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.menuItem')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,preparing,ready,delivered,cancelled']);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send WhatsApp to customer
        $this->notifyCustomer($order);

        return back()->with('success', 'Order #'.$order->id.' status updated to '.ucfirst($request->status).'!');
    }

    private function notifyCustomer(Order $order)
    {
        $phone = $order->customer_phone;

        // Clean phone number — remove spaces, dashes, +
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if not present
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $statusMessages = [
            'preparing' => "✅ Hi {$order->customer_name}! Your order #{$order->id} is now being *prepared*. It'll be ready soon! 🍽️",
            'ready'     => "🔔 Hi {$order->customer_name}! Your order #{$order->id} is *ready*! " . ($order->order_type === 'dine_in' ? "Please collect from the counter." : "Out for delivery soon!") . " 🎉",
            'delivered' => "✅ Hi {$order->customer_name}! Your order #{$order->id} has been *delivered*. Enjoy your meal! 😊 Thank you for ordering from " . Setting::get('shop_name') . "!",
            'cancelled' => "❌ Hi {$order->customer_name}! Sorry, your order #{$order->id} has been *cancelled*. Please call us at " . Setting::get('shop_phone') . " for more info.",
        ];

        $message = $statusMessages[$order->status] ?? null;
        if (!$message) return;

        // CallMeBot API — completely free
        $apiKey  = Setting::get('callmebot_api_key', '');
        $encoded = urlencode($message);
        $url     = "https://api.callmebot.com/whatsapp.php?phone={$phone}&text={$encoded}&apikey={$apiKey}";

        // Fire and forget — don't slow down the admin
        @file_get_contents($url);
    }
}