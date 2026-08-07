<?php

namespace App\Exports;

use App\Models\ScholarshipApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScholarshipsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ScholarshipApplication::with(['student.career', 'student.group'])->get();
    }

    public function headings(): array
    {
        return [
            'Matrícula / Correo',
            'Nombre del Estudiante',
            'Carrera',
            'Grupo',
            'Promedio',
            'Comentario del Profesor',
            'Estado del Sistema'
        ];
    }

    public function map($app): array
    {
        $matricula = 'al' . str_pad($app->student->id, 5, '0', STR_PAD_LEFT) . '@alumno.uptex.edu.mx';

        $cumple = $app->student->has_disability ||
            ($app->student->career->type === 'ingenieria' && $app->current_gpa >= 8.5) ||
            ($app->student->career->type === 'licenciatura' && $app->current_gpa >= 9.5);

        return [
            $matricula,
            $app->student->name,
            $app->student->career->name,
            $app->student->group ? $app->student->group->name : 'Sin Grupo asignado',
            $app->current_gpa,
            $app->professor_comment ?? 'Sin recomendación',
            $cumple ? 'Aprobado' : 'Rechazado'
        ];
    }
}