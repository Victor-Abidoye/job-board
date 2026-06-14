<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    protected $fillable = ['job_listing_id', 'user_id', 'resume_path', 'cover_letter', 'status'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobListing():BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }
}
