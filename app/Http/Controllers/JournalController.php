<?php

namespace App\Http\Controllers;

use App\Models\JournalTheme;
use App\Models\JournalIssue;
use App\Models\SiteSetting; // JANGAN LUPA IMPORT MODEL INI
use App\Enums\SubmissionStatus;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // 1. Fungsi untuk Landing Page
    public function show($slug)
    {
        $settings = SiteSetting::first(); // AMBIL DATA SETTING
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        
        // Kirim $settings ke view
        return view('journal-detail', compact('journalTheme', 'settings'));
    }

    // 2. Fungsi untuk Halaman Arsip (Daftar Volume)
    public function archive($slug)
    {
        $settings = SiteSetting::first(); // AMBIL DATA SETTING
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        
        $issues = JournalIssue::where('journal_theme_id', $journalTheme->id)
                    ->where('is_active', true)
                    ->orderBy('year', 'desc')
                    ->paginate(12);

        // Kirim $settings ke view
        return view('journal-archive', compact('journalTheme', 'issues', 'settings'));
    }

    // 3. Fungsi untuk Detail Isi Volume (Daftar Paper)
    public function issueDetail($slug, $issue_id)
    {
        $settings = SiteSetting::first(); // AMBIL DATA SETTING
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        
        $issue = JournalIssue::where('id', $issue_id)
                    ->where('is_active', true)
                    ->with(['submissions' => function($query) {
                        $query->where('status', \App\Enums\SubmissionStatus::PUBLISHED);
                    }])
                    ->firstOrFail();

        // Kirim $settings ke view
        return view('issue-detail', compact('journalTheme', 'issue', 'settings'));
    }

    // 4. Fungsi untuk Detail Artikel / Paper
    public function articleDetail($slug, $id)
    {
        $settings = SiteSetting::first(); // AMBIL DATA SETTING
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        
        // Ambil data naskah berdasarkan ID, pastikan statusnya PUBLISHED
        $article = \App\Models\Submission::with(['author', 'journalIssue'])
                    ->where('id', $id)
                    ->where('status', \App\Enums\SubmissionStatus::PUBLISHED)
                    ->firstOrFail();

        // Keamanan ekstra: Pastikan Issue-nya masih aktif
        if (!$article->journalIssue->is_active) {
            abort(404, 'Artikel tidak tersedia atau ditarik.');
        }

        // Kirim $settings ke view
        return view('article-detail', compact('journalTheme', 'article', 'settings'));
    }
}