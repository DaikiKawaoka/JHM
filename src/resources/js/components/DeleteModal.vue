<template>
    <div>
        <button type="button" class="btn btn-danger mr-4" @click="show" v-if="is_delete_btn">削除</button>
        <div class="card" v-if="showModal">
            <div class="card-body rounded" id="modal-box">
                <form :action="delete_url" method="POST">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" :value="csrf">
                    <h5 class="card-title text-left">確認</h5>
                    <p class="card-text text-center">本当に削除してもよろしいですか？</p>
                    <div class="mx-auto text-center">
                        <button type="submit" class="btn btn-primary mx-3">削除</button>
                        <button class="btn btn-primary mx-3" type="button" @click="exit">戻る</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="back" @click="exit" v-if="showModal">
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return{
            showModal: true,
        }
    },
    methods:{
        show(){
            this.showModal = true;
        },
        exit(){
            this.showModal = false;
            this.$emit('exitDeleteModal', false);
        }
    },
    created(){
        //モーダルを表示するボタンをこのテンプレート内のものを利用するとき
        if(this.is_delete_btn){
            //このコンポーネント自体は表示し、モーダルと黒背景は隠す
            this.showModal = false;
        }
    },
    props: ['delete_url' ,'csrf', 'is_delete_btn'],
}
</script>

<style scoped lang='scss'>
    #modal-box{
        position: fixed;
        z-index: 22;
        background: #ddd;
        width: 30%;
        top: 30%;
        left: 35%;
    }
    #back {
        z-index: 21;
        height: 150%;
        width: 150%;
        top: 0;
        left: 0;
        background: #000;
        opacity: 0.7;
        position: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>