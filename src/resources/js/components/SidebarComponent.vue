<template>
    <div class="side1">
        <div v-for="taughtClass in classes" :key="taughtClass.id" class="class-box">
            <workspace-box :taughtClass="taughtClass" :workspace_id="workspace_id"></workspace-box>
        </div>
        <div class="ope">
            <a href="/workspaces/create">
                <p>
                    <i class="fas fa-plus"></i>
                </p>
            </a>
        </div>
        <div class="ope" v-if="workspace_id">
            <a :href="'/workspaces/' + workspace_id + '/edit'">
                <p>
                    <i class="fas fa-cog"></i>
                </p>
            </a>
        </div>
    </div>
</template>
<script>
import WorkspaceBox from './WorkspaceBox.vue';
export default {
    components: { WorkspaceBox },
    props: ["classes", "workspace_id"],
};
</script>