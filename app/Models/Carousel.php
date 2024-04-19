<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    public function carouselable()
    {
        return $this->morphTo();
    }


    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
