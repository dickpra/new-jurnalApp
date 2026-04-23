@extends('layouts.public')

@section('title', $cmsPage->title . ' - ' . ($settings->site_name ?? 'SustainScript'))

@section('content')
<div class="py-16 bg-white min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-serif font-bold text-slate-900 mb-8 border-b pb-4">
            {{ $cmsPage->title }}
        </h1>
        
        <div class="prose prose-slate max-w-none text-justify leading-relaxed">
            {!! $cmsPage->content !!}
        </div>
    </div>
</div>

@endsection