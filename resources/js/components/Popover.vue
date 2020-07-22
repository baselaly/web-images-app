<template>
  <div class="sw-popover">
    <div
      class="sw-popover__wrapper"
      :class="popoverClass"
      :style="componentStyle"
      >
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  name: "popover",
  props: {
    zIndex: {
      type: [Number, null],
      required: false,
      default: null
    },
    resizeWidth: {
      type: Boolean,
      required: false,
      default: false
    },
    popoverClass: {
      type: [String, Array, Object],
      required: false,
      default: ""
    },
    /**
     * @deprecated tag:v6.2.0
     */
    isPopover: {
      type: Boolean,
      required: false,
      default: true
    },
    /**
     * @deprecated tag:v6.3.0
     */
    popoverConfigExtension: {
      type: Object,
      required: false,
      default() {
        return {};
      }
    }
  },

  computed: {
    componentStyle() {
      return {
        "z-Index": this.zIndex
      };
    },
    popoverConfig() {
      const popoverConfigBase = this.popoverConfigExtension || {};

      return {
        ...popoverConfigBase,
        active: true,
        resizeWidth: this.resizeWidth
      };
    }
  }
};
</script>
<style lang="scss">
.sw-popover {
  .sw-popover__wrapper {
    position: inherit;
  }
}
</style>