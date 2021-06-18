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
                <div class="card-header">会社詳細ページ</div>
            </div>
        </div>
    </div>
    @if ($entry)
        <div class="alert alert-success ">
            あなたはこの求人にエントリー済みです。
        </div>
    @endif
    @if ($company)
      <table class="table table-bordered">
          <thead>
              <tr>
              <th scope="col">ID</th>
              <th scope="col">会社名</th>
              @if (!($entry))
                <th scope="col">エントリー</th>
              @endif
              </tr>
          </thead>
          <tbody>
              <tr>
                <td scope="row">{{ $company->id }}</th>
                <td>{{ $company->name }}</th>
                @if (!($entry))
                  <td>
                  <form action="{{route('entries.store')}}" method='post'>
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <input type="submit" name="entry" value="エントリー" class="btn btn-success">
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                  </form>
                  </td>
                @endif
              </tr>
          </tbody>
      </table>
    @endif
    @if ($entry)
      @if(!($progress_list->isEmpty()))
      <br/>
      <br/>
      <br/>
      <br/>
        <h1 style="text-align: center;">進捗状況</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                <th scope="col">イベント</th>
                <th scope="col">状況</th>
                <th scope="col">実施日</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($progress_list as $progress)
                <tr>
                  <td scope="row">{{ $progress->action }}</th>
                  <td>{{ $progress->state }}</td>
                  <td>{{ $progress->action_date->format('Y年m月d日') }}</td>
                  <td>
                    <a class="btn btn-secondary" href="/companies/{{ $company->id }}/edit" role="button">編集</a>
                  </td>
                  <td>
                    <form action="{{route('progress.destroy', $progress->id)}}" method='post'>
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <input type="submit" name="delete" class="btn btn-danger" value="削除">
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
      @else
      <br/>
      <br/>
      <br/>
      <h1 style="text-align: center;">現在進捗が登録されていません。</h1>
      @endif

      <br/>
      <br/>
      <br/>
      <br/>
      <h1 style="text-align: center;">進捗登録</h1>
      <form action="{{route('progress.store')}}" method='post' name="progress">
        {{ csrf_field() }}
        {{ method_field('POST') }}
        <table class="table table-bordered">
          <thead>
              <tr>
              <th scope="col">イベント</th>
              <th scope="col">状況</th>
              <th scope="col">実施日</th>
              </tr>
          </thead>
          <tbody>
            <tr>
              <td scope="row">
                <select class="form-select" name="action">
                  <option value="会社説明会">会社説明会</option>
                  <option value="試験受験">試験受験（SPI,筆記など）</option>
                  <option value="一次面接">一次面接</option>
                  <option value="二次面接">二次面接</option>
                  <option value="三次面接">三次面接</option>
                  <option value="社長面接">社長面接</option>
                </select>
              </td>
              <td>
                <select class="form-select" name="state">
                  <option value="待ち">待ち</option>
                  <option value="◯">◯</option>
                  <option value="×">×</option>
                  <option value="内々定">内々定</option>
                  <option value="欠席">欠席</option>
                </select>
              </td>
              <td>
                <input type="date" class="form-control" name="action_date" required autocomplete="action_date">
              </td>
              <td><input type="submit" name="progress" value="登録" class="btn btn-success"></td>
              <input type="hidden" name="company_id" value="{{ $company->id }}">
            </tr>
          </tbody>
        </table>
      </form>
    @endif
</div>
@endsection
