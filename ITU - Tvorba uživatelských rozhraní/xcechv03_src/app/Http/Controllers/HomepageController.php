<?php
// HomepageController.php
//
// autor: Jan Procházka (xproch0g)
//

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\History;
use \App\Models\Item;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function print()
    {
        return view('invoice', [
            'carts' => Cart::all(),
        ]);
    }

    public function pay()
    {
        $carts = Cart::all();
        foreach ($carts as $cart)
        {
            History::create([
                'user_id' => auth()->user(),
                'change_type' => 3,
                'item_id' => $cart->product_id,
            ]);
        }

        Cart::truncate();
        return redirect('/');
    }
}
