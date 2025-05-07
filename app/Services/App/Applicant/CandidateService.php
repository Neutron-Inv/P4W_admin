<?php

namespace App\Services\App\Applicant;

use App\Helpers\Core\Traits\FileHandler;
use App\Helpers\Core\Traits\HasWhen;
use App\Helpers\Core\Traits\Helpers;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Services\App\AppService;
use App\Services\Core\Auth\Traits\HasUserActions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CandidateService extends AppService
{
    use FileHandler, Helpers, HasWhen, HasUserActions;

    public function __construct(Applicant $applicant)
    {
        $this->model = $applicant;
    }

    public function validateApplicant(): static
    {
        $basicInformation = json_decode(request()->basic_information, true);

        // Define validation rules for the JSON data
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:applicants,email',
            'gender' => 'nullable',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date|date_format:Y-m-d',
        ];

        $validator = Validator::make($basicInformation, $rules);

        if($validator->fails()){
            throw_if($validator->fails(),
                ValidationException::withMessages([
                    'errors' => $validator->errors()->getMessages()
                ]));
        }

        return $this;

    }

    public function storeApplicant(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $basicInformation = json_decode(request()->basic_information, true);

        return Applicant::query()->create($basicInformation);
    }
}
