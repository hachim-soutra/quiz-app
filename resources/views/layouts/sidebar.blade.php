<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link" style="padding: 1.81rem 0.5rem;">
        <img src="{{asset('images/logo.jfif')}}"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="margin-left: 3rem; margin-top: -1rem;">
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
