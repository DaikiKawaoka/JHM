<template>
    <div>
        <div class="progress-row" v-if="showStatus">
            <span class="status-action-date">{{status['action_date']}}</span>
            <span class="status-action">{{status['action']}}</span>
            <span class="status-state">{{status['state']}}</span>
            <button v-on:click="onEdit" class="btn btn-success progress-btn">編集</button>
        </div>
        <div class="progress-row" v-else>
            <form class="" :action="'/progress/'+status.id" method="post">
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="_token" :value="csrf">
                <input type="hidden" name="company_type" :value="who_created">
                <span class="status-action-date">{{status['action_date']}}</span>
                <span class="status-action">{{status['action']}}</span>
                <span class="status-state">
                    <select name="state">
                        <option v-if="status['state']=='結果待ち'" value="結果待ち" selected>
                            結果待ち
                        </option>
                        <option v-else value="結果待ち">
                            結果待ち
                        </option>
                        <option v-if="status['state']=='合格'" value="合格" selected>
                            合格
                        </option>
                        <option v-else value="合格">
                            合格
                        </option>
                        <option v-if="status['state']=='不合格'" value="不合格" selected>
                            不合格
                        </option>
                        <option v-else value="不合格">
                            不合格
                        </option>
                        <option v-if="status['state']=='内々定'" value="内々定" selected>
                            内々定
                        </option>
                        <option v-else value="内々定">
                            内々定
                        </option>
                        <option v-if="status['state']=='欠席'" value="欠席" selected>
                            欠席
                        </option>
                        <option v-else value="欠席">
                            欠席
                        </option>
                    </select>
                </span>
                <input type="hidden" name="company_id" :value="company_id">
                <button type="submit" class="btn btn-success progress-btn">変更</button>
                <button type="button" class="btn btn-primary progress-btn" @click="offEdit">戻る</button>
            </form>
            <form class="" :action="'/progress/'+status.id" method="post">
                <input type="hidden" name="_method" value="delete">
                <input type="hidden" name="_token" :value="csrf">
                <button type="submit" class="btn btn-danger progress-btn">削除</button>
            </form>
        </div>
        <hr>
    </div>

</template>

<script>

const moment = require('moment')

export default {
    el:'#file',
    data(){
        return {
            showStatus: true,
            events:[
                '説明会','試験受験（SPI,筆記など）','面接','社長面接'
            ],
            progresStatuses:[
                '結果待ち','合格','不合格','内々定','欠席'
            ]
        }
    },
    methods:{
        onEdit(){
            this.showStatus = false;
        },
        offEdit(){
            this.showStatus = true;
        }
    },
    computed:{
    },
    created(){
        const action_date = moment(this.status['action_date']);
        this.status['action_date'] = String(action_date.year()) + '/' + String(action_date.month()+1) + '/' + String(action_date.date());
    },
    props: ['status', 'csrf', 'company_id',"who_created"],
}
</script>

<style scoped lang='scss'>

*{
    margin: 0px;
    padding: 0px;
}
.progress-row{
    height: 2rem;
    margin: .8rem 0 0;
    display: flex;
    align-items: center;
    .status-action{
        width: 9rem;
        padding: 0 1.8rem;
    }
    .status-state{
        width: 9rem;
        padding: 0 1.8rem;
        select{
            font-size: .9rem;
        }
    }
    .status-action-date{
        width: 9rem;
        padding: 0 1.8rem;
    }
    .progress-btn{
        width: 5rem;
        padding: 0 1.3rem;
        margin: 0 .8rem;
    }
}


</style>