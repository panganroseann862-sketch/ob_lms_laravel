@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f4f3fb;
    }

    .page-header {
        margin-bottom: 24px;
    }
    .page-header h4 {
        font-size: 20px;
        font-weight: 800;
        color: #2d1b5e;
        margin-bottom: 4px;
    }
    .page-header p {
        font-size: 13px;
        color: #9d8cc4;
        margin: 0;
    }

    .ob-card {
        border: 1px solid #ede8f8;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(118, 75, 162, 0.06);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .ob-card-header {
        padding: 16px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ob-card-header span {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.2px;
    }
    .ob-card-header i {
        color: rgba(255,255,255,0.8);
        font-size: 15px;
    }
    .ob-card-body {
        padding: 24px;
    }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #9d8cc4;
        margin-bottom: 6px;
    }
    .form-control {
        border: 1px solid #e0d9f5;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #2d1b5e;
        background: #faf8ff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.12);
        background: #fff;
        outline: none;
    }
    .form-control::placeholder {
        color: #c4b8e0;
        font-size: 13px;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        padding: 10px 28px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(118, 75, 162, 0.25);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(118, 75, 162, 0.35);
        color: #fff;
    }

    .ob-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .ob-table thead tr {
        border-bottom: 2px solid #ede8f8;
    }
    .ob-table th {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9d8cc4;
        padding: 10px 14px;
        text-align: left;
    }
    .ob-table tbody tr {
        border-bottom: 1px solid #f4f0fc;
        transition: background 0.15s;
    }
    .ob-table tbody tr:last-child {
        border-bottom: none;
    }
    .ob-table tbody tr:hover {
        background: #faf8ff;
    }
    .ob-table td {
        padding: 13px 14px;
        color: #3d2b6e;
        vertical-align: middle;
    }

    .code-badge {
        display: inline-block;
        background: #f0ebff;
        color: #5b2d9e;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }

    .instructor-name {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #7a6fa0;
        font-size: 13px;
    }
    .instructor-name i {
        font-size: 12px;
        color: #c4b8e0;
    }

    .btn-edit {
        background: #f0ebff;
        border: 1px solid #ddd5f8;
        color: #5b2d9e;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-edit:hover {
        background: #e4dbff;
        color: #4a1fa8;
    }
    .btn-delete {
        background: #fff0f0;
        border: 1px solid #ffd5d5;
        color: #c0392b;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-delete:hover {
        background: #ffe0e0;
        border-color: #ffb3b3;
        color: #a93226;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #b8add8;
    }
    .empty-state i {
        font-size: 40px;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    .empty-state p {
        font-size: 13px;
    }

    /* ===== FIXED MODAL STYLES ===== */
    .modal {
        z-index: 1055 !important;
    }
    .modal-backdrop {
        z-index: 1054 !important;
    }
    .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(118, 75, 162, 0.2);
        /* REMOVED overflow:hidden — ito ang nagdudulot ng issue */
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 18px 24px;
        border-radius: 18px 18px 0 0;
    }
    .modal-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
    }
    .modal-body {
        padding: 24px;
        background: #fff;
    }
    .modal-body .form-control {
        pointer-events: auto !important;
        position: relative;
        z-index: 1;
    }
    .modal-footer {
        background: #faf8ff;
        border-top: 1px solid #ede8f8;
        padding: 14px 24px;
        border-radius: 0 0 18px 18px;
    }
    .btn-cancel {
        background: #f4f0fc;
        border: 1px solid #ede8f8;
        color: #7a6fa0;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        padding: 8px 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(118, 75, 162, 0.25);
    }
    .btn-update:hover {
        opacity: 0.9;
        color: #fff;
    }
</style>

<div class="container-fluid py-4 px-4">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <h4><i class="fas fa-book me-2" style="color:#764ba2;"></i> Subject Management</h4>
        <p>Add and manage subjects offered this semester.</p>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px; font-size:13px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ADD SUBJECT FORM --}}
    <div class="ob-card">
        <div class="ob-card-header">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Subject</span>
        </div>
        <div class="ob-card-body">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Subject Code</label>
                        <input type="text" name="subject_code" class="form-control" placeholder="e.g. CSE17" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="subject_name" class="form-control" placeholder="e.g. Information Assurance and Security" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Instructor Name</label>
                        <input type="text" name="instructor" class="form-control" placeholder="e.g. JANN ALFRED QUINTO, MSIB" required>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Subject
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SUBJECT LIST --}}
    <div class="ob-card">
        <div class="ob-card-header">
            <i class="fas fa-list"></i>
            <span>Subject List</span>
        </div>
        <div class="ob-card-body" style="padding: 0;">
            @if($subjects->count() > 0)
            <table class="ob-table">
                <thead>
                    <tr>
                        <th style="padding-left:24px;">Code</th>
                        <th>Subject Name</th>
                        <th>Instructor</th>
                        <th style="text-align:right; padding-right:24px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $sub)
                    <tr>
                        <td style="padding-left:24px;">
                            <span class="code-badge">{{ $sub->subject_code }}</span>
                        </td>
                        <td>{{ $sub->subject_name }}</td>
                        <td>
                            <div class="instructor-name">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $sub->instructor ?? 'Not Assigned' }}
                            </div>
                        </td>
                        <td style="text-align:right; padding-right:24px;">
                            <button class="btn-edit me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $sub->id }}">
                                <i class="fas fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('subjects.delete', $sub->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this subject?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p>No subjects found. Add a subject above to get started.</p>
            </div>
            @endif
        </div>
    </div>

</div>
{{-- ✅ FIX: EDIT MODALS ay LABAS na ng container-fluid --}}
@foreach($subjects as $sub)
<div class="modal fade" id="editModal{{ $sub->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Subject</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('subjects.update', $sub->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subject Code</label>
                        <input type="text" name="subject_code" class="form-control" value="{{ old('subject_code', $sub->subject_code) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="subject_name" class="form-control" value="{{ old('subject_name', $sub->subject_name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned Instructor</label>
                        <input type="text" name="instructor" class="form-control" value="{{ old('instructor', $sub->instructor) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-update">
                        <i class="fas fa-save me-1"></i> Update Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
