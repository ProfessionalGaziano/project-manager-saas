{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Teams" icon="la la-question" :link="backpack_url('team')" />
<x-backpack::menu-item title="Projects" icon="la la-question" :link="backpack_url('project')" />
<x-backpack::menu-item title="Tasks" icon="la la-question" :link="backpack_url('task')" />
<x-backpack::menu-item title="Invoices" icon="la la-question" :link="backpack_url('invoice')" />