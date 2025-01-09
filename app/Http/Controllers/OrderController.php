<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order_index()
    {
        return view('shop.order_index');
    }
}
