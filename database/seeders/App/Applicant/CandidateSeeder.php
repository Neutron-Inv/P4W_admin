<?php


namespace Database\Seeders\App\Applicant;


use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\JobStage;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CandidateSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $user = User::where('email', 'candidate@demo.com')->first();
        $candidate = Applicant::query()->create([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'gender' => 'other',
        ]);
        $jobPostIds = JobPost::query()->pluck('id')->toArray();
        $count = count($jobPostIds);
        $jobApplicants = [];

        $end = rand(1, $count);

        for ($i = 0; $i < $end; $i++) {
            $jobPostId = $jobPostIds[$i];
            $jobStages = JobStage::query()->select('name', 'id')->where('job_post_id', $jobPostId)->get()->toArray();
            $randomIndex = rand(0, count($jobStages) - 1);
            $stageValue = $jobStages[$randomIndex];

            $statusName = strtolower(trim($stageValue['name']));
            switch ($statusName) {
                case 'new':
                    $statusName = 'status_new';
                    break;
                case 'hired':
                    $statusName = 'status_hired';
                    break;
                case 'disqualified':
                    $statusName = 'status_disqualified';
                    break;
                default:
                    $statusName = 'status_in_progress';
                    break;
            }

            $jobApplicants[] = [
                "applicant_id" => $candidate->id,
                "job_post_id" => $jobPostId,
                "current_stage_id" => $stageValue['id'],
                "status_id" => resolve(StatusRepository::class)->getStatusId('job_applicant', $statusName),
                "slug" => (string) Str::uuid(),
                "review" => (string) rand(0, 5),
                "created_at" => now(),
                "updated_at" => now()
            ];
        }

        JobApplicant::query()->insert($jobApplicants);

        $this->enableForeignKeys();
    }

}