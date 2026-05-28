<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use App\Models\Invoice;
use App\Models\ProjectRequest;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Disabilita gli observer durante il seeding
        \App\Models\Task::unsetEventDispatcher();
        \App\Models\Project::unsetEventDispatcher();
        
        // ============================
        // 1. CREA UTENTI
        // ============================

        $admin = User::create([
            'name'     => 'Marco Bianchi',
            'email'    => 'admin@demo.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $manager1 = User::create([
            'name'     => 'Laura Conti',
            'email'    => 'manager@demo.com',
            'password' => Hash::make('password'),
        ]);
        $manager1->assignRole('manager');

        $employee1 = User::create([
            'name'     => 'Luca Ferrari',
            'email'    => 'employee@demo.com',
            'password' => Hash::make('password'),
        ]);
        $employee1->assignRole('employee');

        $employee2 = User::create([
            'name'     => 'Anna Romano',
            'email'    => 'employee2@demo.com',
            'password' => Hash::make('password'),
        ]);
        $employee2->assignRole('employee');

        $client1 = User::create([
            'name'     => 'Giuseppe Esposito',
            'email'    => 'client@demo.com',
            'password' => Hash::make('password'),
        ]);
        $client1->assignRole('client');

        $client2 = User::create([
            'name'     => 'Sofia Marino',
            'email'    => 'client2@demo.com',
            'password' => Hash::make('password'),
        ]);
        $client2->assignRole('client');

        // ============================
        // 2. CREA TEAM
        // ============================

        $team = Team::create([
            'name'     => 'Bianchi Digital Agency',
            'slug'     => 'bianchi-digital-agency',
            'owner_id' => $admin->id,
            'plan'     => 'pro',
        ]);

        // Associa membri al team
        $team->users()->attach($admin->id, ['role' => 'owner']);
        $team->users()->attach($manager1->id, ['role' => 'manager']);
        $team->users()->attach($employee1->id, ['role' => 'employee']);
        $team->users()->attach($employee2->id, ['role' => 'employee']);
        $team->users()->attach($client1->id, ['role' => 'client']);
        $team->users()->attach($client2->id, ['role' => 'client']);

        // ============================
        // 3. CREA PROGETTI
        // ============================

        $project1 = Project::create([
            'team_id'    => $team->id,
            'manager_id' => $manager1->id,
            'name'       => 'E-commerce Moda Italiana',
            'description' => 'Sviluppo di un e-commerce completo per un brand di moda italiana con integrazione pagamenti e gestione magazzino.',
            'status'     => 'active',
            'deadline'   => now()->addMonths(2),
        ]);

        $project2 = Project::create([
            'team_id'    => $team->id,
            'manager_id' => $manager1->id,
            'name'       => 'App Mobile Ristorante',
            'description' => 'Applicazione mobile per prenotazioni e ordini online per catena di ristoranti.',
            'status'     => 'active',
            'deadline'   => now()->addMonths(3),
        ]);

        $project3 = Project::create([
            'team_id'    => $team->id,
            'manager_id' => $manager1->id,
            'name'       => 'Dashboard Analytics',
            'description' => 'Dashboard personalizzata per monitoraggio KPI aziendali in tempo reale.',
            'status'     => 'completed',
            'deadline'   => now()->subMonth(),
        ]);

        $project4 = Project::create([
            'team_id'    => $team->id,
            'manager_id' => $manager1->id,
            'name'       => 'Sito Web Studio Legale',
            'description' => 'Restyling completo del sito web con CMS personalizzato.',
            'status'     => 'draft',
            'deadline'   => now()->addMonths(4),
        ]);

        // ============================
        // 4. CREA TASK
        // ============================

        // Task progetto 1
        $tasks1 = [
            ['title' => 'Setup ambiente di sviluppo', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $employee1->id],
            ['title' => 'Progettazione database', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $employee1->id],
            ['title' => 'Sviluppo catalogo prodotti', 'status' => 'in_progress', 'priority' => 'high', 'assigned_to' => $employee1->id],
            ['title' => 'Integrazione Stripe', 'status' => 'in_progress', 'priority' => 'high', 'assigned_to' => $employee2->id],
            ['title' => 'Sistema gestione ordini', 'status' => 'todo', 'priority' => 'medium', 'assigned_to' => $employee2->id],
            ['title' => 'Design UI mobile', 'status' => 'todo', 'priority' => 'medium', 'assigned_to' => $employee1->id],
            ['title' => 'Testing e QA', 'status' => 'todo', 'priority' => 'low', 'assigned_to' => $employee2->id],
        ];

        foreach ($tasks1 as $task) {
            Task::create([
                'project_id'  => $project1->id,
                'title'       => $task['title'],
                'status'      => $task['status'],
                'priority'    => $task['priority'],
                'assigned_to' => $task['assigned_to'],
                'due_date'    => now()->addWeeks(rand(1, 6)),
            ]);
        }

        // Task progetto 2
        $tasks2 = [
            ['title' => 'Analisi requisiti', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $employee1->id],
            ['title' => 'Wireframe e prototipo', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $employee2->id],
            ['title' => 'Sviluppo API backend', 'status' => 'in_progress', 'priority' => 'high', 'assigned_to' => $employee1->id],
            ['title' => 'Sviluppo app iOS', 'status' => 'todo', 'priority' => 'medium', 'assigned_to' => $employee2->id],
            ['title' => 'Sviluppo app Android', 'status' => 'todo', 'priority' => 'medium', 'assigned_to' => $employee1->id],
        ];

        foreach ($tasks2 as $task) {
            Task::create([
                'project_id'  => $project2->id,
                'title'       => $task['title'],
                'status'      => $task['status'],
                'priority'    => $task['priority'],
                'assigned_to' => $task['assigned_to'],
                'due_date'    => now()->addWeeks(rand(2, 8)),
            ]);
        }

        // Task progetto 3 — tutti done
        $tasks3 = [
            ['title' => 'Analisi KPI aziendali', 'assigned_to' => $employee1->id],
            ['title' => 'Design dashboard', 'assigned_to' => $employee2->id],
            ['title' => 'Sviluppo grafici', 'assigned_to' => $employee1->id],
            ['title' => 'Integrazione dati', 'assigned_to' => $employee2->id],
            ['title' => 'Deploy e test', 'assigned_to' => $employee1->id],
        ];

        foreach ($tasks3 as $task) {
            Task::create([
                'project_id'  => $project3->id,
                'title'       => $task['title'],
                'status'      => 'done',
                'priority'    => 'medium',
                'assigned_to' => $task['assigned_to'],
                'due_date'    => now()->subWeeks(rand(1, 4)),
            ]);
        }

        // ============================
        // 5. CREA FATTURE
        // ============================

        Invoice::create([
            'team_id'    => $team->id,
            'project_id' => $project3->id,
            'number'     => 'INV-0001',
            'amount'     => 4500.00,
            'status'     => 'paid',
            'issued_at'  => now()->subMonths(2),
            'due_at'     => now()->subMonth(),
            'notes'      => 'Pagamento per Dashboard Analytics — fase 1',
        ]);

        Invoice::create([
            'team_id'    => $team->id,
            'project_id' => $project3->id,
            'number'     => 'INV-0002',
            'amount'     => 3800.00,
            'status'     => 'paid',
            'issued_at'  => now()->subMonth(),
            'due_at'     => now()->subWeeks(2),
            'notes'      => 'Pagamento per Dashboard Analytics — fase finale',
        ]);

        Invoice::create([
            'team_id'    => $team->id,
            'project_id' => $project1->id,
            'number'     => 'INV-0003',
            'amount'     => 6000.00,
            'status'     => 'sent',
            'issued_at'  => now()->subWeeks(2),
            'due_at'     => now()->addWeeks(2),
            'notes'      => 'Acconto E-commerce Moda Italiana — 50%',
        ]);

        Invoice::create([
            'team_id'    => $team->id,
            'project_id' => $project2->id,
            'number'     => 'INV-0004',
            'amount'     => 2500.00,
            'status'     => 'draft',
            'issued_at'  => now(),
            'due_at'     => now()->addMonth(),
            'notes'      => 'Acconto App Mobile Ristorante',
        ]);

        // ============================
        // 6. CREA RICHIESTE PROGETTO
        // ============================

        ProjectRequest::create([
            'client_id'   => $client1->id,
            'title'       => 'Richiesta di Giuseppe Esposito',
            'description' => 'Ho bisogno di un sito e-commerce per vendere i miei prodotti artigianali online. Vorrei un design moderno con integrazione pagamenti e gestione ordini.',
            'desired_deadline' => now()->addMonths(3),
            'budget'      => 5000.00,
            'status'      => 'accepted',
            'assigned_admin_id' => $admin->id,
            'converted_to_project_id' => $project1->id,
        ]);

        ProjectRequest::create([
            'client_id'   => $client1->id,
            'title'       => 'Richiesta di Giuseppe Esposito',
            'description' => 'Vorrei una dashboard personalizzata per monitorare le vendite del mio negozio in tempo reale.',
            'desired_deadline' => now()->addMonths(2),
            'budget'      => 3000.00,
            'status'      => 'accepted',
            'assigned_admin_id' => $admin->id,
            'converted_to_project_id' => $project3->id,
        ]);

        ProjectRequest::create([
            'client_id'   => $client2->id,
            'title'       => 'Richiesta di Sofia Marino',
            'description' => 'Ho bisogno di un\'app mobile per il mio ristorante per gestire prenotazioni e ordini online.',
            'desired_deadline' => now()->addMonths(4),
            'budget'      => 8000.00,
            'status'      => 'accepted',
            'assigned_admin_id' => $admin->id,
            'converted_to_project_id' => $project2->id,
        ]);

        ProjectRequest::create([
            'client_id'   => $client2->id,
            'title'       => 'Richiesta di Sofia Marino',
            'description' => 'Vorrei rinnovare il sito web del mio studio legale con un design più moderno e professionale.',
            'desired_deadline' => now()->addMonths(5),
            'budget'      => 2500.00,
            'status'      => 'pending',
        ]);

        $this->command->info('✅ Demo data creati con successo!');
        $this->command->info('');
        $this->command->info('Credenziali di accesso:');
        $this->command->info('Admin:     admin@demo.com / password');
        $this->command->info('Manager:   manager@demo.com / password');
        $this->command->info('Employee:  employee@demo.com / password');
        $this->command->info('Client:    client@demo.com / password');
    }
}