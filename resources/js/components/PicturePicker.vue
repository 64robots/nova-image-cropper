<template>
  <div :class="{ 'picker-wrapper': imgSrc }">
    <input
      :id="hash"
      class="inputfile"
      type="file"
      name="image"
      accept="image/*"
      style="font-size: 1.2em; padding: 10px 0;"
      @change="setImage"
    >
    <div
      class="bg-30 flex flex-wrap px-8 py-4"
      v-if="imgSrc"
    >
      <div
        class="btn btn-default btn-primary mr-3 mb-2 cursor-pointer"
        @click="$emit('finished')"
      >{{__('Cancel Crop')}}</div>
      <label
        :for="hash"
        class="btn btn-default btn-primary mr-3 mb-2 cursor-pointer"
      >{{__('Change Image')}}</label>
      <div
        class="btn btn-default btn-primary cursor-pointer"
        @click="cropImage"
      >{{__('Done')}}</div>
    </div>
    <br>
    <div class="cropper-wrapper">
      <PictureCropper
        v-show="imgSrc"
        ref="cropper"
        :image="imgSrc"
        :ratio="ratio"
        :class="{ avatar: isAvatar }"
      />
      <PicturePickerFile
        v-if="!imgSrc"
        class="picker-file"
        @change="setImage"
      />
    </div>
    <br>
  </div>
</template>
<script>
import { resizeImage } from '../utils/image'
import PicturePickerFile from './PicturePickerFile'
import PictureCropper from './PictureCropper'

export default {
  name: 'PicturePicker',

  components: { PicturePickerFile, PictureCropper },

  props: ['value', 'isAvatar', 'aspectRatio'],

  data() {
    return {
      hash: Math.random()
        .toString(36)
        .substring(7),
      imgSrc: '',
      maxWidth: 584,
      cropImg: '',
      cropImgW: 0,
      cropImgH: 0,
      originalName: 'uploaded_file.jpg',
      originalFileType: 'image/jpeg'
    }
  },

  computed: {
    ratio() {
      if (this.isAvatar) {
        return 1
      }
      return this.aspectRatio
    },

    wrapperStyle() {
      return {
        padding: '10px 20px'
      }
    }
  },

  watch: {
    value(image) {
      this.updateCropper(image)
    }
  },

  methods: {
    updateCropper(image) {
      this.imgSrc = image
      this.cropImg = image

      if (this.$refs.cropper) {
        this.$refs.cropper.replace(image)
      }
    },

    setImage(e) {
      let file

      if (e.raw) {
        file = e.raw
      } else {
        file = e.target.files[0]
      }

      if (!file.type.includes('image/')) {
        alert('Please select an image file')
        return
      }

      this.originalName = file.name
      this.originalFileType = file.type

      if (typeof FileReader === 'function') {
        const reader = new FileReader()

        reader.onload = event => {
          resizeImage(
            event.target.result,
            file.type,
            ({ dataUrl, width, height, file }) => {
              this.updateCropper(dataUrl)
              this.$emit('input', dataUrl)
              this.$emit('setWidth', width)
              this.$emit('setHeight', height)
              this.$emit('fileChanged', file)
            }
          )
        }

        reader.readAsDataURL(file)
      } else {
        alert('Sorry, FileReader API not supported')
      }
    },

    cropImage() {
      const canvas = this.$refs.cropper.getCroppedCanvas()
      this.cropImgW = canvas.width
      this.cropImgH = canvas.height
      this.cropImg = canvas.toDataURL()
      canvas.toBlob(blob => {
        const { type } = blob
        const file = new File([blob], this.originalName, {
          type,
          lastModified: Date.now()
        })
        this.$emit('input', this.cropImg)
        this.$emit('fileChanged', file)
        this.$emit('finished')
      }, this.originalFileType)
    }
  }
}
</script>
<style scoped>
.cropper-wrapper {
  padding: 10px 20px;
}
.edit-buttons {
  height: 45px;
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  background-color: #ffffff;
  border-bottom: solid 1px #c4cdd5;
}
.edit-buttons i {
  cursor: pointer;
  color: #767e89;
  margin-right: 15px;
}
.picker-container {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
}
.vertical-slider {
  display: inline-block;
  margin-left: 10px;
}
.horizontal-slider {
  margin-top: 10px;
}
.inputfile {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
.inputfile + label {
  display: block;
}
.picker-button {
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  font-family: Nunito;
  font-size: 14px;
  color: #233c44;
  height: 25px;
  padding: 0 10px;
  border-radius: 4px;
  box-shadow: 0 1px 0 0 rgba(22, 29, 37, 0.05);
  background-image: linear-gradient(to top, #ffffff, #f9fafb);
  border: solid 1px #c4cdd5;
  margin: 0 10px;
}

.inputfile:focus + label,
.inputfile + label:hover {
  background-color: red;
}
</style>
