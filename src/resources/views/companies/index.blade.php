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
                <div class="card-header">求人一覧</div>
            </div>
        </div>
    </div>
    @if (!($companies->isEmpty()))
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
            <th scope="col">エントリー</th>
            <th scope="col">編集</th>
            <th scope="col">削除</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($companies as $company)
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
              <td>
                <form action="{{route('entries.store')}}" method='post'>
                  {{ csrf_field() }}
                  {{ method_field('POST') }}
                  <input type="submit" name="entry" value="エントリー" class="btn btn-success">
                  <input type="hidden" name="company_id" value="{{ $company->id }}">
                </form>
              </td>
              <td>
                <a class="btn btn-secondary" href="/companies/{{ $company->id }}/edit" role="button">編集</a>
              </td>
              <td>
                <form action="{{route('companies.destroy', $company->id)}}" method='post' name="delete_form">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <input type="submit" name="delete" class="btn btn-danger" value="削除" onClick="delete_alert(event);return false;">
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
        </table>
        @else
          <h3 style="text-align: center;">求人がないです。</h3>
        @endif
    {{ $companies->links() }}
</div>
@endsection