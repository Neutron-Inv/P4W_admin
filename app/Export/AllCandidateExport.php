<?php

namespace App\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllCandidateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private Collection $applicants;

    public function __construct(Collection $applicants)
    {
        $this->applicants = $applicants;
    }

    public function headings(): array
    {
        return [
            __t('name'),
            __t('email'),
            __t('phone'),
            __t('gender'),
            __t('applied_job'),
        ];
    }

    public function array(): array
    {
        return
            $this->applicants->map(function ($applicant) {
                return $this->getApplicantRows($applicant, $applicant->totalApplication, $applicant->jobApplicants);
            })->toArray();
    }

    public function getApplicantRows($applicants, $totalApplication, $jobApplicants): array
    {
        return [
            $applicants->full_name,
            $applicants->email,
            $applicants->phone,
            $applicants->gender,
            $this->getJobPosts($jobApplicants),
        ];
    }

    private function getJobPosts($jobApplicants): string
    {
        $jobPosts = $jobApplicants->map(function ($jobApplicant) {
            return $jobApplicant->jobPost->name;
        })->toArray();

        return implode(' , ', $jobPosts);
    }
}