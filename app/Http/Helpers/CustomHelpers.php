<?php

use App\Models\LogManagement;
use Intervention\Image\ImageManager;

if (!function_exists('storeFile')) {
    function storeFile($file, $destinationPath)
    {
        $filename = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs($destinationPath, $filename);
        return $filename;
    }
}