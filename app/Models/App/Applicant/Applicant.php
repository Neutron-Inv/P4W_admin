<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\ApplicantRelationship;
use App\Models\App\Applicant\Traits\Rules\ApplicantRules;
use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends AppModel
{
    use HasFactory, ApplicantRules, ApplicantRelationship;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth',
        'user_id',
        'phone',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime:Y-m-d'
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }
}
