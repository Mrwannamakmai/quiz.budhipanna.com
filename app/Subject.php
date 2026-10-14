<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['course_id', 'category', 'name', 'description', 'sort_order'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function examSets()
    {
        return $this->hasMany(ExamSet::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
