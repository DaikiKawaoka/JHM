@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if (session('status-error'))
        <div class="alert alert-danger ">
            {{ session('status-error') }}
        </div>
    @endif
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">エントリー済み会社一覧</div>
            </div>
        </div>
    </div>
    @if (!($entered_companies->isEmpty()))
    <h4>求人からエントリーした会社一覧</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">会社名</th>
            <th scope="col">現在の進捗</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entered_companies as $company)
            <tr>
                <td><a class="" href="/companies/{{ $company->id }}">{{ $company->name }}</a></th>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <h4>{{ Auth::user()->name }}さんが登録した会社一覧</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">会社名</th>
            <th scope="col">現在の進捗</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entered_student_companies as $company)
            <tr>
                <td><a class="" href="/student/companies/{{ $company->id }}">{{ $company->name }}</a></th>
            </tr>
            @endforeach
        </tbody>
    </table>
        @else
            <h3 style="text-align: center;">エントリーした会社がないです。</h3>
        @endif
</div>
@endsection