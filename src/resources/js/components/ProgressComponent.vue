<template>
<div class="container-fluid">
  <div v-if="students">
    <header class="header_container">
        <div class="title_content">
          <h5>{{ workspace.year }}年度 就職活動リスト</h5>
          <h6>{{ workspace.class_name }}科 / 担任：{{ login_user.name }}</h6>
        </div>
        <div class="btn-group">
          <form action="/progress/excel_export" method='get'>
              <input type="submit" class="btn btn-success btn-lg mb-3 mr-2" value="Excelダウンロード">
          </form>
        </div>
    </header>

    <div class="progress-page-all" style="overflow-y: scroll;">
      <div class="" :style="'width:'+ table_width_px + 'px;'">

        <div class="progress-page-header">
          <div class="attendnum-div header-attendnum-div">
            <span class="header-student-attendnum">出席番号</span>
          </div>
          <div class="name-div header-student-name-div">
            <span class="header-student-name">学生氏名</span>
          </div>
          <div v-for="entry_count in most_many_entry_num" :key="entry_count">
            <div class="company-name-div header-company-name-div">
              <span class="header-company-name">応募先企業名</span>
            </div>
            <div class="header-progress-list-div">
              <span v-for="index in max_progress_count" :key="index" class="header-progress">選考{{index}}</span>
            </div>
            <div class="action-date-div header-action-date-div">
              <span class="header-action-date">日付</span>
            </div>
            <div class="action-state-div header-action-state-div">
              <span class="header-action-state">結果</span>
            </div>
          </div>
        </div>

        <div class="progress-page-body">
          <div class="student-progress-info-all" v-for="(student , s_index) in students" :key="student.id">
            <div class="student-info-div">
              <div class="attendnum-div student-attendnum-div">
                <span class="student-attendnum">{{student.attend_num}}</span>
              </div>
              <div class="name-div student-name-div">
                <span class="student-name">{{student.name}}</span>
              </div>
            </div>
            <div class="student-progress-info-div">
              <!-- entries_listは全生徒のエントリー配列 , entries_list[s_index] = s_index番目の生徒のエントリー配列 -->
              <div class="entry-div" v-for="(entry,e_index) in entries_list[s_index]" :key="(entry.id,e_index)">
                <div class="entered-company-name-div">
                  <!-- 求人からエントリーした会社 -->
                  <span v-if="entry.company_name != null" class="entered-company-name">{{entry.company_name}}</span>
                  <!-- 生徒自身が登録した会社 -->
                  <span v-else class="entered-student-company-name">{{entry.student_company_name}}</span>
                </div>

                <!-- progress_listは全生徒の進捗配列 , progress_list[s_index][e_index] = s_index番目の生徒のe_index番目のエントリーの進捗情報配列 -->
                <div class="student-progress-list" v-for="progress in progress_list[s_index][e_index]" :key="progress">
                  <div class="progress-list">
                    <div class="progress-div">
                      <div class="progress-action-div">
                        <span class="progress-action">{{progress.action}}</span>
                      </div>
                      <div class="progress-action-date-div">
                        <span class="progress-action-date">{{progress.action_date}}</span>
                      </div>
                      <div class="progress-state-div">
                        <span class="progress-state">{{progress.state}}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- 進捗数がmax_progress_count(1エントリーに登録できる進捗数) より少ない場合 (表を綺麗に見せるため) -->
                <div v-if="progress_list[s_index][e_index].length < max_progress_count">
                  <!-- 進捗数がmax_progress_count(デフォルトでは5個)になるまでfor分で回す -->
                  <div class="progress-list" v-for="j in max_progress_count - progress_list[s_index][e_index].length" :key="j">
                    <div class="progress-div">
                      <div class="progress-action-div">
                        <span class="progress-action">action(本当は空)</span>
                      </div>
                      <div class="progress-action-date-div">
                        <span class="progress-action-date">date(本当は空)</span>
                      </div>
                      <div class="progress-state-div">
                        <span class="progress-state">state(本当は空)</span>
                      </div>
                    </div>
                  </div>
                </div>
  -------------------------------エントリー区切り線（わかりやすいように一時的に書いているため消してください）-------------------------------
              </div>

              <!-- 空のエントリー要素を作成 -->
              <!-- エントリー数が1番多い生徒ではない場合 (表を綺麗に見せるため) -->
              <div v-if="entries_list[s_index].length < most_many_entry_num">
                <!-- エントリー数が1番多い人との差分だけfor分で回す -->
                <div class="entry-div" v-for="i in most_many_entry_num - entries_list[s_index].length" :key="i">
                  <div class="entered-company-name-div">
                    <span class="entered-company-name">会社名(本当は空)</span>
                  </div>
                  <!-- 空の進捗要素を作成 -->
                  <div v-for="i in most_many_entry_num - entries_list[s_index].length" :key="i">
                    <!-- max_progress_count(進捗登録最大数,デフォルトでは5個)分forで回す -->
                    <div class="progress-list" v-for="i in max_progress_count" :key="i">
                      <div class="progress-div">
                        <div class="progress-action-div">
                          <span class="progress-action">action(本当は空)</span>
                        </div>
                        <div class="progress-action-date-div">
                          <span class="progress-action-date">date(本当は空)</span>
                        </div>
                        <div class="progress-state-div">
                          <span class="progress-state">state(本当は空)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            ------------------------------生徒区切り線（わかりやすいように一時的に書いているため消してください）---------------------------------------------
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 生徒が登録されていない場合 -->
  <div v-else>
    <h1 style="text-align: center;">生徒が登録されていません。</h1>
  </div>
</div>
</template>

<script>

export default {
  data(){
    return {
      workspace: Object,
      login_user: Object,
      students: [],
      entries_list: [],
      progress_list: [],
      most_many_entry_num: 0,
      table_width_px: 0,
      entry_column_width_px: 0,
      max_progress_count: 0,
    }
  },
  created() {
    let self = this;
    let url = '/api/progress';
    axios.get(url).then(function(response){
      self.workspace = response.data.workspace;
      self.login_user = response.data.login_user;
      self.students = response.data.students;
      self.entries_list = response.data.entries_list;
      self.progress_list = response.data.progress_list;
      self.most_many_entry_num = response.data.most_many_entry_num;
      self.table_width_px = response.data.table_width_px;
      self.entry_column_width_px = response.data.entry_column_width_px;
      self.max_progress_count = response.data.max_progress_count;
    });
  },
  methods: {
    // excelExport: function() {
    //   let url = '/progress/excel_export';
    //   try {
    //     const res = axios.get(url)
    //     if(res.status === 200){
    //       window.location = res.request.responseURL
    //     } else {
    //      // ダウンロード失敗のメッセージって何の飾り気のないalertでも許してもらえる気がする
    //       alert("downloadに失敗しました")
    //     }
    //   } catch(e) {
    //     alert("downloadに失敗しました")
    //   }
    // }
  },

}
</script>

<style scoped lang='scss'>
</style>