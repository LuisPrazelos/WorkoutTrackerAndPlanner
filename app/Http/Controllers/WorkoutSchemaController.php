<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutSchemaController extends Controller
{
    public function exportPdf(WorkoutSchema $workoutSchema)
    {
        // Controleer toegang
        $isOwner = $workoutSchema->user_id === Auth::id();
        $isTemplate = $workoutSchema->is_template;

        if (!$isOwner && !Auth::user()->isTrainer()) {
            abort(403);
        }

        $workoutSchema->load(['schemaExercises.exercise', 'user', 'exerciseLogs.exercise']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.workout-schema', [
            'schema' => $workoutSchema,
        ]);

        $filename = 'schema-' . str($workoutSchema->name)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}
