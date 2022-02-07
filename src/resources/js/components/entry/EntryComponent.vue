<template>
    <div class="entryComponent">
        <div class="entryComponent-left-part">
            <div class="show-pdf">
                <img :src="'/storage/pdf_image/'+company.image_path+'.jpg'" class="companies_pic" v-if="company.image_path">
                <img src="/img/no_image_square.jpg" class="companies_pic" v-else>
            </div>
        </div>
        <div class="entryComponent-right-part">
            <div class="detail-button">
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
                <div class="entry-field">
                    <form v-if="entry" :action="'/entries/'+entry.id" method="post">
                        <input type="hidden" name="_method" value="delete">
                        <input type="hidden" name="_token" :value="csrf">
                        <button class="btn btn-danger">取り消し</button>
                    </form>
                    <form v-else action="/entries" method="post">
                        <input type="hidden" name="_method" value="post">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" :value="company.id" name="company_id">
                        <button class="btn btn-success">エントリー</button>
                    </form>
                    <a :href="'/companies/'+company.id+'/download_pdf'" class="btn btn-info active mt-4" role="button" v-if="company.image_path">PDFダウンロード</a>
                </div>
            </div>
            <add-progress :company_id="company.id" :csrf="csrf" :entry="entry" :statuses="statuses" who_created="teacher_created_company"></add-progress>
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
import AddProgress from './AddProgress.vue';

export default {
    components:{
        AddProgress,
    },
    data(){
        return{
            events:[
                '説明会','試験受験','面接','社長面接'
            ],
            progresStatuses:[
                '結果待ち','合格','不合格','内々定','欠席'
            ]
        }
    },
    methods:{
    },
    computed:{
    },
    props: ['entry','statuses','company', 'csrf'],
}
</script>

<style scoped lang='scss'>

    .entryComponent{
        margin-top: 1rem;
        display: flex;
        .entryComponent-left-part{
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

        .entryComponent-right-part{
            width: 100%;
            margin-left: 20px;
            .detail-button{
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
                .entry-field{
                    margin: 3% 3%;
                    button, a{
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