<template>
    <div id="sub-content">
        <button @click="showSchedule(); getScheduleType();" id="show-schedule-btn" :class="show_schedule_btn + ' show-btn'">予定</button>
        <button @click="showProgress(); getProgressType();" id="show-progress-btn" :class="show_progress_btn + ' show-btn'">生徒進捗</button>
        <button id="show-add-modal" class="show-btn" @click="showAddModal"><i class="fas fa-plus"/>追加する</button>
    </div>
</template>

<script>
export default {
    data(){
        return{
            show_schedule_btn: 'select',
            show_progress_btn: 'non_select',
        }
    },
    methods:{
        showAddModal(){
            this.$emit("showAddModal", true);
        },
        showSchedule(){
            this.show_schedule_btn = 'select';
            this.show_progress_btn = 'non_select';
            this.$emit("showSchedule", true);
        },
        showProgress(){
            this.show_schedule_btn = 'non_select';
            this.show_progress_btn = 'select';
            this.$emit("showProgress", false);
        },
        getScheduleType(){
            this.$emit("changeSchedule", "予定");
        },
        getProgressType(){
            this.$emit("changeProgress", "進捗");
        },
    }
}
</script>

<style scoped lang="scss">
    #sub-content{
        width: 15%;
        // background: #fff;
        // border: 1px solid #eee;
        margin: 3rem 1rem 1rem 0;
        text-align: center;
        display: flex;
        flex-direction: column;
        .show-btn{
            width: 80%;
            height: 3rem;
            border-radius: .4rem;
            padding: .2rem;
            background: #2ecc71;
            color: #fff;
            border: none;
            margin: 0 auto;
        }
        #show-schedule-btn{
            margin-top: 4.0rem;
        }
        #show-progress-btn{
            margin-top: 1.5rem;
        }
        .select{
            border: .2rem solid #16a085;
        }
        .non_select{
            color: #ccc;
            background: #27ae60;
            &:hover{
                opacity: .8;
            }
        }
        #show-add-modal{
            background: #e67e22;
            margin-top: 3.0rem;
            &:hover{
                opacity: .8;
            }
            .fas{
                margin-right: .3rem;
            }
        }
    }
</style>