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
            'success_url' => route('checkout.exito'). '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancelado'),
            'metadata' => [
                'user_id' => Auth::id(),
            ]
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $session_id = $request->get('session_id');
        if (!$session_id) {
            abort(403, 'No session_id provided');
        }

        // Configura la clave secreta de Stripe
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // Recupera la sesión de Stripe
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        // Verifica que el pago esté completado
        if ($session->payment_status === 'paid') {
            // Obtén el usuario desde los metadatos
            $userId = $session->metadata->user_id ?? null;
            $user = $userId ? \App\Models\User::find($userId) : Auth::user();

            if ($user && !$user->is_premium) {
                $user->is_premium = true;
                $user->save();
            }

            return view('checkout.success');
        }

        // Si el pago no está completado, muestra error
        abort(403, 'El pago no fue completado.');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
