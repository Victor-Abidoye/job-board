<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobListingRequest;
use App\Http\Requests\UpdateJobListingRequest;
use App\Models\JobListing;
use Inertia\Inertia;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobListiings = JobListing::all();

        return Inertia::render('JobListings/Index', [
            'jobListings' => $jobListiings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobListingRequest $request)
    {
        auth()->user()->company->jobListings()->create($request->validated());

        return redirect()->route('job-listings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobListing $jobListing)
    {
        return Inertia::render('JobListings/Show', ['jobListing' => $jobListing]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobListingRequest $request, JobListing $jobListing)
    {
        $field = $request->validated();

        // TODO: authorize via policy.

        $jobListing->update($field);
        return redirect()->route('job-listings.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobListing $jobListing)
    {
        $jobListing->delete();

        return redirect()->route('job-listings.index');
    }
}
