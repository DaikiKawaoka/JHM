<template>
    <div id="main-content">
        <div v-if="getSchedule.count > 0" id="multiple-schedule">
            <div class="show-btn" @click="showModal">
                <span v-if="getSchedule.count > 1">{{getSchedule.count}}件の予定</span>
                <span v-else>{{getSchedule.schedules[0].content}}</span>
            </div>
            <div id="modal-bg" v-show="isShowModal" @click="exitModal"></div>
            <transition name="schedule-modal">
                <div class="card" v-show="isShowModal" id="modal-box">
                    <div class="card-header modal-header">
                        {{this.date.month}}月{{this.date.date}}日
                    </div>
                    <div class="card-body">
                        <div class="schedule-date-modal">
                            <div v-for="schedule in getSchedule.schedules" :key="schedule" class="one-schedule">
                                <span class="content">{{schedule.content}}</span>
                                <button class="edit-btn" @click="showEdit" v-if="is_teacher">編集</button>
                                <edit-schedule :csrf="csrf" v-on:exitEditModal="this.isEdit = $event" v-if="isEdit" :schedule="schedule"></edit-schedule>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import EditSchedule from './EditSchedule.vue';

const moment = require('moment')

export default {
  components: { EditSchedule },
    data(){
        return{
            isShowModal: false,
            changeInput: false,
            isEdit: false,
        }
    },
    methods:{
        showModal(){
            this.isShowModal = true;
        },
        exitModal(){
            this.isShowModal = false;
        },
        showEdit(){
            this.isEdit = true;
        }
    },
    computed:{
        getSchedule(){
            const schedule_info = {
                count: 0,
                schedules: [],
                is_input: false,
            };
            const strDate = String(this.date.year) + '-' + String(this.date.month) + '-' + String(this.date.date);
            let calendarDate = moment(strDate, "YYYY-MM-DD");
            for(let key in this.schedules){
                let scheduleDate = moment(this.schedules[key].schedule_date);
                if(
                    calendarDate.year() === scheduleDate.year() &&
                    calendarDate.month() === scheduleDate.month() &&
                    calendarDate.date() === scheduleDate.date()
                ){
                    schedule_info.count++;
                    schedule_info.schedules.push(this.schedules[key]);
                }
            }
            return schedule_info;
        }
    },
    props: ["schedules", "date", "is_teacher", "csrf"],
}
</script>

<style scoped lang='scss'>
    *{
        margin: 0;
        padding: 0;
        color: #000;;
    }
    #main-content{
        #multiple-schedule{
            .show-btn{
                width: 80%;
                max-height: 4.5rem;
                font-size: 0.7rem;
                background: #dfe6e9;
                color: #333;
                border-radius: .5rem;
                padding: .5rem;
                margin: .5rem auto 0;
                cursor: pointer;
                text-align: center;
                overflow: scroll;
                &:hover{
                    opacity: .7;
                }
                .fa-angle-up{
                    padding: 0 .3rem;
                }
            }
            #modal-bg{
                z-index: 1;
                height: 150%;
                width: 150%;
                top: 0;
                left: 0;
                background: #ddd;
                opacity: 0.4;
                position: fixed;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            #modal-box{
                position: fixed;
                z-index: 2;
                padding: .5rem;
                width: 30%;
                max-height: 50%;
                top: 20%;
                left: 35%;
                border-radius: .5rem;
                overflow: scroll;
                background: #fff;
                .modal-header{
                    font-size: 1.1rem;
                }
                .schedule-date-modal{
                    font-size: 1.25rem;
                    color: #333;
                    margin: 1rem;
                    padding: 1rem;
                    .one-schedule{
                        text-align: center;
                        margin: .2rem auto;
                        .edit-btn{
                            border-radius: .4rem;
                            background: #74b9ff;
                            color: #fff;
                            border: 1px solid #ccc;
                            margin: 0 .2rem 0 .8rem;
                            padding: 0 .3rem;
                            font-size: .8rem;
                            vertical-align: middle;
                            &:hover{
                                opacity: .7;
                            }
                        }
                    }
                }
            }
        }
    }

    .schedule-modal-enter{
        opacity: 0;
        transform: translateY(-20px);
    }
    .schedule-modal-leave{
        opacity: 0;
    }
    .schedule-modal-enter-active{
        transition: opacity 300ms ease-in, transform 300ms ease-in;
    }
    .schedule-modal-leave-active{
        transition: opacity 500ms ease-out, transform 500ms ease-out;
    }
    .schedule-modal-leave-to{
        opacity: 0;
        transform: scale(1);
    }
</style>