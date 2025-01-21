<?php

namespace App\Service;

use App\Models\Student;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookService
{

    public function webhook($payload = [])
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $secret = env('STRIPE_SECRET');
        // this is for local
        $endpoint_secret = 'whsec_9cc81324992ea05354c3c078aaa50514e7d1b1980925f112213a90ec1d44cbe3';

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
                $paymentIntent = $event->data->object->metadata;
                $student = Student::where('user_id', $paymentIntent->user_id)->first();
                $student->courses()->attach($paymentIntent->course_id);
                break;

            default:
                echo 'Received unknown event type ' . $event->type;
        }
        http_response_code(200);
    }

}
