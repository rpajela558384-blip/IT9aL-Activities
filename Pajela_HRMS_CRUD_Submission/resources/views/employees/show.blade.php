@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Employee Details</h2>
        <p class="subtitle">Review the full profile for {{ $employee->FN }} {{ $employee->LN }}.</p>
    </div>
    <div class="form-actions">
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('employees.index') }}" class="btn">Back to List</a>
    </div>
</div>

<div class="card">
    <div class="field-grid">
        <div>
            <strong>First Name</strong>
            <p>{{ $employee->FN }}</p>
        </div>

        <div>
            <strong>Middle Name</strong>
            <p>{{ $employee->MN ?? '-' }}</p>
        </div>

        <div>
            <strong>Last Name</strong>
            <p>{{ $employee->LN }}</p>
        </div>

        <div>
            <strong>Gender</strong>
            <p>{{ ucfirst($employee->gender) }}</p>
        </div>

        <div>
            <strong>Hire Date</strong>
            <p>{{ $employee->hire_date }}</p>
        </div>

        <div>
            <strong>Status</strong>
            <p>{{ ucfirst(str_replace('_', ' ', $employee->status)) }}</p>
        </div>
    </div>
</div>

@endsection