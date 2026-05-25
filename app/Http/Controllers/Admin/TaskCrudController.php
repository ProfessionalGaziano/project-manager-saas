<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TaskRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Events\TaskAssigned;


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

        $user = backpack_user();
        
        // Admin e manager possono gestire i task
        if (!backpack_user()->hasAnyRole(['admin', 'manager', 'employee'])) {
            abort(403, 'Non hai i permessi per accedere a questa sezione.');
        }

        if ($user->hasRole('client')) {
        CRUD::denyAccess(['create', 'update', 'delete']);
        return;
        }

         // Employee può solo aggiornare i task assegnati a lui
        if ($user->hasRole('employee')) {
            CRUD::denyAccess(['create', 'delete']);
            return;
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

        $user = backpack_user();

        if ($user->hasRole('manager')) {
            // Manager vede solo i task dei progetti assegnati a lui
            CRUD::addClause('whereHas', 'project', function($query) use ($user) {
                $query->where('manager_id', $user->id);
            });
        } elseif ($user->hasRole('employee')) {
            // Employee vede solo i task assegnati a lui
            CRUD::addClause('where', 'assigned_to', $user->id);
        } else {
            // Admin vede tutti i task del team
            CRUD::addClause('whereHas', 'project', function($query) use ($user) {
                $query->where('team_id', $user->ownedTeams()->first()->id ?? 0);
            });
        }

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
    }

public function updated(Task $task): void
{
    
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
            return $query->whereHas('roles', function($q) {
                $q->where('name', 'employee');
            })->whereHas('teams', function($q) {
                // Prendi gli employee del team dell'admin
                $teamId = backpack_user()->hasRole('admin') 
                    ? backpack_user()->ownedTeams()->first()->id
                    : backpack_user()->teams()->first()->id;
                $q->where('teams.id', $teamId);
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
