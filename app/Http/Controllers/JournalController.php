<?php

namespace App\Http\Controllers;

use App\Models\JournalTheme;
use App\Models\JournalIssue;
use App\Enums\SubmissionStatus;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // 1. Fungsi untuk Landing Page (Kode kamu)
    public function show($slug)
    {
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        // Kirim $settings juga jika kamu punya model Setting
        return view('journal-detail', compact('journalTheme'));
    }

    // 2. Fungsi BARU untuk Halaman Arsip
    public function archive($slug)
    {
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        
        $issues = JournalIssue::where('journal_theme_id', $journalTheme->id)
                    ->where('is_active', true) // FIX: Sekarang yang non-aktif tidak akan muncul!
                    ->orderBy('year', 'desc')
                    ->paginate(12);

        return view('journal-archive', compact('journalTheme', 'issues'));
    }

    public function issueDetail($slug, $issue_id)
    {
        $journalTheme = JournalTheme::where('slug', $slug)->firstOrFail();
        
        $issue = JournalIssue::where('id', $issue_id)
                    ->where('is_active', true)
                    ->with(['submissions' => function($query) {
                        $query->where('status', \App\Enums\SubmissionStatus::PUBLISHED);
                    }])
                    ->firstOrFail();

        return view('issue-detail', compact('journalTheme', 'issue'));
    }
}