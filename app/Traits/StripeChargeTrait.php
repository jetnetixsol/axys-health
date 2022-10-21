<?php

namespace App\Traits;

use Exception;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

trait StripeChargeTrait
{
    private $stripe_key = '';
    private $stripe_secret = '';

    function setKeys($stripe_key, $stripe_secret)
    {
        $this->stripe_key = $stripe_key;
        $this->stripe_secret = $stripe_secret;
    }

    function createCustomer($token)
    {
        $data = [];
        try {
            Stripe::setApiKey($this->stripe_secret);
            $customer = Customer::create(array(
                'source' => $token
            ));

            $data['result'] = $customer->id;
            $data['success'] = 1;
        } catch (Exception $e) {
            $data['result'] = $e->getMessage();
            $data['success'] = 0;
        }
        return $data;
    }

    function chargeCustomer($customerID, $amount, $message = " ")
    {
        $data = [];
        try {
            $charge = Charge::create(array(
                'customer' => $customerID,
                'amount' => $amount * 100,
                'currency' => 'USD',
                'description' => $message
            ));

            $data['result'] = $charge->amount;
            $data['success'] = 1;
        } catch (Exception $e) {
            $data['result'] = $e->getMessage();
            $data['success'] = 0;
        }
        return $data;
    }
}
