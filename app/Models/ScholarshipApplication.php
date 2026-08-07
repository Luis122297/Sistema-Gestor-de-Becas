<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipApplication extends Model
{
    protected $fillable = [
        'student_id',
        'current_gpa',
        'status',
        'requested_percentage',
        'justification',
        'assigned_percentage',
        'professor_comment',
        'validated_economy',
        'validated_disability',
        'validated_grades',
    ];

    protected function casts(): array
    {
        return [
            'validated_economy'    => 'boolean',
            'validated_disability' => 'boolean',
            'validated_grades'     => 'boolean',
            'current_gpa'          => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
