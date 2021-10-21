@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('求人登録') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('companies.store') }}">
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

                        @if(Auth::user()->is_teacher())

                            <div class="form-group row">
                                <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('ホームページURL') }}</label>

                                <div class="col-md-7">
                                    <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" autocomplete="url" autofocus>

                                    @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="deadline" class="col-md-4 col-form-label text-md-right">応募締切</label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control  @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline') }}" autocomplete="deadline">
                                    @error('deadline')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('備考') }}</label>
                                <div class="col-md-7">
                                    <textarea id="remarks" class="form-control" name='remarks' placeholder="備考" rows="8">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                        @endif

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