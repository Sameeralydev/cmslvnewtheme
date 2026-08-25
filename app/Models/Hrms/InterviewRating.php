<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Model;

class InterviewRating extends Model
{
    protected $table = 'interview_ratings';
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'interview_date' => 'date',
            'salary_expectation' => 'string',
            'appearance_rating' => 'integer', 'communication_rating' => 'integer', 'reasoning_rating' => 'integer',
            'education_rating' => 'integer', 'job_knowledge_rating' => 'integer', 'work_experience_rating' => 'integer',
            'general_knowledge_rating' => 'integer', 'iq_level_rating' => 'integer', 'pose_maturity_rating' => 'integer',
            'personality_rating' => 'integer', 'total_points' => 'integer',
        ];
    }
}
