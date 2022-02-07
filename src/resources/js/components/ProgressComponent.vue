<template>
  <div class="container-fluid">
    <div v-if="students">
      <header class="header_container">
        <div class="title_content">
          <h5>{{ workspace.year }}年度 就職活動リスト</h5>
          <h6>{{ workspace.class_name }}科 / 担任：{{ login_user.name }}</h6>
        </div>
        <div class="progress_view_box">
          <select
            class="progress_view_select"
            v-model="ProgressViewSelected"
            v-on:change="selectProgressView"
          >
            <option v-for="option in options" v-bind:value="option.value">
              {{ option.text }}
            </option>
          </select>
          <span class="progress_view_select_highlight"></span>
          <span class="progress_view_select_selectbar"></span>
          <label class="progress_view_select_selectlabel">リスト指定</label>
        </div>
                <div class="btn-group">
          <form action="/progress/excel_export" method="get">
            <input
              id="excel_dl_btn"
              type="submit"
              class="btn btn-success btn-lg mb-1"
              value="Excelダウンロード"
            />
          </form>
          <input
            type="checkbox"
            v-model="checked"
            v-on:change="isProgressView(checked)"
            id="checkbox"
          />
          <label for="checkbox" class="checkbox">{{isProgressViewMessage}}</label>
        </div>
      </header>
      <div class="progress-page-all" style="overflow-y: scroll">
        <table
          class="table table-bordered"
          :style="'width:' + table_width_px + 'px;'"
        >
          <thead>
            <tr>
              <th
                class="fixed"
                rowspan="4"
                style="
                  width: 65px;
                  text-align: center; 
                  vertical-align: middle;
                  padding: 0;
                "
              >
                出席番号
              </th>
              <th
                class="fixed2"
                rowspan="4"
                style="
                  width: 100px;
                  text-align: center;
                  vertical-align: middle;
                  padding: 0;
                "
              >
                学生氏名
              </th>
              <th
                v-for="entry_count in most_many_entry_num"
                :key="entry_count"
                :colspan="max_progress_count"
                style="width: 400px; text-align: center; padding: 0"
              >
                応募先企業名
              </th>
            </tr>
            <tr style="width: 100px">
              <template
                v-for="entry_count in most_many_entry_num"
                :key="entry_count"
              >
                <th
                  v-for="index in max_progress_count"
                  :key="index"
                  class="header-progress"
                  style="text-align: center; padding: 0"
                >
                  選考{{ index }}
                </th>
              </template>
            </tr>
            <tr style="width: {{entry_column_width_px}}px;">
              <th
                v-for="entry_count in most_many_entry_num"
                :key="entry_count"
                :colspan="max_progress_count"
                style="text-align: center; padding: 0"
              >
                日付
              </th>
            </tr>
            <tr style="width: {{entry_column_width_px}}px;">
              <th
                v-for="entry_count in most_many_entry_num"
                :key="entry_count"
                :colspan="max_progress_count"
                style="text-align: center; padding: 0"
              >
                結果
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(student, s_index) in students" :key="student">
              <tr>
                <td
                  class="fixed"
                  rowspan="4"
                  style="
                    width: 65px;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0;
                  "
                >
                  {{ student.attend_num }}
                </td>
                <td
                  class="fixed2"
                  rowspan="4"
                  style="
                    width: 100px;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0;
                  "
                >
                  {{ student.name }}
                </td>
                <!-- entries_listは全生徒のエントリー配列 , entries_list[s_index] = s_index番目の生徒のエントリー配列 -->
                <template
                  v-for="(entry, e_index) in entries_list[s_index]"
                  :key="(entry.id, e_index)"
                >
                  <!-- 求人からエントリーした会社 -->
                  <td
                    v-if="entry.company_name != null"
                    class="entered-company-name"
                    :colspan="max_progress_count"
                    style="text-align: center; padding: 0"
                  >
                    {{ entry.company_name }}
                  </td>
                  <!-- 生徒自身が登録した会社 -->
                  <td
                    v-else
                    class="entered-student-company-name"
                    style="text-align: center; padding: 0"
                  >
                    {{ entry.student_company_name }}
                  </td>
                </template>
                <!-- 空のエントリー要素を作成 -->
                <!-- エントリー数が1番多い生徒ではない場合 (表を綺麗に見せるため) -->
                <template
                  v-if="entries_list[s_index].length < most_many_entry_num"
                >
                  <!-- エントリー数が1番多い人との差分だけfor分で回す -->
                  <template
                    v-for="i in most_many_entry_num -
                    entries_list[s_index].length"
                    :key="i"
                  >
                    <td
                      :colspan="max_progress_count"
                      style="text-align: center; padding: 0"
                    >
                      &nbsp;
                    </td>
                  </template>
                </template>
              </tr>
              <tr>
                <!-- entries_listは全生徒のエントリー配列 , entries_list[s_index] = s_index番目の生徒のエントリー配列 -->
                <template
                  v-for="(entry, e_index) in entries_list[s_index]"
                  :key="(entry.id, e_index)"
                >
                  <!-- progress_listは全生徒の進捗配列 , progress_list[s_index][e_index] = s_index番目の生徒のe_index番目のエントリーの進捗情報配列 -->
                  <template
                    class="student-progress-list"
                    v-for="progress in progress_list[s_index][e_index]"
                    :key="progress.id"
                  >
                    <td style="width: 100px; text-align: center; padding: 0">
                      {{ progress.action }}
                    </td>
                  </template>
                  <template
                    v-if="
                      progress_list[s_index][e_index].length <
                      max_progress_count
                    "
                  >
                    <template
                      class="progress-list"
                      v-for="j in max_progress_count -
                      progress_list[s_index][e_index].length"
                      :key="j"
                    >
                      <td style="width: 100px; text-align: center; padding: 0">
                        &nbsp;
                      </td>
                    </template>
                  </template>
                </template>
                <!-- 空の進捗要素を作成 -->
                <!-- エントリー数が1番多い生徒ではない場合 (表を綺麗に見せるため) -->
                <template
                  v-if="entries_list[s_index].length < most_many_entry_num"
                >
                  <!-- エントリー数が1番多い人との差分だけfor分で回す -->
                  <template
                    v-for="i in most_many_entry_num -
                    entries_list[s_index].length"
                    :key="i"
                  >
                    <!-- max_progress_count(進捗登録最大数,デフォルトでは5個)分forで回す -->
                    <template
                      class="progress-list"
                      v-for="i in max_progress_count"
                      :key="i"
                    >
                      <td style="width: 100px; text-align: center; padding: 0">
                        &nbsp;
                      </td>
                    </template>
                  </template>
                </template>
              </tr>
              <tr>
                <!-- entries_listは全生徒のエントリー配列 , entries_list[s_index] = s_index番目の生徒のエントリー配列 -->
                <template
                  v-for="(entry, e_index) in entries_list[s_index]"
                  :key="(entry.id, e_index)"
                >
                  <!-- progress_listは全生徒の進捗配列 , progress_list[s_index][e_index] = s_index番目の生徒のe_index番目のエントリーの進捗情報配列 -->
                  <template
                    class="student-progress-list"
                    v-for="progress in progress_list[s_index][e_index]"
                    :key="progress.id"
                  >
                    <td style="width: 100px; text-align: center; padding: 0">
                      {{ moment(progress.action_date) }}
                    </td>
                  </template>
                  <template
                    v-if="
                      progress_list[s_index][e_index].length <
                      max_progress_count
                    "
                  >
                    <template
                      class="progress-list"
                      v-for="j in max_progress_count -
                      progress_list[s_index][e_index].length"
                      :key="j"
                    >
                      <td style="width: 100px; text-align: center; padding: 0">
                        &nbsp;
                      </td>
                    </template>
                  </template>
                </template>
                <!-- 空の進捗要素を作成 -->
                <!-- エントリー数が1番多い生徒ではない場合 (表を綺麗に見せるため) -->
                <template
                  v-if="entries_list[s_index].length < most_many_entry_num"
                >
                  <!-- max_progress_count(進捗登録最大数,デフォルトでは5個)分forで回す -->

                  <template
                    v-for="i in most_many_entry_num -
                    entries_list[s_index].length"
                    :key="i"
                  >
                    <template
                      class="progress-list"
                      v-for="i in max_progress_count"
                      :key="i"
                    >
                      <td style="width: 100px; text-align: center; padding: 0">
                        &nbsp;
                      </td>
                    </template>
                  </template>
                </template>
              </tr>
              <tr>
                <!-- entries_listは全生徒のエントリー配列 , entries_list[s_index] = s_index番目の生徒のエントリー配列 -->
                <template
                  v-for="(entry, e_index) in entries_list[s_index]"
                  :key="(entry.id, e_index)"
                >
                  <!-- progress_listは全生徒の進捗配列 , progress_list[s_index][e_index] = s_index番目の生徒のe_index番目のエントリーの進捗情報配列 -->
                  <template
                    class="student-progress-list"
                    v-for="progress in progress_list[s_index][e_index]"
                    :key="progress.id"
                  >
                    <td style="width: 100px; text-align: center; padding: 0">
                      <template v-if="progress.state == '合格'">
                        <i
                          class="fas fa-check-circle my-success"
                          aria-hidden="true"
                        ></i>
                      </template>
                      <template v-else-if="progress.state == '不合格'">
                        <i
                          class="fas fa-times-circle my-fail"
                          aria-hidden="true"
                        ></i>
                      </template>
                      <template v-else>
                        {{ progress.state }}
                      </template>
                    </td>
                  </template>
                  <template
                    v-if="
                      progress_list[s_index][e_index].length <
                      max_progress_count
                    "
                  >
                    <template
                      class="progress-list"
                      v-for="j in max_progress_count -
                      progress_list[s_index][e_index].length"
                      :key="j"
                    >
                      <td style="width: 100px; text-align: center; padding: 0">
                        &nbsp;
                      </td>
                    </template>
                  </template>
                </template>
                <!-- 空の進捗要素を作成 -->
                <!-- エントリー数が1番多い生徒ではない場合 (表を綺麗に見せるため) -->
                <template
                  v-if="entries_list[s_index].length < most_many_entry_num"
                >
                  <!-- max_progress_count(進捗登録最大数,デフォルトでは5個)分forで回す -->

                  <template
                    v-for="i in most_many_entry_num -
                    entries_list[s_index].length"
                    :key="i"
                  >
                    <template
                      class="progress-list"
                      v-for="i in max_progress_count"
                      :key="i"
                    >
                      <td style="width: 100px; text-align: center; padding: 0">
                        &nbsp;
                      </td>
                    </template>
                  </template>
                </template>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <!-- 生徒が登録されていない場合 -->
    </div>
    <div v-else>
      <h1 style="text-align: center">生徒が登録されていません。</h1>
    </div>
  </div>
