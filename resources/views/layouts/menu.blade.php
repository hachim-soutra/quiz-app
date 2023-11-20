<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link text-dark {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('quiz.index') }}" class="nav-link text-dark {{ Request::is('quiz.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>Quizzes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.answer') }}" class="nav-link text-dark {{ Request::is('answer') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gift"></i>
        <p>Answers</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('categorie.index') }}" class="nav-link text-dark {{ Request::is('categorie.index') ? 'active' : '' }}">
        <p>Categories</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('settings.index') }}" class="nav-link text-dark {{ Request::is('settings.index') ? 'active' : '' }}">
        <p>Settings</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('folder.index') }}" class="nav-link text-dark {{ Request::is('folder.index') ? 'active' : '' }}">
        <i class="fa-regular fa-folder-open"></i>
        <p>Folders</p>
    </a>
</li>
