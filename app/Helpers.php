<?php
if (!function_exists('settings')) {
    function settings($key)
    {
        $setting = \DB::table('settings')->where('module_name', $key)->first();
        return (!empty($setting->id)) ? $setting->content : null;
    }
}


