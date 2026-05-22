<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $subjects = DB::table('subjects')->get();
        $assessments = DB::table('assessments')
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select('assessments.*', 'subjects.subject_code', 'subjects.subject_name')
            ->get();

        return view('reports', compact('subjects', 'assessments', 'request'));
    }

    public function generate(Request $request)
    {
        if (!$request->subject_id) {
            return back()->with('error', 'Please select a subject first.');
        }

        $subject = DB::table('subjects')->where('id', $request->subject_id)->first();
        $instructor = $subject->instructor ?? "SIGNATURE OVER PRINTED NAME";
        $dean = "JANN ALFRED QUINTO, MSIB";

        $academic_year = $request->school_year;
        $semester = $request->semester;
        $term = $request->term;

        $assessment_name = str_replace('+', ' ', $request->assessment);

        $mapping = DB::table('assessments')
            ->where('subject_id', $request->subject_id)
            ->where('school_year', $request->school_year)
            ->where('semester', $request->semester)
            ->where('name', $assessment_name)
            ->first();

        try {
            $students = DB::table('students')
                ->join('grades', function($join) use ($request, $mapping) {
                    $join->on('students.id', '=', 'grades.student_id')
                         ->where('grades.subject_id', '=', $request->subject_id)
                         ->where('grades.term', '=', $request->term)
                         ->where('grades.assessment_id', '=', optional($mapping)->id);
                })
                ->select(
                    'students.id',
                    'students.firstname',
                    'students.lastname',
                    'students.student_id_no',
                    'grades.score'
                )
                ->get();

            if ($students->isEmpty()) {
                return back()->with('error', 'No grades found for this assessment.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }

        $po_descriptions = [
            'PO1'  => 'Engineering/Technical Knowledge: Apply knowledge of mathematics, science, and engineering fundamentals.',
            'PO2'  => 'Problem Analysis: Identify, formulate, and analyze complex engineering problems.',
            'PO3'  => 'Design/Development of Solutions: Design solutions for complex engineering problems.',
            'PO4'  => 'Investigation of Complex Problems: Use research-based knowledge and methods to investigate problems.',
            'PO5'  => 'Modern Tool Usage: Create, select, and apply appropriate techniques and resources.',
            'PO6'  => 'The Engineer and Society: Apply reasoning informed by contextual knowledge.',
            'PO7'  => 'Environment and Sustainability: Understand the impact of professional engineering solutions.',
            'PO8'  => 'Ethics and Professionalism: Apply ethical principles and commit to professional ethics.',
            'PO9'  => 'Individual and Team Work: Function effectively as an individual or member/leader in diverse teams.',
            'PO10' => 'Communication Proficiency: Communicate effectively on complex engineering activities.',
            'PO11' => 'Project Management and Finance: Demonstrate knowledge and understanding of management principles.',
            'PO12' => 'Lifelong Learning: Recognize the need for and have the preparation for independent learning.',
            'PO13' => 'UdD Institutional Outcome: Demonstrate the core values of the institution.'
        ];

        $summary = ['excellent' => 0, 'passed' => 0, 'at_risk' => 0];

        foreach ($students as $student) {

            if ($mapping && !empty($mapping->po_id)) {
                $mapped_pos = array_map('trim', explode(',', $mapping->po_id));
                $desc_list = [];

                foreach ($mapped_pos as $po) {
                    $clean_po = strtoupper(trim($po));
                    if (isset($po_descriptions[$clean_po])) {
                        $desc_list[] = "• <strong>$clean_po:</strong> " . $po_descriptions[$clean_po];
                    }
                }

                $student->po_description = !empty($desc_list)
                    ? implode('<br>', $desc_list)
                    : "PO ($mapping->po_id) description not found.";

                $student->mapped_po = $mapping->po_id;
            } else {
                $student->po_description = "Outcome description not yet mapped.";
                $student->mapped_po = "N/A";
            }

            // Status logic: Below 75 = At Risk, 75-89 = Passed, 90+ = Excellent
            if ($student->score >= 90) {
                $student->goal = "EXCELLENT";
                $student->statusClass = "status-excellent";
                $summary['excellent']++;
            } elseif ($student->score >= 75) {
                $student->goal = "PASSED";
                $student->statusClass = "status-passed";
                $summary['passed']++;
            } else {
                $student->goal = "AT RISK";
                $student->statusClass = "status-failed";
                $summary['at_risk']++;
            }
        }

        $at_risk_count = $summary['at_risk'];
        $total = count($students);

        if ($at_risk_count > 0) {
            $at_risk_names = $students->filter(function ($s) {
                return $s->goal === 'AT RISK';
            })->map(function ($s) {
                return ucfirst(strtolower($s->lastname)) . ', ' . ucfirst(strtolower($s->firstname));
            })->values()->toArray();

            $summary['message'] = "$at_risk_count out of $total students are at risk and need improvement.";
            $summary['at_risk_names'] = $at_risk_names;
            $summary['status_color'] = "text-danger";
        } else {
            $summary['message'] = "All students no need to improve.";
            $summary['at_risk_names'] = [];
            $summary['status_color'] = "text-success";
        }

        return view('academic_report', compact(
            'subject', 'students', 'request', 'summary',
            'instructor', 'dean', 'mapping', 'academic_year', 'semester', 'term'
        ));
    }
}
