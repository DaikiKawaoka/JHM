<template>
    <div class="student-company-show">
        <div class="student-company-left-part">
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
                </table>
            </div>
            <div class="btn-field">
                <a :href="'/student/companies/'+company.id+'/edit'"><button class="btn btn-info active mt-4 ml-2 mr-3">編集</button></a>
                <button class="btn btn-danger active mt-4 ml-3" type="button" @click="on_delete">削除</button>
            </div>
            <delete-modal v-if="show_delete" :is_delete_btn="false" :csrf="csrf" :delete_url="delete_url" v-on:exitDeleteModal="this.show_delete = $event"></delete-modal>
        </div>
        <div class="student-company-right-part">
            <add-progress :company_id="company.id" :csrf="csrf" :entry="entry" :statuses="statuses" who_created="student_created_company"></add-progress>
        </div>
    </div>
</template>

<script>
import AddProgress from '../entry/AddProgress.vue';
import DeleteModal from '../DeleteModal.vue';

export default {
    components:{
        AddProgress, DeleteModal
    },
    data(){
        return{
            events:[
                '説明会','試験受験','面接','社長面接'
            ],
            progresStatuses:[
                '結果待ち','合格','不合格','内々定','欠席'
            ],
            show_delete: false,
        }
    },
    methods:{
        on_delete(){
            this.show_delete = true;
        },
        off_delete(){
            this.show_delete = false;
        }
    },
    computed:{
    },
    props: ['entry','statuses','company', 'csrf', 'delete_url'],
}
</script>

<style scoped lang='scss'>

    .student-company-show{
        margin-top: 1rem;
        display: flex;
        .student-company-left-part{
            width: 40%;
            .company-detail{
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
                button{
                    width: 8rem;
                    height: 4rem;
                    color: #ffffff;
                    font-size: 1.2rem;
                }
            }
        }

        .student-company-right-part{
            width: 60%;
            margin-left: 20px;
            .detail-button{
                display: flex;
                .entry-field{
                    margin: 0 3%;
                    button{
                        width: 12rem;
                        height: 4rem;
                        color: #ffffff;
                        font-size: 1.2rem;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                }
            }
        }
    .radius{
        border-radius: 5px;
    }
    .margin-top{
        margin-top: 1.4rem;
        padding: 1.2rem;
    }
    .font-size{
        font-size: 1.3em;
    }
}

</style>