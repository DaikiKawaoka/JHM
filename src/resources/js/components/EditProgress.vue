<template>
    <div>
        <div class="status progress-status" v-show="editStatus">
                <td class="edit-parts"> {{status['action']}} </td>
                <td class="state"> {{status['state']}} </td>
                <td class="edit-parts"> {{ status['action_date'] }} </td>
                <td class="edit-parts">
                    <button v-on:click="editBtn" class="btn btn-success">編集</button>
                </td>
        </div>
        <div v-show="editCommit">
            <form class="edit-status" action="" >
                    <span class="edit-parts">{{status['action']}} </span>
                    <span class="edit-parts">
                        <select name="status">
                            <option v-for="progresStatus in progresStatuses" v-bind:key="progresStatus">{{progresStatus}}</option>
                        </select>
                    </span>
                    <span class="edit-parts">{{status['action_date']}}</span>
                    <button type="submit" class="btn btn-success">変更</button>
                </form>
        </div>
            
    </div>

</template>

<script>

export default {
    el:'#file',
    data(){
        return{
            editStatus: true,
            editCommit: false,
            events:[
                '説明会','試験受験（SPI,筆記など）','面接','社長面接'
            ],
            progresStatuses:[
                '待ち','◯','✕','内々定','欠席'
            ]
        }
    },
    methods:{
        editBtn: function(){
            this.editStatus == true ? this.editStatus = false : this.editStatus = true
            this.editCommit == false ? this.editCommit = true : this.editCommit = false
        },
        editCommit: function(){
            this.editStatus == false ? this.editStatus = true : this.editStatus = false
            this.editCommit == true ? this.editCommit = false : this.editCommit = true
        },
    },
    computed:{
    },
    mounted(){
    },
    props: ['status'],
}
</script>

<style scoped lang='scss'>

body{
    margin: 0px;
    padding: 0px;
}    
.status{
    margin: 10px 20px 5px 145px;
    padding: 10px;
    .state{
        padding: 20px 50px 10px 40px;
    }
}
.progress-status{
    padding-left: 30px;
}
.edit-parts{
    padding: 20px 30px 10px 30px;
}
.edit-status{
    margin: 40px 20px 25px 20px;
}



</style>