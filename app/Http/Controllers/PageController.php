<?php
namespace App\Http\Controllers;

use App\Models\JournalTheme;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::first();
        $themes = JournalTheme::all();
        return view('welcome', compact('settings', 'themes'));
    }

    // --- TAMBAHKAN METHOD INI ---
    // Menggunakan route model binding berdasarkan 'slug'
    public function show(\App\Models\JournalTheme $journalTheme)
    {
        $settings = \App\Models\SiteSetting::first();
        // Laravel otomatis mengisi $journalTheme berdasarkan slug di URL!
        return view('journal-detail', compact('settings', 'journalTheme'));
    }
}