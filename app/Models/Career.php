<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = ['name', 'type'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
