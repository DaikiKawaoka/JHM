@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user()->is_teacher())
        <calendar-component :progress="{{json_encode($progress)}}" :schedules="{{json_encode($schedule)}}" is_teacher={{$is_teacher}} :csrf="{{json_encode(csrf_token())}}"></calendar-component>
    @else
        <calendar-component :schedules="{{json_encode($schedule)}}" is_teacher={{$is_teacher}}></calendar-component>
    @endif
</div>
@endsection
