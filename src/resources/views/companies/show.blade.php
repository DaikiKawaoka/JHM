@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(Auth::user()->is_teacher())
        <company-show :company="{{json_encode($company)}}" all_entry_count={{$allEntryCount}} :class_entry={{$classEntry}}></company-show>
    @else
        <entry-component :entry="{{json_encode($entry)}}" :statuses="{{json_encode($status)}}" :company="{{json_encode($company)}}" :csrf="{{json_encode(csrf_token())}}"></entry-component>
    @endif
</div>
@endsection

