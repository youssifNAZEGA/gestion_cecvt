<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\ConseillerController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($user->role === 'conseiller') {
        return redirect('/conseiller/dashboard');
    } else {
        return redirect('/etudiant/dashboard');
}})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes étudiants
Route::middleware(['auth', 'verified'])->prefix('etudiant')->group(function () {
    Route::get('/dashboard', [EtudiantController::class, 'dashboard'])->name('etudiant.dashboard');
    Route::get('/dossiers', [EtudiantController::class, 'mesDossiers'])->name('etudiant.dossiers.index');
    Route::get('/dossiers/create', [EtudiantController::class, 'creerDossier'])->name('etudiant.dossiers.create');
    Route::post('/dossiers', [EtudiantController::class, 'enregistrerDossier'])->name('etudiant.dossiers.store');
    Route::get('/dossiers/{dossier}', [EtudiantController::class, 'voirDossier'])->name('etudiant.dossiers.show');
    Route::get('/dossiers/{dossier}/upload-recu', [EtudiantController::class, 'uploadRecu'])->name('etudiant.dossiers.upload-recu.form');
    Route::post('/dossiers/{dossier}/upload-recu', [EtudiantController::class, 'enregistrerRecu'])->name('etudiant.dossiers.enregistrer-recu');
    Route::get('/dossiers/{dossier}/upload-document', [EtudiantController::class, 'formulaireUploadDocument'])->name('etudiant.dossiers.upload-document.form');
    Route::post('/dossiers/{dossier}/upload-documents', [EtudiantController::class, 'uploadDocuments'])->name('etudiant.dossiers.upload-documents');
    Route::get('/dossiers/{dossier}/verifier-documents-conseiller',[EtudiantController::class, 'formulaireVerificationDocumentsConseiller'])->name('etudiant.dossiers.verifier-documents-conseiller.form');
    Route::post('/dossiers/{dossier}/verifier-documents-conseiller',[EtudiantController::class, 'verifierDocumentsConseiller'])->name('etudiant.dossiers.verifier-documents-conseiller');
    Route::post('/dossiers/{dossier}/confirmer-reception', [EtudiantController::class, 'confirmerReceptionEmail'])->name('etudiant.dossiers.confirmer-reception');
});

// Routes Conseillers
Route::middleware(['auth', 'verified'])->prefix('conseiller')->group(function () {
    Route::get('/dashboard', [ConseillerController::class, 'dashboard'])->name('conseiller.dashboard');
    Route::get('/dossiers', [ConseillerController::class, 'tousDossiers'])->name('conseiller.dossiers.index');
    Route::get('/dossiers/{dossier}', [ConseillerController::class, 'voirDossier'])->name('conseiller.dossiers.show');
    Route::put('/dossiers/{dossier}/statut', [ConseillerController::class, 'changerStatut'])->name('conseiller.dossiers.changer-statut');
    Route::post('/dossiers/{dossier}/verifier-paiement', [ConseillerController::class, 'verifierPaiement'])->name('conseiller.dossiers.verifier-paiement');
    //Route::post('/documents/{document}/verifier', [ConseillerController::class, 'verifierDocument'])->name('conseiller.documents.verifier');
    Route::post('/dossiers/{dossier}/soumettre', [ConseillerController::class, 'soumettreCampusFrance'])->name('conseiller.dossiers.soumettre');
    Route::get('/documents/{document}/verifier', [ConseillerController::class, 'formulaireVerificationDocument'])->name('conseiller.documents.verifier.form');
    Route::post('/documents/{document}/verifier', [ConseillerController::class, 'verifierDocument'])->name('conseiller.documents.verifier');
    Route::get('/dossiers/{dossier}/verifier-paiement', [ConseillerController::class, 'formulaireVerificationPaiement'])->name('conseiller.dossiers.verifier-paiement.form');
    Route::post('/dossiers/{dossier}/verifier-paiement', [ConseillerController::class, 'verifierPaiement'])->name('conseiller.dossiers.verifier-paiement');
    Route::get('/dossiers/{dossier}/traiter', [ConseillerController::class, 'formulaireTraitementDossier'])->name('conseiller.dossiers.traiter.form');
    Route::post('/dossiers/{dossier}/traiter', [ConseillerController::class, 'traiterDossier'])->name('conseiller.dossiers.traiter');
    Route::post('/dossiers/{dossier}/envoyer-email-suivi', [ConseillerController::class, 'envoyerEmailSuivi'])->name('conseiller.dossiers.envoyer-email-suivi');
});

// Routes Admin
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'listeUtilisateurs'])->name('admin.utilisateurs.index');
    Route::get('/utilisateurs/creer', [AdminController::class, 'creerUtilisateur'])->name('admin.utilisateurs.creer');
    Route::post('/utilisateurs', [AdminController::class, 'enregistrerUtilisateur'])->name('admin.utilisateurs.store');
    Route::get('/utilisateurs/{user}/edit', [AdminController::class, 'editerUtilisateur'])->name('admin.utilisateurs.edit');
    Route::put('/utilisateurs/{user}', [AdminController::class, 'modifierUtilisateur'])->name('admin.utilisateurs.update');
    Route::delete('/utilisateurs/{user}', [AdminController::class, 'supprimerUtilisateur'])->name('admin.utilisateurs.destroy');
    Route::get('/dossiers', [AdminController::class, 'listeDossiers'])->name('admin.dossiers.index');
    Route::get('/dossiers/{dossier}/assigner', [AdminController::class, 'formulaireAssignation'])->name('admin.dossiers.assigner.form');
    Route::post('/dossiers/{dossier}/assigner', [AdminController::class, 'assignerDossier'])->name('admin.dossiers.assigner');
    Route::get('/dossiers/{dossier}', [AdminController::class, 'voirDossier'])->name('admin.dossiers.show');
    Route::get('/performances', [AdminController::class, 'tableauBordPerformances'])->name('admin.performances.index');
});
require __DIR__.'/auth.php';
