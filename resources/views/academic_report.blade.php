@extends('layouts.app')

@section('content')
<style>
    body { background: #eeebf7 !important; }

    .report-page {
        padding: 28px 40px;
        min-height: 100vh;
        background: #eeebf7;
        font-family: 'Segoe UI', sans-serif;
    }

    /* ── Top Bar ── */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #d4c8ee;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 12px;
        font-weight: 700;
        color: #1e0f42;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.5px;
        transition: background 0.15s;
    }
    .btn-back:hover { background: #f5f2fc; color: #1e0f42; text-decoration: none; }

    .btn-export {
        background: linear-gradient(135deg, #6c47d6 0%, #3b28a8 100%);
        border: none;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.5px;
        transition: opacity 0.15s, box-shadow 0.15s;
        box-shadow: 0 4px 14px rgba(108,71,214,0.3);
    }
    .btn-export:hover {
        opacity: 0.92;
        box-shadow: 0 6px 18px rgba(108,71,214,0.4);
    }

    /* ── Export Dropdown ── */
    .export-dropdown-menu {
        border-radius: 14px !important;
        border: 1px solid #ece9f8 !important;
        box-shadow: 0 8px 30px rgba(59,40,168,0.13) !important;
        padding: 8px !important;
        min-width: 210px !important;
        overflow: hidden;
    }

    .export-dropdown-menu .dropdown-item {
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #1e0f42;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.15s, color 0.15s;
    }
    .export-dropdown-menu .dropdown-item:hover {
        background: #f4f0ff;
        color: #6c47d6;
    }

    .export-dropdown-menu .dropdown-item .item-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .icon-pdf    { background: #f3eeff; color: #6c47d6; }
    .icon-excel  { background: #f3eeff; color: #6c47d6; }
    .icon-word   { background: #f3eeff; color: #6c47d6; }
    .icon-txt    { background: #f3eeff; color: #6c47d6; }

    .export-dropdown-menu .dropdown-divider {
        border-color: #ece9f8;
        margin: 4px 0;
    }

    /* ── Header Card ── */
    .report-header-card {
        background: #fff;
        border: 1px solid #d4c8ee;
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 16px;
    }

    .report-title {
        font-size: 22px;
        font-weight: 700;
        color: #4a2fa0;
        margin-bottom: 20px;
        letter-spacing: 0.3px;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 40px;
    }

    .meta-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
        font-size: 13px;
    }

    .meta-label {
        color: #888;
        min-width: 100px;
        font-weight: 400;
    }

    .meta-value {
        color: #1e0f42;
        font-weight: 500;
    }

    /* ── Table Card ── */
    .table-card {
        background: #fff;
        border: 1px solid #d4c8ee;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .rpt-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .rpt-table thead tr {
        background: #f3f1fb;
    }

    .rpt-table thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #6b52b0;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 1px solid #e8e2f5;
    }

    .rpt-table thead th.center { text-align: center; }

    .rpt-table tbody tr {
        border-bottom: 1px solid #f0edf9;
        transition: background 0.12s;
    }

    .rpt-table tbody tr:last-child { border-bottom: none; }
    .rpt-table tbody tr:hover { background: #faf9fe; }

    .rpt-table td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #1e0f42;
    }

    .rpt-table td.center { text-align: center; }

    .student-name {
        font-weight: 600;
        font-size: 13px;
        color: #1e0f42;
    }

    .student-id {
        font-size: 11px;
        color: #a898cc;
        margin-top: 2px;
    }

    /* Outcome badge */
    .outcome-badge {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #ece9f5;
        color: #6b52b0;
        border: 1px solid #d4c8ee;
    }

    .outcome-mapped {
        background: #eaf3ff;
        color: #2563a8;
        border-color: #bcd3f0;
        font-size: 11px;
        line-height: 1.5;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-block;
    }

    /* Score */
    .score-val {
        font-weight: 700;
        font-size: 16px;
        color: #1e0f42;
    }

    /* Status pills */
    .pill {
        display: inline-block;
        padding: 5px 18px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .pill-excellent {
        background: #ece9f5;
        color: #4a2fa0;
        border: 1px solid #c8b8e8;
    }

    .pill-passed {
        background: #d6f4e5;
        color: #1a6b47;
        border: 1px solid #a3d9bf;
    }

    .pill-atrisk {
        background: #fde8e8;
        color: #9b1c1c;
        border: 1px solid #f5b8b8;
    }

    /* ── Summary ── */
    .summary-card {
        background: #fff;
        border: 1px solid #d4c8ee;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 16px;
    }

    .summary-label {
        font-size: 10px;
        font-weight: 700;
        color: #a898cc;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
    }

    .summary-msg {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 13px;
        font-weight: 600;
    }

    .summary-msg.success {
        background: #eafaf2;
        color: #1a6b47;
        border: 1px solid #a3d9bf;
    }

    .summary-msg.danger {
        background: #fde8e8;
        color: #9b1c1c;
        border: 1px solid #f5b8b8;
    }

    .summary-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .summary-icon.success { background: #1a6b47; }
    .summary-icon.danger  { background: #9b1c1c; }

    .at-risk-list {
        margin: 8px 0 0 0;
        padding-left: 18px;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.8;
    }

    /* ── Signatories ── */
    .signatories {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding: 0 12px;
    }

    .signatory {
        text-align: center;
        min-width: 220px;
    }

    .sig-name {
        font-weight: 700;
        font-size: 13px;
        color: #1e0f42;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .sig-line {
        border-bottom: 1.5px solid #333;
        margin-bottom: 8px;
    }

    .sig-role {
        font-size: 11px;
        color: #888;
        margin-top: 3px;
    }

    /* ── Print ── */
    @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; }
        .report-page { background: #fff !important; padding: 16px !important; }
        .report-header-card, .table-card, .summary-card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>

<div class="report-page">

    {{-- Top Bar --}}
    <div class="top-bar no-print">
        <a href="{{ route('reports.index') }}" class="btn-back">
            ← Back to filters
        </a>
        <div class="dropdown">
            <button class="btn-export dropdown-toggle" type="button" data-bs-toggle="dropdown">
                ↓ Export report
            </button>
            <ul class="dropdown-menu dropdown-menu-end export-dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#" onclick="window.print()">
                        <span class="item-icon icon-pdf"><i class="fa-solid fa-file-pdf"></i></span>
                        Save as PDF
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" onclick="exportToExcel()">
                        <span class="item-icon icon-excel"><i class="fa-solid fa-file-excel"></i></span>
                        Export to Excel (.xls)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="exportToDocs()">
                        <span class="item-icon icon-word"><i class="fa-solid fa-file-word"></i></span>
                        Export to Docs (.doc)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="exportToNotes()">
                        <span class="item-icon icon-txt"><i class="fa-solid fa-file-lines"></i></span>
                        Save as Notes (.txt)
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Header Card --}}
    <div class="report-header-card">
        <div class="report-title">Academic Performance Report</div>
        <div class="meta-grid">
            <div>
                <div class="meta-row">
                    <span class="meta-label">Subject</span>
                    <span class="meta-value">{{ $subject->subject_name ?? 'N/A' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Code</span>
                    <span class="meta-value">{{ $subject->subject_code ?? 'N/A' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Term</span>
                    <span class="meta-value">{{ $term ?? 'N/A' }}</span>
                </div>
            </div>
            <div>
                <div class="meta-row">
                    <span class="meta-label">Academic Year</span>
                    <span class="meta-value">{{ $academic_year ?? 'N/A' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Semester</span>
                    <span class="meta-value">{{ $semester ?? 'N/A' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Assessment</span>
                    <span class="meta-value">{{ $request->assessment ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-card" id="reportContent">
        <table class="rpt-table" id="reportTable">
            <thead>
                <tr>
                    <th style="width: 25%;">Student Name</th>
                    <th style="width: 40%;">Skill / Outcome</th>
                    <th class="center" style="width: 12%;">Score</th>
                    <th class="center" style="width: 23%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td>
                        <div class="student-name">
                            {{ ucfirst(strtolower($s->lastname ?? 'N/A')) }}, {{ ucfirst(strtolower($s->firstname ?? 'N/A')) }}
                        </div>
                        <div class="student-id">{{ $s->student_id_no ?? '---' }}</div>
                    </td>
                    <td>
                        @if($s->po_description === 'Outcome description not yet mapped.')
                            <span class="outcome-badge">Not yet mapped</span>
                        @else
                            <div class="outcome-mapped">{!! $s->po_description !!}</div>
                        @endif
                    </td>
                    <td class="center">
                        <span class="score-val">{{ $s->score }}%</span>
                    </td>
                    <td class="center">
                        @if(($s->goal ?? '') === 'EXCELLENT')
                            <span class="pill pill-excellent">Excellent</span>
                        @elseif(($s->goal ?? '') === 'PASSED')
                            <span class="pill pill-passed">Passed</span>
                        @else
                            <span class="pill pill-atrisk">At Risk</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 40px; color: #a898cc;">
                        No student records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Performance Summary --}}
    <div class="summary-card">
        <div class="summary-label">Performance Summary</div>
        @php $isAtRisk = isset($summary['at_risk']) && $summary['at_risk'] > 0; @endphp
        <div class="summary-msg {{ $isAtRisk ? 'danger' : 'success' }}">
            <div class="summary-icon {{ $isAtRisk ? 'danger' : 'success' }}">
                @if($isAtRisk)
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M6 2v5M6 9v.5" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                @else
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <polyline points="2.5,6 5,8.5 9.5,3.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif
            </div>
            <div>
                {{-- Main message --}}
                <div>
                    {{ $summary['message'] ?? ($isAtRisk ? 'Some students need improvement.' : 'All students no need to improve.') }}
                </div>

                {{-- List of at-risk students --}}
                @if($isAtRisk && !empty($summary['at_risk_names']))
                    <ul class="at-risk-list">
                        @foreach($summary['at_risk_names'] as $name)
                            <li>{{ $name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Signatories --}}
    <div class="signatories">
        <div class="signatory">
            <div class="sig-name">{{ $instructor ?? 'Signature Over Printed Name' }}</div>
            <div class="sig-line"></div>
            <div class="sig-role">Subject Instructor</div>
            <div class="sig-role">School of Information Technology Education</div>
        </div>
        <div class="signatory">
            <div class="sig-name">{{ $dean ?? 'Jann Alfred Quinto, MSIB' }}</div>
            <div class="sig-line"></div>
            <div class="sig-role">Department Head</div>
            <div class="sig-role">Dean, SITE Department</div>
        </div>
    </div>

</div>

<script>
    function exportToExcel() {
        const table = document.getElementById("reportTable");
        const html = table.outerHTML;
        const blob = new Blob([html], { type: "application/vnd.ms-excel" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Academic_Report_{{ $subject->subject_name ?? 'Report' }}.xls";
        a.click();
    }

    function exportToDocs() {
        const header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'></head><body>";
        const footer = "</body></html>";
        const content = document.getElementById("reportContent").innerHTML;
        const blob = new Blob([header + content + footer], { type: "application/msword" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Academic_Report.doc";
        a.click();
    }

    function exportToNotes() {
        let text = "ACADEMIC PERFORMANCE REPORT\n";
        text += "Subject: {{ $subject->subject_name ?? 'N/A' }}\n";
        text += "Term: {{ $term ?? 'N/A' }}\n";
        text += "-------------------------------------------\n\n";
        document.querySelectorAll("#reportTable tr").forEach(row => {
            const cols = Array.from(row.querySelectorAll("th, td")).map(c => c.innerText.trim());
            text += cols.join(" | ") + "\n";
        });
        const blob = new Blob([text], { type: "text/plain" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Report_Notes.txt";
        a.click();
    }
</script>

@endsection
