<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Routing\Controller;

class ShopController extends Controller
{
    public function dashboard()
    {
        return view('shop::admin.index');
    }
}
