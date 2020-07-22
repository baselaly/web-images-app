<template>
  <router-link v-if="routerLink" :to="routerLink" class="sw-button" :class="buttonClasses">
    <span class="sw-button__content">
      <slot></slot>
    </span>
  </router-link>
  <a
    v-else-if="link"
    :href="link"
    target="_blank"
    rel="noopener"
    class="sw-button"
    :class="buttonClasses"
  >
    <span class="sw-button__content">
      <slot></slot>
    </span>
  </a>
  <button
    v-else
    class="sw-button"
    :class="buttonClasses"
    :disabled="disabled || isLoading"
    v-on="$listeners"
  >
    <icon name="default-web-loading-circle" class="sw-button__loader" v-if="isLoading" size="20px"></icon>
    <span class="sw-button__content" :class="contentVisibilityClass">
      <slot></slot>
    </span>
  </button>
</template>
<script>
import Icon from "./Icon";
export default {
  name: "Button",
  components: {
    Icon
  },
  props: {
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    variant: {
      type: String,
      required: false,
      default: "",
      validValues: ["primary", "ghost", "danger", "contrast"],
      validator(value) {
        if (!value.length) {
          return true;
        }
        return ["primary", "ghost", "danger", "contrast"].includes(value);
      }
    },
    size: {
      type: String,
      required: false,
      default: "",
      validValues: ["x-small", "small", "large"],
      validator(value) {
        if (!value.length) {
          return true;
        }
        return ["x-small", "small", "large"].includes(value);
      }
    },
    square: {
      type: Boolean,
      required: false,
      default: false
    },
    block: {
      type: Boolean,
      required: false,
      default: false
    },
    routerLink: {
      type: Object,
      required: false
    },
    link: {
      type: String,
      required: false
    },
    isLoading: {
      type: Boolean,
      default: false,
      required: false
    }
  },

  computed: {
    buttonClasses() {
      return {
        [`sw-button--${this.variant}`]: this.variant,
        [`sw-button--${this.size}`]: this.size,
        "sw-button--block": this.block,
        "sw-button--disabled": this.disabled,
        "sw-button--square": this.square
      };
    },

    contentVisibilityClass() {
      return {
        "is--hidden": this.isLoading
      };
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

$sw-button-transition: all 0.15s ease-out;

.sw-button {
  border: 1px solid gray;
  background: #80808061;
  color: #80808061;
  transition: $sw-button-transition;
  display: inline-block;
  border-radius: 3;
  padding: 2px 24px;
  font-size: 14px;
  line-height: 34px;
  outline: none;
  font-weight: 600;
  font-family: $font-family-base;
  white-space: nowrap;
  text-overflow: ellipsis;
  vertical-align: middle;
  text-decoration: none;
  cursor: pointer;
  user-select: none;
  margin: 0;
  position: relative;

  .sw-button__content {
    display: grid;
    grid-auto-flow: column;
    align-items: center;
    grid-gap: 0 8px;
  }

  .sw-button__content.is--hidden {
    visibility: hidden;
  }

  &:hover:not(.sw-button--disabled),
  &:hover:not([disabled]) {
    background: #80808061;
  }

  &:active:not(.sw-button--disabled) {
    background: #80808061;
    border-color: #80808061;
  }

  &:disabled,
  &.sw-button--disabled {
    color: #808080610;
    border-color: #80808061;
    cursor: not-allowed;

    .sw-icon {
      color: #80808061;
    }
  }

  .sw-icon {
    color: #80808061;
  }

  &.sw-button--primary {
    background: blue;
    color: white;
    line-height: 36px;
    border: 0 none;

    .sw-icon {
      color: white
    }

    &.sw-button--x-small {
      line-height: 20px;
    }

    &.sw-button--small {
      line-height: 28px;
    }

    &.sw-button--large {
      line-height: 44px;
    }

    &:hover {
      background: blue;
    }

    &:active {
      background: blue;
    }

    &:disabled,
    &.sw-button--disabled {
      background: blue;
    }

    &.sw-button--square .sw-icon {
      color: white
    }
  }

  &.sw-button--contrast {
    background: yellow;
    color: #80808061;
    line-height: 36px;
    border: 0 none;

    .sw-icon {
      color: #80808061;
    }

    &.sw-button--x-small {
      line-height: 20px;
    }

    &.sw-button--small {
      line-height: 28px;
    }

    &.sw-button--large {
      line-height: 44px;
    }

    &:hover {
      background: yellow;
    }

    &:active {
      background: yellow;
    }

    &:disabled,
    &.sw-button--disabled {
      background: yellow;
      color: #80808061;

      .sw-icon {
        color: #80808061;
      }
    }
  }

  &.sw-button--danger {
    background: crimson-500;
    color: white;
    line-height: 36px;
    border: 0 none;

    .sw-icon {
      color: white
    }

    &.sw-button--x-small {
      line-height: 20px;
    }

    &.sw-button--small {
      line-height: 28px;
    }

    &.sw-button--large {
      line-height: 44px;
    }

    &:hover {
      background: crimson-700;
    }

    &:active {
      background: crimson-800;
    }

    &:disabled,
    &.sw-button--disabled {
      background: crimson-200;

      .sw-icon {
        color: white;
      }
    }
  }

  &.sw-button--ghost {
    background-color: transparent;
    border-color: blue;
    color: blue;

    .sw-icon {
      color: blue;
    }

    &:hover {
      background-color: blue;
    }

    &:active {
      background-color: blue;
    }

    &:disabled,
    &.sw-button--disabled {
      background-color: transparent;
      border-color: blue;
      color: blue;

      .sw-icon {
        color: blue;
      }
    }
  }

  &.sw-button--block {
    display: block;
    width: 100%;
  }

  &.sw-button--x-small {
    padding-left: 10px;
    padding-right: 10px;
    font-size: 12px;
    line-height: 18px;

    &.sw-button--square {
      width: 24px;
    }
  }

  &.sw-button--small {
    padding-left: 15px;
    padding-right: 15px;
    font-size: 12px;
    line-height: 26px;

    &.sw-button--square {
      width: 32px;
    }
  }

  &.sw-button--large {
    padding-left: 28px;
    padding-right: 28px;
    line-height: 42px;
    font-size: 15px;

    &.sw-button--square {
      width: 48px;
    }
  }

  &.sw-button--square {
    width: 40px;
    padding-left: 0;
    padding-right: 0;
    text-align: center;

    .sw-button__content {
      display: inline;
    }
  }

  .sw-button__loader {
    position: absolute;
    left: 50%;
    top: 50%;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    from {
      transform: translate(-50%, -50%) rotate(0deg);
    }

    to {
      transform: translate(-50%, -50%) rotate(360deg);
    }
  }
}
</style>