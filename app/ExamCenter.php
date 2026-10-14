<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamCenter extends Model
{
    protected $fillable = ['name'];

    public function registrations()
    {
        return $this->hasMany(ExamRegistration::class);
    }
}
