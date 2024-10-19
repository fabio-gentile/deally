<x-mail::message>

@foreach ($introLines as $line)
<div style="font-weight: 700; color: #1a202c">
{{ $line }}
</div>
<br>
<p><strong>Name:</strong> {{ $name }}</p>
<p><strong>Email:</strong> {{  $email  }}</p>
<p><strong>Sujet:</strong> {{  $subject  }}</p>
<p><strong>Message:</strong></p>
<p>{{ $message }}</p>
@endforeach
</x-mail::message>

