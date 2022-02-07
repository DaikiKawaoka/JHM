@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            @if(Auth::user()->is_teacher())
                <div class="card-header">{{ __('求人登録') }}</div>
            @else
                <div class="card-header">{{ __('') }}</div>
            @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('studentCompanies.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('会社名') }}</label>

                            <div class="col-md-7">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="prefecture" class="col-md-4 col-form-label text-md-right">勤務先</label>
                            <div class="col-md-7">
                                <select class="form-control" id="prefecture" name="prefecture" value="{{ old('prefecture') }}" autocomplete="prefecture">
                                    <option value="北海道">北海道</option>
                                    <option value="青森">青森</option>
                                    <option value="東京">東京</option>
                                    <option value="大阪">大阪</option>
                                    <option value="愛媛" selected>愛媛</option>
                                    <option value="福岡">福岡</option>
                                    <option value="沖縄">沖縄</option>
                                </select>
                                @error('prefecture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('確認') }}</label>
                            <div class="col-md-7">
                                <h6>※この会社情報は{{Auth::user()->name}}さんと先生のみ閲覧できます。</h6><br>
                                <h6>※会社情報を登録した時点でエントリーしているとみなします</h6><br>
                                <h6>※他の生徒がこの会社情報を見てエントリーすることはありません。</h6><br>
                                <h6>※この会社情報は先生が{{Auth::user()->name}}さんの就活の進捗を確認するときに使用されます。</h6>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('登録') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection