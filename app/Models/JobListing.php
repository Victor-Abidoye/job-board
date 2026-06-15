<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'category_id', 'title', 'description', 'type', 'location', 'salary_min', 'salary_max', 'is_remote', 'status', 'expires_at'];

    protected function casts()
    {
        return  [
            'is_remote' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
