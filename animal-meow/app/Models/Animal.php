<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $appends = ['words'];

    public function getWordsAttribute()
    {
        return mb_str_split($this->language);
    }
}
