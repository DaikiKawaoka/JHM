@extends('layouts.app')

@section('content')
<div class="container">
    <calendar-component :progress="{{json_encode($progress)}}"></calendar-component>
</div>
@endsection
