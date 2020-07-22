<template>
  <li
    class="sw-select-result"
    :class="resultClasses"
    @mouseenter="onMouseEnter"
    @click.stop="onClickResult"
  >
    <span class="sw-select-result__result-item-text">
      <slot></slot>
    </span>
    <transition name="sw-select-result-appear">
      <icon v-if="selected" name="small-default-checkmark-line-medium" size="16px"></icon>
    </transition>
  </li>
</template>
<script>
import Icon from "./Icon";

export default {
  name: "SelectResult",
  inject: ["setActiveItemIndex"],
  components: {
    Icon
  },
  props: {
    index: {
      type: Number,
      required: true
    },
    item: {
      type: Object,
      required: true
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    selected: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  data() {
    return {
      active: false
    };
  },

  computed: {
    resultClasses() {
      return [
        {
          "is--active": this.active,
          "is--disabled": this.disabled
        },
        `sw-select-option--${this.index}`
      ];
    }
  },

  created() {
    this.createdComponent();
  },

  destroyed() {
    this.createdComponent();
  },

  methods: {
    createdComponent() {
      this.$parent.$parent.$on("active-item-change", this.checkIfActive);
      this.$parent.$parent.$on("item-select-by-keyboard", this.checkIfSelected);
    },

    destroyedComponent() {
      this.$parent.$parent.$off("active-item-change", this.checkIfActive);
      this.$parent.$parent.$off(
        "item-select-by-keyboard",
        this.checkIfSelected
      );
    },

    checkIfSelected(selectedItemIndex) {
      if (selectedItemIndex === this.index) this.onClickResult({});
    },

    checkIfActive(activeItemIndex) {
      this.active = this.index === activeItemIndex;
    },

    onClickResult() {
      if (this.disabled) {
        return;
      }

      this.$parent.$parent.$emit("item-select", this.item);
    },

    onMouseEnter() {
      this.setActiveItemIndex(this.index);
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

$sw-select-result-active-color-background: lighten(blue, 40%);
$sw-select-result-active-color-text: blue;
$sw-select-result-color-border: black;
$sw-select-result-color-icon: darken(gray, 20%);
$sw-select-result-transition-item-icon: all ease-in-out 0.15s;
$sw-select-result-disabled-color-background: #80808061;
$sw-select-result-disabled-color-text: darken(black, 15%);

.sw-select-result {
  padding: 12px 15px;
  border-bottom: 1px solid $sw-select-result-color-border;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;

  .sw-select-result__result-item-text {
    word-break: break-word;
    display: flex;
  }

  .sw-icon {
    color: $sw-select-result-color-icon;
    flex-grow: 0;
    flex-shrink: 0;
    margin-left: 10px;
  }

  &.is--active {
    background: $sw-select-result-active-color-background;
    color: $sw-select-result-active-color-text;
  }

  &.is--disabled {
    color: $sw-select-result-disabled-color-text;

    &.is--active {
      background: $sw-select-result-disabled-color-background;
      color: $sw-select-result-disabled-color-text;
      cursor: default;
    }

    .sw-highlight-text__highlight {
      color: $sw-select-result-disabled-color-text;
    }
  }

  &:last-child {
    border-bottom: 0 none;
  }

  // Vue.js transitions
  .sw-select-result-appear-enter-active,
  .sw-select-result-appear-leave-active {
    transition: $sw-select-result-transition-item-icon;
    transform: translateY(0);
  }

  .sw-select-result-appear-enter,
  .sw-select-result-appear-leave-to {
    opacity: 0;
    transform: translateY(-15px);
  }
}
</style>