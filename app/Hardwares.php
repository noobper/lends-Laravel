<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hardwares extends Model
{
    protected $fillable = ['hardware_name','hardware_type','hardware_detail','hw_no','delected'];
}
