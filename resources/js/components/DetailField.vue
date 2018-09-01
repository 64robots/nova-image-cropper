<template>
  <panel-item :field="field">
    <div slot="value">
      <img
        :src="field.thumbnailUrl"
        :class="{ avatar: field.isAvatar }"
      />
    </div>
  </panel-item>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field']
};
</script>
<style scoped>
.avatar {
  object-fit: cover;
  width: 318px;
  height: 318px;
  border-radius: 9999px;
  margin: 20px 0;
}
</style>
