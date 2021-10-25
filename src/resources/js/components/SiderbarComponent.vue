<template>
  <div class="side1">
    <div v-for="item in items" :key="item.id">
      <div class="box1">
        <p>2</p>
      </div>
    </div>
    <div class="add">
      <p><i class="fas fa-plus"></i></p>
    </div>
  </div>
</template>
<script>
export default {
  props: ['items'],
};
</script>