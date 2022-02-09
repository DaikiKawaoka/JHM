@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('確認') }}</div>

                <div class="card-body">

                        <div class="form-group row mb-0">

                        <div class="col-md-8 offset-md-4">
                        <h4>教師ですか？生徒ですか？</h4>
                        </div>
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-primary" href="{{ url('/register?teacher=true') }}" role="button">{{ __('教師です') }}</a>
                                <a class="btn btn-primary mx-5" href="{{ route('students.create')  }}" role="button">{{ __('生徒です') }}</a>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
