<nav class="navbar">
    <strong>HRMS</strong>

    <div class="navbar-links">
        <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.index') ? 'active-link' : '' }}">Home</a>
        <a href="{{ route('employees.create') }}" class="{{ request()->routeIs('employees.create') ? 'active-link' : '' }}">Add Employee</a>
    </div>
</nav>
