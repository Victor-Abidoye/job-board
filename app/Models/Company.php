<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'description', 'location'];

    public function user():BelongsTo
    {
       return $this->belongsTo(User::class);
    }

    public function jobListings():HasMany
    {
       return $this->hasMany(JobListing::class);
    }
}
