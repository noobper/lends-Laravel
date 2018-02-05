<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lends extends Model
{
    protected $fillable = ['id','user_id','hw_id','hw_out','hw_back','room','building','with_setup','status'];    
}
