<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'career_id',
        'group_id',
        'has_disability',
        'declared_income',
        'profile_photo_path',
        'kardex_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function application(): HasOne
    {
        return $this->hasOne(ScholarshipApplication::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ScholarshipApplication::class);
    }
}
