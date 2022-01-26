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
  <companies-teacher-component :companies="{{json_encode($companies)}}" :user="{{json_encode($user)}}" :entries="{{json_encode($entries)}}" :is_teacher="{{json_encode($is_teacher)}}" :csrf="{{json_encode(csrf_token())}}"></companies-teacher-component>
</div>
@endsection