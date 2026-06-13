<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'description', 'location'];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function jobListings()
    {
       return $this->hasMany(JobListing::class);
    }
}
