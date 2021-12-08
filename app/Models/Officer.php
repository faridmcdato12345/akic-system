<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Officer extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'students_id'
    ];

    public function students(){
        return $this->belongsTo(Student::class,'students_id','id');
    }
}
