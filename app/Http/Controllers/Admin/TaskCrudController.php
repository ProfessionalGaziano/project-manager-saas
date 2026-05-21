<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TaskRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TaskCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TaskCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Task::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/task');
        CRUD::setEntityNameStrings('task', 'tasks');

        // Admin e manager possono gestire i task
        if (!backpack_user()->hasAnyRole(['admin', 'manager'])) {
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
        CRUD::addClause('whereHas', 'project', function($query) {
            $query->where('team_id', session('active_team_id'));
        });

        CRUD::column('title')->label('Titolo');
        CRUD::column('project_id')
            ->type('select')
            ->label('Progetto')
            ->model('App\Models\Project')
            ->attribute('name')
            ->entity('project');
        CRUD::column('assigned_to')
            ->type('select')
            ->label('Assegnato a')
            ->model('App\Models\User')
            ->attribute('name')
            ->entity('assignedTo');
        CRUD::column('status')->label('Stato');
        CRUD::column('priority')->label('Priorità');
        CRUD::column('due_date')->type('date')->label('Scadenza');
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
        CRUD::setValidation(TaskRequest::class);
        CRUD::field('project_id')
            ->type('select')
            ->label('Progetto')
            ->model('App\Models\Project')
            ->attribute('name')
        ->options(function($query) {
                return $query->where('team_id', session('active_team_id'))->get();
        });

        CRUD::field('project_id')
            ->type('select')
            ->label('Progetto')
            ->model('App\Models\Project')
            ->attribute('name');

        CRUD::field('assigned_to')
            ->type('select')
            ->label('Assegnato a')
            ->model('App\Models\User')
            ->attribute('name')
        ->options(function($query) {
            // Mostra solo gli utenti che appartengono al team attivo
            return $query->whereHas('teams', function($q) {
                $q->where('teams.id', session('active_team_id'));
            })->get();
        })
        ->allows_null(true);

        CRUD::field('title')->type('text')->label('Titolo');
        CRUD::field('description')->type('textarea')->label('Descrizione');

        CRUD::field('status')
            ->type('select_from_array')
            ->label('Stato')
            ->options([
                'todo'        => 'Da fare',
                'in_progress' => 'In corso',
                'review'      => 'In revisione',
                'done'        => 'Completato',
            ]);

        CRUD::field('priority')
            ->type('select_from_array')
            ->label('Priorità')
            ->options([
                'low'    => 'Bassa',
                'medium' => 'Media',
                'high'   => 'Alta',
            ]);

        CRUD::field('due_date')->type('date')->label('Scadenza');
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
