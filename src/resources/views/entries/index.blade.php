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
    <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">会社名</th>
            <th scope="col">勤務場所</th>
            <th scope="col">URL</th>
            <th scope="col">action</th>
            <th scope="col">state</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($entered_companies as $company)
            <tr>
              <td scope="row">{{ $company->id }}</th>
              <td><a class="" href="/companies/{{ $company->id }}">{{ $company->name }}</a></th>
              <td>{{ $company->prefecture }}</td>
              <td>{{ $company->url }}</td>
            </tr>
          @endforeach
        </tbody>
        </table>
        @else
          <h3 style="text-align: center;">エントリーした会社がないです。</h3>
        @endif
</div>
@endsection