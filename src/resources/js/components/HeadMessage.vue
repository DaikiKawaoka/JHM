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
        setTimeout(() => {this.is_show = false;}, 5000);
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
    border: 1px solid #bee5eb;
    background: #d1ecf1;
    color: #0c5460;
}
.error-message{
    border: 1px solid #ffeeba;
    background: #fff3cd;
    color: #e58e26;
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