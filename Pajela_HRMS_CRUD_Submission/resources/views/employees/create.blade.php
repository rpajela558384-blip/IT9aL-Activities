@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Create Employee</h2>
        <p class="subtitle">Fill out the details to add a new employee record.</p>
    </div>
    <a href="{{ route('employees.index') }}" class="btn btn-primary">Back to List</a>
</div>

<div class="card">
    @if ($errors->any())
        <div class="alert alert-error">
            <strong>There were some problems with your input.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <div class="field-grid">
            <div class="form-group">
                <label for="FN">First Name</label>
                <input id="FN" name="FN" type="text" class="form-input" value="{{ old('FN') }}" required>
            </div>

            <div class="form-group">
                <label for="MN">Middle Name</label>
                <input id="MN" name="MN" type="text" class="form-input" value="{{ old('MN') }}">
            </div>

            <div class="form-group">
                <label for="LN">Last Name</label>
                <input id="LN" name="LN" type="text" class="form-input" value="{{ old('LN') }}" required>
            </div>
        </div>

        <div class="field-grid">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="form-select" required>
                    <option value="">Select gender</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input id="hire_date" name="hire_date" type="date" class="form-input" value="{{ old('hire_date') }}" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="">Select status</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="resigned" {{ old('status') === 'resigned' ? 'selected' : '' }}>Resigned</option>
                    <option value="terminated" {{ old('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    <option value="on_leave" {{ old('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Employee</button>
            <a href="{{ route('employees.index') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>

@endsection