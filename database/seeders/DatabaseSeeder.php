<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Career;
use App\Models\Group;
use App\Models\Student;
use App\Models\ScholarshipApplication;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Edurnet',
            'email' => 'edurnet@uptex.edu.mx',
            'password' => Hash::make('V7kN2!qL9#xP4'),
            'role' => 'jefe_carrera'
        ]);

        $ingenieria = Career::create([
            'name' => 'Ingeniería en Sistemas Computacionales',
            'type' => 'ingenieria'
        ]);

        $licenciatura = Career::create([
            'name' => 'Licenciatura en Administración',
            'type' => 'licenciatura'
        ]);
        
        $carreras = [$ingenieria->id, $licenciatura->id];

        $profesorGerardo = User::create([
            'name' => 'Gerardo',
            'email' => 'gerardo@uptex.edu.mx',
            'password' => Hash::make('R8@wM3#zH7!tQ5'),
            'role' => 'profesor'
        ]);

        $profesorOctavio = User::create([
            'name' => 'Octavio',
            'email' => 'octavio@uptex.edu.mx',
            'password' => Hash::make('yK9@pD6#nX8'),
            'role' => 'profesor'
        ]);

        $grupos = [
            Group::create(['name' => '1V11', 'user_id' => $profesorGerardo->id])->id,
            Group::create(['name' => '2V11', 'user_id' => $profesorGerardo->id])->id,
            Group::create(['name' => '1M11', 'user_id' => $profesorOctavio->id])->id,
            Group::create(['name' => '2M11', 'user_id' => $profesorOctavio->id])->id,
        ];

        $porcentajes = [25, 50, 75, 100];
        $motivos = [
            'Dificultad económica severa', 
            'Apoyo por discapacidad visual', 
            'Gastos de transporte desde zona rural', 
            'Apoyo por excelencia académica'
        ];

        for ($i = 1; $i <= 30; $i++) {
            $matricula = 132026000 + $i;
            $nombreAlumno = 'Alumno Prueba ' . $i;
            
            $alumno = User::create([
                'name' => $nombreAlumno,
                'email' => $matricula . '@alumno.uptex.edu.mx',
                'password' => Hash::make('Alumno_Prueba_Uptex_2026'),
                'role' => 'alumno'
            ]);

            $student = Student::create([
                'user_id' => $alumno->id,
                'career_id' => $carreras[array_rand($carreras)],
                'group_id' => $grupos[array_rand($grupos)],
                'name' => $nombreAlumno, 
                'declared_income' => rand(2000, 15000),
                'has_disability' => (bool)rand(0, 1)
            ]);

            ScholarshipApplication::create([
                'student_id' => $student->id,
                'matricula' => (string)$matricula,
                'requested_percentage' => $porcentajes[array_rand($porcentajes)],
                'justification' => $motivos[array_rand($motivos)],
                'status' => 'pendiente',
                'current_gpa' => rand(70, 100) / 10
            ]);
        }
    }
}