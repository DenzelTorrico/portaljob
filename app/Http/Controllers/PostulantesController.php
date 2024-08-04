<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostulantesController extends Controller
{
    public function Applicationsview(){
        return view("applications");
    }
    public function listJobs()
{
    $userId = Auth::id();
    
    // Ordenar los trabajos por fecha de creación descendente
    $jobs = Jobs::with(['applications' => function($query) use ($userId) {
        $query->where('user_id', $userId);
    }])->orderBy('created_at', 'desc')->paginate(10);
    
    // Añadir información sobre si el usuario ya ha aplicado
    $jobs->getCollection()->transform(function ($job) use ($userId) {
        $job->has_applied = $job->applications->isNotEmpty();
        return $job;
    });
    
    return response()->json($jobs);
}

    public function applyJob(Request $request, $id)
    {
        Log::info('Iniciando applyJob', ['request' => $request->all()]);

        $userId = Auth::id();

    // Verificar si el usuario ya ha aplicado al trabajo
    $existingApplication = Applications::where('user_id', $userId)
        ->where('job_id', $id)
        ->exists();

    if ($existingApplication) {
        return response()->json(['message' => 'Ya has aplicado a este trabajo.'], 400);
    }

        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'message' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        Log::info('Datos validados', $validated);

        try {
            $path = $request->file('cv')->store('cvs', 'public');

            Log::info('Archivo CV almacenado', ['path' => $path]);

            $application = Applications::create([
                'job_id' => $validated['job_id'],
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'cv_path' => $path,
            ]);

            Log::info('Aplicación creada', ['application' => $application]);

            return response()->json($application);
        } catch (\Exception $e) {
            Log::error('Error aplicando al trabajo', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Error'], 500);
        }
    }
    public function listApplications()
    {
        $applications = Applications::where('user_id', Auth::id())
            ->with('job')
            ->paginate(10); 
    
        return response()->json($applications);
    }
}
