<li class="nav-item">
    <a href="{{ route('client.home') }}" class="nav-link text-dark {{ Request::is('home') ? 'active' : '' }}"
    style="padding-left: 10px;">
        <i class="nav-icon fas fa-home"></i>
        <p>Dashboard</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('client.quizzes') }}" class="nav-link text-dark px-2 {{ Request::is('quiz.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>Quizzes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('client.promos') }}" class="nav-link text-dark {{ Request::is('promo') ? 'active' : '' }}">
        <i class="fa-solid fa-bullhorn"></i>
        <p>Promos</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('client.formations') }}" class="nav-link text-dark px-3 {{ Request::is('formations') ? 'active' : '' }}">
        <i class="fa-solid fa-graduation-cap" style="margin-right: 2px;"></i>
        <p>Formations</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('answers') }}" class="nav-link text-dark px-2 {{ Request::is('answers') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gift"></i>
        <p>Answers</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('account') }}" class="nav-link text-dark px-3 {{ Request::is('account') ? 'active' : '' }}">
        <i class="fa-solid fa-gears" style="margin-right: 2px;"></i>
        <p>Profile</p>
    </a>
</li>
