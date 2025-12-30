<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingSaveRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SettingsController
{
    public function index(): View
    {
        $settings = Setting::paginate(Setting::PAGINATION_LIMIT);
        return view('admin.pages.settings.index', compact('settings'));
    }

    public function create(): View
    {
        return view('admin.pages.settings.create');
    }

    public function store(SettingSaveRequest $request): JsonResponse
    {
        $setting = new Setting($request->validated());
        $setting->save();

        return response()->json([
            'message' => 'Setting successfully created!',
            'redirect' => route('settings.index')
        ]);
    }

    public function edit(Setting $setting): View
    {
        return view('admin.pages.settings.update', compact('setting'));
    }

    public function update(SettingSaveRequest $request, Setting $setting): JsonResponse
    {
        $setting->fill($request->validated());
        $setting->save();

        return response()->json(['message' => 'Setting successfully updated!']);
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();
        return redirect()->route('settings.index');
    }
}
