@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('会社編集') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('studentCompanies.update',$company->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('会社名') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', isset($company->name) ? $company->name : '') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                          <label for="prefecture" class="col-md-4 col-form-label text-md-right">勤務先</label>
                          <div class="col-md-6">
                              <select class="form-control" id="prefecture" name="prefecture" autocomplete="prefecture">
                                <option value="北海道" @if($company['prefecture']=='北海道') selected @endif>北海道</option>
                                <option value="青森" @if($company['prefecture'] == '青森') selected @endif>青森</option>
                                <option value="東京" @if($company['prefecture'] == '東京') selected @endif>東京</option>
                                <option value="大阪" @if($company['prefecture'] == '大阪') selected @endif>大阪</option>
                                <option value="愛媛" @if($company['prefecture'] == '愛媛') selected @endif>愛媛</option>
                                <option value="福岡" @if($company['prefecture'] == '福岡') selected @endif>福岡</option>
                                <option value="沖縄" @if($company['prefecture'] == '沖縄') selected @endif>沖縄</option>
                              </select>
                              @error('prefecture')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('更新') }}
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