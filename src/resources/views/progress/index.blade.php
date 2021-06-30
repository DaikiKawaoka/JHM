@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">生徒進捗一覧</div>
            </div>
        </div>
    </div>
    @if(!($students->isEmpty()))
      <table class="table table-bordered" style="width: {{$table_width_px}}px;">
        <thead>
            <tr>
              <th rowspan="4" style="width: 65px; text-align: center; vertical-align: middle;  padding:0;">出席番号</th>
              <th rowspan="4" style="width: 100px; text-align: center; vertical-align: middle;  padding:0;">学生氏名</th>
              @for ($i = 0; $i < $most_many_entry_count ; $i++)
                <th colspan="{{$max_progress_count}}" style="width: 400px; text-align: center; padding:0;">応募先企業名</th>
              @endfor
            </tr>
            <tr style="width: 100px;">
              @for ($i = 0; $i < $most_many_entry_count ; $i++)
                @for($j = 1; $j <= $max_progress_count; $j++)
                <th style="text-align: center; padding:0;">選考{{$j}}</th>
                @endfor
              @endfor
            </tr>
            <tr style="width: {{$entry_column_width_px}}px;">
              @for ($i = 0; $i < $most_many_entry_count ; $i++)
                <th colspan="{{$max_progress_count}}" style="text-align: center; padding:0;">日付</th>
              @endfor
            </tr>
            <tr style="width: {{$entry_column_width_px}}px;">
              @for ($i = 0; $i < $most_many_entry_count ; $i++)
                <th colspan="{{$max_progress_count}}" style="text-align: center; padding:0;">結果</th>
              @endfor
            </tr>
        </thead>
        <tbody>
          @foreach($students as $student)
            <tr>
              <td rowspan="4" style="width: 65px; text-align: center; vertical-align: middle;  padding:0;">{{$student->attend_num}}</td>
              <td rowspan="4" style="width: 100px; text-align: center; vertical-align: middle;  padding:0;">{{$student->name}}</td>
              <?php $i = 0;?>
              @foreach($student->getMyEntries() as $entry)
                <td colspan="{{$max_progress_count}}" style="text-align: center; padding:0;">
                  {{$entry -> name}}
                </td>
                <?php $i++;?>
              @endforeach
              @if($i < $most_many_entry_count)
                @for(; $i < $most_many_entry_count; $i++)
                  <td colspan="{{$max_progress_count}}" style="text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>

            <tr>
              <?php $entry_count = 0;?>
              <!-- 自分がエントリーした会社数分ループし値を代入 -->
              @foreach($student->getMyEntries() as $entry)
                <?php $entry_count++;?>
                <?php $progress_count = 0;?>
                <!-- 1つのエントリーに登録した進捗分ループし値を代入 -->
                @foreach($entry->getProgressList() as $progress)
                  <?php $progress_count++;?>
                  <td style="width: 100px; text-align: center; padding:0;">
                    {{$progress->action}}
                  </td>
                @endforeach
                <!-- 1エントリーの進捗数を5に合わしテーブルを綺麗に見せるため、進捗数が5になるまでループで空の値を代入 -->
                @if($progress_count < $max_progress_count)
                  @for(; $progress_count < $max_progress_count; $progress_count++)
                    <td style="width: 100px; text-align: center; padding:0;">
                      &nbsp;
                    </td>
                  @endfor
                @endif
              @endforeach
              <!-- 生徒のエントリー数を合わせ、テーブルを正方形にするため、一番エントリーが多い人の数までループで空の値を代入 -->
              @if($entry_count < $most_many_entry_count)
                @for($count = $entry_count * $max_progress_count; $count < $most_many_entry_count * $max_progress_count; $count++)
                  <td style="width: 100px; text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>

            <!-- 上のfor文と構造は同じ（1つのエントリーに5つの進捗を入れる） -->
            <tr>
              <?php $entry_count = 0;?>
              @foreach($student->getMyEntries() as $entry)
                <?php $entry_count++;?>
                <?php $progress_count = 0;?>
                @foreach($entry->getProgressList() as $progress)
                  <?php $progress_count++;?>
                  <td style="width: 100px; text-align: center; padding:0;">
                    {{$progress->action_date->format('Y/m/d')}}
                  </td>
                @endforeach
                @if($progress_count < $max_progress_count)
                  @for(; $progress_count < $max_progress_count; $progress_count++)
                    <td style="width: 100px; text-align: center; padding:0;">
                      &nbsp;
                    </td>
                  @endfor
                @endif
              @endforeach
              @if($entry_count < $most_many_entry_count)
                @for($count = $entry_count * $max_progress_count; $count < $most_many_entry_count * $max_progress_count; $count++)
                  <td style="width: 100px; text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>

            <!-- 上のfor文と構造は同じ（1つのエントリーに5つの進捗を入れる） -->
            <tr>
              <?php $entry_count = 0;?>
              @foreach($student->getMyEntries() as $entry)
                <?php $entry_count++;?>
                <?php $progress_count = 0;?>
                @foreach($entry->getProgressList() as $progress)
                  <?php $progress_count++;?>
                  <td style="width: 100px; text-align: center; padding:0;">
                    {{$progress->state}}
                  </td>
                @endforeach
                @if($progress_count < $max_progress_count)
                  @for(; $progress_count < $max_progress_count; $progress_count++)
                    <td style="width: 100px; text-align: center; padding:0;">
                      &nbsp;
                    </td>
                  @endfor
                @endif
              @endforeach
              @if($entry_count < $most_many_entry_count)
                @for($count = $entry_count * $max_progress_count; $count < $most_many_entry_count * $max_progress_count; $count++)
                  <td style="width: 100px; text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <!-- 生徒が登録されていない場合 -->
      <h1 style="text-align: center;">生徒が登録されていません。</h1>
    @endif
</div>
@endsection