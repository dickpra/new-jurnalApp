<x-mail::message>
# Editorial Decision: Rejected

Dear **{{ $submission->author->name }}**,

Thank you for submitting your manuscript titled **"{{ $submission->title }}"** to the *{{ $submission->journalTheme->name }}*.

<x-mail::panel>
**Editor & Reviewer Notes:**

{!! nl2br(e($customMessage)) !!}
</x-mail::panel>

We appreciate your interest in our journal and wish you the best in finding a suitable venue for your research. 

<x-mail::button :url="route('author.dashboard')">
View Submission Details
</x-mail::button>

Sincerely,<br>
**Editorial Board**<br>
{{ $submission->journalTheme->name }}
</x-mail::message>