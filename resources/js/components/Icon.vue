<template>
  <div
    class="sw-icon"
    v-on="$listeners"
    :class="classes"
    :is="iconName"
    :style="styles"
    :aria-hidden="decorative"
  ></div>
</template>
<script>
export default {
  name: "Icon",
  props: {
    name: {
      type: String,
      required: true
    },
    color: {
      type: String,
      required: false
    },
    small: {
      type: Boolean,
      required: false,
      default: false
    },
    large: {
      type: Boolean,
      required: false,
      default: false
    },
    size: {
      type: String,
      required: false
    },
    title: {
      type: String,
      required: false,
      default: ""
    },
    multicolor: {
      type: Boolean,
      required: false,
      default: false
    },
    decorative: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  created() {
    this.createdComponent();
  },

  methods: {
    createdComponent() {
      if (this.color && this.multicolor) {
        warn(
          this.$options.name,
          `The color of "${this.name}" cannot be adjusted because it is a multicolor icon.`
        );
      }
    }
  },

  computed: {
    iconName() {
      return `icons-${this.name}`;
    },

    classes() {
      return [
        `icon--${this.name}`,
        this.multicolor ? "sw-icon--multicolor" : "sw-icon--fill",
        {
          "sw-icon--small": this.small,
          "sw-icon--large": this.large
        }
      ];
    },

    styles() {
      let size = this.size;

      if (!Number.isNaN(parseFloat(size)) && !Number.isNaN(size - 0)) {
        size = `${size}px`;
      }

      return {
        color: this.color,
        width: size,
        height: size
      };
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

.sw-icon {
  @include size(24px);
  display: inline-block;
  vertical-align: middle;
  line-height: 0;

  > svg {
    width: 100%;
    height: 100%;
    vertical-align: middle;
  }

  &.sw-icon--fill > svg {
    fill: currentColor;

    path,
    use {
      fill: currentColor;
    }
  }

  &.sw-icon--small {
    @include size(16px);
  }

  &.sw-icon--large {
    @include size(32px);
  }
}
</style>