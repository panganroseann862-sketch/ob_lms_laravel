@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f4f3fb;
    }

    .page-header { margin-bottom: 24px; }
    .page-header h4 { font-size: 20px; font-weight: 800; color: #2d1b5e; margin-bottom: 4px; }
    .page-header p { font-size: 13px; color: #9d8cc4; margin: 0; }

    .ob-card { border: 1px solid #ede8f8; border-radius: 18px; background: #fff; box-shadow: 0 8px 24px rgba(118,75,162,0.06); margin-bottom: 24px; overflow: hidden; }
    .ob-card-header { padding: 16px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; gap: 10px; }
    .ob-card-header span { font-size: 14px; font-weight: 700; color: #fff; }
    .ob-card-header i { color: rgba(255,255,255,0.8); font-size: 15px; }
    .ob-card-body { padding: 24px; }

    .form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #9d8cc4; margin-bottom: 6px; }
    .form-control { border: 1px solid #e0d9f5; border-radius: 10px; padding: 10px 14px; font-size: 13.5px; color: #2d1b5e; background: #faf8ff; transition: border-color 0.2s, box-shadow 0.2s; width: 100%; }
    .form-control:focus { border-color: #764ba2; box-shadow: 0 0 0 3px rgba(118,75,162,0.12); background: #fff; outline: none; }
    .form-control::placeholder { color: #c4b8e0; font-size: 13px; }

    .btn-save { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff; padding: 10px 28px; border-radius: 10px; font-size: 13.5px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 14px rgba(118,75,162,0.25); transition: all 0.2s; display: inline-flex; align-items: center; gap: 7px; }
    .btn-save:hover { transform: translateY(-2px); color: #fff; }

    .ob-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .ob-table thead tr { border-bottom: 2px solid #ede8f8; }
    .ob-table th { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9d8cc4; padding: 10px 14px; text-align: left; }
    .ob-table tbody tr { border-bottom: 1px solid #f4f0fc; transition: background 0.15s; }
    .ob-table tbody tr:last-child { border-bottom: none; }
    .ob-table tbody tr:hover { background: #faf8ff; }
    .ob-table td { padding: 13px 14px; color: #3d2b6e; vertical-align: middle; }

    .code-badge { display: inline-block; background: #f0ebff; color: #5b2d9e; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 6px; }
    .instructor-name { display: flex; align-items: center; gap: 8px; color: #7a6fa0; font-size: 13px; }
    .instructor-name i { font-size: 12px; color: #c4b8e0; }

    .btn-edit { background: #f0ebff; border: 1px solid #ddd5f8; color: #5b2d9e; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
    .btn-edit:hover { background: #e4dbff; color: #4a1fa8; }
    .btn-delete { background: #fff0f0; border: 1px solid #ffd5d5; color: #c0392b; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
    .btn-delete:hover { background: #ffe0e0; border-color: #ffb3b3; color: #a93226; }

    .empty-state { text-align: center; padding: 40px 20px; color: #b8add8; }
    .empty-state i { font-size: 40px; margin-bottom: 12px; opacity: 0.4; }
    .empty-state p { font-size: 13px; }

    /* CUSTOM MODAL */
    .custom-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .custom-overlay.active {
        display: flex;
    }
    .custom-modal {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(118,75,162,0.2);
        overflow: hidden;
    }
    .custom-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .custom-modal-header h5 { font-size: 15px; font-weight: 700; color: #fff; margin: 0; }
    .custom-modal-header .btn-x { background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; line-height: 1; padding: 0; }
    .custom-modal-body { padding: 24px; background: #fff; }
    .custom-modal-footer { background: #faf8ff; border-top: 1px solid #ede8f8; padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 18px 18px; }
    .btn-cancel { background: #f4f0fc; border: 1px solid #ede8f8; color: #7a6fa0; padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .btn-update { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff; padding: 8px 24px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; }
</style>

<div class="container-fluid py-4 px-4">

    <div class="page-header">
        <h4><i class="fas fa-book me-2" style="color:#764ba2;"></i> Subject Management</h4>
        <p>Add and manage subjects offered this semester.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px; font-size:13px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

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
                            <button class="btn-edit me-1"
                                onclick="openEditModal('{{ $sub->id }}', '{{ addslashes($sub->subject_code) }}', '{{ addslashes($sub->subject_name) }}', '{{ addslashes($sub->instructor) }}')">
                                <i class="fas fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('subjects.delete', $sub->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">
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

<!-- CUSTOM EDIT MODAL -->
<div class="custom-overlay" id="editOverlay">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h5><i class="fas fa-edit me-2"></i> Edit Subject</h5>
            <button class="btn-x" onclick="closeEditModal()">×</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="custom-modal-body">
                <div class="mb-3">
                    <label class="form-label">Subject Code</label>
                    <input type="text" name="subject_code" id="edit_subject_code" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="subject_name" id="edit_subject_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assigned Instructor</label>
                    <input type="text" name="instructor" id="edit_instructor" class="form-control" required>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-update">
                    <i class="fas fa-save me-1"></i> Update Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, code, name, instructor) {
        document.getElementById('edit_subject_code').value = code;
        document.getElementById('edit_subject_name').value = name;
        document.getElementById('edit_instructor').value = instructor;
        document.getElementById('editForm').action = '/subjects/update/' + id;
        document.getElementById('editOverlay').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editOverlay').classList.remove('active');
    }

    document.getElementById('editOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
