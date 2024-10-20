<x-mail::message>
@foreach ($introLines as $line)
<div style="font-weight: 700; color: #1a202c">
{{ $line }}
</div>

<br>
{{ $blog->title }}
{{ Str::limit(strip_tags($blog->content), 20) }}
@endforeach

@isset($actionText)

<x-mail::button :url="$actionUrl" :color="'primary'">
{{ $actionText }}
</x-mail::button>
@endisset

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards,')<br>
{{ config('app.name') }}
@endif

</x-mail::message>

