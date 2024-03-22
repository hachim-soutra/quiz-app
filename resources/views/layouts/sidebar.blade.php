<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #cfe2f3ff">
    @if (Auth::user()->userable_type == App\Models\User::ADMIN_TYPE)
        <a href="{{ route('home') }}" class="brand-link" style="padding: 1.81rem 0.5rem;border: none">
            <img src="{{ asset('images/' . $logo_home->value) }}" alt="AdminLTE Logo" class="w-100">
        </a>
    @else
        <a href="{{ route('client.home') }}" class="brand-link" style="padding: 1.81rem 0.5rem;border: none">
            <img src="{{ asset('images/' . $logo_home->value) }}" alt="AdminLTE Logo" class="w-100">
        </a>
    @endif

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if (Auth::user()->userable_type == App\Models\User::ADMIN_TYPE)
                    @include('layouts.menu')
                @else
                    @include('layouts.user-menu')
                @endif
            </ul>
        </nav>
    </div>

</aside>
