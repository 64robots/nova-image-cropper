<template>
    <default-field :field="field">
        <template slot="field">
            <div class="picker-wrapper">
                <PicturePicker
                    ref="picturePicker"
                    v-show="editingImage || !value"
                    v-model="value"
                    @finished="editingImage = false"
                    @fileChanged="setFile"
                />
                <template v-if="!editingImage && value">
                    <div class="bg-30 flex px-8 py-4">
                        <a
                            class="btn btn-default btn-primary cursor-pointer"
                            @click="editingImage = true"
                        >Edit Image</a>
                    </div>
                    <img :src="value">
                </template>

                <p v-if="hasError" class="my-2 text-danger">
                    {{ firstError }}
                </p>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import { UrlToBase64 } from '../utils/image';
import PicturePicker from './PicturePicker';

require('element-ui/lib/theme-chalk/index.css');

export default {
  components: { PicturePicker },

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      editingImage: false,
      file: null,
      fileName: 'ras'
    };
  },

  mounted() {
    this.value = this.field.previewUrl;
    this.field.fill = formData => {
      formData.append(this.field.attribute, this.file, this.fileName);
    };
  },

  methods: {
    /*
    * Set the initial, internal value for the field.
    */
    setInitialValue() {
      this.value = this.field.value || '';
    },

    setFile(file) {
      this.file = file;
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    // fill(formData) {
    //   formData.append(this.field.attribute, this.value || '');
    // },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
    }
  }
};
</script>
