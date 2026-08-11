<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Models\Career;
use App\Models\Group;
use App\Http\Resources\ScholarshipResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        
        $query = ScholarshipApplication::with(['student.career', 'student.group'])->latest();

        if ($user->role === 'alumno') {
             $query->whereHas('student', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $applications = $query->get();
        return ScholarshipResource::collection($applications);
    }

    public function getMyStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->role !== 'alumno') return response()->json(['status' => 'ninguno']);

        $student = $user->student;
        if (!$student) return response()->json(['status' => 'ninguno']);

        $app = ScholarshipApplication::where('student_id', $student->id)->first();
        if (!$app) return response()->json(['status' => 'ninguno']);

        return response()->json(['status' => $app->status]);
    }

    public function export(Request $request)
    {
        if ($request->user()->role !== 'jefe_carrera') return response()->json(['message' => 'Acceso denegado'], 403);

        $applications = ScholarshipApplication::with('student.user')->get();
        $csvData = "\xEF\xBB\xBF" . "Alumno;Matricula;Promedio;Porcentaje_Asignado;Estatus\n";
        
        foreach ($applications as $app) {
            $nombre = $app->student && $app->student->user ? $app->student->user->name : 'Sin Nombre';
            $porcentaje = $app->assigned_percentage ?? 0;
            $csvData .= "{$nombre};{$app->matricula};{$app->current_gpa};{$porcentaje};{$app->status}\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="reporte_becas_uptex.csv"');
    }

    public function recommend(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->role !== 'profesor') return response()->json(['message' => 'Acción exclusiva para profesores'], 403);

        $application = ScholarshipApplication::findOrFail($id);

        $validated = $request->validate([
            'professor_comment'    => 'required|string|max:500',
            'validated_economy'    => 'boolean',
            'validated_disability' => 'boolean',
            'validated_grades'     => 'boolean',
        ]);

        $newStatus = $application->status;

        if (str_starts_with($validated['professor_comment'], '[RECHAZADO]')) {
            $newStatus = 'rechazada';
        }

        $application->update([
            'professor_comment'    => $validated['professor_comment'],
            'validated_economy'    => $validated['validated_economy'],
            'validated_disability' => $validated['validated_disability'],
            'validated_grades'     => $validated['validated_grades'],
            'status'               => $newStatus,
        ]);

        return response()->json(['message' => 'Recomendación guardada con éxito', 'data' => new ScholarshipResource($application)]);
    }

    public function submitRequest(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->role !== 'alumno') return response()->json(['message' => 'Solo los alumnos pueden solicitar la beca.'], 403);

        $validated = $request->validate([
            'matricula'        => 'required|numeric|digits:9', 
            'scholarship_type' => 'required|string|in:promedio,socioeconomica,discapacidad',
            'career_type'      => 'required|string|in:licenciatura,ingenieria',
            'current_gpa'      => 'required|numeric|min:0|max:10',
            'justification'    => 'required|string|max:1000',
        ]);

        $student = $user->student;
        $career = Career::where('type', $validated['career_type'])->first();
        
        if (!$career) {
            $career = Career::create(['name' => ucfirst($validated['career_type']) . ' General', 'type' => $validated['career_type']]);
        }

        if (!$student) {
            $group = Group::first();
            $student = $user->student()->create([
                'name'      => $user->name,
                'career_id' => $career->id,
                'group_id'  => $group ? $group->id : null,
            ]);
        } else {
            $student->update(['career_id' => $career->id]);
            
            if (!$student->group_id) {
                $group = Group::first();
                if ($group) {
                    $student->update(['group_id' => $group->id]);
                }
            }
        }

        $application = ScholarshipApplication::updateOrCreate(
            ['student_id' => $student->id],
            [
                'matricula'        => $validated['matricula'],
                'scholarship_type' => $validated['scholarship_type'],
                'justification'    => $validated['justification'],
                'status'           => 'pendiente',
                'current_gpa'      => $validated['current_gpa'],
            ]
        );

        return response()->json(['message' => 'Solicitud enviada correctamente', 'data' => new ScholarshipResource($application)]);
    }

    public function assignPercentage(Request $request, string $id): JsonResponse
    {
        if ($request->user()->role !== 'jefe_carrera') return response()->json(['message' => 'Acceso denegado'], 403);

        $validated = $request->validate([
            'assigned_percentage' => 'required|integer|min:0|max:100',
        ]);

        $application = ScholarshipApplication::findOrFail($id);

        if ($application->status === 'rechazada') {
            return response()->json(['message' => 'No puedes asignar un porcentaje. El alumno ya fue rechazado en la evaluación.'], 403);
        }

        $application->update([
            'assigned_percentage' => $validated['assigned_percentage'],
            'status'              => $validated['assigned_percentage'] > 0 ? 'aprobada' : 'rechazada'
        ]);

        return response()->json(['message' => 'Porcentaje asignado correctamente', 'data' => new ScholarshipResource($application)]);
    }
    
    public function myStatus(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'alumno') {
            return response()->json(['status' => 'ninguno']);
        }

        $student = $user->student;
        
        if (!$student) {
            return response()->json([
                'status' => 'ninguno',
                'message' => 'No has enviado ninguna solicitud aún.'
            ]);
        }
        
        $application = \App\Models\ScholarshipApplication::where('student_id', $student->id)->first();

        if (!$application) {
            return response()->json([
                'status' => 'ninguno',
                'message' => 'No has enviado ninguna solicitud aún.'
            ]);
        }

        return response()->json([
            'status' => $application->status,
            'application_status' => $application->status, 
            'assigned_percentage' => $application->assigned_percentage
        ]);
    }

    public function store(Request $request): JsonResponse { return response()->json(['message' => 'Not implemented'], 501); }
    public function show(string $id): JsonResponse { return response()->json(['message' => 'Not implemented'], 501); }
    public function update(Request $request, string $id): JsonResponse { return response()->json(['message' => 'Not implemented'], 501); }
    public function destroy(string $id): JsonResponse { return response()->json(['message' => 'Not implemented'], 501); }
}