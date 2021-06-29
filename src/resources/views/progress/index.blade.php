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
              @for ($i = 0; $i < $max_entry_count ; $i++)
                <th colspan="5" style="width: 400px; text-align: center; padding:0;">応募先企業名</th>
              @endfor
            </tr>
            <tr style="width: 100px;">
              @for ($i = 0; $i < $max_entry_count ; $i++)
                <th style="text-align: center; padding:0;">選考1</th>
                <th style="text-align: center; padding:0;">選考2</th>
                <th style="text-align: center; padding:0;">選考3</th>
                <th style="text-align: center; padding:0;">選考4</th>
                <th style="text-align: center; padding:0;">選考5</th>
              @endfor
            </tr>
            <tr style="width: 500px;">
              @for ($i = 0; $i < $max_entry_count ; $i++)
                <th colspan="5" style="text-align: center; padding:0;">日付</th>
              @endfor
            </tr>
            <tr style="width: 500px;">
              @for ($i = 0; $i < $max_entry_count ; $i++)
                <th colspan="5" style="text-align: center; padding:0;">結果</th>
              @endfor
            </tr>
        </thead>
        <tbody>
          @foreach($students as $student)
            <tr>
              <td rowspan="4" style="width: 65px; text-align: center; vertical-align: middle;  padding:0;">{{$student->attend_num}}</td>
              <td rowspan="4" style="width: 100px; text-align: center; vertical-align: middle;  padding:0;">{{$student->name}}</td>
              <?php $i = 0;?>
              @foreach($entry_list as $entry)
                @if($student->id == $entry->user_id)
                  <td colspan="5" style="width: 400px; text-align: center; padding:0;">
                    {{$entry -> name}}
                  </td>
                  <?php $i++;?>
                @endif
              @endforeach
              @if($i < $max_entry_count)
                @for(; $i < $max_entry_count; $i++)
                  <td colspan="5" style="width: 400px; text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>

            <?php
              $my_entries=[];
              foreach($entry_list as $entry){
                if($student->id == $entry->user_id){
                  $my_entries[] = $entry;
                }
              }
            ?>

            <tr>
              <?php $entry_num = 0;?>
              <!-- 自分がエントリーした会社数分ループし値を代入 -->
              @foreach($my_entries as $entry)
                <?php $entry_num++;?>
                <?php $progress_num = 0;?>
                <!-- 1つのエントリーに登録した進捗分ループし値を代入 -->
                @foreach($progress_list as $progress)
                  @if($entry->id == $progress->entry_id && $progress->user_id == $student->id)
                    <?php $progress_num++;?>
                    <td style="width: 100px; text-align: center; padding:0;">
                      {{$progress->action}}
                    </td>
                  @endif
                @endforeach
                <!-- 1エントリーの進捗数を5に合わしテーブルを綺麗に見せるため、進捗数が5になるまでループで空の値を代入 -->
                @if($progress_num < 5)
                  @for(; $progress_num < 5; $progress_num++)
                    <td style="width: 100px; text-align: center; padding:0;">
                      &nbsp;
                    </td>
                  @endfor
                @endif
              @endforeach
              <!-- 生徒のエントリー数を合わせ、テーブルを正方形にするため、一番エントリーが多い人の数までループで空の値を代入 -->
              @if($entry_num < $max_entry_count)
                @for($count = $entry_num * 5; $count < $max_entry_count * 5; $count++)
                  <td style="width: 100px; text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>

            <!-- 上のfor文と構造は同じ（1つのエントリーに5つの進捗を入れる） -->
            <tr>
              <?php $entry_num = 0;?>
              @foreach($my_entries as $entry)
                <?php $entry_num++;?>
                <?php $progress_num = 0;?>
                @foreach($progress_list as $progress)
                  @if($entry->id == $progress->entry_id && $progress->user_id == $student->id)
                    <?php $progress_num++;?>
                    <td style="width: 100px; text-align: center; padding:0;">
                      {{$progress->action_date}}
                    </td>
                  @endif
                @endforeach
                @if($progress_num < 5)
                  @for(; $progress_num < 5; $progress_num++)
                    <td style="width: 100px; text-align: center; padding:0;">
                      &nbsp;
                    </td>
                  @endfor
                @endif
              @endforeach
              @if($entry_num < $max_entry_count)
                @for($count = $entry_num * 5; $count < $max_entry_count * 5; $count++)
                  <td style="width: 100px; text-align: center; padding:0;">
                    &nbsp;
                  </td>
                @endfor
              @endif
            </tr>

            <!-- 上のfor文と構造は同じ（1つのエントリーに5つの進捗を入れる） -->
            <tr>
              <?php $entry_num = 0;?>
              @foreach($my_entries as $entry)
                <?php $entry_num++;?>
                <?php $progress_num = 0;?>
                @foreach($progress_list as $progress)
                  @if($entry->id == $progress->entry_id && $progress->user_id == $student->id)
                    <?php $progress_num++;?>
                    <td style="width: 100px; text-align: center; padding:0;">
                      {{$progress->state}}
                    </td>
                  @endif
                @endforeach
                @if($progress_num < 5)
                  @for(; $progress_num < 5; $progress_num++)
                    <td style="width: 100px; text-align: center; padding:0;">
                      &nbsp;
                    </td>
                  @endfor
                @endif
              @endforeach
              @if($entry_num < $max_entry_count)
                @for($count = $entry_num * 5; $count < $max_entry_count * 5; $count++)
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