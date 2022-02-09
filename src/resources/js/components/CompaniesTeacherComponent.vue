<template>
    <div>
        <div class="search_form">
            <h4 class="search_title">会社検索</h4>
                <div class="row mb-3" id="search_elem">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="会社検索" v-model="search_name" ref="input" v-on:keyup.enter="setKeyword" autofocus>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary" id="search_btn" @click="setKeyword">検索</button>
                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
        </div>

        <div class="filter_form">
            <div class="filter_prefecture">
                <label for="filter_prefecture" class="form-select">勤務先</label>
                <div class="col-md-3">
                    <select class="filter_prefecture" id="filter_prefecture" name="filter_prefecture" @change="setPrefecture">
                        <option value="">指定なし</option>
                        <option value="北海道">北海道</option>
                        <option value="青森">青森</option>
                        <option value="東京">東京</option>
                        <option value="大阪">大阪</option>
                        <option value="愛媛">愛媛</option>
                        <option value="福岡">福岡</option>
                        <option value="沖縄">沖縄</option>
                    </select>
                </div>
            </div>

            <div class="filter_order">
                <label for="filter_order" class="">並び順</label>
                <div class="form-check">
                    <input id="asc" type="radio" class="form-check-input" name="filter_order"  @click="setAsc" />
                    <label for="asc" class="form-check-label">古い順</label>
                    <input type="hidden" name="_token" v-bind:value="csrf"/>
                </div>
                <div class="form-check">
                    <input id="desc" type="radio" class="form-check-input" name="filter_order" @click="setDesc" checked/>
                    <label for="desc" class="form-check-label">新しい順</label>
                    <input type="hidden" name="_token" v-bind:value="csrf"/>
                </div>
            </div>
        </div>

        <div class="companies_container">
            <ul class="companies_list"  v-if="this.companiesData.length > 0">
                <a :href="'/companies/' + company.id" class="companies_link" v-for="company in companiesData" :key="company.id">
                    <li class="companies_elements">
                        <p class="companies_name text-truncate">{{ company.name }}</p>
                        <p class="deadline" v-if="company.deadline !== null">締切日:{{ company.deadline }}</p>
                        <img :src="'/storage/pdf_image/'+company.image_path+'.jpg'" class="companies_pic" v-if="company.image_path"/>
                        <img src="/img/no_image_square.jpg" class="companies_pic" v-else/>

                        <p class="create_user_name">{{ company.create_user_name }}</p>

                        <!-- 取り消し・エントリー -->
                        <div class="entries_delete_btn" v-if="!(this.user.id == this.is_teacher)">
                            <div v-if="this.entries[company.id]">
                                <form :action="'/entries/' + this.entries[company.id].id" method="post">
                                    <button type="submit" class="btn btn-danger" id="cancel_btn">取り消し</button>
                                    <input type="hidden" name="_method" value="delete">
                                    <input type="hidden" name="_token" v-bind:value="csrf"/>
                                </form>
                            </div>

                            <div v-else>
                                <form :action="'/entries/'" method="post">
                                    <input type="submit" name="entry" value="エントリー" class="btn btn-success" id="entry_btn"/>
                                    <input type="hidden" name="company_id" :value=company.id />
                                    <input type="hidden" name="_token" v-bind:value="csrf" />
                                </form>
                            </div>
                        </div>

                        <!-- 編集・削除 -->
                        <div v-if=" this.user.id == this.is_teacher && company.create_user_id == this.user.id ">
                            <a class="btn btn-success" id="edit_btn" :href="'/companies/' + company.id + '/edit'" role="button">編集</a>

                            <form :action="'/companies/' + company.id" method="post" name="delete_form">
                                <input type="submit" name="delete" class="btn btn-danger" id="delete_btn" value="削除"/>
                                <input type="hidden" name="_method" value="delete">
                                <input type="hidden" name="_token" v-bind:value="csrf"/>
                            </form>
                        </div>
                    </li>
                </a>
            </ul>
            <ul v-else class="no_company" v-show="!this.isLoading">該当する求人がありません。</ul>
            <spinner class="spinner" v-show="this.isLoading">読み込み中...</spinner>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data(){
        return{
            companiesData: [],
            junban: 'desc',
            prefecture: '',
            search_name: '',
            login_user: Object,
            isLoading: true

        }
    },
    methods:{
        setKeyword(){
            this.search_name = this.$refs.input.value;
            this.searchCompany();
        },
        setDesc(){
            this.junban = 'desc';
            this.searchCompany();
        },
        setAsc(){
            this.junban = 'asc';
            this.searchCompany();
        },
        setPrefecture (pre){
            this.prefecture = pre.target.value;
            this.searchCompany();
        },
        searchCompany(){
            let url = '/api/companies/getCompanies?order=' + this.junban;
            if (this.prefecture != ''){
                url += '&prefecture=' + this.prefecture
            }
            if (this.search_name != ''){
                url += '&company_name=' + this.search_name
            }

            axios.get(url)
            .then(response => {
                this.login_user = response.data.login_user;
                this.companiesData = response.data.search_companies.data;
                this.isLoading = false;
            });
        },

    },
    created(){
        let self = this;
        let company_url = '/api/companies/getCompanies';

        axios.get(company_url).then(function(response){
            self.login_user = response.data.login_user;
            self.companiesData = response.data.search_companies.data;
            self.isLoading = false;
        }).catch(response => console.log(response))
    },
    mounted() {
    },
    props: ["companies", "user", "entries", "is_teacher", "csrf"],
};
</script>

