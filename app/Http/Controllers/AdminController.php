<?php

namespace App\Http\Controllers;

use App\Models\Jobs;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listview(){
        return view("jobs");
    }
    public function listJobs()
    {
        $jobs = Jobs::paginate(10);
        return response()->json($jobs);
    }

    public function createJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        $job = Jobs::create($validated);

        return response()->json($job, 201);
    }

    public function showJob($id)
    {
        $job = Jobs::with(['applications.user.postulante'])
                   ->findOrFail($id);
    
        return response()->json($job);
    }

    public function deleteJob($id)
    {
        $job = Jobs::findOrFail($id);
        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}
