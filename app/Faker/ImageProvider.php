<?php
namespace App\Faker;

use Faker\Provider\Base;

class ImageProvider extends Base
{
    public function randomImage($width = 640, $height = 480)
    {
        return "https://picsum.photos/$width/$height";
    }
}
