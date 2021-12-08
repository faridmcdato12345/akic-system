<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'course_id',
        'house_id'
    ];

    public function courses(){
        return $this->belongsTo(Course::class);
    }

    public function houses(){
        return $this->belongsToMany(House::class);
    }

    public function fines(){
        return $this->belongsToMany(Fine::class);
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function getMiddleNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }
}
