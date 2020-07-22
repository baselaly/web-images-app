<template>
  <span class="sw-label" :class="labelClasses" @click.stop="$emit('selected')">
    <span class="sw-label__caption">
      <slot></slot>
    </span>

    <button
      v-if="showDismissable"
      class="sw-label__dismiss"
      :title="'global.sw-label.buttonDismiss'"
      @click.prevent.stop="$emit('dismiss')"
    >
      <slot name="dismiss-icon">
        <icon name="small-default-x-line-medium"></icon>
      </slot>
    </button>
  </span>
</template>
<script>
import Icon from "./Icon";
export default {
  name: "WlLabel",
  components: {
    Icon
  },
  props: {
    variant: {
      type: String,
      required: false,
      default: "",
      validValues: [
        "info",
        "danger",
        "success",
        "warning",
        "neutral",
        "primary"
      ],
      validator(value) {
        if (!value.length) {
          return true;
        }
        return [
          "info",
          "danger",
          "success",
          "warning",
          "neutral",
          "primary"
        ].includes(value);
      }
    },
    size: {
      type: String,
      required: false,
      default: "default",
      validValues: ["small", "medium", "default"],
      validator(value) {
        return ["small", "medium", "default"].includes(value);
      }
    },
    appearance: {
      type: String,
      required: false,
      default: "default",
      validValues: ["default", "pill", "circle"],
      validator(value) {
        return ["default", "pill", "circle"].includes(value);
      }
    },
    ghost: {
      type: Boolean,
      required: false,
      default: false
    },
    caps: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  computed: {
    labelClasses() {
      return [
        `sw-label--appearance-${this.appearance}`,
        `sw-label--size-${this.size}`,
        {
          [`sw-label--${this.variant}`]: this.variant,
          "sw-label--dismissable": this.showDismissable,
          "sw-label--ghost": this.ghost,
          "sw-label--caps": this.caps,
          "sw-label--light": this.light
        }
      ];
    },
    showDismissable() {
      return !!this.$listeners.dismiss;
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";
@mixin sw-label-variant($color-background, $color-text, $color-border) {
  background-color: $color-background;
  border-color: transparent;
  color: $color-text;

  &.sw-label--small::before {
    background: $color-border;
  }

  .sw-label__dismiss {
    color: $color-text;

    .sw-icon {
      color: $color-text;
    }
  }

  &.sw-label--ghost {
    background: transparent;
    border-color: $color-border;
  }

  &.sw-label--dismissable:hover {
    border-color: $color-border;
  }
}

$sw-label-border-radius: 3 / 2;
$sw-label-pill-border-radius: 50px;

.sw-label {
  display: inline-block;
  position: relative;
  max-width: 100%;
  min-width: 56px;
  margin: 0 6px 6px 0;
  padding: 8px 12px;
  line-height: 14px;
  font-size: 12px;
  height: 32px;
  border: 1px solid gray;
  background: #80808061;
  border-radius: $sw-label-border-radius;
  color: #80808061;
  cursor: default;

  .sw-label__caption {
    // @include truncate();

    display: inline-block;
    width: 100%;
  }

  &.sw-label--dismissable:hover {
    border-color: blue;

    .sw-label__caption {
      width: calc(100% - 15px);
    }

    .sw-label__dismiss {
      display: inline-block;
      color: blue;
      background: transparent;
    }
  }

  &.sw-label--size-medium {
    height: 24px;
    padding: 4px 12px;
  }

  &.sw-label--size-small {
    height: 16px;
    padding: 0 8px;
  }

  .sw-label__dismiss {
    display: none;
    position: absolute;
    height: 100%;
    right: 10px;
    top: 0;
    color: #80808061;
    background-color: #80808061;
    border: 0 none;
    cursor: pointer;
    outline: none;

    .sw-icon {
      width: 12px;
      height: 12px;
    }
  }

  &.sw-label--ghost {
    background: transparent;
    border-color: #80808061;
  }

  &.sw-label--appearance-pill {
    border-radius: $sw-label-pill-border-radius;
  }

  &.sw-label--appearance-circle {
    width: 24px;
    height: 24px;
    border-radius: 16px;
    padding: 4px;
    border: 0;
    min-width: 24px;
  }

  &.sw-label--caps {
    text-transform: uppercase;
  }

  &.sw-label--info,
  &.sw-label--danger,
  &.sw-label--success,
  &.sw-label--warning,
  &.sw-label--neutral {
    &.sw-label--small {
      font-weight: 600;
      line-height: 14px;
      font-size: 12px;
      padding: 0 5px;
      padding-left: 15px;
      height: 16px;
    }

    &.sw-label--small::before {
      content: "";
      display: block;
      height: 6px;
      width: 6px;
      position: absolute;
      top: 4px;
      left: 5px;
    }
  }

  &.sw-label--info {
    @include sw-label-variant(
      blue,
      blue,
      blue
    );
  }

  &.sw-label--success {
    @include sw-label-variant(
      red,
      red0,
      red0
    );
  }

  &.sw-label--danger {
    @include sw-label-variant(
      crimson-50,
      crimson-500,
      crimson-500
    );
  }

  &.sw-label--warning {
    @include sw-label-variant(
      pink,
      pink0,
      pink0
    );
  }

  &.sw-label--neutral {
    @include sw-label-variant(
      gray,
      gray,
      gray
    );
  }

  &.sw-label--primary {
    @include sw-label-variant(
      blue,
      blue,
      blue
    );
  }
}
</style>