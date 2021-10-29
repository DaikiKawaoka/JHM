<template>
    <div class="side1">
        <div v-for="taughtClass in classes" :key="taughtClass.id" class="class-box">
            <a class="workspace-link" v-if="workspace_id == taughtClass.id">
                <div class="box1" id="selected">
                    <p v-if="taughtClass.class_name.length > 3">
                        {{ taughtClass.class_name.substr(0, 3) }}...
                    </p>
                    <p v-else>{{ taughtClass.class_name }}</p>
                </div>
            </a>
            <a
                :href="'/workspaces/' + taughtClass.id + '/change'"
                class="workspace-link"
                v-else
            >
                <div class="box1">
                    <p v-if="taughtClass.class_name.length > 3">
                        {{ taughtClass.class_name.substr(0, 3) }}...
                    </p>
                    <p v-else>{{ taughtClass.class_name }}</p>
                </div>
            </a>
        </div>
        <div class="ope">
            <a href="/workspaces/create">
                <p>
                    <i class="fas fa-plus"></i>
                </p>
            </a>
        </div>
        <div class="ope">
            <a :href="'/workspaces/' + workspace_id + '/edit'">
                <p>
                    <i class="fas fa-cog"></i>
                </p>
            </a>
        </div>
    </div>
</template>
<script>
export default {
    props: ["classes", "workspace_id"],
};
</script>