<template>
  <div class="side1">
    <div v-for="taughtClass in classes" :key="taughtClass.id">
      <a :href="'/workspaces/'+ taughtClass.id +'/change'">
        <div class="box1">
          <p>{{taughtClass.class_name.substr(0,3)}}...</p>
        </div>
      </a>
    </div>
    <div class="add">
      <p>
        <a href="/workspaces/create">
          <i class="fas fa-plus"></i>
        </a>
      </p>
    </div>
  </div>
</template>
<script>
export default {
  props: ['classes'],
};
</script>