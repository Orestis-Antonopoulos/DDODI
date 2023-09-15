@extends('layouts.app')

@section('content')
<form action="{{ route('game.play') }}" method="POST">
    @csrf
    <button type="submit">Continue</button>
</form>
You earned {{ round($experienceAfter - $experienceBefore) }} exp points.
@endsection
