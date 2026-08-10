<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarshipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_name' => $this->student->name ?? ($this->student->user->name ?? 'Usuario sin nombre'),
            'career_name' => $this->student->career->name ?? 'General',
            'career_type' => $this->student->career->type ?? 'general',
            'matricula' => $this->matricula,
            'gpa' => $this->current_gpa,
            'current_gpa' => $this->current_gpa,
            'income' => $this->student->declared_income ?? 0,
            'has_disability' => (bool) ($this->student->has_disability ?? false),
            'scholarship_type' => $this->scholarship_type,
            'assigned_percentage' => $this->assigned_percentage,
            'status' => $this->status,
            'meets_academic_criteria' => $this->evaluateCriteria(),
            'professor_comment' => $this->professor_comment,
            'validated_economy' => (bool) $this->validated_economy,
            'validated_disability' => (bool) $this->validated_disability,
            'validated_grades' => (bool) $this->validated_grades,
            'justification' => $this->justification,
            'kardex_url' => $this->student->kardex_path ?? null,
            'photo_url' => $this->student->profile_photo_path ?? null,
            'extra_document_path' => $this->student->extra_document_path ?? null,
            'student' => [
                'kardex_path' => $this->student->kardex_path ?? null,
                'extra_document_path' => $this->student->extra_document_path ?? null,
                'profile_photo_path' => $this->student->profile_photo_path ?? null,
            ]
        ];
    }

    private function evaluateCriteria(): bool
    {
        if (!isset($this->student)) {
            return false;
        }

        if ($this->student->has_disability) {
            return true;
        }

        if (($this->student->career->type ?? '') === 'ingenieria' && $this->current_gpa >= 8.5) {
            return true;
        }

        if (($this->student->career->type ?? '') === 'licenciatura' && $this->current_gpa >= 9.5) {
            return true;
        }

        return false;
    }
}