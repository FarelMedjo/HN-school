<?php

use App\Http\Controllers\Admin\AnneeAcademiqueController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\CycleController;
use App\Http\Controllers\Admin\EleveController;
use App\Http\Controllers\Admin\EnseignantController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\ScolariteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('enseignant')) {
        return redirect()->route('teacher.dashboard');
    }
    if ($user->hasRole('parent')) {
        return redirect()->route('parent.dashboard');
    }
    if ($user->hasRole('eleve')) {
        return redirect()->route('student.dashboard');
    }
    abort(403, 'Aucun rôle attribué.');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');

    Route::resource('annees', AnneeAcademiqueController::class)->parameters(['annees' => 'annee']);
    Route::resource('cycles', CycleController::class);
    Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
    Route::resource('salles', SalleController::class);
    Route::resource('eleves', EleveController::class);
    Route::post('eleves/{eleve}/affecter', [EleveController::class, 'affecter'])->name('eleves.affecter');
    Route::resource('enseignants', EnseignantController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('parents', ParentController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('cours', CoursController::class);
    Route::resource('paiements', PaiementController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('scolarites', ScolariteController::class);
    Route::resource('evaluations', EvaluationController::class)->only(['index', 'create', 'store', 'destroy']);
});

Route::middleware(['auth', 'role:enseignant'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::view('/', 'teacher.dashboard')->name('dashboard');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::view('/', 'parent.dashboard')->name('dashboard');
});

Route::middleware(['auth', 'role:eleve'])->prefix('student')->name('student.')->group(function () {
    Route::view('/', 'student.dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
