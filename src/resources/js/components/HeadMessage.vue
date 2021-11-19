<template>
    <transition name="message-box">
        <div v-bind:class="getClass" v-if="is_show">
            <p class="message-content">{{message}}</p>
        </div>
    </transition>
</template>

<script>
export default {
    data(){
        return{
            is_show: true
        }
    },
    computed: {
        getClass(){
            if(this.is_success)
                return {
                    "success-message" : true,
                    "error-message" : false,
                    "message-board" : true,
                }
            else
                return {
                    "success-message" : false,
                    "error-message" : true,
                    "message-board" : true,
                }
        }
    },
    created() {
        setTimeout(() => {this.is_show = false;}, 3000);
    },
    props: ["is_success", "message"]
}
</script>

<style scoped lang="scss">
*{
    margin: 0;
    padding: 0;
}
.message-board{
    padding: .8rem;
    width: 20%;
    height: 4rem;
    left: 78%;
    top: 7%;
    text-align: center;
    position: fixed;
    z-index: 1;
    border-radius: .2rem;
    display: flex;
    justify-content: center;
    flex-direction: column;
    .message-content{
        font-size: .9rem;
    }
}
.success-message{
    border: 1px solid #3742fa;
    background: rgba(112, 161, 255, 0.6);
    color: #3742fa;
}
.error-message{
    border: 1px solid #f39c12;
    background: rgba(236, 204, 104, 0.8);
    color: #f39c12;
}
.message-box-leave{
    opacity: 0;
}
.message-box-leave-active{
    transition: opacity 500ms ease-out, transform 500ms ease-out;
}
.message-box-leave-to{
    opacity: 0;
    transform: scale(1);
}
</style>