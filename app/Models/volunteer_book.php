<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class volunteer_book extends Model
{
    use HasFactory;

    protected $table = 'volunteer_book';
    protected $fillable = ['volunteer_id','protest_id','is_validated'];

}
