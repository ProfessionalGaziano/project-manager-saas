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
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
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
        CRUD::setValidation(InvoiceRequest::class);

        CRUD::field('team_id')
            ->type('select')
            ->label('Team')
            ->model('App\Models\Team')
            ->attribute('name');

        CRUD::field('project_id')
            ->type('select')
            ->label('Progetto')
            ->model('App\Models\Project')
            ->attribute('name')
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
        CRUD::field('due_at')->type('date')->label('Data Scadenza');
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
