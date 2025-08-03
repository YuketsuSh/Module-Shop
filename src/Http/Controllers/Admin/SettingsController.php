<?php

namespace Modules\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shop\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        return view('shop::admin.settings.index', [
            'stripe' => Setting::get('payment.stripe', []),
            'paypal' => Setting::get('payment.paypal', []),
            'general' => Setting::get('shop.general', []),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // Forcer les booleans depuis hidden fields
        $data['payment']['stripe']['enabled'] = isset($data['payment']['stripe']['enabled']) && $data['payment']['stripe']['enabled'];
        $data['payment']['paypal']['enabled'] = isset($data['payment']['paypal']['enabled']) && $data['payment']['paypal']['enabled'];
        $data['shop']['general']['tax_enabled'] = isset($data['shop']['general']['tax_enabled']) && $data['shop']['general']['tax_enabled'];
        $data['shop']['general']['shipping_enabled'] = isset($data['shop']['general']['shipping_enabled']) && $data['shop']['general']['shipping_enabled'];

        $validated = validator($data, [
            'payment.stripe.api_key' => 'nullable|string',
            'payment.stripe.secret_key' => 'nullable|string',
            'payment.stripe.enabled' => 'boolean',

            'payment.paypal.client_id' => 'nullable|string',
            'payment.paypal.secret' => 'nullable|string',
            'payment.paypal.enabled' => 'boolean',

            'shop.general.name' => 'nullable|string',
            'shop.general.email' => 'nullable|email',
            'shop.general.phone' => 'nullable|string',
            'shop.general.address' => 'nullable|string',
            'shop.general.currency' => 'nullable|string',
            'shop.general.tax_enabled' => 'boolean',
            'shop.general.tax_rate' => 'nullable|numeric|min:0|max:100',
            'shop.general.shipping_enabled' => 'boolean',
            'shop.general.shipping_flat_rate' => 'nullable|numeric|min:0',
        ])->validate();

        Setting::set('payment.stripe', $validated['payment']['stripe']);
        Setting::set('payment.paypal', $validated['payment']['paypal']);
        Setting::set('shop.general', $validated['shop']['general']);

        return redirect()->route('admin.shop.settings.index')->with('success', 'Paramètres mis à jour.');
    }
}
