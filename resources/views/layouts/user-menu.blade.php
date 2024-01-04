<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link text-dark {{ Request::is('home') ? 'active' : '' }}"
    style="padding-left: 10px;">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.answer') }}" class="nav-link text-dark px-2 {{ Request::is('answer') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gift"></i>
        <p>Answers</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('settings.index') }}" class="nav-link text-dark px-3 {{ Request::is('settings.index') ? 'active' : '' }}">
        <i class="fa-solid fa-gears" style="margin-right: 2px;"></i>
        <p>Account</p>
    </a>
</li>
