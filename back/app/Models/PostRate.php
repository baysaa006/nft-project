<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostRate extends Model
{
    use HasFactory;
    protected $table = 'post_rates';
    public $timestamps = false;
}
