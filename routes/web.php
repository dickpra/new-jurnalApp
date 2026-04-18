<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;

// ==========================================
// 2. JEMBATAN LOGIN BREEZE
// ==========================================
// Bawaan Breeze selalu melempar user ke '/dashboard' setelah login.
// Kita tangkap rute itu di sini, lalu arahkan paksa ke dashboard author kita.
Route::get('/dashboard', function () {
    return redirect()->route('author.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- ROUTE PUBLIK ---
Route::get('/', [\App\Http\Controllers\PageController::class, 'index'])->name('home');
// KEMBALIKAN KE SLUG
Route::get('/jurnal/{journalTheme:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('jurnal.show');

// --- ROUTE AUTHOR ---
Route::middleware(['auth', 'verified'])->prefix('author')->name('author.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AuthorController::class, 'index'])->name('dashboard');
    
    // KEMBALIKAN KE SLUG
    Route::get('/submit/{journalTheme:slug}', [\App\Http\Controllers\AuthorController::class, 'create'])->name('submit');
    Route::post('/submit/{journalTheme:slug}', [\App\Http\Controllers\AuthorController::class, 'store'])->name('store');

    Route::get('/submissions/{id}', [\App\Http\Controllers\AuthorController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{id}/download', [\App\Http\Controllers\AuthorController::class, 'download'])->name('submissions.download');

    Route::get('/submissions/{id}/revision', [AuthorController::class, 'editRevision'])->name('submissions.revision');
    Route::post('/submissions/{id}/revision', [AuthorController::class, 'storeRevision'])->name('submissions.store_revision');

    Route::post('/submissions/{id}/payment', [\App\Http\Controllers\AuthorController::class, 'storePayment'])->name('submissions.payment');
    Route::get('/submissions/{id}/loa', [\App\Http\Controllers\AuthorController::class, 'generateLoa'])->name('submissions.loa');
});


// ==========================================
// 4. ROUTE PROFILE (Bawaan Breeze)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
// ROUTE REVIEWER
// ==========================================
Route::middleware(['auth', 'verified'])->prefix('reviewer')->name('reviewer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ReviewerController::class, 'index'])->name('dashboard');
    
    // Gunakan {id} agar aman
    Route::get('/evaluate/{id}', [\App\Http\Controllers\ReviewerController::class, 'show'])->name('evaluate.show');
    Route::post('/evaluate/{id}', [\App\Http\Controllers\ReviewerController::class, 'store'])->name('evaluate.store');
    Route::get('/download/{id}', [\App\Http\Controllers\ReviewerController::class, 'download'])->name('download');
});


/// RUTE FILE VIEWER (ENCRYPTED + ANTI-IDOR + SILENT 404)
    Route::get('/secure-file/{payload}', function($payload) {
        try {
            // 1. Coba buka gembok URL-nya
            $data = decrypt($payload);
            $id = $data['id'];
            $type = $data['type'];
        } catch (\Exception $e) {
            // Jika gembok rusak/dimanipulasi hacker, pura-pura file tidak ada
            abort(404);
        }

        $submission = \App\Models\Submission::find($id);
        if (!$submission) abort(404);

        $user = auth()->user();

        // 2. IDENTIFIKASI USER
        $isAuthor = $submission->author_id === $user->id;
        $isReviewer = \App\Models\Review::where('submission_id', $id)
            ->where('reviewer_id', $user->id)
            ->exists();
        $isManager = in_array($user->role ?? '', ['super_admin', 'manager']); 

        // 3. SATPAM PENJAGA PINTU (Mode Bisu)
        if ($type === 'manuscript') {
            // Jika bukan Author/Reviewer/Manager -> 404 Not Found
            if (!$isAuthor && !$isReviewer && !$isManager) abort(404);
        } elseif (in_array($type, ['payment', 'loa'])) {
            // Pembayaran & LOA dilarang untuk Reviewer (Blind Review)
            if (!$isAuthor && !$isManager) abort(404);
        } else {
            abort(404);
        }

        // 4. AMBIL FILE
        $column = match($type) {
            'manuscript' => 'manuscript_file',
            'payment' => 'payment_proof',
            'loa' => 'loa_file',
            default => null,
        };

        if (!$column || !$submission->{$column}) abort(404);
        
        $path = $submission->{$column};

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404);
        }
        
        return response()->file(storage_path('app/' . $path));
        
    })->name('secure.file');

// 1. Landing Page Jurnal
Route::get('/journal/{slug}', [JournalController::class, 'show'])->name('journal.show');

// 2. Daftar Archive (Grid Sampul)
Route::get('/journal/{slug}/archive', [JournalController::class, 'archive'])->name('journal.archive');

// 3. Detail Isi Volume (Daftar Paper)
Route::get('/journal/{slug}/issue/{issue_id}', [JournalController::class, 'issueDetail'])->name('journal.issue.detail');

require __DIR__.'/auth.php';