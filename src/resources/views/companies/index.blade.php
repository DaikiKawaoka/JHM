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
  <div class="companies_container">
    <ul class="companies_list">
      @foreach ($companies as $company)
      <li class="companies_elements">
        <a class="companies_link" href="/companies/{{ $company->id }}">
          <p class="companies_name">{{ $company->name }}</p>
          @if ($company->deadline)
          <p class="deadline">締切日：
          {{ $company->deadline->format('Y年m月d日') }}
          @endif
          </p>
          <img src="{{ asset('img/no_image_square.jpg')}}" class="companies_pic">
          <div class="entries_delete_btn">
            @if(!($user->is_teacher()))
            @if($entries[$company->id])
            <form action="{{route('entries.destroy', $entries[$company->id]->id)}}" method="post">
              {{ csrf_field() }}
              {{ method_field('delete') }}
              <button type="submit" class="btn btn-danger" id="cancel_btn">取り消し</button>
            </form>
            @else
            <form action="{{route('entries.store')}}" method='post'>
              {{ csrf_field() }}
              {{ method_field('POST') }}
              <input type="submit" name="entry" value="エントリー" class="btn btn-success" id="entry_btn">
              <input type="hidden" name="company_id" value="{{ $company->id }}">
            </form>
            @endif
          </div>
          @endif
          @if($user->is_teacher())
          @if($company->create_user_id == $user->id)
          <div class="edit_delete_btn">
            <a class="btn btn-success" id="edit_btn" href="/companies/{{ $company->id }}/edit" role="button">編集</a>

            <form action="{{route('companies.destroy', $company->id)}}" method='post' name="delete_form">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <input type="submit" name="delete" class="btn btn-danger" id="delete_btn" value="削除">
            </form>
          </div>
          @else
          <div>&nbsp;</div>
          <div>&nbsp;</div>
          @endif
          @endif
        </a>
      </li>
      @endforeach
    </ul>
    @else
    <h3 style="text-align: center;">求人がないです。</h3>
    @endif
    {{ $companies->links() }}
  </div>
</div>
@endsection