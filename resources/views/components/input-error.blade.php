@props(['for'])

@error($for)
	<span {{ $attributes->merge(['class' => 'invalid-feedback text-left']) }} role="alert" style="display:block;" >
        <strong>{{ $message }}</strong>
    </span>
@enderror