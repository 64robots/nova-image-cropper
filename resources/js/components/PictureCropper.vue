<template>
  <div
    ref="container"
    class="container"
  >
    <img
      ref="img"
      :src="image"
      alt="Picture"
    >
  </div>
</template>
<script>
import Cropper from 'cropperjs'
import 'cropperjs/dist/cropper.css'

export default {
  name: 'PictureCropper',

  props: {
    image: {
      type: String,
      default: ''
    },

    ratio: {
      type: Number,
      default: 1
    }
  },

  data() {
    return {
      cropper: null,
      width: 0,
      height: 0
    }
  },

  watch: {
    image(image) {
      if (image) {
        this.buildCropper()
      }
    }
  },

  mounted() {
    window.addEventListener('resize', this.setWidth)
    this.buildCropper()
  },

  destroyed() {
    window.removeEventListener('resize', this.setWidth)
    this.cropper.destroy()
  },

  methods: {
    buildCropper() {
      if (this.cropper) {
        this.cropper.destroy()
      }

      this.setWidth()
      const self = this

      this.cropper = new Cropper(this.$refs.img, {
        viewMode: 1,
        dragMode: 'crop',
        autoCropArea: 1,
        aspectRatio: self.ratio,
        checkCrossOrigin: false,
        minContainerWidth: self.width,
        minContainerHeight: self.height
      })
      this.cropper.replace(this.image)
    },

    setWidth() {
      const width = this.$refs.container.clientWidth
      if (!width) {
        return
      }
      this.width = width
      this.height = this.ratio ? this.width / this.ratio : this.height
    },

    getCroppedCanvas() {
      if (!this.cropper) {
        return
      }
      return this.cropper.getCroppedCanvas()
    },

    replace(image) {
      this.image = image

      this.buildCropper()
    }
  }
}
</script>
<style scoped>
.container {
  max-width: 640px;
  margin: 20px auto;
}
img {
  width: 100%;
  max-width: 100%;
}
</style>
