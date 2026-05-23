<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\TeamInvitationMail;
use App\Models\TeamInvitation;

class TeamInvitationController extends Controller
{
    // Admin invia un invito
    public function invite(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role'  => 'required|in:manager,client',
        ]);

        $team = backpack_user()->ownedTeams()->first();

        // Controlla se esiste già un invito pendente per questa email
        $existing = TeamInvitation::where('team_id', $team->id)
            ->where('email', $request->email)
            ->whereNull('accepted_at')
            ->first();

        if ($existing) {
            return back()->with('error', 'Esiste già un invito pendente per questa email.');
        }

        // Crea il token univoco
        $token = Str::random(32);

        // Crea l'invito
        $invitation = TeamInvitation::create([
            'team_id'    => $team->id,
            'email'      => $request->email,
            'token'      => $token,
            'role'       => $request->role,
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Invia email con il link di invito
     
        Mail::to($request->email)->send(new TeamInvitationMail($invitation));

        return back()->with('success', 'Invito inviato con successo!');
    }

    // Mostra la pagina degli inviti
    public function index()
    {
        $team = backpack_user()->ownedTeams()->first();
        
        $invitations = TeamInvitation::where('team_id', $team->id)
            ->whereNull('accepted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.invite', [
            'team'        => $team,
            'invitations' => $invitations,
        ]);
    }



    // Utente accetta l'invito e si registra
    public function accept(Request $request, string $token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        // Controlla se l'invito è scaduto
        if ($invitation->isExpired()) {
            return redirect('/')->with('error', 'Questo invito è scaduto.');
        }

        // Mostra il form di registrazione con il token
        return view('auth.register-invitation', [
            'invitation' => $invitation,
            'token'      => $token,
        ]);
    }

    // Completa la registrazione tramite invito
    public function register(Request $request, string $token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect('/')->with('error', 'Questo invito è scaduto.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crea l'utente
        $user = User::create([
            'name'     => $request->name,
            'email'    => $invitation->email,
            'password' => Hash::make($request->password),
        ]);

        // Assegna il ruolo
        $user->assignRole($invitation->role);

        // Aggiungi l'utente al team
        $invitation->team->users()->attach($user->id, [
            'role' => $invitation->role,
        ]);

        // Segna l'invito come accettato
        $invitation->update(['accepted_at' => now()]);

        // Logga l'utente1 senza MailVerification
        //auth()->login($user);

        // Logga l'utente
        auth()->login($user);

        // Invia email di verifica
        $user->sendEmailVerificationNotification();

        return redirect('/admin')->with('success', 'Benvenuto nel team!');
    }
}