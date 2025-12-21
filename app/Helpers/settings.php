<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        // Cache settings to avoid query on every call
        $settings = Cache::rememberForever('app_settings', function () {
            return Setting::all()->pluck('value', 'key');
        });

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('get_menu')) {
    function get_menu($slug)
    {
        return Cache::rememberForever("menu_{$slug}", function () use ($slug) {
            return \App\Models\Menu::where('slug', $slug)->with(['items' => function ($query) {
                $query->orderBy('order');
            }])->first();
        });
    }
}
