<template>
  <block-field
    class="sw-select"
    :class="swFieldClasses"
    v-bind="$attrs"
    :disabled="disabled"
    v-on="$listeners"
  >
    <template
      #sw-field-input="{ identification, error, disabled, size, setFocusClass, removeFocusClass }"
    >
      <div
        class="sw-select__selection"
        tabindex="0"
        @click="expand"
        @focus="expand"
        @keydown.tab="collapse"
        @keydown.esc="collapse"
      >
        <slot
          name="sw-select-selection"
          v-bind="{ identification, error, disabled, size, expand, collapse }"
        ></slot>
        <div class="sw-select__selection-indicators">
          <loader v-if="isLoading" class="sw-select__select-indicator" size="16px"></loader>
          <icon class="sw-select__select-indicator" name="small-arrow-medium-down" small></icon>
        </div>
      </div>

      <template v-if="expanded">
        <transition name="sw-select-result-list-fade-down">
          <slot name="results-list" v-bind="{ collapse }"></slot>
        </transition>
      </template>
    </template>
  </block-field>
</template>
<script>
import BlockField from "./BlockField";
import Icon from "./Icon";

export default {
  name: "SelectBase",
  components: {
    BlockField,
    Icon
  },

  props: {
    isLoading: {
      type: Boolean,
      required: false,
      default: false
    },

    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  data() {
    return {
      expanded: false
    };
  },

  computed: {
    swFieldClasses() {
      return { "has--focus": this.expanded };
    }
  },

  methods: {
    toggleExpand() {
      if (!this.expanded) {
        this.expand();
      } else {
        this.collapse();
      }
    },

    expand() {
      if (this.expanded) {
        return;
      }

      if (this.disabled) {
        return;
      }

      this.expanded = true;
      document.addEventListener("click", this.listenToClickOutside);
      this.$emit("select-expanded");
    },

    collapse() {
      document.removeEventListener("click", this.listenToClickOutside);
      this.expanded = false;
      this.$emit("select-collapsed");
    },

    listenToClickOutside(event) {
      let path = event.path;
      if (typeof path === "undefined") {
        path = this.computePath(event);
      }

      if (
        !path.find(element => {
          return element === this.$el;
        })
      ) {
        this.collapse();
      }
    },

    computePath(event) {
      const path = [];
      let target = event.target;

      while (target) {
        path.push(target);
        target = target.parentElement;
      }

      return path;
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

$sw-select-focus-transition: all ease-in-out 0.2s;

.sw-select {
  position: relative;

  .sw-block-field__block {
    transition: $sw-select-focus-transition;
    background-color: white;
    position: relative;
    overflow: visible;
  }

  .sw-select__selection {
    position: relative;
    padding: 0 8px;
    border: none;
    font-size: $font-size-sm;
    font-family: $font-family-base;
    line-height: 22px;
    color: #80808061;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
  }

  .sw-select__selection-indicators {
    position: absolute;
    display: flex;
    top: 14px;
    right: 16px;
  }

  .sw-select__selection-indicators .sw-loader {
    position: static;
    width: 16px;
    height: 16px;
    margin: 0;

    .sw-loader__container {
      transform: none;
      left: 0;
      top: 0;
    }
  }

  .sw-select__select-indicator {
    flex-shrink: 0;
    cursor: pointer;
  }

  .sw-select__select-indicator + .sw-select__select-indicator {
    margin-left: 8px;
  }

  &.sw-field--medium .sw-select__selection {
    padding: 4px 6px 0;

    .sw-select__selection-indicators {
      top: 10px;
    }
  }

  &.sw-field--small .sw-select__selection {
    padding: 4px 6px 0;

    .sw-select__selection-indicators {
      top: 6px;
    }
  }

  &.is--disabled {
    .sw-block-field__block {
      background-color: #80808061;
    }

    .sw-label {
      background-color: #80808061;
    }

    input {
      background-color: #80808061;
    }
  }
}

// Vue.js transitions
.sw-select-result-list-fade-down-enter-active,
.sw-select-result-list-fade-down-leave-active {
  transition: $sw-select-focus-transition;
  transform: translateY(0);
}

.sw-select-result-list-fade-down-enter,
.sw-select-result-list-fade-down-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>