<?php
namespace App\Http\Controllers;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $item = MenuItem::findOrFail($request->menu_item_id);
        $cart = session()->get('cart', []);
        $key  = $item->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$key] = [
                'id'       => $item->id,
                'name'     => $item->name,
                'price'    => $item->price,
                'is_veg'   => $item->is_veg,
                'quantity' => $request->quantity ?? 1,
            ];
        }

        session()->put('cart', $cart);
        return response()->json([
            'success' => true,
            'count'   => collect($cart)->sum('quantity'),
            'message' => $item->name . ' added to cart!'
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$request->id])) {
            if ($request->quantity <= 0) {
                unset($cart[$request->id]);
            } else {
                $cart[$request->id]['quantity'] = $request->quantity;
            }
        }
        session()->put('cart', $cart);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return response()->json(['success' => true, 'total' => $total]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->id]);
        session()->put('cart', $cart);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return response()->json(['success' => true, 'total' => $total]);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('menu');
    }
}