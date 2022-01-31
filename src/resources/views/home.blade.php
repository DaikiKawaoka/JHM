@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    @if (Auth::user()->is_teacher)
                        <h2>ようこそ、{{ Auth::user()->name }}先生！</h2>
                    @else
                        <h2>ようこそ、{{ Auth::user()->name }}さん！</h2>
                    @endif

                    <div class="btn-group btn-toolbar">
                        @if (Auth::user()->is_teacher)
                            <div class="top-right links mr-2 mb-2">
                                <a class="btn btn-danger" href="{{ route('progress.index') }}" role="button">生徒進捗一覧ページ</a>
                            </div>
                            <div class="top-right links mr-2 mb-2">
                                <a class="btn btn-success" href="{{ url('/users/create') }}" role="button">生徒登録ページ</a>
                            </div>
                        @else
                            <div class="top-right links mr-2 mb-2">
                                <a class="btn btn-success" href="{{ url('/entries') }}" role="button">エントリー済み会社一覧</a>
                            </div>
                        @endif
                        <div class="top-right links mr-2 mb-2">
                            <a class="btn btn-success" href="{{ url('/companies/create') }}" role="button">求人登録ページ</a>
                        </div>
                        <div class="top-right links mr-2 mb-2">
                            <a class="btn btn-success" href="{{ url('/companies') }}" role="button">求人一覧ページ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
