<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProjectRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProjectCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Project::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/project');
        CRUD::setEntityNameStrings('project', 'projects');

        $user = backpack_user();

        // Admin e manager possono gestire i progetti
        if (!backpack_user()->hasAnyRole(['admin', 'manager', 'client'])) {
            abort(403, 'Non hai i permessi per accedere a questa sezione.');
        }

        if ($user->hasRole('client')) {
        CRUD::denyAccess(['create', 'update', 'delete']);
        return;
        }

        if (!$user->hasAnyRole(['admin', 'manager'])) {
            abort(403, 'Non hai i permessi per accedere a questa sezione.');
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
        CRUD::addClause('where', 'team_id', session('active_team_id'));

        //CRUD::addClause('where', 'team_id', backpack_user()->id);
    
        CRUD::column('name')->label('Nome');
        CRUD::column('team_id')
            ->type('select')
            ->label('Team')
            ->model('App\Models\Team')
            ->attribute('name');
        CRUD::column('status')->label('Stato');
        CRUD::column('deadline')->type('date')->label('Scadenza');
        CRUD::column('created_at')->type('datetime')->label('Data Creazione');

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
       
        CRUD::setValidation(ProjectRequest::class);
        CRUD::field('team_id')->value(session('active_team_id'))->type('hidden');

        CRUD::field('team_id')
            ->type('select')
            ->label('Team')
            ->model('App\Models\Team')
            ->attribute('name');

        CRUD::field('name')->type('text')->label('Nome');
        CRUD::field('description')->type('textarea')->label('Descrizione');

        CRUD::field('status')
            ->type('select_from_array')
            ->label('Stato')
            ->options([
                'draft'     => 'Bozza',
                'active'    => 'In corso',
                'completed' => 'Completato',
                'archived'  => 'Archiviato',
            ]);
            
        CRUD::field('deadline')->type('date')->label('Scadenza');
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
