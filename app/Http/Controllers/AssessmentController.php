<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function showLogin() {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['username' => 'Invalid Credentials'])->withInput();
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function dashboard() {
        return view('dashboard', [
            'activeStudents'    => DB::table('students')->count(),
            'enrolledSubjects'  => DB::table('subjects')->count(),
            'passedEvaluations' => DB::table('assessments')->count(),
            'recentStudents'    => DB::table('students')->orderBy('id', 'desc')->limit(5)->get()
        ]);
    }

    public function manageStudents() {
        return view('manage_students', ['students' => DB::table('students')->get()]);
    }

    public function storeStudent(Request $request) {
        // Insert student
        $studentId = DB::table('students')->insertGetId([
            'student_id_no' => $request->student_id_no,
            'firstname'     => $request->firstname,
            'lastname'      => $request->lastname,
            'created_at'    => now()
        ]);

        // Auto-insert grades for all existing assessments
        $assessments = DB::table('assessments')->get();
        foreach ($assessments as $assessment) {
            $rand = rand(1, 100);
            if ($rand <= 20) {
                $score = rand(50, 74);
            } elseif ($rand <= 60) {
                $score = rand(75, 89);
            } else {
                $score = rand(90, 100);
            }

            DB::table('grades')->insert([
                'student_id'    => $studentId,
                'subject_id'    => $assessment->subject_id,
                'assessment_id' => $assessment->id,
                'term'          => $assessment->term,
                'score'         => $score,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Student added!');
    }

    public function deleteStudent(int $id) {
        DB::table('grades')->where('student_id', $id)->delete();
        DB::table('students')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Student deleted!');
    }

    public function manageSubjects() {
        return view('manage_subjects', ['subjects' => DB::table('subjects')->get()]);
    }

    public function storeSubject(Request $request) {
        DB::table('subjects')->insert([
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
            'instructor'   => $request->instructor,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);
        return redirect()->back()->with('success', 'Subject saved!');
    }

    public function updateSubject(Request $request, int $id) {
        DB::table('subjects')->where('id', $id)->update([
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
            'instructor'   => $request->instructor,
            'updated_at'   => now()
        ]);
        return redirect()->back()->with('success', 'Subject updated!');
    }

    public function deleteSubject(int $id) {
        DB::table('subjects')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Subject deleted!');
    }

    public function manageAssessments() {
        $subjects = DB::table('subjects')->get();

        $assessments = DB::table('assessments')
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select('assessments.*', 'subjects.subject_name', 'subjects.subject_code')
            ->orderBy('assessments.id', 'desc')
            ->get();

        return view('assessment', compact('subjects', 'assessments'));
    }

    public function store(Request $request) {
        $request->validate([
            'school_year'     => 'required',
            'semester'        => 'required',
            'subject_id'      => 'required',
            'assessment_type' => 'required',
            'term'            => 'required',
            'po_id'           => 'required|array'
        ]);

        $po_string = implode(',', $request->input('po_id'));

        // Insert assessment
        $assessmentId = DB::table('assessments')->insertGetId([
            'school_year' => $request->school_year,
            'semester'    => $request->semester,
            'subject_id'  => $request->subject_id,
            'name'        => $request->assessment_type,
            'term'        => $request->term,
            'po_id'       => $po_string,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        // Auto-insert grades for all existing students
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            $rand = rand(1, 100);
            if ($rand <= 20) {
                $score = rand(50, 74);
            } elseif ($rand <= 60) {
                $score = rand(75, 89);
            } else {
                $score = rand(90, 100);
            }

            DB::table('grades')->insert([
                'student_id'    => $student->id,
                'subject_id'    => $request->subject_id,
                'assessment_id' => $assessmentId,
                'term'          => $request->term,
                'score'         => $score,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return redirect()->route('assessment')->with('success', 'Mapping Saved Successfully!');
    }

    public function destroy(int $id) {
        DB::table('grades')->where('assessment_id', $id)->delete();
        DB::table('assessments')->where('id', $id)->delete();
        return redirect()->route('assessment')->with('success', 'Mapping deleted successfully!');
    }
}
