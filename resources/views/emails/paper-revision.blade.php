<x-mail::message>
# Editorial Decision: Revision Required

Dear **{{ $submission->author->name }}**,

Thank you for your submission titled **"{{ $submission->title }}"**. Following a thorough peer-review process, the editorial board has decided that your manuscript requires **REVISION** before it can be reconsidered for publication.

<x-mail::panel>
**Reviewer Feedback & Instructions:**

{!! nl2br(e($customMessage)) !!}
</x-mail::panel>

Please address the comments above and upload your revised manuscript through your Author Workspace.

<x-mail::button :url="route('author.dashboard')">
Submit Revision
</x-mail::button>

Sincerely,<br>
**Editorial Board**<br>
{{ $submission->journalTheme->name }}
</x-mail::message>