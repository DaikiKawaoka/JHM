@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" id="student-add-col">
        <form method="POST" action="{{ route('workspaces.createWorkspaceStudents') }}">

        @csrf
        @foreach($students as $student)
            <div class="list">
                <input class="student-add-checkbox" id="toggle{{$student->id}}" type="checkbox" name="students[]" value="{{$student->id}}">
                <label class="student-add-label" for="toggle{{$student->id}}">{{$student->name}}：{{$student->email}}</label>
            </div>
        @endforeach

        <button type='submit' class="student-add-btn">
        {{ __('登録') }}
        </button>
        </div>
        </form>
    </div>
</div>
@endsection
