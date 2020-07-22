<template>
  <div class="sw-select-result-list" @scroll="onScroll">
    <popover
      class="sw-select-result-list-popover"
      :popoverClass="popoverClass"
      :popoverConfigExtension="popoverConfig"
      :zIndex="1100"
      :resizeWidth="true"
    >
      <div
        class="sw-select-result-list__content"
        :class="{ 'sw-select-result-list__content_empty': isLoading && (!options || options.length <= 0) }"
      >
        <slot name="before-item-list"></slot>

        <ul class="sw-select-result-list__item-list">
          <template v-for="(item, index) in options">
            <slot name="result-item" v-bind="{ item, index }"></slot>
          </template>
        </ul>

        <slot name="after-item-list"></slot>

        <div
          v-if="!isLoading && options && options.length < 1"
          class="sw-select-result-list__empty"
        >
          <icon name="default-action-search" size="20px"></icon>
          {{ emptyMessageText }}
        </div>
      </div>
    </popover>
  </div>
</template>
<script>
import Icon from "./Icon";
import Popover from "./Popover";

export default {
  name: "SelectResultList",
  components: {
    Icon,
    Popover
  },
  provide() {
    return {
      setActiveItemIndex: this.setActiveItemIndex
    };
  },

  props: {
    options: {
      type: Array,
      required: false,
      default() {
        return [];
      }
    },

    emptyMessage: {
      type: String,
      required: false,
      default: null
    },

    focusEl: {
      type: [HTMLDocument, HTMLElement],
      required: false,
      default() {
        return document;
      }
    },

    isLoading: {
      type: Boolean,
      required: false,
      default: false
    },

    popoverClasses: {
      type: Array,
      required: false,
      default() {
        return [];
      }
    },

    /**
     * @deprecated tag:v6.3.0
     */
    popoverConfig: {
      type: Object,
      required: false,
      default() {
        return {};
      }
    }
  },

  data() {
    return {
      activeItemIndex: 0
    };
  },

  computed: {
    emptyMessageText() {
      return (
        this.emptyMessage ||
        "global.sw-select-result-list.messageNoResults"
      );
    },

    popoverClass() {
      return [...this.popoverClasses, "sw-select-result-list-popover-wrapper"];
    }
  },

  created() {
    this.createdComponent();
  },

  mounted() {
    this.mountedComponent();
  },

  beforeDestroy() {
    this.beforeDestroyedComponent();
  },

  methods: {
    createdComponent() {
      this.addEventListeners();
    },

    mountedComponent() {
      // Set first item active
      this.emitActiveItemIndex();
    },

    beforeDestroyedComponent() {
      this.removeEventListeners();
    },

    setActiveItemIndex(index) {
      this.activeItemIndex = index;
      this.emitActiveItemIndex();
    },

    addEventListeners() {
      this.focusEl.addEventListener("keydown", this.navigate);
    },

    removeEventListeners() {
      this.focusEl.removeEventListener("keydown", this.navigate);
    },

    emitActiveItemIndex() {
      this.$emit("active-item-change", this.activeItemIndex);
    },

    navigate({ key }) {
      key = key.toUpperCase();
      if (key === "ARROWDOWN") {
        this.navigateNext();
        return;
      }

      if (key === "ARROWUP") {
        this.navigatePrevious();
        return;
      }

      if (key === "ENTER") {
        this.emitClicked();
      }
    },

    navigateNext() {
      if (this.activeItemIndex >= this.options.length - 1) {
        this.$emit("paginate");
        return;
      }

      this.activeItemIndex += 1;

      this.emitActiveItemIndex();
      this.updateScrollPosition();
    },

    navigatePrevious() {
      if (this.activeItemIndex > 0) {
        this.activeItemIndex -= 1;
      }

      this.emitActiveItemIndex();
      this.updateScrollPosition();
    },

    updateScrollPosition() {
      // wait until the new active item is rendered and has the active class
      this.$nextTick(() => {
        const resultContainer = document.querySelector(
          ".sw-select-result-list__content"
        );
        const activeItem = resultContainer.querySelector(".is--active");
        const itemHeight = activeItem.offsetHeight;
        const activeItemPosition = activeItem.offsetTop;
        const actualScrollTop = resultContainer.scrollTop;

        if (activeItemPosition === 0) {
          return;
        }

        // Check if we need to scroll down
        if (
          resultContainer.offsetHeight + actualScrollTop <
          activeItemPosition + itemHeight
        ) {
          resultContainer.scrollTop += itemHeight;
        }

        // Check if we need to scroll up
        if (
          actualScrollTop !== 0 &&
          activeItemPosition - actualScrollTop - itemHeight <= 0
        ) {
          resultContainer.scrollTop -= itemHeight;
        }
      });
    },

    emitClicked() {
      // This emit is subscribed in the sw-result component. They can for example be disabled and need
      // choose on their own if they are selected
      this.$emit("item-select-by-keyboard", this.activeItemIndex);
    },

    onScroll(event) {
      if (this.getBottomDistance(event.target) !== 0) {
        return;
      }

      this.$emit("paginate");
    },

    getBottomDistance(element) {
      return element.scrollHeight - element.clientHeight - element.scrollTop;
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

$sw-select-result-list-transition: all ease-in-out 0.2s;

.sw-select-result-list,
.sw-select-result-list-popover {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden;
}

.sw-select-result-list-popover .sw-popover__wrapper {
  width: 100%;
}

.sw-select-result-list__content {
  width: 100%;
  max-height: 250px;
  overflow-x: hidden;
  overflow-y: auto;
  border: 1px solid gray;
  box-shadow: 0 3px 6px 0 gray;
  background-color: white;
  font-size: $font-size-sm;
  font-family: $font-family-base;

  .sw-select-result-list__item-list {
    list-style: none;
  }

  .sw-select-result-list__empty {
    padding: 10px 16px;
  }
}

.sw-select-result-list__content_empty {
  opacity: 0;
  min-height: 293px;
  height: 293px;
}

.sw-popover__wrapper.--placement-bottom-outside.sw-select-result-list-popover-wrapper {
  transform: translate(0, calc(-100% - 48px));
}
</style>