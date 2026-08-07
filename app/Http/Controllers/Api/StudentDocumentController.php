<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentDocumentController extends Controller
{

    public function uploadDocuments(Request $request, int $studentId): JsonResponse
    {
        $request->validate([
            'photo'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kardex' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $student = Student::findOrFail($studentId);
        $user    = $request->user();

        $esJefe    = $user->role === 'jefe_carrera';
        $esPropio  = $student->user_id === $user->id;
        $esProfesor = $user->role === 'profesor' && $student->group && $student->group->user_id === $user->id;

        if (! $esJefe && ! $esPropio && ! $esProfesor) {
            return response()->json(['message' => 'No tienes permisos para modificar este expediente.'], 403);
        }

        return $this->processUpload($request, $student);
    }

    public function uploadMyDocuments(Request $request): JsonResponse
    {
        $request->validate([
            'photo'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kardex' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $user = $request->user();
        $student = $user->student;

        if (! $student) {
            return response()->json(['message' => 'No se encontró un expediente asociado a esta cuenta.'], 404);
        }

        return $this->processUpload($request, $student);
    }

    private function processUpload(Request $request, Student $student): JsonResponse
    {
        $uploaded = [];

        if ($request->hasFile('photo')) {
            if ($student->profile_photo_path) {
                Storage::disk('local')->delete($student->profile_photo_path);
            }

            $path = $request->file('photo')->store('profiles', 'local');
            $student->profile_photo_path = $path;
            
            $uploaded['photo'] = route('documents.show', ['filename' => basename($path), 'type' => 'profiles']);
        }

        if ($request->hasFile('kardex')) {
            if ($student->kardex_path) {
                Storage::disk('local')->delete($student->kardex_path);
            }

            $path = $request->file('kardex')->store('kardex', 'local');
            $student->kardex_path = $path;
            
            $uploaded['kardex'] = route('documents.show', ['filename' => basename($path), 'type' => 'kardex']);
        }

        $student->save();

        return response()->json([
            'message' => 'Archivos subidos correctamente',
            'files'   => $uploaded,
        ]);
    }

    public function serveDocument(Request $request, string $type, string $filename)
    {
        if (! in_array($type, ['profiles', 'kardex'])) {
            abort(404);
        }

        $path = $type . '/' . $filename;

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($path));
    }
}