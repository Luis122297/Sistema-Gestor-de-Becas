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
            'student_name' => $this->student->name ?? ($this->student->user->name ?? 'Usuario sin nombre'),
            'career_name' => $this->student->career->name,
            'career_type' => $this->student->career->type,
            'gpa' => $this->current_gpa,
            'income' => $this->student->declared_income,
            'has_disability' => (bool) $this->student->has_disability,
            'status' => $this->status,
            'meets_academic_criteria' => $this->evaluateCriteria(),
            
            'professor_comment' => $this->professor_comment,
            'validated_economy' => (bool) $this->validated_economy,
            'validated_disability' => (bool) $this->validated_disability,
            'validated_grades' => (bool) $this->validated_grades,
            
            'requested_percentage' => $this->requested_percentage,
            'justification' => $this->justification,
            'kardex_url' => $this->student->kardex_path ? url('api/documents/kardex/' . basename($this->student->kardex_path)) : null,
            'photo_url' => $this->student->profile_photo_path ? url('api/documents/photo/' . basename($this->student->profile_photo_path)) : null,
        ];
    }

    private function evaluateCriteria(): bool
    {
        if ($this->student->has_disability) {
            return true;
        }

        if ($this->student->career->type === 'ingenieria' && $this->current_gpa >= 8.5) {
            return true;
        }

        if ($this->student->career->type === 'licenciatura' && $this->current_gpa >= 9.5) {
            return true;
        }

        return false;
    }
}