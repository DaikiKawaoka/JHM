@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <student-company-show :company="{{json_encode($company)}}" :entry="{{json_encode($entry)}}" :statuses="{{$progress_list}}" :csrf="{{json_encode(csrf_token())}}" delete_url="{{route('studentCompanies.destroy',$company->id)}}"></student-company-show>
</div>
@endsection
