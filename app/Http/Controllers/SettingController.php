<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Owner');
    }

    public function index()
    {
        $setting = StoreSetting::first();
        if (!$setting) {
            $setting = StoreSetting::create(['store_name' => 'Toko Saya']);
        }
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'service_charge' => 'required|numeric|min:0',
            'active_payment_methods' => 'nullable|array',
            'logo' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'receipt_footer' => 'nullable|string',
        ]);

        $setting = StoreSetting::first();
        $data = $request->except(['logo', '_token', '_method']);

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                ImageHelper::deleteImage($setting->logo_path);
            }
            $data['logo_path'] = ImageHelper::uploadAndConvertToWebp($request->file('logo'), 'store-logos', 'public');
        }

        $data['active_payment_methods'] = $request->active_payment_methods ?? [];

        $setting->update($data);

        return redirect()->route('settings.index')->with('success', 'Pengaturan toko berhasil diperbarui');
    }
}
