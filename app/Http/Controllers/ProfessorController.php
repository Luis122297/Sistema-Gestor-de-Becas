<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || $request->user()->role !== 'jefe_carrera') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $profesores = User::where('role', 'profesor')->orderBy('name')->get();
        return response()->json($profesores);
    }

    public function store(Request $request)
    {

        if (!$request->user() || $request->user()->role !== 'jefe_carrera') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $profesor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'profesor'
        ]);

        return response()->json(['message' => 'Profesor creado con éxito', 'profesor' => $profesor]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user() || $request->user()->role !== 'jefe_carrera') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $profesor = User::where('id', $id)->where('role', 'profesor')->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $profesor->id,
        ]);

        $profesor->name = $request->name;
        $profesor->email = $request->email;

        if ($request->filled('password')) {
            $profesor->password = Hash::make($request->password);
        }

        $profesor->save();

        return response()->json(['message' => 'Profesor actualizado correctamente', 'profesor' => $profesor]);
    }

    public function destroy(Request $request, $id)
    {

        if (!$request->user() || $request->user()->role !== 'jefe_carrera') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $profesor = User::where('id', $id)->where('role', 'profesor')->firstOrFail();
        $profesor->delete();

        return response()->json(['message' => 'Profesor eliminado del sistema']);
    }
}