<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;

class AppConfigController extends Controller
{
    public function index()
    {
        $configs = AppConfig::paginate(15);
        return view('admin.config.index', compact('configs'));
    }

    public function edit(AppConfig $config)
    {
        return view('admin.config.edit', compact('config'));
    }

    public function update(Request $request, AppConfig $config)
    {
        if ($config->key === 'subscription_price') {
            $validated = $request->validate([
                'value' => 'required|numeric|min:1',
            ]);

            $config->update([
                'value' => (string) ((int) round($validated['value'] * 100)),
            ]);

            return redirect()->route('admin.config.index')->with('success', 'Subscription price updated successfully!');
        }

        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $config->update($validated);
        return redirect()->route('admin.config.index')->with('success', 'Configuration updated successfully!');
    }
}
