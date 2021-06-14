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
            <th scope="col">締切日</th>
            <th scope="col">登録者名</th>
            <th scope="col">登録日</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($entered_companies as $company)
            <tr>
              <td scope="row">{{ $company->id }}</th>
              <td>{{ $company->name }}</th>
              <td>{{ $company->prefecture }}</td>
              <td>{{ $company->url }}</td>
              @if ($company->deadline)
                <td>{{ $company->deadline->format('Y年m月d日') }}</td>
              @else
                <td>null</td>
              @endif
              <td>{{ $company->create_user_name }}</td>
              @if ($company->created_at)
                <td>{{ $company->created_at->format('Y年m月d日') }}</td>
              @else
                <td>null</td>
              @endif
            </tr>
          @endforeach
        </tbody>
        </table>
        @else
          <h3 style="text-align: center;">エントリーした会社がないです。</h3>
        @endif
</div>
@endsection