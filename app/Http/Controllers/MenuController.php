<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Setting;

class MenuController extends Controller
{
    public function home()
    {
        $specials = MenuItem::where('is_special', true)
                            ->where('is_available', true)
                            ->with('category')
                            ->take(6)->get();
        $shopName = Setting::get('shop_name', 'Tiffin Centre');
        $timings  = Setting::get('shop_timings');
        return view('home', compact('specials', 'shopName', 'timings'));
    }

        public function welcome()
    {
        $shopName = Setting::get('shop_name', 'Tiffin Shop');
        $timings  = Setting::get('shop_timings');
        return view('welcome', compact('shopName', 'timings'));
    }

    public function index()
    {
        $categories = Category::where('is_active', true)
                               ->with(['menuItems' => function($q) {
                                   $q->where('is_available', true);
                               }])
                               ->orderBy('sort_order')
                               ->get();
        $shopName = Setting::get('shop_name', 'Tiffin Centre');
        return view('menu', compact('categories', 'shopName'));
    }

    public function contact()
    {
        $shopName    = Setting::get('shop_name');
        $phone       = Setting::get('shop_phone');
        $address     = Setting::get('shop_address');
        $timings     = Setting::get('shop_timings');
        $whatsapp    = Setting::get('whatsapp_number');
        return view('contact', compact('shopName', 'phone', 'address', 'timings', 'whatsapp'));
    }
}