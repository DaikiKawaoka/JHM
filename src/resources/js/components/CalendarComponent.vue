<template>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <button class="page-link text-reset ml-5" @click="reduceMonth">&laquo;</button>
                    <div class="col text-center mt-2">{{current.year()}}年 {{current.month() + 1}}月</div>
                    <button class="page-link text-reset mr-5" @click="addMonth">&raquo;</button>
                </div>
            </div>

            <div class="card-body">
                <table>
                    <thead id="calendar-head">
                        <tr>
                            <td class="sunday">日</td>
                            <td>月</td>
                            <td>火</td>
                            <td>水</td>
                            <td>木</td>
                            <td>金</td>
                            <td class="saturday">土</td>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <tr v-for="(week, index) in calendar" :key="index">
                            <td v-for="(date, index) in week" :key="index" :class="date.class + ' date-box'">
                                <p>
                                    {{date.date}}
                                </p>
                                <div v-if="!date.thisMonth">
                                    <progress-date :progress="progress" :year="date.year" :month="date.month" :date="date"></progress-date>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</template>

<script>
import ProgressDate from './ProgressDate.vue';

const moment = require('moment');

export default {
  components: { ProgressDate },
    data(){
        return{
            current: moment(),
        }
    },
    methods:{
        getCalendar(){
            const calendarByWeekAry = [];
            const calendarAry = this.getBeforeMonth().concat(
                this.getThisMonth(), this.getNextMonth()
            );
            const weekCount = calendarAry.length / 7;
            for(let i = 0; i < weekCount; i++){
                const week = calendarAry.splice(0,7);
                for(let key in week){
                    if(key == 0){
                        week[key].class += ' sunday';
                    }
                    else if(key == 6){
                        week[key].class += ' saturday';
                    }else{
                        week[key].class += ' weekday';
                    }
                }
                calendarByWeekAry.push(week);
            }

            return calendarByWeekAry;
        },
        getBeforeMonth(){
            const current = this.current;
            const beforeMonthDateAry = [];
            //今月の初日の曜日（数値）
            const startDayNum = current.startOf('month').day();
            //先月に戻る
            const beforeMonth = current.subtract(1, 'M');
            //先月の末日
            let lastDateNum = beforeMonth.endOf('month').date();
            //先月の末日から順に配列の先頭へ挿入する
            for(let i = 0; i < startDayNum; i++){
                beforeMonthDateAry.unshift({
                    date: lastDateNum,
                    class: 'notThisMonth',
                    year: beforeMonth.year(),
                    month: beforeMonth.month() + 1,
                    thisMonth: true,

                });
                lastDateNum--;
            }
            //今月に戻す
            current.add(1, 'M');
            return beforeMonthDateAry;
        },
        getThisMonth(){
            const current = this.current;
            const thisMonthDateAry = [];
            //今月の末日
            const endDate =  current.endOf('month').date();
            for(let date = 1; date <= endDate; date++){
                if(current.month() == moment().month() && date === moment().date()){
                    thisMonthDateAry.push({
                        date: date,
                        class: 'today',
                        year: current.year(),
                        month: current.month() + 1,
                        thisMonth: false,

                    } );
                }else{
                    thisMonthDateAry.push({
                        date: date,
                        class: '',
                        year: current.year(),
                        month: current.month() + 1,
                        thisMonth: false,

                    });
                }
            }
            return thisMonthDateAry;
        },
        getNextMonth(){
            const current = this.current;
            const nextMonthDateAry = [];
            //今月の末日の曜日（数値(０~６)）
            const endDayNum = current.endOf('month').day();
            //来月に進める
            const nextMonth = current.add(1, 'M');
            //来月の初日
            let nextMonthDate = 1;
            for(let i = endDayNum; i <  6; i++){
                nextMonthDateAry.push({
                    date: nextMonthDate,
                    class: 'notThisMonth',
                    year: nextMonth.year(),
                    month: nextMonth.month() + 1,
                    thisMonth: true,
                });
                nextMonthDate++;
            }
            //今月に戻す
            current.subtract(1, 'M');
            return nextMonthDateAry;
        },
        addMonth(){
            this.current = moment(this.current).add(1, 'M');
            this.getCalendar();
        },
        reduceMonth(){
            this.current = moment(this.current).add(-1, 'M');
            this.getCalendar();
        },
    },
    computed:{
        calendar(){
            return this.getCalendar();
        }
    },
    mounted(){
    },
    props: ['progress'],
}
</script>

<style scoped lang='scss'>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300&display=swap');
    $font: 'Noto Sans JP', sans-serif;
    $sunday-color : #d63031;
    $saturday-color: #0984e3;
    table{
        font-family: $font;
        border-collapse: collapse;
        margin: auto;
        #calendar-head{
            text-align: center;
            width: 90%;
            height: 4rem;
            tr{
                td{
                    border: 2px solid #eee;
                    width: 6rem;
                }
                .sunday{
                    color: $sunday-color;
                }
                .saturday{
                    color: $saturday-color;
                }
            }
        }
        #calendar-body{
            width: 90%;
            tr{
                td{
                    vertical-align: top;
                    border: 2px solid #eee;
                    height: 6rem;
                    width: 6rem;
                    p{
                        margin-bottom: 0rem;
                    }
                }
                .sunday{
                    color: $sunday-color;
                }
                .saturday{
                    color: $saturday-color;
                }
                .today{
                    p{
                        width: 1.5rem;
                        height: 1.5rem;
                        border-radius: 50%;
                        background: #a29bfe;
                        color: #fff;
                        text-align: center;
                    }
                }
                .notThisMonth{
                    opacity: .5;
                }
            }
        }
    }
</style>