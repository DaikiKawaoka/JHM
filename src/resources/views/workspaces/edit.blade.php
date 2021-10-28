@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">{{ __('ワークスペース編集') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{route('workspaces.update', $workspace->id)}}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group row">
                            <label for="class_name" class="col-md-4 col-form-label text-md-right">{{ __('クラス名') }}</label>

                            <div class="col-md-6">
                                <input id="class_name" type="text" class="form-control @error('class_name') is-invalid @enderror" name="class_name" value="{{ old('class_name', isset($workspace->class_name) ? $workspace->class_name : '') }}" required  autofocus>

                                @error('class_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="year" class="col-md-4 col-form-label text-md-right">{{ __('年度') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" id="year" name="year" @if(old('year')) value="{{ old('year') }}" @endif>
                                    @foreach($years as $year)
                                        <option value="{{$year}}" @if($year==$this_year) selected @endif>{{$year}}</option>
                                    @endforeach
                                </select>

                                @error('year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row my-3 ">
                            <div class="col-md-6 offset-md-4 d-flex">
                                <button type="submit" class="btn btn-primary mr-4">
                                    {{ __('更新') }}
                                </button>
                                <delete-modal delete_url="{{route('workspaces.destroy', $workspace->id)}}" :csrf="{{json_encode(csrf_token())}}"></delete-modal>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection