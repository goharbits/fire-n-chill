<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount, $currency = 'USD')
    {
        return number_format($amount, 2) . ' ' . strtoupper($currency);
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('toObject')) {
    function toObject($array)
    {
        return json_decode(json_encode($array));
    }
}

if (!function_exists('currentPage')) {
    function currentPage()
    {
        return Str::slug(Str::replace('.','-',Route::currentRouteName()));
    }
}

if (!function_exists('allCardImages')) {
    function allCardImages()
    {
        $files = File::files(resource_path('images/card-type'));
        $image_paths = [];
        foreach ($files as $file) {
            $image_paths[$file->getFilenameWithoutExtension()] = Vite::image('card-type/'.$file->getFilename());
        }

        return $image_paths;
    }
}