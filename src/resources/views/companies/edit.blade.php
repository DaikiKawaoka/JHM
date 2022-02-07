@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('会社編集') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('companies.update',$company->id) }}" enctype="multipart/form-data">
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
                            <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('ホームページURL') }}</label>

                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url', isset($company->url) ? $company->url : '') }}" autocomplete="url" autofocus>

                                @error('url')
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

                        <div class="form-group row">
                          <label for="deadline" class="col-md-4 col-form-label text-md-right">応募締切</label>
                          <div class="col-md-6">
                            <!-- Unixstrtotime:タイムスタンプ変換 -->
                            <input type="date" class="form-control  @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ isset($company->deadline) ? date('Y-m-d',strtotime($company->deadline)) : ''}}">
                              @error('deadline')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>

                        @php
                            $pdf_num = 1
                        @endphp
                        @foreach($pdf as $p)
                        <div class="form-group row">
                            <label for="" class="col-md-4 col-form-label text-md-right">PDF{{$pdf_num}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{$p->pdf}}.pdf" disabled>
                            </div>
                            <div class="col-md-2">
                                <delete-modal :is_delete_btn="true" :csrf="{{json_encode(csrf_token())}}" delete_url="/companies/{{$company->id}}/pdf/{{$p->id}}/destroy"></delete-modal>
                            </div>
                        </div>
                            @php
                                $pdf_num++
                            @endphp
                        @endforeach

                        @for($i=$pdf->count(); $i < 3; $i++)
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">PDF{{$pdf_num}}</label>
                            <div class="col-md-6">
                                <input type="file" name="pdf{{$i+1}}" class="form-control">
                            </div>
                        </div>
                            @php
                                $pdf_num++
                            @endphp
                        @endfor

                        <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('備考') }}</label>
                          <div class="col-md-6">
                              <textarea id="remarks" class="form-control" name='remarks' placeholder="備考" rows="8">{{ trim($company->remarks) }}</textarea>
                              @error('remarks')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 mx-auto my-2 text-sm-right">
                                ※PDF1はサムネイルとして表示されます
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