{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@if(backpack_user()->hasRole('admin'))
    <x-backpack::menu-item title="Teams" icon="la la-users" :link="backpack_url('team')" />
@endif

@if(backpack_user()->hasAnyRole(['admin', 'manager', 'client']))
    <x-backpack::menu-item title="Projects" icon="la la-project-diagram" :link="backpack_url('project')" />
@endif

@if(backpack_user()->hasAnyRole(['admin', 'manager']))
    <x-backpack::menu-item title="Tasks" icon="la la-tasks" :link="backpack_url('task')" />
@endif

@if(backpack_user()->hasRole('employee'))
    <x-backpack::menu-item title="I miei Task" icon="la la-tasks" :link="backpack_url('task')" />
@endif

@if(backpack_user()->hasRole('admin'))
    <x-backpack::menu-item title="Abbonamento" icon="la la-credit-card" :link="url('subscription/plans')" />
    <x-backpack::menu-item title="Invita Utenti" icon="la la-user-plus" :link="url('invitation')" />
@endif

@if(backpack_user()->hasRole('admin'))
    @php $team = backpack_user()->ownedTeams()->first(); @endphp
    @if($team && $team->plan === 'pro')
        <x-backpack::menu-item title="Invoices" icon="la la-file-invoice" :link="backpack_url('invoice')" />
    @endif
@endif

