<?php

namespace Database\Seeders\App\Notification;

use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        NotificationEvent::withoutGlobalScope('name')->get()->map(function (NotificationEvent $event) {
            if ($this->checkCondition($event)) {
                [$name, $action] = explode('_', $event->name);
                $templates = [
                    'system' => '',
                    'subject' => '',
                    'content' => ''
                ];
                if (array_key_exists($event->name, $this->template())) {
                    $templates = $this->template()[$event->name];
                } elseif (array_key_exists($action, $this->template())) {
                    $templates = $this->template()[$action];
                }

                $mail = NotificationTemplate::query()->create([
                    'subject' => strtr($templates['subject'], [
                        '{resource}' => $name,
                        '{app_name}' => $event->type->alias == 'app' ? '{app_name}' : '{brand_name}'
                    ]),
                    'default_content' => strtr($templates['content'], [
                        '{resource}' => $name,
                        '{button_label}' => 'View ' . ucfirst($name)
                    ]),
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $database = NotificationTemplate::query()->create([
                    'subject' => null,
                    'default_content' => strtr($templates['system'], [
                        '{resource}' => $name
                    ]),
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $event->templates()->attach(
                    [$database->id, $mail->id]
                );

            } else if ($event->name == 'user_invitation') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'User invitation form {app_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>Hope this mail finds you well and healthy. We are informing you that you\'ve been invited to our application by {action_by}. It\'ll be a great opportunity to work with you.</p><br>
<p><a href="{invitation_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Accept Invitation</a></p><br>

<p></p><p>Thanks &amp; Regards,
</p><p>{app_name}</p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } else if ($event->name == 'password_reset') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Password reset link provided by {app_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>Your request for reset password has been approved from {app_name}. Press the button below to reset the password.</p><br>
<p><a href="{reset_password_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Reset password</a></p><br>

We are highly expecting you as soon as possible. Hope you\'ll join us.
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach([$mail->id]);

            } else if ($event->name == 'disqualification_mail_for_candidate') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Disqualified for the post {job_post}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {candidate_name}</span><br></p><p>Thank you for taking the time to attend the recruitment process for the position {job_post}. Our team appreciates your interest in the role and in our organisation.
<br><br>Unfortunately, on this occasion, your application was not successful, and we have appointed an applicant who we believe more closely matches the skills required for the job position.
<br><br>We really appreciate you taking the time to attend the recruitment process.
<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } else if ($event->name == 'create_event_mail_for_candidate') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => '{event_type} has been scheduled for the post {job_post}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {candidate_name}</span><br></p><p>Thank you for taking the time to attend the recruitment process for the position {job_post}. Our team appreciates your interest in the role and in our organisation.
<br><br>{event_type} has been scheduled for the post {job_post}.
<br><br>Description: {description}.
<br><br>Location: {location}.

<p>Start Time: <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{start_at_query}">{start_at} (UTC)</a> </p>
<p>End Time: <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{end_at_query}">{end_at} (UTC)</a></p>


<br><br>Zoom Meeting -
<br>Meeting ID: {zoom_meeting_id}.
<br>Meeting Topic: {topic}.
<br>Meeting Duration (min): {duration}.
<br><p><a href="{zoom_join_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Zoom Meeting Start URL</a></p>.

<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            }
            else if ($event->name == 'job_apply_response_for_candidate') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Thank you {candidate_name} for your application',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {candidate_name}</span><br></p><p>
Your application for {job_post} has been received successfully.
<br><br>We will get back to you as soon as possible.
<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } else if ($event->name == 'job_alert') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Don\'t Miss Out! New Job Posts Just Arrived at {app_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {candidate_name}</span><br></p><p>
We hope this message finds you well. We have some exciting news for you! New job openings have just been posted at JobPoint. If you\'re seeking new opportunities to set sail on a thrilling career journey, check out the latest job posts below:

<br><br>
{job_post_card}
<br><br>
<a href="{career_page_link}" style="cursor:pointer;  ">See all new jobs</a>

<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            }

        });
        $this->enableForeignKeys();
    }

    private function checkCondition($event)
    {
        return $event->name != 'user_invitation'
            && $event->name != 'password_reset'
            && $event->name != 'disqualification_mail_for_candidate'
            && $event->name != 'create_event_mail_for_candidate'
            && $event->name != 'job_apply_response_for_candidate'
            && $event->name != 'job_alert';
    }

    public function template()
    {
        return [
            'user_joined' => [
                'system' => 'A new user has been joined in {app_name}',
                'subject' => 'A new user has been joined in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>It\'s a piece of good news that a new user {name} has been joined in our application. Hope you will enjoy his work and collaborations.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">View Resource</a></p><br>

<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'user_invited' => [
                'system' => '{name} has been invited by {action_by}.',
                'subject' => 'A new user has been invited in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>It\'s a piece of good news that a new user {name} has been invited in our application, invited by {action_by}.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">View Resource</a></p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'created' => [
                'system' => 'A new {resource} named {name} has been created by {action_by}.',
                'subject' => 'A new {resource} has been created in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>It\'s a piece of good news that a new {resource} named {name} has been created in our application by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; ; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'updated' => [
                'system' => 'A {resource} named {name} has been updated by {action_by}.',
                'subject' => 'A {resource} has been updated in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>It\'s a piece of good news that a {resource} named {name} has been updated in our application by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>

<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'deleted' => [
                'system' => 'A {resource} named {name} has been deleted by {action_by}.',
                'subject' => 'A {resource} has been deleted in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>We are going to inform you that a {resource} has been deleted from our application by {action_by}.</p>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'confirmed' => [
                'system' => 'A {resource} named {name} has been confirmed by {action_by}.',
                'subject' => 'A {resource} has been confirmed',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>It\'s a piece of good news that a {resource} named {name} has been confirmed by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>

<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'sent' => [
                'system' => 'A {resource} named {name} has been sent successfully by {action_by}.',
                'subject' => 'A {resource} has been sent successfully',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>It\'s a piece of good news that a {resource} named {name} has been sent successfully by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>

<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'candidate_disqualified' => [
                'system' => '{candidate_name} has been disqualified by {action_by} for the post of {job_post}',
                'subject' => '{candidate_name} has been disqualified',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p> {candidate_name} has been disqualified by {action_by}. 
<br><br>Job post: {job_post}
<br><br>Disqualification reason: {disqualification_reason}

<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'event_created' => [
                'system' => '{event_type} with {candidate_name} at {event_time}',
                'subject' => 'Invitation for {event_type}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>{action_by} invites you to join {event_type} with {candidate_name} for the post of {job_post}.
<p>Time: <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{start_at_query}">{start_at} (UTC)</a> to <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{end_at_query}">{end_at} (UTC)</a></p>

<br>Location: {event_location}

<br><br>Zoom Meeting -
<br>Meeting ID: {zoom_meeting_id}.
<br>Meeting Topic: {topic}.
<br>Meeting Duration (min) : {duration}.
<br><p><a href="{zoom_join_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Zoom Meeting URL</a></p>.

<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'note_created' => [
                'system' => '{noted_by} added a note on {job_post} for {candidate_name}',
                'subject' => 'New note added by {noted_by}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>{noted_by} added a note on {job_post} for {candidate_name}.<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'job_applied' => [
                'system' => '{candidate_name} applied for the {job_post}',
                'subject' => 'A new candidate named {candidate_name} applied for the {job_post}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}</span><br></p><p>A new candidate named {candidate_name} applied for the {job_post}.
<br><br>Best regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
        ];
    }

}
