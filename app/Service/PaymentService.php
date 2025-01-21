<?php
namespace App\Service;

use App\Http\Controllers\Controller;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentService extends Controller
{
    protected $stripeSecretKey;
    public function __construct()
    {
        $this->stripeSecretKey = env('STRIPE_SECRET');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    public function payCourse($course, $user)
    {
        return $this->handleRequest(function () use ($course, $user) {

            $line_items = $this->prepareLineItems($course);

            $payment_intent_data = $this->preparePaymentIntentData($user, $course);

            $data = $this->prepareSessionData($line_items, $payment_intent_data, $user);

            try {
                $checkout_session = Session::create($data);
                return $this->successResponse(['url' => $checkout_session->url]);
            } catch (\Exception $e) {
                return $this->errorResponse(['message' => 'Payment failed: ' . $e->getMessage()]);
            }
        });
    }

    private function prepareLineItems($course)
    {
        return [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => (float) $course->price * 100,
                    'product_data' => [
                        'name' => $course->title,
                    ],
                ],
                'quantity' => 1,
            ],
        ];
    }

    private function preparePaymentIntentData($user, $course)
    {
        return [
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
        ];
    }

    private function prepareSessionData($line_items, $payment_intent_data, $user)
    {
        return [
            'line_items' => $line_items,
            'mode' => 'payment',
            'payment_intent_data' => $payment_intent_data,
            'success_url' => env('APP_URL') . '/my-courses',
            'cancel_url' => env('APP_URL'),
            'customer_email' => $user->email,
        ];
    }

}
