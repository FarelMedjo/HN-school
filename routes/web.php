<?php

use App\Http\Controllers\Admin\AnneeAcademiqueController;
use App\Http\Controllers\Admin\BulletinController;
use App\Http\Controllers\Admin\AppreciationController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\ConvocationController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\CycleController;
use App\Http\Controllers\Admin\EleveController;
use App\Http\Controllers\Admin\EmploiDuTempsController;
use App\Http\Controllers\Admin\EmpruntController;
use App\Http\Controllers\Admin\EnseignantController;
use App\Http\Controllers\Admin\EtablissementController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\LivreController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\RemarqueController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\ScolariteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\Finance\ModePaiementController as FinanceModeController;
use App\Http\Controllers\Finance\PaiementController as FinancePaiementController;
use App\Http\Controllers\Finance\ScolariteController as FinanceScolariteController;
use App\Http\Controllers\Scolarite\AnneeAcademiqueController as ScolariteAnneeController;
use App\Http\Controllers\Scolarite\ClasseController as ScolariteClasseController;
use App\Http\Controllers\Scolarite\CycleController as ScolariteCycleController;
use App\Http\Controllers\Scolarite\EleveController as ScolariteEleveController;
use App\Http\Controllers\Scolarite\EvaluationController as ScolariteEvaluationController;
use App\Http\Controllers\Scolarite\SalleController as ScolariteSalleController;
use App\Http\Controllers\Scolarite\ScolariteController as ScolariteSpaceController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Tuteur\TuteurController;
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
    if ($user->hasRole('scolarite')) {
        return redirect()->route('scolarite.dashboard');
    }
    if ($user->hasRole('finance')) {
        return redirect()->route('finance.dashboard');
    }
    abort(403, 'Aucun rôle attribué.');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');

    Route::resource('annees', AnneeAcademiqueController::class)->parameters(['annees' => 'annee']);
    Route::resource('cycles', CycleController::class);
    Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
    Route::resource('salles', SalleController::class);
    Route::resource('eleves', EleveController::class)->parameters(['eleves' => 'eleve']);
    Route::post('eleves/{eleve}/affecter', [EleveController::class, 'affecter'])->name('eleves.affecter');
    Route::resource('enseignants', EnseignantController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('parents', ParentController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('cours', CoursController::class);
    Route::resource('paiements', PaiementController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('scolarites', ScolariteController::class);
    Route::resource('evaluations', EvaluationController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::resource('messages', MessageController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('bulletins', [BulletinController::class, 'index'])->name('bulletins.index');
    Route::get('bulletins/{eleve}/{trimestre}', [BulletinController::class, 'show'])->name('bulletins.show');

    Route::resource('convocations', ConvocationController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::resource('appreciations', AppreciationController::class)->only(['index', 'store', 'destroy'])->parameters(['appreciations' => 'appreciation']);
    Route::resource('remarques', RemarqueController::class)->only(['index', 'store', 'destroy']);

    Route::get('presences', [PresenceController::class, 'index'])->name('presences.index');
    Route::post('presences', [PresenceController::class, 'store'])->name('presences.store');
    Route::get('presences/eleve/{eleve}', [PresenceController::class, 'eleve'])->name('presences.eleve');

    Route::get('emploi-du-temps', [EmploiDuTempsController::class, 'index'])->name('emploi-du-temps.index');
    Route::post('emploi-du-temps', [EmploiDuTempsController::class, 'store'])->name('emploi-du-temps.store');
    Route::delete('emploi-du-temps/{emploiDuTemp}', [EmploiDuTempsController::class, 'destroy'])->name('emploi-du-temps.destroy');

    Route::get('horaires', [EmploiDuTempsController::class, 'horaires'])->name('horaires.index');
    Route::post('horaires', [EmploiDuTempsController::class, 'storeHoraire'])->name('horaires.store');
    Route::delete('horaires/{horaire}', [EmploiDuTempsController::class, 'destroyHoraire'])->name('horaires.destroy');

    Route::resource('livres', LivreController::class)->except(['show']);
    Route::resource('emprunts', EmpruntController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::patch('emprunts/{emprunt}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');

    Route::get('utilisateurs', [UserController::class, 'index'])->name('utilisateurs.index');
    Route::patch('utilisateurs/{user}/role', [UserController::class, 'updateRole'])->name('utilisateurs.role');
    Route::patch('utilisateurs/{user}/actif', [UserController::class, 'toggleActive'])->name('utilisateurs.actif');

    Route::get('etablissement', [EtablissementController::class, 'edit'])->name('etablissement.edit');
    Route::post('etablissement', [EtablissementController::class, 'update'])->name('etablissement.update');
});

Route::middleware(['auth', 'role:enseignant'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('cours/{cours}/eleves', [TeacherController::class, 'eleves'])->name('cours.eleves');
    Route::get('cours/{cours}/presences', [TeacherController::class, 'presences'])->name('cours.presences');
    Route::post('cours/{cours}/presences', [TeacherController::class, 'storePresences'])->name('cours.presences.store');
    Route::get('cours/{cours}/notes', [TeacherController::class, 'notes'])->name('cours.notes');
    Route::post('cours/{cours}/notes', [TeacherController::class, 'storeNotes'])->name('cours.notes.store');
    Route::get('emploi-du-temps', [TeacherController::class, 'emploiDuTemps'])->name('emploi-du-temps');

    Route::get('vie-scolaire', [TeacherController::class, 'vieScolaire'])->name('vie-scolaire');
    Route::post('vie-scolaire/appreciations', [TeacherController::class, 'storeAppreciation'])->name('vie-scolaire.appreciations');
    Route::post('vie-scolaire/remarques', [TeacherController::class, 'storeRemarque'])->name('vie-scolaire.remarques');
    Route::post('vie-scolaire/convocations', [TeacherController::class, 'storeConvocation'])->name('vie-scolaire.convocations');
    Route::get('convocations/{convocation}', [TeacherController::class, 'convocationShow'])->name('convocations.show');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/', [TuteurController::class, 'dashboard'])->name('dashboard');
    Route::get('enfant/{eleve}', [TuteurController::class, 'enfant'])->name('enfant.show');
    Route::get('enfant/{eleve}/notes', [TuteurController::class, 'notes'])->name('enfant.notes');
    Route::get('enfant/{eleve}/absences', [TuteurController::class, 'presences'])->name('enfant.presences');
    Route::get('enfant/{eleve}/paiements', [TuteurController::class, 'paiements'])->name('enfant.paiements');
    Route::get('enfant/{eleve}/bulletin/{trimestre}', [TuteurController::class, 'bulletin'])->name('enfant.bulletin');
    Route::get('messages', [TuteurController::class, 'messages'])->name('messages');
    Route::get('messages/{message}', [TuteurController::class, 'showMessage'])->name('messages.show');
    Route::get('enfant/{eleve}/emploi-du-temps', [TuteurController::class, 'emploiDuTemps'])->name('enfant.emploi-du-temps');
    Route::get('enfant/{eleve}/emprunts', [TuteurController::class, 'emprunts'])->name('enfant.emprunts');
    Route::get('enfant/{eleve}/vie-scolaire', [TuteurController::class, 'vieScolaire'])->name('enfant.vie-scolaire');
    Route::get('convocations/{convocation}', [TuteurController::class, 'convocationShow'])->name('convocations.show');
});

Route::middleware(['auth', 'role:eleve'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('notes', [StudentController::class, 'notes'])->name('notes');
    Route::get('absences', [StudentController::class, 'absences'])->name('absences');
    Route::get('emploi-du-temps', [StudentController::class, 'emploiDuTemps'])->name('emploi-du-temps');
    Route::get('bulletin', [StudentController::class, 'bulletin'])->name('bulletin');
    Route::get('bulletin/{trimestre}', [StudentController::class, 'bulletinShow'])->name('bulletin.show');
    Route::get('emprunts', [StudentController::class, 'emprunts'])->name('emprunts');
    Route::get('vie-scolaire', [StudentController::class, 'vieScolaire'])->name('vie-scolaire');
    Route::get('convocations/{convocation}', [StudentController::class, 'convocationShow'])->name('convocations.show');
});

Route::middleware(['auth', 'role:scolarite'])->prefix('scolarite')->name('scolarite.')->group(function () {
    Route::get('/', [ScolariteSpaceController::class, 'dashboard'])->name('dashboard');

    Route::resource('eleves', ScolariteEleveController::class)->parameters(['eleves' => 'eleve']);
    Route::post('eleves/{eleve}/affecter', [ScolariteEleveController::class, 'affecter'])->name('eleves.affecter');

    Route::resource('annees', ScolariteAnneeController::class)->parameters(['annees' => 'annee']);
    Route::resource('cycles', ScolariteCycleController::class);
    Route::resource('classes', ScolariteClasseController::class)->parameters(['classes' => 'classe']);
    Route::resource('salles', ScolariteSalleController::class);

    Route::resource('evaluations', ScolariteEvaluationController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::get('presences', [ScolariteSpaceController::class, 'presences'])->name('presences.index');
    Route::post('presences', [ScolariteSpaceController::class, 'storePresences'])->name('presences.store');
    Route::get('presences/eleve/{eleve}', [ScolariteSpaceController::class, 'presenceEleve'])->name('presences.eleve');

    Route::get('emploi-du-temps', [ScolariteSpaceController::class, 'emploiDuTemps'])->name('emploi-du-temps.index');

    Route::get('bulletins', [ScolariteSpaceController::class, 'bulletins'])->name('bulletins.index');
    Route::get('bulletins/{eleve}/{trimestre}', [ScolariteSpaceController::class, 'bulletinShow'])->name('bulletins.show');

    Route::get('convocations', [ScolariteSpaceController::class, 'convocations'])->name('convocations.index');
    Route::get('convocations/create', [ScolariteSpaceController::class, 'createConvocation'])->name('convocations.create');
    Route::post('convocations', [ScolariteSpaceController::class, 'storeConvocation'])->name('convocations.store');
    Route::get('convocations/{convocation}', [ScolariteSpaceController::class, 'convocationShow'])->name('convocations.show');
    Route::delete('convocations/{convocation}', [ScolariteSpaceController::class, 'destroyConvocation'])->name('convocations.destroy');

    Route::get('appreciations', [ScolariteSpaceController::class, 'appreciations'])->name('appreciations.index');
    Route::post('appreciations', [ScolariteSpaceController::class, 'storeAppreciation'])->name('appreciations.store');
    Route::delete('appreciations/{appreciation}', [ScolariteSpaceController::class, 'destroyAppreciation'])->name('appreciations.destroy');

    Route::get('remarques', [ScolariteSpaceController::class, 'remarques'])->name('remarques.index');
    Route::post('remarques', [ScolariteSpaceController::class, 'storeRemarque'])->name('remarques.store');
    Route::delete('remarques/{remarque}', [ScolariteSpaceController::class, 'destroyRemarque'])->name('remarques.destroy');
});

Route::middleware(['auth', 'role:finance'])->prefix('finance')->name('finance.')->group(function () {
    Route::get('/', [FinanceController::class, 'dashboard'])->name('dashboard');

    Route::resource('paiements', FinancePaiementController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('scolarites', FinanceScolariteController::class)->except(['show']);
    Route::resource('modes', FinanceModeController::class)->except(['show']);

    Route::get('soldes', [FinanceController::class, 'soldes'])->name('soldes.index');

    Route::get('rapports', [FinanceController::class, 'rapports'])->name('rapports.index');
    Route::get('rapports/export', [FinanceController::class, 'export'])->name('rapports.export');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
