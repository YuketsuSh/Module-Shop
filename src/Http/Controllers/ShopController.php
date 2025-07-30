<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Routing\Controller;

class ShopController extends Controller
{
    public function index()
    {
        return view('Shop::index');
    }
}