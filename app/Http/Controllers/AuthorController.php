<?php

namespace App\Http\Controllers;

use App\Models\JournalTheme;
use App\Models\Submission;
use App\Enums\SubmissionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AuthorController extends Controller
{
    // Menampilkan Dashboard Author (Daftar Naskah)
    public function index()
    {
        // Ambil semua naskah milik user yang sedang login
        $submissions = Submission::with('journalTheme')
            ->where('author_id', Auth::id())
            ->latest()
            ->get();

        return view('author.dashboard', compact('submissions'));
    }

    public function create(\App\Models\JournalTheme $journalTheme)
    {
        return view('author.create-submission', compact('journalTheme'));
    }

    public function store(Request $request, \App\Models\JournalTheme $journalTheme)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'co_authors' => 'nullable|array', // Validasi array
            'manuscript_file' => 'required|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $request->file('manuscript_file')->store('submissions', 'local');

        // Proses array co-authors untuk membuang input yang kosong
        $coAuthors = null;
        if ($request->filled('co_authors')) {
            $coAuthors = array_filter($request->co_authors, function($author) {
                return !empty($author['name']); 
            });
        }

        \App\Models\Submission::create([
            'journal_theme_id' => $journalTheme->id, 
            'author_id' => Auth::id(),
            'title' => $request->title,
            'abstract' => $request->abstract,
            'manuscript_file' => $path,
            'status' => \App\Enums\SubmissionStatus::PENDING,
            'current_round' => 1,
            'keywords' => $request->keywords,
            'co_authors' => empty($coAuthors) ? null : array_values($coAuthors), // Simpan sebagai JSON

        ]);

        return redirect()->route('author.dashboard')->with('success', 'Naskah berhasil dikirim ke ' . $journalTheme->name);
    }

    // Menampilkan Detail Naskah
    // Show Manuscript Details
    public function show($id)
    {
        $submission = \App\Models\Submission::with('journalTheme')->findOrFail($id);

        // Security check: Only the owner can view
        if ($submission->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action. This is not your manuscript.');
        }

        return view('author.show-submission', compact('submission'));
    }

    // Download Manuscript File
    public function download($id)
    {
        $submission = \App\Models\Submission::findOrFail($id);

        if ($submission->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return Storage::disk('local')->download($submission->manuscript_file);
    }

    public function editRevision($id)
    {
        $submission = Submission::findOrFail($id);
        if ($submission->author_id !== Auth::id() || $submission->status->value !== 'revision_required') {
            abort(403);
        }
        return view('author.upload-revision', compact('submission'));
    }

    public function storeRevision(Request $request, $id)
    {
        $submission = \App\Models\Submission::findOrFail($id);
        
        // 1. SATPAM IDENTITAS (ANTI-IDOR)
        // Pastikan yang nge-submit adalah Author pemilik naskah itu sendiri
        if ($submission->author_id !== auth()->id()) {
            abort(403, 'Akses Ditolak! Anda bukan pemilik naskah ini.');
        }

        // 2. SATPAM STATUS (ANTI-ZOMBIE)
        // Pastikan hanya naskah yang berstatus "Butuh Revisi" yang bisa diubah
        if ($submission->status->value !== 'revision_required') {
            abort(403, 'Akses Ditolak! Naskah ini tidak sedang dalam masa revisi.');
        }

        $request->validate([
            'revised_file' => 'required|mimes:pdf,doc,docx|max:10240', // Max 10MB
        ]);

        // 3. BERSIH-BERSIH FILE LAMA (Pencegah Hardisk Penuh)
        // Hapus file naskah ronde sebelumnya sebelum menyimpan yang baru
        if ($submission->manuscript_file && \Illuminate\Support\Facades\Storage::disk('local')->exists($submission->manuscript_file)) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($submission->manuscript_file);
        }

        // 4. SIMPAN FILE REVISI BARU
        $path = $request->file('revised_file')->store('submissions', 'local');

        // 5. UPDATE DATABASE
        $submission->update([
            'manuscript_file' => $path,
            'status' => \App\Enums\SubmissionStatus::UNDER_REVIEW, // Lempar kembali ke antrean Manager
            'current_round' => $submission->current_round + 1, // Naik ronde!
        ]);

        return redirect()->route('author.dashboard')->with('success', 'Revised manuscript has been uploaded. Round ' . $submission->current_round . ' starts now.');
    }

    public function storePayment(Request $request, $id)
    {
        $submission = \App\Models\Submission::findOrFail($id);
        
        if ($submission->author_id !== Auth::id() || $submission->status->value !== 'accepted') {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Maks 5MB, format gambar
        ]);

        $path = $request->file('payment_proof')->store('payments', 'local');

        $submission->update([
            'payment_proof' => $path,
            'payment_status' => 'pending_verification',
        ]);

        return redirect()->back()->with('success', 'Payment proof uploaded! Awaiting Manager verification.');
    }

    public function generateLoa($id)
    {
        $submission = \App\Models\Submission::with(['author', 'journalTheme'])->findOrFail($id);

        if ($submission->author_id !== \Illuminate\Support\Facades\Auth::id() || $submission->payment_status !== 'paid') {
            abort(404); // Buat silent error juga di sini
        }

        // Jika Manager sudah upload file LOA Custom, enkripsi URL-nya
        if ($submission->loa_file) {
            $payload = encrypt(['id' => $submission->id, 'type' => 'loa']);
            return redirect()->route('secure.file', $payload);
        }

        return view('author.loa-print', compact('submission'));
    }
}