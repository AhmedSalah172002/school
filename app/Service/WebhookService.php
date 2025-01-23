<?php

namespace App\Service;

use App\Mail\StudentJoinedMail;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookService
{

    public function webhook($payload = [])
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $secret = env('STRIPE_SECRET');

        $endpoint_secret = env('WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            http_response_code(400);
            echo json_encode(['Error parsing payload: ' => $e->getMessage()]);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            echo json_encode(['Error verifying webhook signature: ' => $e->getMessage()]);
            exit();
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->paymentIntentWebhook($event);
                break;

            default:
                echo 'Received unknown event type ' . $event->type;
        }
        http_response_code(200);
    }


    private function paymentIntentWebhook($event)
    {
        try {
            $paymentIntent = $event->data->object->metadata;
            if (empty($paymentIntent->user_id) || empty($paymentIntent->course_id)) {
                return;
            }

            $student = Student::where('user_id', $paymentIntent->user_id)->first();
            if (!$student) {
                Log::error('Student not found for user ID: ' . $paymentIntent->user_id);
                return;
            }

            DB::beginTransaction();

            $student->courses()->attach($paymentIntent->course_id);

            $schedule = Schedule::where('course_id', $paymentIntent->course_id)->first();

            if (!$schedule) {
                DB::rollBack();
                return;
            }

            $emails = json_decode($schedule->students_emails, true) ?? [];

            if (!in_array($student->user->email, $emails)) {
                $emails[] = $student->user->email;
            }

            $schedule->students_emails = json_encode($emails);
            $schedule->save();

            $course = Course::findOrFail($paymentIntent->course_id);

            Mail::to($student->user->email)->send(new StudentJoinedMail($student->user->username , $course->title));

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

}
