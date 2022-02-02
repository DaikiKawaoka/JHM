<template>
    <div class="show-component">
        <div class="show-left-part">
            <div class="show-pdf">
                <img :src="'http://localhost:8000/storage/pdf_image/'+company.image_path+'.jpg'" class="companies_pic" v-if="company.image_path">
                <img src="http://localhost:8000/img/no_image_square.jpg" class="companies_pic" v-else>
            </div>
        </div>
        <div class="show-right-part">
            <div class="show-right-top-part">
                <div class="company-detail radius margin-top font-size">
                    <table>
                        <tr>
                            <th>会社名</th>
                            <td>{{company.name}}</td>
                        </tr>
                        <tr>
                            <th>勤務先</th>
                            <td>{{company.prefecture}}</td>
                        </tr>
                        <tr>
                            <th>URL</th>
                            <td>{{company.url}}</td>
                        </tr>
                        <tr>
                            <th>応募締切日</th>
                            <td>{{company.deadline}}</td>
                        </tr>
                    </table>
                </div>
                <div class="btn-field">
                    <a :href="'/companies/'+company.id+'/edit'" class="btn btn-info active mt-4" role="button">編集</a>
                    <button class="btn btn-danger active mt-4" type="button" @click="on_delete">削除</button>
                </div>
                <delete-modal v-if="show_delete" :is_delete_btn="false" :csrf="csrf" :delete_url="delete_url" v-on:exitDeleteModal="this.show_delete = $event"></delete-modal>
            </div>
            <div class="entry-circumstances radius margin-top font-size">
                <table>
                    <tr>
                        <th>エントリー人数</th>
                        <td>{{all_entry_count}}名</td>
                    </tr>
                    <tr>
                        <th>クラスのエントリー人数</th>
                        <td>{{class_entry.length}}名</td>
                    </tr>
                    <tr v-if="class_entry.length != 0">
                        <th>エントリーした生徒一覧</th>
                    </tr>
                    <tr v-for="student in class_entry" :key="student.id">
                        <th></th>
                        <td>{{student.name}}</td>
                    </tr>
                </table>
            </div>
            <div class="company-remarks radius margin-top my-3" v-show="company.remarks">
                <p class="font-size remarks-title">詳細説明</p>
                <div class="mx-3">
                    {{company.remarks}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DeleteModal from '../DeleteModal.vue';

export default {
  components: { DeleteModal },
    data(){
        return {
            show_delete: false
        }
    },
    methods: {
        on_delete(){
            this.show_delete = true;
        },
        off_delete(){
            this.show_delete = false;
        }
    },
    props: ['company', 'all_entry_count', 'class_entry', 'csrf', 'delete_url'],
    mounted(){
        console.log(this.class_entry);
    }
}
</script>

<style scoped lang='scss'>

    .show-component{
        margin-top: 1rem;
        display: flex;
        .show-left-part{
            .show-pdf{
                margin-top: 1rem;
                display: flex;
                justify-content: center;
                align-items: center;
                img{
                    width: 35rem;
                    height: 35rem;
                }
            }
        }

        .show-right-part{
            width: 100%;
            margin-left: 20px;
            .show-right-top-part{
                display: flex;
                .company-detail{
                    width: 70%;
                    background: #fff;
                    border: solid 1px #aaa;
                    box-shadow: 0 0 20px rgba(170, 170, 170, .1);
                    table{
                        tr{
                            th{
                                padding-left: 40px;
                            }
                            td{
                                padding-left: 100px;
                            }
                        }
                    }
                }
                .btn-field{
                    margin: 3% 3%;
                    button, a{
                        width: 8rem;
                        height: 4rem;
                        color: #ffffff;
                        font-size: 1.2rem;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                }
            }
            .entry-circumstances{
                width: 90%;
                background: #fff;
                border: solid 1px #aaa;
                box-shadow: 0 0 20px rgba(170, 170, 170, .1);
                table{
                    tr{
                        th{
                            padding-left: 40px;
                        }
                        td{
                            padding-left: 100px;
                        }
                    }
                }
            }
            .company-remarks{
                background: #fff;
                border: solid 1px #aaa;
                box-shadow: 0 0 20px rgba(170, 170, 170, .1);
                .remarks-title{
                    font-weight: bold;
                }
            }
        }
    .radius{
        border-radius: 5px;
    }
    .margin-top{
        margin-top: 20px;
        padding: 20px;
    }
    .font-size{
        font-size: 1.3em;
    }
}

</style>