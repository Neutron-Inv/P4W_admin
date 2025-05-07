<?php


namespace App\Filters\App\Applicant;

use App\Filters\FilterBuilder;
use App\Models\App\Recruitment\HiringTeam;
use App\Repositories\Core\Status\StatusRepository;

class ApplicantFilter extends FilterBuilder
{
    public function appliedDate($qry)
    {
        $date = json_decode(htmlspecialchars_decode(request()->get('applied_date_range')), true);

        $qry->when($date, function ($qry) use ($date) {
            return $qry->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });

        return $this;
    }

    public function applicantJobPost($qry)
    {
        if(auth()->user()->hasRole('Candidate') && auth()->user()->can('can_view_applicant')) {
                auth()->user()->load('applicant');
                $qry->where('applicant_id', auth()->user()->applicant->id ?? null);
            return $this;
        } else if (!auth()->user()->isAppAdmin()) {
            if (auth()->user()->can('can_view_applicant')) {
                $qry->whereHas('recruiters', function ($qIn) {
                    $qIn->where('recruiter_id', auth()->id());
                });
                $qry->when(request()->get('job'), function ($qry) {
                    return $qry->where('job_post_id', request()->get('job'));
                });
            }
            return $this;
        } else {
            $qry->when(request()->get('job'), function ($qry) {
                return $qry->where('job_post_id', request()->get('job'));
            });

            return $this;
        }
    }

    public function jobApplicantStatus($qry)
    {
        $qry->when(request()->get('status'), function ($qry) {
            return $qry->where('status_id', request('status'));
        },
            function ($qry) {
                return $qry->where('status_id', '!=', resolve(StatusRepository::class)
                    ->getStatusId('job_applicant', 'status_disqualified'))->latest('id');
            }
        );

        return $this;
    }

    public function jobApplicantReview($qry)
    {
        $qry->when((isset(request()['review']) && request()->get('review') !== null), function ($qry) {
            return $qry->where('review', request()->get('review'));
        });

        return $this;
    }


}
