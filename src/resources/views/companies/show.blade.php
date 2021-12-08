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
    <entry-component :entries="{{json_encode($entered_companies)}}" :statuses="{{json_encode($status)}}" :companies="{{json_encode($company)}}"></entry-component>
</div>
@endsection

