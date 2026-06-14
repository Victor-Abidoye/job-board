<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function jobListings():HasMany
    {
        return $this->hasMany(JobListing::class);
    }
}
