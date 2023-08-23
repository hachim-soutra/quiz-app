<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('quiz.index') }}" class="nav-link {{ Request::is('quiz.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>Quiz</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.answer') }}" class="nav-link {{ Request::is('answer') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gift"></i>
        <p>Answer</p>
    </a>
</li>
