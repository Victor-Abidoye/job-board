<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['job_listing_id', 'user_id', 'resume_path', 'cover_letter', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobListing(){
        return $this->belongsTo(JobListing::class);
    }
}
