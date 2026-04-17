<x-mail::message>
# Editorial Decision: Accepted

Dear **{{ $submission->author->name }}**,

Congratulations! We are pleased to inform you that your manuscript titled **"{{ $submission->title }}"** has been **ACCEPTED** for publication in *{{ $submission->journalTheme->name }}*.

<x-mail::panel>
**Editor's Final Comments:**

{!! nl2br(e($customMessage)) !!}
</x-mail::panel>

Your manuscript will now proceed to the final stages. Please log in to your dashboard to complete any remaining requirements, such as payment or final formatting.

<x-mail::button :url="route('author.dashboard')">
Go to Dashboard
</x-mail::button>

Best regards,<br>
**Editorial Office**<br>
{{ $submission->journalTheme->name }}
</x-mail::message>