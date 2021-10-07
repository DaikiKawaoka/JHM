@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('先生アカウント編集') }}</div>
                    <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @error('password'|'password_current') @else active @enderror" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                    プロフィール
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @error('password') active @enderror
                            @error('password_current') active @enderror" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">
                                    パスワード
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">

                            <div class="tab-pane fade
                            @error('password'|'password_current') @else show active @enderror" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form action="{{route('users.updateTeacherProfile', $user->id)}}" method="post">
                                    @csrf
                                    {{ method_field('PUT') }}

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('名前') }}</label>
                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}"  autofocus>
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label for="class" class="col-md-4 col-form-label text-md-right">{{ __('クラス') }}</label>
                                        <div class="col-md-6">
                                            <input id="class" type="text" class="form-control @error('class') is-invalid @enderror" name="class" value="{{ old('class') ?? $user->class }}"  autofocus>
                                        </div>
                                        @error('class')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('教師メールアドレス') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $user->email }}" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4 text-right">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('変更') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <div class="tab-pane fade mt-4
                            @error('password') show active @enderror
                            @error('password_current') show active @enderror" id="password" role="tabpanel" aria-labelledby="password-tab">
                                <form action="{{route('users.updatePassword', $user->id)}}" method="post">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="form-group row">
                                        <label for="password-current" class="col-md-4 col-form-label text-md-right">{{ __('現在のパスワード') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-current" type="password" class="form-control @error('password_current') is-invalid @enderror" name="password_current" required autocomplete="new-password">
                                        </div>

                                        @error('password_current')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-new" class="col-md-4 col-form-label text-md-right">{{ __('新しいパスワード') }}</label>
                                        <div class="col-md-6">
                                            <input id="password-new" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        </div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('新しいパスワード（確認）') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4 text-right">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('変更') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection