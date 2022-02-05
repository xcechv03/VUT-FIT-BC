<?php
// HistoryController.php
//
// autor: Jan Procházka (xproch0g)
//

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Item;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function show($id)
    {
        return view('history', [
            'histories' => History::all(),
            'item' => Item::find($id),
        ]);
    }
}
