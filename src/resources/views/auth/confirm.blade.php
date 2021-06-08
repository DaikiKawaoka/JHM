@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('確認') }}</div>

                <div class="card-body">

                        <div class="form-group row mb-0">
                        <div>
                        <p>このアプリは生徒が自らアカウント作成をすることができません。<br>
                        生徒の場合は教師にアカウントを作ってもらってください。</p>
                        </div>

                        <div class="col-md-8 offset-md-4">
                        <h4>教師ですか？</h4>
                        </div>
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-primary" href="{{ url('/register?teacher=true') }}" role="button">{{ __('教師です') }}</a>
                                <a class="btn btn-primary" href="{{ route('login') }}" role="button">{{ __('違います') }}</a>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
