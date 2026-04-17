<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan QRIS
     */
    public function showQrisSettings()
    {
        $qrisSetting = Setting::where('key', 'qris_image_path')->first();
        return view('petugas.pengaturan.qris', compact('qrisSetting'));
    }

    /**
     * Update gambar QRIS
     */
    public function updateQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $setting = Setting::where('key', 'qris_image_path')->first();

        if ($request->hasFile('qris_image')) {
            // Hapus file lama jika bukan default
            if ($setting->value && $setting->value !== 'qris/default-qris.png') {
                Storage::disk('public')->delete($setting->value);
            }

            // Simpan file baru
            $path = $request->file('qris_image')->store('qris', 'public');
            
            // Update database
            $setting->update(['value' => $path]);
        }

        return back()->with('success', 'Gambar QRIS berhasil diperbarui!');
    }
}
