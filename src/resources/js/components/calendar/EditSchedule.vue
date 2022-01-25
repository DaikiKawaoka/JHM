<template>
    <div id="edit-content-modal">
        <div id="edit-modal-bg" @click="exitEditModal"></div>
        <transition name="edit-modal">
            <div id="edit-modal-box" class="card">
                <div class="edit-modal-header card-header">
                    予定を編集する
                </div>
                <div class="edit-modal-body card-body">
                    <form :action="'/schedule/' + schedule.id +'/update'" method="post">
                        <input type="hidden" name="_method" value="post">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="form-row">
                            <div class="form-content">
                                <input type="date" name="schedule_date" :value="schedule.schedule_date" autocomplete="schedule_date" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-content">
                                <textarea name='content' placeholder="○○会社説明会" rows="3" :value="schedule.content" required></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-btn">
                                <button type="submit">
                                    変更する
                                </button>
                                <button type="button" @click="exitEditModal">
                                    戻る
                                </button>
                                <button type="button" class="btn-show-modal" @click="showDeleteModal">削除</button>
                                <delete-modal v-if="is_delete_modal" :csrf="csrf" :delete_url="delete_url" :is_delete_btn=false v-on:exitDeleteModal="this.is_delete_modal = $event"></delete-modal>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import DeleteModal from '../DeleteModal.vue';
export default {
  components: { DeleteModal },
    data(){
        return {
            is_delete_modal: false,
            delete_url: '/schedule/' + this.schedule.id + '/destroy',
        }
    },
    methods: {
        exitEditModal(){
            this.$emit('exitEditModal', false);
        },
        showDeleteModal(){
            this.is_delete_modal = true;
        }
    },
    props:['csrf', 'schedule']
}
</script>

<style scoped lang="scss">
#edit-content-modal{
    font-size: .9rem;
    background: #6c5ce7;
    #edit-modal-bg{
        z-index: 3;
        height: 150%;
        width: 150%;
        top: 0;
        left: 0;
        background: #444;
        opacity: 0.4;
        position: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #edit-modal-box{
        border-color: #74b9ff;
        border-radius: .5rem;
        position: fixed;
        z-index: 4;
        width: 50%;
        height: 45%;
        top: 10%;
        left: 25%;
        overflow: scroll;
        .edit-modal-header{
            width: 100%;
            background: #74b9ff;
            font-size: 1.1rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        .edit-modal-body{
            padding: .5rem;
            .form-row{
                width: 90%;
                margin: auto;
                .form-content{
                    width: 80%;
                    margin: 1rem auto;
                    input, textarea{
                        width: 100%;
                        border: 1px solid #444;
                        outline: none;
                        border-radius: .2rem;
                        color: #444;
                        padding: .2rem;
                    }
                }
                .form-btn{
                    display: flex;
                    width: 80%;
                    margin: 1rem auto 2rem;
                    text-align: right;
                    button{
                        border-radius: .4rem;
                        background: #74b9ff;
                        padding: .2rem .5rem;
                        color: #fff;
                        border: none;
                        margin: 0 .5rem;
                        &:hover{
                            opacity: .7;
                            background: #0984e3;
                        }
                    }
                    .btn-show-modal{
                        background: #d63031;
                        &:hover{
                            background: #ff7675;
                        }

                    }
                }
            }
        }
    }
}

.edit-modal-enter{
    opacity: 0;
    transform: translateY(-20px);
}
.edit-modal-leave{
    opacity: 0;
}
.edit-modal-enter-active{
    transition: opacity 300ms ease-in, transform 300ms ease-in;
}
.edit-modal-leave-active{
    transition: opacity 500ms ease-out, transform 500ms ease-out;
}
.edit-modal-leave-to{
    opacity: 0;
    transform: scale(1);
}
</style>
