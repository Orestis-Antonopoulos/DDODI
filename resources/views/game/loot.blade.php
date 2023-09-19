@extends('layouts.app')

@section('content')
<form action="{{ route('game.play') }}" method="POST">
    @csrf
    <button type="submit">Continue</button>
</form>
<p>You earned {{ round($experienceAfter - $experienceBefore) }} exp points.</p>
<p>levelup value: {{ $level_up }}</p>
@endsection
