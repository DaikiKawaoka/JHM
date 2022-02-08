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
            @if($member->count() == 0)
                <div class="card">
                    <div class="card-header text-center">{{ __('生徒を登録しましょう') }}</div>

                    <div class="card-body p-5">
                        　是非、ワークスペースに生徒を登録してください。生徒を登録していただくと、エントリーしている企業や就活の進捗を把握することができます。
                        <p class="text-right mt-3 mr-3"><a href="{{ route('workspaces.addStudentsShow',['workspace_id'=> $workspace_id]) }}">今すぐ登録する</a></p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
