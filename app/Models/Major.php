<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'name',
        'description',
        'logo',
        'website',
        'contact_email',
        'slogan',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(Classroom::class, 'major_id');
    }

    public function roleAssignments()
    {
        return $this->hasMany(RoleAssignment::class, 'major_id');
    }

    public function companyRelations(): HasMany
    {
        return $this->hasMany(CompanyRelation::class, 'major_id');
    }

    public function majorSubjects(): HasMany
    {
        return $this->hasMany(MajorSubject::class, 'major_id');
    }
    // Major.php
    public function allowedSubjects()
    {
        return $this->hasMany(SubjectMajorAllowed::class, 'major_id');
    }

    public function subjects() // existing many-to-many through major_subject
    {
        return $this->belongsToMany(Subject::class, 'major_subject', 'major_id', 'subject_id')
                    ->using(MajorSubject::class)
                    ->withTimestamps()
                    ->withPivot(['notes']);
    }
}
