<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\JobOfferRequest;
use App\Http\Resources\JobOfferResource;
use App\Mail\JobOffer\JobOfferMail;
use App\Models\Tenant\Career;
use App\Models\Tenant\JobOffer;
use App\Models\Tenant\User\User;
use Illuminate\Support\Facades\Mail;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobOffers = JobOffer::latest()->with('career','user')->get();
        return response()->json([
            'data' =>JobOfferResource::collection($jobOffers),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobOfferRequest $request)
    {
        $jobOffer = JobOffer::create($request->validated());
        $getUser = User::find($request->validated('user_id'));
        $getCareer = Career::with('benefits')->find($request->validated('career_id'));
        Mail::to($getUser->email)->send(new JobOfferMail($jobOffer->salary,$getCareer->name,$getCareer->description,$getCareer->benefits));
        return response()->json([
            'message' => trans('crud.created'),
            'data' =>new JobOfferResource($jobOffer),
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobOffer = JobOffer::find($id);
        return response()->json([
            'data' =>new JobOfferResource($jobOffer),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(JobOfferRequest $request, $id)
    {
        $jobOffer = JobOffer::find($id);
        $jobOffer->update($request->validated());
        return response()->json([
            'message' => trans('crud.updated'),
            'data' =>new JobOfferResource($jobOffer),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        JobOffer::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
