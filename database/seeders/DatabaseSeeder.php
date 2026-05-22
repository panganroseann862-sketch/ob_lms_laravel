<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123')
        ]);

        // ===== GRADES =====
        DB::table('grades')->truncate();

        $students    = DB::table('students')->get();
        $assessments = DB::table('assessments')->get();

        foreach ($assessments as $assessment) {
            foreach ($students as $student) {
                // 20% At Risk, 40% Passed, 40% Excellent
                $rand = rand(1, 100);

                if ($rand <= 20) {
                    $score = rand(50, 74);   // At Risk
                } elseif ($rand <= 60) {
                    $score = rand(75, 89);   // Passed
                } else {
                    $score = rand(90, 100);  // Excellent
                }

                DB::table('grades')->insert([
                    'student_id'    => $student->id,
                    'subject_id'    => $assessment->subject_id,
                    'assessment_id' => $assessment->id,
                    'term'          => $assessment->term,
                    'score'         => $score,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
