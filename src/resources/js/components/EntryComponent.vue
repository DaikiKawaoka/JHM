<template>
    <div class="entryComponent">
        <div class="entryComponent-left-part">
            <div class="show-pdf">
                <img src="http://localhost:8000/img/no_image_square.jpg" class="companies_pic">
            </div>
        </div>
        <div class="entryComponent-right-part">
            <div class="detail-button">
                <div class="company-detail radius margin-top font-size">
                    <table>
                        <tr>
                            <th>会社名</th>
                            <td>{{companies['name']}}</td>
                        </tr>
                        <tr>
                            <th>勤務先</th>
                            <td>{{companies['prefecture']}}</td>
                        </tr>
                        <tr>
                            <th>URL</th>
                            <td>{{companies['url']}}</td>
                        </tr>
                        <tr>
                            <th>備考</th>
                            <td>{{companies['remarks']}}</td>
                        </tr>
                        <tr>
                            <th>応募締切日</th>
                            <td>{{companies['deadline']}}</td>
                        </tr>
                    </table>
                </div>
                <div class="entry-delete">
                    <button class="btn btn-success" v-if="statuses.length == 0" >エントリー</button>
                    <button class="btn btn-danger" v-else-if="statuses.length > 0">取り消し</button>
                </div>
            </div>
                
            <div class="event-form radius margin-top font-size">
                <form action="">
                    <p>進捗登録</p>
                    <span>
                        イベント
                        <select >
                            <option v-for="event in events" v-bind:key="event">{{event}}</option>
                        </select>
                    </span>
                    <span>
                        状況
                        <select>
                            <option v-for="progresStatus in progresStatuses" v-bind:key="progresStatus">{{progresStatus}}</option>
                        </select>
                    </span>
                    <span>
                        実施日
                        <input type="date" name="implementation-date">
                    </span>
                    <button type="submit" class="btn btn-success">登録</button>
                </form>
            </div>
            <div class="entry-company ">
                <table class="radius font-size">
                    <tr v-for="status in statuses" :key="status">
                        <edit-progress :status="status"></edit-progress>
                    </tr>
                    
                </table>
                
            </div>
        </div>
        
            
            
            
    </div>

</template>

<script>

import EditProgress from './EditProgress.vue';

export default {
    components:{
        EditProgress,
    },
    data(){
        return{
            events:[
                '説明会','試験受験（SPI,筆記など）','面接','社長面接'
            ],
            progresStatuses:[
                '待ち','◯','✕','内々定','欠席'
            ]
        }
    },
    methods:{
        
    },
    computed:{
    },
    mounted(){
    },
    props: ['entries','statuses','companies'],
}
</script>

<style scoped lang='scss'>

    .entryComponent{
        display: flex;
        .entryComponent-left-part{
            width: 80%;
            .show-pdf{
                img{
                    width: 100%;
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
                    border: solid 1px #000000;
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
                .entry-delete{
                    margin: 3% 3%;
                    button{
                        color: #ffffff;
                        font-size: 120%; 
                        padding: 17px 40px 17px 40px;
                    }
                }
            }
            .event-form{
                border: solid 1px #000000;
                span{
                    padding-left: 10px;
                }
                button{
                    margin-left: 50px;
                    padding: 5px 20px 5px 20px;
                }
            }
            .entry-company{
                table{
                    width: 100%;
                    tr{
                        text-align: center;
                        border-bottom:solid 1px #ccc;
                    }
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