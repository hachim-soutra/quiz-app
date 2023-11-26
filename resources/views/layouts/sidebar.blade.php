<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #cfe2f3ff">
    <a href="{{ route('home') }}" class="brand-link" style="padding: 1.81rem 0.5rem;border: none">
        <img src="{{ asset('images/' . $logo_home->value) }}" alt="AdminLTE Logo" class="w-100">
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
