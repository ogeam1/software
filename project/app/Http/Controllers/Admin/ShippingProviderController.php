<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingProviderSetting;
use Illuminate\Http\Request;

class ShippingProviderController extends Controller
{
    public function index()
    {
        // Ensure DHL and FedEx settings exist, create if not
        ShippingProviderSetting::firstOrCreate(['provider_name' => 'DHL']);
        ShippingProviderSetting::firstOrCreate(['provider_name' => 'FedEx']);

        $providers = ShippingProviderSetting::all();
        return view('admin.shipping_providers.index', compact('providers'));
    }

    public function edit($id)
    {
        $provider = ShippingProviderSetting::findOrFail($id);
        return view('admin.shipping_providers.edit', compact('provider'));
    }

    public function update(Request $request, $id)
    {
        $provider = ShippingProviderSetting::findOrFail($id);

        $validatedData = $request->validate([
            'api_key' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'default_service_type_code' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'other_settings' => 'nullable|json',
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        $provider->update($validatedData);

        return redirect()->route('admin.shipping.providers.index')->with('success', $provider->provider_name . ' settings updated successfully.');
    }
}
