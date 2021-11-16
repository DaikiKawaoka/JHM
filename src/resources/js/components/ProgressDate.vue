<template>
    <div id="main-content">
        <div v-if="getProgress.count > 1" id="multiple-progress">
            <div class="show-btn" @click="showModal">
                {{getProgress.progresses[0].name}}さんと他{{getProgress.progresses.length-1}}名
            </div>
            <div id="modal-bg" v-show="isShowModal" @click="exitModal"></div>
            <transition name="progress-modal">
                <div class="card" v-show="isShowModal" id="modal-box">
                    <div class="card-header modal-header">
                        {{this.date.month}}月{{this.date.date}}日
                    </div>
                    <div class="card-body">
                        <div class="process-date-modal">
                            <div v-for="process in getProgress.progresses" :key="process" class="one-process">
                                <span class="name">{{process.name}}</span>
                                <span class="action">{{process.action}}</span>
                                <span :class="getProgress.class.state + 'state'">{{process.state}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <div v-else-if="getProgress.count == 1" id="one-progress">
            <ul>
                <li>
                    {{getProgress.progresses[0].name}}
                </li>
                <li>
                    {{getProgress.progresses[0].action}}
                    <span :class="getProgress.class.state">{{getProgress.progresses[0].state}}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
const moment = require('moment')

export default {
    data(){
        return{
            isShowModal: false,
        }
    },
    methods:{
        showModal(){
            this.isShowModal = true;
        },
        exitModal(){
            this.isShowModal = false;
        },
    },
    computed:{
        getProgress(){
            const progress_info = {
                count: 0,
                progresses: [],
                class: {
                    state: '',
                },
            };
            const strDate = String(this.date.year) + '-' + String(this.date.month) + '-' + String(this.date.date);
            const calendarDate = moment(strDate, "YYYY-MM-DD");
            for(let key in this.progress){
                let progressDate = moment(this.progress[key].action_date);
                if(
                    calendarDate.year() === progressDate.year() &&
                    calendarDate.month() === progressDate.month() &&
                    calendarDate.date() === progressDate.date()
                ){
                    progress_info.count++;
                    const action = this.progress[key].action;
                    //actionの文字列が長すぎるので、簡略化する
                    if(action == '試験受験（SPI,筆記など）'){
                        this.progress[key].action = '試験受験';
                    }
                    progress_info.progresses.push(this.progress[key]);
                    const state = this.progress[key].state;
                    if(state == '◯'){
                        progress_info.class.state += ' success';
                    }else if(state == '×'){
                        progress_info.class.state += ' unsuccess';
                    }else if(state == '内々定'){
                        progress_info.class.state += ' early-offer';
                    }else if(state == '欠席'){
                        progress_info.class.state += ' no-attend';
                    }
                }
            }
            return progress_info;
        }
    },
    mounted(){
    },
    props: ["progress", "date"],
}
</script>

<style scoped lang='scss'>
    *{
        margin: 0;
        padding: 0;
    }
    #main-content{
        #multiple-progress{
            .show-btn{
                width: 80%;
                font-size: 0.7rem;
                background: #dfe6e9;
                color: #333;
                border-radius: .5rem;
                padding: .5rem;
                margin: auto;
                cursor: pointer;
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
                opacity: 0.1;
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
                .process-date-modal{
                    font-size: 1.25rem;
                    color: #333;
                    margin: 1rem;
                    padding: 1rem;
                    .one-process{
                        text-align: center;
                        margin: 0 auto;
                        .name{
                            width: 20%;
                            margin: .3rem .5rem;
                        }
                        .action{
                            width: 20%;
                            margin: .3rem .5rem;
                        }
                        .state{
                            width: 20%;
                            margin: .3rem .5rem;
                        }
                    }
                }
            }
        }
        #one-progress{
            font-size: 0.7rem;
            ul{
                width: 5.0rem;
                height: 4.5rem;
                background: #dfe6e9;
                color: #333;
                list-style: none;
                border-radius: .5rem;
                margin: auto;
                padding: .5rem;
                overflow: scroll;
                text-align: center;
            }
        }
    }

    .success{
        color: #00b894;
    }
    .unsuccess{
        color: #d63031;
    }
    .early-offer{
        color: #0984e3;
    }
    .no-attend{
        color: #e056fd;
    }

    .progress-modal-enter{
        opacity: 0;
        transform: translateY(-20px);
    }
    .progress-modal-leave{
        opacity: 0;
    }
    .progress-modal-enter-active{
        transition: opacity 300ms ease-in, transform 300ms ease-in;
    }
    .progress-modal-leave-active{
        transition: opacity 500ms ease-out, transform 500ms ease-out;
    }
    .progress-modal-leave-to{
        opacity: 0;
        transform: scale(1);
    }
</style>