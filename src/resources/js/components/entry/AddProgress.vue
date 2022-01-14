<template>
    <div>
        <div class="event-form radius margin-top font-size" v-if="entry">
            <form action="/progress" method="post">
                <input type="hidden" name="company_id" :value="company_id">
                <input type="hidden" name="_method" value="post">
                <input type="hidden" name="_token" :value="csrf">
                <input type="hidden" name="company_type" value="teacher_created_company">
                <p>進捗登録<button type="submit" class="btn btn-success">登録</button></p>
                <span>
                    イベント
                    <select name="action">
                        <option v-for="event in events" v-bind:key="event" :value="event">{{event}}</option>
                    </select>
                </span>
                <span>
                    状況
                    <select name="state">
                        <option v-for="progressStatus in progressStatuses" v-bind:key="progressStatus" :value="progressStatus">{{progressStatus}}</option>
                    </select>
                </span>
                <span>
                    実施日
                    <input type="date" name="action_date">
                </span>
            </form>
        </div>
        <div class="event-form radius margin-top font-size not-entry" v-else>
            <p>進捗登録<button type="submit" class="btn btn-success" disabled>登録</button></p>
            <span>
                イベント
                <select name="action">
                    <option v-for="event in events" v-bind:key="event" :value="event">{{event}}</option>
                </select>
            </span>
            <span>
                状況
                <select name="state">
                    <option v-for="progressStatus in progressStatuses" v-bind:key="progressStatus" :value="progressStatus">{{progressStatus}}</option>
                </select>
            </span>
            <span>
                実施日
                <input type="date" disabled>
            </span>
        </div>
    </div>
</template>

<script>

const moment = require('moment')

export default {
    el:'#file',
    data(){
        return {
            events:[
                '説明会','試験受験','面接','社長面接'
            ],
            progressStatuses:[
                '結果待ち','合格','不合格','内々定','欠席'
            ]
        }
    },
    methods:{
    },
    computed:{
    },
    created(){
    },
    props: ['csrf', 'company_id', 'entry'],
}
</script>

<style scoped lang='scss'>

*{
    margin: 0px;
    padding: 0px;
}
.event-form{
    border: solid 1px #000000;
    span{
        padding-left: .8rem;
    }
    button{
        margin-bottom: .4rem;
        margin-left: 2rem;
        padding: 0.2rem .8rem;
    }
}
.radius{
    border-radius: .3rem;
}
.margin-top{
    margin-top: 1.4rem;
    padding: 1.2rem;
}
.font-size{
    font-size: 1.1rem;
}
.not-entry{
    opacity: .6;
    border-color: #666;
    color: #666;
    input, select{
        color: #666;
    }
}

</style>