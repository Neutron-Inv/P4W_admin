<?php

namespace App\Http\Controllers\App\JobPost;

use App\Exceptions\GeneralException;
use App\Filters\App\JobPost\JobPostFilter;
use App\Helpers\App\AppOnDeleteRelatedModels;
use App\Helpers\traits\SiteMapUpdateHelper;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Recruitment\JobStage;
use App\Models\Core\Status;
use App\Models\App\JobPost\JobPost;
use App\Http\Controllers\Controller;
use App\Models\App\Recruitment\HiringTeam;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\App\JobPost\JobPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AggregateJobController extends Controller
{
    use SiteMapUpdateHelper;

    public function __construct(JobPostService $service, JobPostFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    //--------------------AGGREGATE METHODS--------------------------------------------
    public function summery()
    {

        return $this->service
            ->filters($this->filter)
            ->select('id', 'company_location_id', 'posted_by', 'job_type_id', 'status_id', 'department_id', 'experience_level_id', 'name', 'working_arrangement', 'slug',
                'created_at', 'stages', 'salary', 'is_viewable', 'vacancy_count', 'last_submission_date')
            ->with([
                'location:id,address',
                'jobType:id,name',
                'experienceLevel:id,name',
                'department:id,name',
                'status:id,name,class,type',
                'postedBy:id,first_name,last_name,email',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with(['jobApplicantCount']);
                },
                'totalApplicants',
            ])
//            ->when(!auth()->user()->isAppAdmin(),
//                function ($query) {
//                    if (auth()->user()->can('can_view_job_post')) {
//                        $jobs = HiringTeam::where('recruiter_id', auth()->user()->id)->pluck('job_post_id')->toArray();
//                        $query->whereIn('id', $jobs);
//                    } else {
//                        $query->whereRaw('false');
//                    }
//                }
//            )

            ->when(auth()->user()->hasRole('Candidate') || !request()->get('status'), function ($query) {
                $query->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'));
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function viewOverview(JobPost $job_post)
    {
        $has_permission = false;
        if(auth()->user()->isAppAdmin()) {
            $has_permission = true;
        } else if (auth()->user()->can('can_view_job_post', 'can_view_job_overview')){
            $has_permission = JobPost::query()
                ->whereHas('recruiters', function ($qIn) {
                    $qIn->where('recruiter_id', auth()->id());
                })
                ->where('id', $job_post->id)
                ->exists();
        }
        if(!$has_permission) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }
        return view('dashboard.job-overview', ['job_post_id' => $job_post->id]);
    }

    public function overview(JobPost $job_post)
    {
        return JobPost::select('id', 'company_location_id', 'posted_by', 'job_type_id', 'status_id', 'name', 'slug', 'created_at', 'stages', 'working_arrangement', 'experience_level_id')
            ->with([
                'location:id,address',
                'experienceLevel:id,name',
                'jobType:id,name',
                'status:id,name,class,type',
                'postedBy:id,first_name,last_name,email',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with([
                            'jobApplicantCount',
                            'jobApplicants.appliedBy:id,first_name,last_name,email',
                            'jobApplicants.status:id,name,class,type'
                        ]);
                },

                'totalApplicants',
            ])
            ->when(!auth()->user()->isAppAdmin(),
                function ($query) {
                    if (auth()->user()->can('can_view_job_post', 'can_view_job_overview')) {
                        $query->whereHas('recruiters', function ($qIn) {
                            $qIn->where('recruiter_id', auth()->id());
                        });
                    } else {
                        $query->whereRaw('false');
                    }
                }
            )
            ->find($job_post->id);
    }

    public function applicants(Applicant $applicant, $jobPostId)
    {
        if (intval($jobPostId) < 1) {
            return [];
        }
        $jobPostId = intval($jobPostId);
        return $applicant
            ->with([
                    'jobApplicants' => function ($query) use ($jobPostId) {
                        $query->where('job_post_id', $jobPostId);
                    },
                    'jobApplicants.currentStage',
                    'jobApplicants.status',
                    'totalApplication'
                ]
            )
            ->whereHas('jobApplicants', function ($query) use ($jobPostId) {
                $query->where('job_post_id', intval($jobPostId));
            })
            ->when(!auth()->user()->isAppAdmin(),
                function ($query) use ($jobPostId) {
                    if (auth()->user()->can('can_view_job_post')) {
                        $jobs = HiringTeam::where('recruiter_id', auth()->user()->id)->pluck('job_post_id')->toArray();
                        in_array($jobPostId, $jobs) ? $query->whereRaw('true') : $query->whereRaw('false');

                    } else {
                        $query->whereRaw('false');
                    }
                })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function redirectUrl($job_slug)
    {
//        return route('public.jobPost.show_pob_post', ['job_slug' => $job_slug]);
        return redirect(route('public.jobPost.show_pob_post', ['job_slug' => $job_slug]));
    }

    public function showJobPost(JobPost $jobPost, Status $status, $jobPostSlug)
    {
        $status = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();

        $job = $jobPost
            ->select('id', 'slug', 'name', 'description', 'job_type_id',
                'company_location_id', 'job_post_settings', 'status_id', 'last_submission_date', 'vacancy_count', 'salary', 'department_id', 'is_viewable', 'working_arrangement', 'experience_level_id')
            ->with([
                'jobPostThumbnail',
                'status:id,name,type',
                'experienceLevel:id,name',
                'jobType:id,name',
                'department:id,name',
                'location:id,address',
                'totalApplicants'
            ])
            ->where('slug', $jobPostSlug)
            ->first();

        if ($job) {
            $response = $job;
            if (auth()->id() === null && ($job->last_submission_date < date("Y-m-d") || intval($job->status_id) != intval($status->id))) {
                return view('custom_errors.404', ['message' => __t('job_post_closed')]);
            }

        } else {
            return view('custom_errors.404', ['message' => __t('no_job_post_found')]);
        }

        $applyRoute = route('public.jobPost.apply_job_post', ['job_slug' => $job->slug]);
        $viewRoute = route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);

        return view('candidates.job-post', ['response' => $response, 'applyLink' => $applyRoute, 'viewLink' => $viewRoute]);
    }

    public function applyJobPost(JobPost $jobPost, Status $status, $jobPostSlug)
    {
        $status = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();

        $job = $jobPost
            ->with('jobPostThumbnail', 'location:id,address')
            ->select('id', 'slug', 'name', 'description', 'job_type_id',
                'company_location_id', 'apply_form_settings', 'status_id', 'last_submission_date', 'vacancy_count')
            ->where('slug', $jobPostSlug)
            ->first();

        $response = false;


        if ($job) {
            $response = $job;
            if (auth()->id() === null && ($job->last_submission_date < date("Y-m-d") || intval($job->status_id) != intval($status->id))) {
                return view('custom_errors.404', ['message' => __t('job_post_closed')]);
            }

        } else {
            return view('custom_errors.404', ['message' => __t('no_job_post_found')]);
        }

        $viewRoute = route('public.jobPost.apply_job_post', ['job_slug' => $job->slug]);

        return view('candidates.apply-form', ['response' => $response, 'viewLink' => $viewRoute]);
    }

    public function viewSetting($jobPostId)
    {
        return view('dashboard.job-settings', ['job_post_id' => $jobPostId]);
    }

    public function editJobPost(JobPost $job)
    {
        $previewLink = route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);
        return view('dashboard.job-editor', ['job' => $job->load('department:id,name', 'jobType:id,name', 'experienceLevel:id,name'), 'previewLink' => $previewLink]);
    }

    public function updateJobPost(Request $request, JobPost $job)
    {
        $request->validate([
            'job_post_settings' => 'required|array'
        ]);

        $this->service
            ->setModel($job)
            ->save($request->only(['job_post_settings', 'name', 'description', 'vacancy_count']));

        $this->notifyGoogleIndex($job, $job->status->name === 'status_closed' ? 'delete' : 'update');
        $this->updateSiteMap();

        return $job->wasChanged() ? updated_responses('job_post_template') : nothing_to_update_response(__t('nothing_to_update'),['job_post_settings' => $request->job_post_settings]);

    }

    public function publishJobPost(Request $request, JobPost $job)
    {
        $request->validate([
            'job_post_settings' => 'required|array'
        ]);

        $input = [];
        $input['job_post_settings'] = $request->job_post_settings;
        $input['status_id'] = resolve(StatusRepository::class)->getStatusId('job_post', 'status_open');

        $this->service
            ->setModel($job)
            ->save($input);

        return $job->wasChanged() ? updated_responses('job_post_template') : failed_responses(['job_post_settings' => $request->job_post_settings]);
    }

    public function editApplyForm(Request $request, JobPost $job)
    {
        $request->validate([
            'apply_form_settings' => 'required|array'
        ]);
        $this->service
            ->setModel($job)
            ->save($request->only(['apply_form_settings']));

        return $job->wasChanged() ? updated_responses('apply_form_updated') : failed_responses(['apply_form_settings' => $request->apply_form_settings]);
    }

    public function sharableLink(JobPost $job): string
    {
        return route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);
    }

    public function activateJobPost(JobPost $jobPost, Status $status)
    {
        $id = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();
        $this->service
            ->setModel($jobPost)
            ->save(['status_id' => $id]);

        return updated_responses('job_activated');
    }

    public function closeJobPost(JobPost $jobPost, Status $status)
    {
        $id = $status
            ->where('type', 'job_post')
            ->where('name', 'status_closed')
            ->first();
        $this->service
            ->setModel($jobPost)
            ->save(['status_id' => $id]);

        return updated_responses('job_close');
    }

    public function bulkAssignJobs(Request $request): array
    {
        $jobPostId = $request->job_post_id;
        $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $jobPostId)->first();

        $attachableIds = $request->is_all_selected ? Applicant::query()->get()->pluck('id')->toArray() : $request->attachable_ids;

        $assignableJobApplicant = collect($attachableIds)->map(function ($applicantId) use ($jobPostId, $stage) {
            $jobApplicant = JobApplicant::query()->where('job_post_id', $jobPostId)
                ->where('applicant_id', $applicantId)->exists();

            if (!$jobApplicant) {
                return [
                    'applicant_id' => $applicantId,
                    'job_post_id' => $jobPostId,
                    'current_stage_id' => optional($stage)->id,
                    'apply_form_setting' => '',
                    'status_id' => app(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
                    'slug' => Str::uuid(),
                ];
            }

            return null;
        })->filter()->toArray();

        JobApplicant::query()->insert($assignableJobApplicant);

        return custom_response('bulk_candidate_has_been_assigned_to_the_job');
    }

    public function bulkRetractJobs(Request $request, AppOnDeleteRelatedModels $model)
    {
        $attachableIds = $request->is_all_selected ? Applicant::query()->get()->pluck('id')->toArray() : $request->attachable_ids;

        $jobApplicants = JobApplicant::query()
            ->whereIn('applicant_id', $attachableIds)
            ->where('job_post_id', $request->job_post_id)
            ->get();

        foreach ($jobApplicants as $jobApplicant) {

            $model = $model->setModel($jobApplicant);
            $model->loadRelatedModelsOnDeleteJobApplicant();
            $model->removeData();
            $jobApplicant->delete();
        }

        return deleted_responses('job_applicant');
    }
}
