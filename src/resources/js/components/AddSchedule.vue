<template>
    <div id="content-modal">
        <div id="modal-bg" @click="exitAddModal"></div>
        <transition name="add-modal">
            <div id="modal-box">
                <div class="modal-header">
                    予定を追加する
                </div>
                <div class="modal-body">
                    <form action="/schedule/store" method="post">
                        <input type="hidden" name="_method" value="post">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="form-row">
                            <div class="form-content">
                                <input type="date" name="schedule_date" value="" autocomplete="schedule_date" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-content">
                                <textarea name='content' placeholder="○○会社説明会" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <button type="submit">
                                追加する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data(){
        return {

        }
    },
    methods: {
        exitAddModal(){
            this.$emit('exitAddModal', false);
        }
    },
    props:['csrf']
}
</script>

<style scoped lang="scss">
#content-modal{
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
        border-radius: .3rem;
        position: fixed;
        z-index: 2;
        padding: .5rem;
        width: 40%;
        height: 45%;
        top: 20%;
        left: 30%;
        border-radius: .5rem;
        overflow: scroll;
        .modal-header{
            font-size: 1.1rem;
            background: #fab1a0;
            color: #fff;
        }
        .modal-body{
            background: #ddd;
            .form-row{
                width: 90%;
                margin: 2rem auto;
                .form-content{
                    width: 80%;
                    margin: auto;
                    input, textarea{
                        width: 100%;
                        border: none;
                        background: #eee;
                        color: #aaa;
                        outline: none;
                    }
                }
                button{
                    border-radius: .4rem;
                    background: #fab1a0;
                    padding: .2rem .5rem;
                    color: #fff;
                    border: none;
                    margin: 0 auto;
                    &:hover{
                        opacity: .7;
                        background: #e67e22;
                    }
                }
            }
        }
    }
}

.add-modal-enter{
    opacity: 0;
    transform: translateY(-20px);
}
.add-modal-leave{
    opacity: 0;
}
.add-modal-enter-active{
    transition: opacity 300ms ease-in, transform 300ms ease-in;
}
.add-modal-leave-active{
    transition: opacity 500ms ease-out, transform 500ms ease-out;
}
.add-modal-leave-to{
    opacity: 0;
    transform: scale(1);
}
</style>
