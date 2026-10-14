<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EssayCriterion extends Model
{
    protected $table = 'essay_criteria';

    protected $fillable = [
        'question_id', 'name', 'description', 'max_points', 'sort_order',
    ];

    protected $casts = [
        'max_points'  => 'decimal:2',
        'sort_order'  => 'integer',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scores()
    {
        return $this->hasMany(EssayCriterionScore::class);
    }
}
