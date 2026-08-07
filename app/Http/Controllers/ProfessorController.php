<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
    // 1. Obtener la lista de todos los profesores
    public function index()
    {
        $profesores = User::where('role', 'profesor')->orderBy('name')->get();
        return response()->json($profesores);
    }

    // 2. Crear un profesor nuevo
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $profesor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'profesor' // Forzamos el rol para que no creen administradores
        ]);

        return response()->json(['message' => 'Profesor creado con éxito', 'profesor' => $profesor]);
    }

    // 3. Actualizar un profesor existente
    public function update(Request $request, $id)
    {
        $profesor = User::where('id', $id)->where('role', 'profesor')->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $profesor->id,
        ]);

        $profesor->name = $request->name;
        $profesor->email = $request->email;

        // Si la jefa escribió una contraseña nueva, la actualizamos
        if ($request->filled('password')) {
            $profesor->password = Hash::make($request->password);
        }

        $profesor->save();

        return response()->json(['message' => 'Profesor actualizado correctamente', 'profesor' => $profesor]);
    }

    // 4. Eliminar a un profesor
    public function destroy($id)
    {
        $profesor = User::where('id', $id)->where('role', 'profesor')->firstOrFail();
        $profesor->delete();

        return response()->json(['message' => 'Profesor eliminado del sistema']);
    }
}