<style scoped lang='scss'>
 * {
    margin: 0;
}
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&display=swap');
$font:'Noto Sans JP', sans-serif;
.search_form{
    text-align: center;
    .search_title{
        margin: 5px;
    }
}


.filter_form{
    float: left;
    margin-left: 3%;
    .filter_prefecture{
        margin-top: 10px;
    }
    .filter_order{
        margin-top: 20px;
        .form-check-input{
            margin-top: 5px;
        }
        .form-check-label{
            margin-left: 20px;
        }
    }

}
.companies_container {
    margin-left: 15%;
    border-left: 0.5px solid rgb(233, 232, 232);
    &:hover {
        text-decoration: none;
    }
    .companies_list {
        display: grid;
        flex-wrap: wrap;
        list-style: none;
        grid-template-columns: 550px 550px;
        .companies_link{
            &:hover {
                text-decoration: none;
            }
            .create_user_name{
                float: right;
                margin-top: 5px;
                margin-right: 5%;
                font-size: 20px;
                color: #000;
            }
        }
        .companies_elements {
            transition: .6s;
            border: 0.5px solid #ccc;
            border-radius: 5px;
            margin: 18px;
            height: 680px;
            //タイトルを真ん中に持ってくる
            text-align: center;
            position: relative;
            .companies_link{
                display: block;
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0%;
                left: 0%;
            }
            //会社名
            .companies_name {
                font-size: 2rem;
                color: #000;
                margin-top: 20px;
                font-family: $font;
            }
            //締切日
            .deadline {
                font-size: 1.2rem;
                text-align: right;
                margin-right: 30px;
                color: #000;
                font-family: $font;
            }
            //写真
            .companies_pic {
                width: 460px;
                height: 460px;
            }
            //ボタン
            #edit_btn {
                float: right;
                display: block;
                position: absolute;
                bottom: 20px;
                right: 30px;
            }
            #delete_btn{
                float: right;
                display: block;
                position: absolute;
                bottom: 20px;
                right: 105px;
            }
            #entry_btn{
                margin-top: 40px;
            }
            #cancel_btn{
                margin-top: 40px;
            }
            &:hover{
                background: #eee;
                opacity: .9;
                transition: .6s;
            }
        }
    }
    .no_company{
        font-size: 20px;
        font-weight: 500;
        text-align: center;
    }
    .spinner{
        font-size: 20px;
        font-weight: 500;
        // text-align: center;
        margin-left: 40%;

    }
}
</style>
