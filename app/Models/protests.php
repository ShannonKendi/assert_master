<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class protests extends Model
{
    use HasFactory;

    protected $primaryKey = 'protest_id';
    public $incrementing = false;
    protected $table = "protests";
    protected $fillable = ['protest_id', 'title', 'description', 'venue', 'event_date', 'is_validated', 'creator_token'];
}
