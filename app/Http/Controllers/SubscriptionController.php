<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Mostra la pagina con i piani
    public function plans()
    {
        $team = backpack_user()->ownedTeams()->first();
        
        return view('subscription.plans', [
            'team' => $team,
            'onFreePlan' => $team->plan === 'free',
            'onProPlan' => $team->plan === 'pro',
        ]);
    }

    // Avvia il checkout per il piano Pro
    public function checkout(Request $request)
    {
        $team = backpack_user()->ownedTeams()->first();

        return $team->newSubscription('default', env('STRIPE_PRO_PRICE_ID'))
            ->checkout([
                'success_url' => route('subscription.success'),
                'cancel_url'  => route('subscription.plans'),
            ]);
    }

    // Gestisce il successo del pagamento
    public function success()
    {
        $team = backpack_user()->ownedTeams()->first();
        $team->update(['plan' => 'pro']);

        return redirect('/admin')->with('success', 'Abbonamento Pro attivato con successo!');
    }

    public function cancel()
    {
        $team = backpack_user()->ownedTeams()->first();
        
        // Controlla se esiste una subscription attiva
        $subscription = $team->subscription('default');
        
        if ($subscription) {
            $subscription->cancel();
        }
        
        // Aggiorna il piano a free in ogni caso
        $team->update(['plan' => 'free']);

        return redirect()->route('subscription.plans')
            ->with('success', 'Abbonamento cancellato. Ora sei nel piano Free.');
    }
}
