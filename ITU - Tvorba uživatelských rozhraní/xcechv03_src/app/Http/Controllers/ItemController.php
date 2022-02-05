<?php
// ItemController.php
//
//


namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\History;
use App\Models\Item;
use \App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ItemController extends Controller
{
    public function index()
    {
        return view('homepage', [
            'items' => Item::latest()->filter(\request(['search', 'category', 'price', 'weight']))->get(),
            'carts' => Cart::all(),
            'categories' => Category::all()
        ]);
    }

    public function show($id)
    {
        return view('item', ['item' => Item::find($id), 'categories' => Category::all()]);
    }

    public function create()
    {
        return view('create_item', [
            'categories' => Category::all(),
        ]);
    }

    public function createInDb()
    {
        request()->validate([
            'name' => ['required', 'max:255'],
            'company' => ['required', 'max:255'],
            'num_of_items' => ['required', 'gt:0', 'lt:4000000'], // remake for max of unsigned int
            'weight' => ['required', 'gt:0', 'lt:4000000'],
            'price' => ['required', 'gt:0', 'lt:2000'],
        ]);

        Item::create([
            'product' => \request('name'),
            'company' => \request('company'),
            'num_of_items' => \request('num_of_items'),
            'weight_kg' => \request('weight'),
            'price' => \request('price'),
            'category_id' => \request('category'),
        ]);
        return redirect('/');
    }

    public function add()
    {
        return view('add_item', ['categories' => Category::all()]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required', 'max:255'],
            'company' => ['required', 'max:255'],
            'num_of_items' => ['required', 'gt:0', 'lt:4000000'], // remake for max of unsigned int
            'weight' => ['required', 'gt:0', 'lt:4000000'],
            'price' => ['required', 'gt:0', 'lt:4000000'],
        ]);

        $item = Item::find($id);
        $item->update([
            'product' => request('name'),
            'company' => request('company'),
            'num_of_items' => request('num_of_items'),
            'weight_kg' => request('weight'),
            'price' => \request('price'),
            'category_id' => request('category'),
        ]);

        $user = isset(auth()->user()->id) ? auth()->user()->id : null;

        History::create([
            'item_id' => $item->id,
            'user_id' => $user,
            'change_type' => 2,
        ]);

        return redirect('/item/' . $id);
    }

    public function delete($id)
    {
        $item = Item::find($id);
        $item->delete();

        return redirect('/');
    }

}
