<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, Settings $setting)
    {
        $request->validate([
            "value" => "required"
        ]);
        if($setting->type === "img") {
            if ($request->hasFile('value')) {
                $destination = 'images/' . $setting->value;
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('value');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('images/', $filename);
            }
            $setting->update([
                'value' => $request->hasFile('value') ? $filename : $setting->value,
            ]);
        } else {
            $setting->update([
                'value' => $request->value,
            ]);
        }
        
        return redirect()->back()->with('status', 'Setting Has Been updated');
    }
}
