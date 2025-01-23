<?php

namespace App\Console\Commands;

use App\Mail\TeacherReminderMail;
use App\Models\Course;
use App\Models\Schedule;
use App\Service\GoogleMeetService;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendScheduledEmails extends Command
{
    protected $signature = 'email:send-scheduled';

    protected $description = 'Send scheduled emails based on user-defined schedules';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now('Africa/Cairo');
        $day = $now->format('l');

        $schedules = Schedule::where('day', $day)
            ->get();

        foreach ($schedules as $schedule) {
            $scheduleTimeStr = trim($schedule->time);

            if ($this->isWithinFiveMinutesBeforeSchedule($scheduleTimeStr)) {
                $course = Course::findOrFail($schedule->course_id);

                if ($schedule->teacher_email) {
                    Mail::to($schedule->teacher_email)->send(new TeacherReminderMail($course->teacher->user->username, $course->title));
                }
                if ($schedule->students_emails) {
                    $students_emails = json_decode($schedule->students_emails, true);
                    foreach ($students_emails as $student_email) {
                        Mail::to($student_email)->send(new TeacherReminderMail('Student', $course->title));
                    }
                }

            }
        }

        $this->info('Scheduled emails sent successfully.');
    }

    private function isWithinFiveMinutesBeforeSchedule($scheduleTimeStr)
    {
        try {
            $now = Carbon::now('Africa/Cairo');
            $scheduleTime = Carbon::createFromFormat('H:i:s', $scheduleTimeStr, 'Africa/Cairo');

            $scheduleBeforeFiveMinutes = $scheduleTime->copy()->subMinutes(5);
            $scheduleBeforeFourMinutes = $scheduleTime->copy()->subMinutes(4);

            return $now->between($scheduleBeforeFiveMinutes, $scheduleBeforeFourMinutes);

        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            throw new InvalidFormatException("Invalid time format for schedule: {$scheduleTimeStr}");
        }
    }

}
