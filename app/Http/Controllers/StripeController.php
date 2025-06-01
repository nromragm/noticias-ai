<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Checkout\Session as StripeSession;

class StripeController extends Controller
{
    public function checkout()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => 499, // $4.99 USD
                    'product_data' => [
                        'name' => 'Suscripción Premium (GPT-4.5)',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.exito'),
            'cancel_url' => route('checkout.cancelado'),
            'metadata' => [
                'user_id' => Auth::id(),
            ]
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        // Marcar al usuario como premium
        $user = Auth::user();
        $user->is_premium = true;
        $user->save();

        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
