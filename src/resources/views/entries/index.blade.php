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
    <div class="companies_container">
        <ul class="companies_list">
            @foreach ($entered_companies as $entered_company)
            <li class="companies_elements">
            <a class="companies_link" href="/companies/{{ $entered_company->id }}">
                    <p class="companies_name">{{ $entered_company->name }}</p>
                </a>
                @if(!($progress_list == NULL))
                <br/>
                <br/>
                <br/>
                <!-- <h1 style="text-align: center;">進捗状況</h1> -->
                <!-- <img src="{{ asset('img/no_image_square.jpg')}}" class="companies_pic"> -->
                <br/>
                <h3>最近の活動</h3>
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
                    @if (array_keys($progress)[0] == $entered_company->id)
                    <tr>
                        <td scope="row">{{ $progress[$entered_company->id]->action }}</th>
                        <td>{{ $progress[$entered_company->id]->state }}</td>
                        <td>{{ $progress[$entered_company->id]->action_date->format('Y年m月d日') }}</td>
                        <td>
                            <a class="btn btn-secondary" href="/companies/{{ $entered_company->id }}/edit" role="button">編集</a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
                @else
                <br/>
                <br/>
                <br/>
                <h1 style="text-align: center;">現在進捗が登録されていません。</h1>
                @endif
                </li>
            @endforeach
        </ul>
    @else
        <h3 style="text-align: center;">エントリーした会社がないです。</h3>
    @endif
</div>
@endsection