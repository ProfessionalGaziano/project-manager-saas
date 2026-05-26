<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InvoiceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InvoiceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InvoiceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Invoice::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/invoice');
        CRUD::setEntityNameStrings('invoice', 'invoices');
    
        $user = backpack_user();

        // Solo admin possono gestire le fatture
        if (!backpack_user()->hasRole('admin')) {
            abort(403, 'Non hai i permessi per accedere a questa sezione.');
        }

        if ($user->hasRole('client')) {
        CRUD::denyAccess(['create', 'update', 'delete']);
        return;
        }

        // Blocca le fatture per il piano Free
        if (backpack_user()->hasRole('admin')) {
            $team = backpack_user()->ownedTeams()->first();
            if ($team && $team->plan === 'free') {
                CRUD::denyAccess(['create', 'update', 'delete']);
                \Alert::warning('Le fatture sono disponibili solo nel piano Pro. Passa al piano Pro per utilizzarle!')->flash();
            }
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    protected function setupListOperation()
    {
        CRUD::addClause('whereHas', 'team', function($query) {
             $query->where('owner_id', backpack_user()->id);
        });

        CRUD::column('number')->label('Numero Fattura');

        CRUD::column('team_id')
            ->type('select')
            ->label('Team')
            ->model('App\Models\Team')
            ->attribute('name');

        CRUD::column('project_id')
            ->type('select')
            ->label('Progetto')
            ->model('App\Models\Project')
            ->attribute('name');

        CRUD::column('amount')->label('Importo');
        CRUD::column('status')->label('Stato');

        CRUD::column('issued_at')->type('date')->label('Data Emissione');
        CRUD::column('due_at')->type('date')->label('Data Scadenza');
        
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        //dd(session('active_team_id'));
        CRUD::setValidation(InvoiceRequest::class);
        CRUD::field('team_id')->value(session('active_team_id'))->type('hidden');

       CRUD::field('team_id')
        ->type('select')
        ->label('Team')
        ->model('App\Models\Team')
        ->attribute('name')
        ->options(function($query) {
            // Mostra solo i team dove l'utente è owner
            return $query->where('owner_id', backpack_user()->id)->get();
        });

        CRUD::field('project_id')
        ->type('select')
        ->label('Progetto')
        ->model('App\Models\Project')
        ->attribute('name')
        ->options(function($query) {
            // Mostra solo i progetti del team attivo
            return $query->where('team_id', session('active_team_id'))->get();
        })
        ->allows_null(true);

        CRUD::field('number')->type('text')->label('Numero Fattura');
        CRUD::field('amount')->type('number')->label('Importo')->attributes(['step' => '0.01']);

        CRUD::field('status')
            ->type('select_from_array')
            ->label('Stato')
            ->options([
                'draft'   => 'Bozza',
                'sent'    => 'Inviata',
                'paid'    => 'Pagata',
                'overdue' => 'Scaduta',
            ]);

        CRUD::field('issued_at')->type('date')->label('Data Emissione');
        CRUD::setValidation([
        'issued_at' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addYears(2)->toDateString(),
            ]);
        CRUD::field('due_at')->type('date')->label('Data Scadenza');
        CRUD::setValidation([
        'due_at' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addYears(2)->toDateString(),
            ]);
        CRUD::field('notes')->type('textarea')->label('Note');
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
