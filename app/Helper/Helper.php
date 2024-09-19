<?php

namespace App\Helper;

use Illuminate\Support\Str;

class Helper
{
    // Upload Image
    public static function fileUpload($file, $folder, $name): ?string
    {
        // Check if $file is not null and is a valid uploaded file
        if (!$file || !$file->isValid()) {
            return null; // Or handle the error as needed
        }

        $imageName = Str::slug($name) . '.' . $file->extension();
        $file->move(public_path('uploads/' . $folder), $imageName);

        return 'uploads/' . $folder . '/' . $imageName;
    }


    // Make Slug
    public static function makeSlug($modal, $title): string
    {
        $slug = $modal::where('slug', Str::slug($title))->first();
        if ($slug) {
            $randomString = Str::random(5);
            $slug         = Str::slug($title) . $randomString;
        } else {
            $slug = Str::slug($title);
        }
        return $slug;
    }
}
