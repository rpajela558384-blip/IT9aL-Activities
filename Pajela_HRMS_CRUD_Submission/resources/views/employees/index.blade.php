@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Employees</h2>
        <p class="subtitle">Manage existing employees or add a new team member.</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Hire Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($employees as $employee)
            <tr>
                <td>{{ $employee->FN }}</td>
                <td>{{ $employee->MN ?? '-' }}</td>
                <td>{{ $employee->LN }}</td>
                <td>{{ ucfirst($employee->gender) }}</td>
                <td>{{ $employee->hire_date }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $employee->status)) }}</td>
                <td>
                    <a href="{{ route('employees.show', $employee) }}" class="text-link">View</a> |
                    <a href="{{ route('employees.edit', $employee) }}" class="text-link">Edit</a> |

                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this employee?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No employees found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection