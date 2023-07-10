<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class volunteers extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $table = "volunteers";
    protected $fillable = ['id', 'username', 'email', 'password', 'phone_number', 'national_id', 'conduct_certificate','is_validated'];
}
