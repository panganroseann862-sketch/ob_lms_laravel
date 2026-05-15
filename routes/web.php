<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes (Login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AssessmentController::class, 'showLogin'])->name('login');
    Route::post('/login', [AssessmentController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AssessmentController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AssessmentController::class, 'dashboard'])->name('dashboard');

    // Students
    Route::prefix('students')->group(function () {
        Route::get('/', [AssessmentController::class, 'manageStudents'])->name('students.index');
        Route::post('/store', [AssessmentController::class, 'storeStudent'])->name('students.store');
        Route::delete('/delete/{id}', [AssessmentController::class, 'deleteStudent'])->name('students.delete');
    });

    // Subjects — PINALITAN NG PATCH
    Route::prefix('subjects')->group(function () {
        Route::get('/', [AssessmentController::class, 'manageSubjects'])->name('subjects.index');
        Route::post('/store', [AssessmentController::class, 'storeSubject'])->name('subjects.store');
        Route::patch('/update/{id}', [AssessmentController::class, 'updateSubject'])->name('subjects.update');
        Route::delete('/delete/{id}', [AssessmentController::class, 'deleteSubject'])->name('subjects.delete');
    });

    // Assessments (Mapping)
    Route::prefix('assessments')->group(function () {
        Route::get('/', [AssessmentController::class, 'manageAssessments'])->name('assessment');
        Route::post('/store', [AssessmentController::class, 'store'])->name('assessments.store');
        Route::delete('/delete/{id}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });
});

// System Fix Routes
Route::get('/fix', function () {
    Artisan::call('optimize:clear');
    return 'SYSTEM FIXED: Cache cleared.';
});

Route::get('/force-fix-db', function() {
    try {
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('po_id')->change();
        });
        return 'DATABASE COLUMN FIXED: po_id is now a string. Pwede na mag-save!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
