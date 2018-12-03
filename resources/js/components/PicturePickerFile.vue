<template>
  <el-upload
    class="upload"
    drag
    action
    :auto-upload="false"
    :on-change="onChange"
    :show-file-list="false"
  >
    <div>{{__('Click here or drop the file to upload')}}</div>
  </el-upload>
</template>
<script>
import { Upload } from 'element-ui'

export default {
  name: 'PicturePickerFile',

  components: { 'el-upload': Upload },

  methods: {
    onChange(e) {
      this.$emit('change', e)
    }
  }
}
</script>
<style scoped>
.upload {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  border-radius: 4px;
  background-color: #ffffff;
  font-family: Nunito;
  font-size: 16px;
  color: #9098a2;
}
.upload * {
  margin-top: 20px;
}
</style>