</template>

<script>
const moment = require("moment");
export default {
  data() {
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
      ProgressViewSelected: "1",
      options: [
        { text: "すべて", value: 1 },
        { text: "進行中のみ", value: 2 },
        { text: "内定済みのみ", value: 3 },
      ],
    };
  },
  created() {
    let self = this;
    let url = "/api/progress";
    axios.get(url).then(function (response) {
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
    moment: function (date) {
      return moment(date).format("YYYY/MM/DD");
    },
    selectProgressView: function () {
      let self = this;
      switch (self.ProgressViewSelected) {
        case 1:
          let url = "/api/progress/getEntries";
          axios.get(url).then(function (response) {
            self.entries_list = response.data.entries_list;
            self.progress_list = response.data.progress_list;
            self.most_many_entry_num = response.data.most_many_entry_num;
            self.table_width_px = response.data.table_width_px;
          });
          break;
        case 2:
          url = "/api/progress/getOngoingEntries";
          axios.get(url).then(function (response) {
            self.entries_list = response.data.entries_list;
            self.progress_list = response.data.progress_list;
            self.most_many_entry_num = response.data.most_many_entry_num;
            self.table_width_px = response.data.table_width_px;
          });
          break;
        case 3:
          url = "/api/progress/getSuccessfulEntries";
          axios.get(url).then(function (response) {
            self.entries_list = response.data.entries_list;
            self.progress_list = response.data.progress_list;
            self.most_many_entry_num = response.data.most_many_entry_num;
            self.table_width_px = response.data.table_width_px;
          });
          break;
      }
    },
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
};
</script>

<style scoped lang='scss'>
</style>