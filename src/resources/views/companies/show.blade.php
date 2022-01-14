@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">会社詳細ページ</div>
            </div>
        </div>
    </div>
    <entry-component :entry="{{json_encode($entry)}}" :statuses="{{json_encode($status)}}" :company="{{json_encode($company)}}" :csrf="{{json_encode(csrf_token())}}"></entry-component>
</div>
@endsection

