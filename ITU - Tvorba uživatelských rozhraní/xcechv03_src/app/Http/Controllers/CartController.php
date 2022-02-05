<?php
// CartController.php
//
// autor: Vojtěch Orava (xorava02)
//


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Category;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add($id)
    {
        $Items_of_product = Item::find($id)->num_of_items;
        request()->validate([
            'order_number_items' => ['required', 'gt:0', 'lte:' . $Items_of_product],
        ]);

        Cart::create([
            'product_id' => $id,
            'number_of_items' => request('order_number_items'),
        ]);

        $item = Item::find($id);
        $item->update([
            'num_of_items' => $item->num_of_items - \request('order_number_items'),
        ]);

        History::create([
            'item_id' => $item->id,
            'user_id' => \request('user'),
            'change_type' => 1,
        ]);

        return redirect('/');
    }

    public function remove($id)
    {
        if($id == -1){
            $items = Cart::all();
            if($items != NULL){
                foreach($items as $i){
                    echo "<div class='text-lg 2xl:text-xl'>" . $i->number_of_items . "x </div>";
                    $product = \App\Models\Item::find($i->product_id);
                    echo "<div class='text-lg 2xl:text-xl col-span-3'>" . $product->product . "</div>";
                    echo "<div class='mb-2 text-lg 2xl:text-xl'><x-blue_button class='removeItem cursor-pointer font-bold bg-blue-900 px-2 rounded-xl text-white' data-id='".$i->id."'>X</x-blue_button></div>";
                }
            }
        }
        else{
            $cart_record = Cart::find($id);
            if($cart_record != NULL){
                $item = Item::find($cart_record->product_id);
                $item->update([
                    'num_of_items' => $item->num_of_items + $cart_record->number_of_items,
                ]);
                $cart_record->delete();

                $items = Cart::all();
                if($items != NULL){
                    foreach($items as $i){
                        echo "<div class='text-lg 2xl:text-xl'>" . $i->number_of_items . "x </div>";
                        $product = \App\Models\Item::find($i->product_id);
                        echo "<div class='text-lg 2xl:text-xl col-span-3'>" . $product->product . "</div>";
                        echo "<div class='mb-2 text-lg 2xl:text-xl'><x-blue_button class='removeItem cursor-pointer font-bold bg-blue-900 px-2 rounded-xl text-white' data-id='".$i->id."'>X</x-blue_button></div>";
                    }
                }
            }
        }
    }
}
