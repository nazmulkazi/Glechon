<script>
import Multiselect from '@vueform/multiselect';

export default {
  components: {
    Multiselect,
  },
  props: {
    initialSelectedTags: {
      type: Array,
      default: () => [],
    },
    tagPlaceholder: {
      type: String,
      default: 'Enter a new tag',
    },
    tagPosition: {
      type: String,
      default: 'bottom',
    },
    tagText: {
      type: Function,
      default: (newTag) => newTag,
    },
    tags: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      selectedTags: [],
    };
  },
  mounted() {
    this.selectedTags = this.initialSelectedTags;
  },
  methods: {
    addTag(newTag) {
      this.tags.push(newTag);
      this.selectedTags.push(newTag);
    },
  },
};
</script>

<template>
  <div>
    <multiselect
      v-model="selectedTags"
      :options="tags"
      placeholder="Select tags"
      label="name"
      track-by="id"
      :multiple="true"
      :taggable="true"
      :tag-placeholder="tagPlaceholder"
      :tag-position="tagPosition"
      :tag-text="tagText"
      @tag="addTag"
    ></multiselect>
  </div>
</template>