<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    protected $fillable = ['company_id', 'category_id', 'title', 'description', 'type', 'location', 'salary_min', 'salary_max', 'is_remote', 'status', 'expires_at'];

    protected function casts()
    {
        return  [
            'is_remote' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
