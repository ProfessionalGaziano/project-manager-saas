<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeamRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TeamCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeamCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Team::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/team');
        CRUD::setEntityNameStrings('team', 'teams');

        $user = backpack_user();
        
        // Solo gli admin possono gestire i team
        if (!backpack_user()->hasRole('admin')) {
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
        CRUD::addClause('where', 'owner_id', backpack_user()->id);

        CRUD::column('name')->label('Nome');
        CRUD::column('slug')->label('Slug');
        CRUD::column('plan')->label('Piano');
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
        CRUD::setValidation(TeamRequest::class);
        CRUD::field('owner_id')->type('hidden')->value(backpack_user()->id);

        CRUD::field('name')->type('text')->label('Nome');
        CRUD::field('slug')->type('text')->label('Slug');

        /*
        CRUD::field('owner_id')
            ->type('select')
            ->label('Proprietario')
            ->model('App\Models\User')
            ->attribute('name')
            ->allows_null(false);
        */
        CRUD::field('plan')
            ->type('select_from_array')
            ->label('Piano')
            ->options(['free' => 'Free', 'pro' => 'Pro']);
            
        CRUD::field('trial_ends_at')
            ->type('datetime')
            ->label('Fine Trial');
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
