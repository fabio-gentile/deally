<x-mail::message>

@foreach ($introLines as $line)
<div style="font-weight: 700; color: #1a202c">
{{ $line }}
</div>
<br>
@foreach($deals as $deal)
{{ $deal->title }}
{{ Str::limit($deal->content, 20) }}
@endforeach
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

