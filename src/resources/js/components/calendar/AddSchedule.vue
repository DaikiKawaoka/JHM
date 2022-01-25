<template>
    <div id="content-modal">
        <div id="modal-bg" @click="exitAddModal"></div>
        <transition name="add-modal" appear>
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
    *{
        margin: 0;
        padding: 0;
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
        width: 40%;
        height: 40%;
        top: 20%;
        left: 30%;
        border-radius: .5rem;
        overflow: scroll;
        background: #eee;
        border: 1px solid #ccc;
        .modal-header{
            font-size: 1.1rem;
            background: #e67e22;
            color: #fff;
            padding: .6rem;
        }
        .modal-body{
            .form-row{
                width: 90%;
                margin: 2rem auto;
                .form-content{
                    width: 80%;
                    margin: auto;
                    input, textarea{
                        width: 100%;
                        border: 1px solid #444;
                        background: #ddd;
                        color: #444;
                        outline: none;
                    }
                }
                button{
                    border-radius: .4rem;
                    background: #e67e22;
                    padding: .2rem .5rem;
                    color: #fff;
                    border: none;
                    margin: 0 auto;
                    &:hover{
                        opacity: .7;
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
