<?php

namespace App\Http\Controllers\Post;

use App\Models\BlogPost;
use App\Models\NewsPost;
use Exception;

class PostHelpers {
    public static function getModelClass($type): string
    {
        return match ($type) {
            'news' => NewsPost::class,
            'blog' => BlogPost::class,
            default => throw new Exception('Invalid post type'),
        };
    }

    public static function getPermissionName($type, $action = 'view'): string
    {
        return "$action $type";
    }

    public static function getStoragePath($type): string
    {
        return "$type-images";
    }
}
