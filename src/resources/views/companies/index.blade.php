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
        <p class="companies_name"><a class="companies_name" href="/companies/{{ $company->id }}">{{ $company->name }}</a></p>
        <p class="deadline">締切日：
          @if ($company->deadline)
          {{ $company->deadline->format('Y年m月d日') }}
          @else
          null
          @endif
        </p>
        <img src="https://1.bp.blogspot.com/-D2I7Z7-HLGU/Xlyf7OYUi8I/AAAAAAABXq4/jZ0035aDGiE5dP3WiYhlSqhhMgGy8p7zACNcBGAsYHQ/s400/no_image_square.jpg" class="companies_pic">
        <div class="entries_delete_btn">
          @if(!($user->is_teacher()))
          @if($entries[$company->id])
          <form action="{{route('entries.destroy', $entries[$company->id]->id)}}" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button type="submit" class="btn btn-danger">取り消し</button>
          </form>
          @else
          <form action="{{route('entries.store')}}" method='post'>
            {{ csrf_field() }}
            {{ method_field('POST') }}
            <input type="submit" name="entry" value="エントリー" class="btn btn-success">
            <input type="hidden" name="company_id" value="{{ $company->id }}">
          </form>
          @endif
        </div>
        @endif
        @if($user->is_teacher())
        @if($company->create_user_id == $user->id)
        <div class="edit_delete_btn">
          <a class="btn btn-success" href="/companies/{{ $company->id }}/edit" role="button">編集</a>

          <form action="{{route('companies.destroy', $company->id)}}" method='post' name="delete_form">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <input type="submit" name="delete" class="btn btn-danger" value="削除">
          </form>
        </div>
        @else
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        @endif
        @endif
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