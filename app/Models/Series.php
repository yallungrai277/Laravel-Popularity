<?php

namespace App\Models;

use App\Visitable\Visitable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory, Visitable;

    protected $fillable = ['title', 'slug'];
}