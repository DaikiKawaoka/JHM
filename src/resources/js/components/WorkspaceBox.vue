
<template>
    <div>
        <transition name="pop-box">
            <div class="pop" v-if="is_pop">
                {{taughtClass.year}}年度 {{taughtClass.class_name}}科
            </div>
        </transition>
        <a class="workspace-link" v-if="workspace_id == taughtClass.id" @mouseenter="show_pop" @mouseleave="hide_pop">
            <div class="box1" id="selected">
                <p v-if="taughtClass.class_name.length > 3">
                    {{ taughtClass.class_name.substr(0, 3) }}...
                </p>
                <p v-else>{{ taughtClass.class_name }}</p>
            </div>
        </a>
        <a :href="'/workspaces/' + taughtClass.id + '/change'" class="workspace-link"
            v-else  @mouseenter="show_pop" @mouseleave="hide_pop">
            <div class="box1">
                <p v-if="taughtClass.class_name.length > 3">
                    {{ taughtClass.class_name.substr(0, 3) }}...
                </p>
                <p v-else>{{ taughtClass.class_name }}</p>
            </div>
        </a>
    </div>
</template>
<script>
export default {
    data(){
        return {
            is_pop: false
        }
    },
    methods: {
        show_pop(){
            this.is_pop = true
        },
        hide_pop(){
            this.is_pop = false
        }
    },
    props: ["taughtClass", "workspace_id"],
};
</script>
<style lang="scss" scoped>
.pop{
    color: #555;
    background: #eee;
    position: absolute;
    z-index: 2;
    margin-left: 3rem;
    padding: .3rem;
    border: #aaa .5px solid;
    border-radius: .2rem;
    font-size: .6rem;
}
.pop-box-leave{
    opacity: 0;
}
.pop-box-leave-active{
    transition: opacity 500ms ease-out, transform 500ms ease-out;
}
.pop-box-leave-to{
    opacity: 0;
    transform: scale(1);
}
</style>