<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $table = "users";
    protected $fillable = ['id', 'username', 'email', 'password', 'role'];
}
