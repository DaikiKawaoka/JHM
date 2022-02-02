@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <progress-component :csrf="{{json_encode(csrf_token())}}"></progress-component>
</div>
@endsection