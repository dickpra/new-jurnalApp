<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Acceptance - {{ $submission->author->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background: #e5e7eb; }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            margin: 1cm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        @media print {
            body { background: white; margin: 0; }
            .page { margin: 0; box-shadow: none; padding: 1.5cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="font-sans text-stone-800">

    <div class="text-center py-4 no-print bg-stone-900 text-white">
        <p class="mb-2">This is an automatically generated Letter of Acceptance.</p>
        <button onclick="window.print()" class="px-6 py-2 bg-stone-100 text-stone-900 font-bold uppercase tracking-widest text-sm hover:bg-white transition">
            Print / Save as PDF
        </button>
    </div>

    <div class="page border-4 border-double border-stone-200">
        
        <div class="text-center border-b-2 border-stone-800 pb-6 mb-10">
            <h1 class="text-4xl font-serif font-bold text-stone-900 uppercase tracking-widest mb-2">Letter of Acceptance</h1>
            <p class="text-lg font-serif text-stone-600 uppercase tracking-widest">{{ $submission->journalTheme->name }}</p>
            <p class="text-xs text-stone-500 mt-2">Ref ID: {{ strtoupper(substr(md5($submission->id), 0, 8)) }} | Date: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        </div>

        <div class="text-justify font-serif text-lg leading-loose mb-10">
            <p class="mb-6">Dear <span class="font-bold text-stone-900">{{ $submission->author->name }}</span>,</p>
            
            <p class="mb-6">
                We are pleased to inform you that your manuscript submitted to <span class="font-bold italic">{{ $submission->journalTheme->name }}</span> has successfully passed the blind peer-review process and has been <span class="font-bold text-green-700">ACCEPTED</span> for publication.
            </p>

            <div class="bg-stone-50 p-6 border border-stone-200 mb-6">
                <p class="text-xs uppercase tracking-widest text-stone-500 mb-1">Manuscript Title:</p>
                <h2 class="text-xl font-bold text-stone-900 leading-snug">{{ $submission->title }}</h2>
            </div>

            <p class="mb-6">
                The editorial board commends the quality of your research. This official letter serves as confirmation that your payment has been verified and your manuscript will be included in the upcoming publication cycle.
            </p>

            <p>Thank you for your valuable contribution to our journal.</p>
        </div>

        <div class="mt-20 flex justify-end">
            <div class="text-center">
                <p class="font-serif mb-12">Sincerely,</p>
                <div class="border-b border-stone-400 w-48 mb-2 mx-auto"></div>
                <p class="font-bold text-stone-900 font-serif">Chief Editor</p>
                <p class="text-xs text-stone-500 uppercase tracking-widest">{{ $submission->journalTheme->name }}</p>
            </div>
        </div>

    </div>

</body>
</html>