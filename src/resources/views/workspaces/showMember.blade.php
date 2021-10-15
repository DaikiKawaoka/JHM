@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="list-group my-4 text-center">
                @foreach($member as $student)
                    <li class="list-group-item">{{$student->name}}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
