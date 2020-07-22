<template>
  <ul class="sw-select-selection-list">
    <li
      v-for="(selection, index) in selections"
      class="sw-select-selection-list__item-holder"
      :class="'sw-select-selection-list__item-holder--' + index"
      :key="selection[valueProperty]"
      :data-id="selection[valueProperty]"
    >
      <slot name="selected-option" v-bind="{ selection, defaultLabel: selection[labelProperty] }">
        <label @dismiss="onClickDismiss(selection)" :size="size">
          <span class="sw-select-selection-list__item">
            <slot
              name="label-property"
              v-bind="{ item: selection, index, labelProperty, valueProperty }"
            >{{ selection[labelProperty] }}</slot>
          </span>
        </label>
      </slot>
    </li>

    <li v-if="invisibleCount > 0" class="sw-select-selection-list__load-more">
      <slot name="invisible-count" v-bind="{ invisibleCount, onClickInvisibleCount }">
        <button
          class="sw-select-selection-list__load-more-button"
          @click.stop="onClickInvisibleCount"
        >+{{ invisibleCount }}</button>
      </slot>
    </li>

    <li>
      <slot name="input" v-bind="{ placeholder, searchTerm, onSearchTermChange, onKeyDownDelete }">
        <input
          ref="swSelectInput"
          class="sw-select-selection-list__input"
          type="text"
          :readonly="!enableSearch"
          :placeholder="selections.length > 0 ? '' : placeholder"
          :value="searchTerm"
          @input="onSearchTermChange"
          @keydown.delete="onKeyDownDelete"/>
      </slot>
    </li>
  </ul>
</template>
<script>
import Button from "./Button";
import Label from "./Label";

export default {
  name: "SelectSelectionList",
  components: {
    Button,
    Label
  },
  props: {
    selections: {
      type: Array,
      required: false,
      default: []
    },
    labelProperty: {
      type: String,
      required: false,
      default: "label"
    },
    valueProperty: {
      type: String,
      required: false,
      default: "value"
    },
    enableSearch: {
      type: Boolean,
      required: false,
      default: true
    },
    invisibleCount: {
      type: Number,
      required: false,
      default: 0
    },
    size: {
      type: String,
      required: false
    },
    placeholder: {
      type: String,
      required: false,
      default: ""
    },
    isLoading: {
      type: Boolean,
      required: false,
      default: false
    },
    searchTerm: {
      type: String,
      required: false,
      default: ""
    }
  },

  methods: {
    onClickInvisibleCount() {
      this.$emit("total-count-click");
    },

    onSearchTermChange(event) {
      this.$emit("search-term-change", event.target.value, event);
    },

    onKeyDownDelete() {
      if (this.searchTerm.length < 1) {
        this.$emit("last-item-delete");
      }
    },

    onClickDismiss(item) {
      this.$emit("item-remove", item);
    },

    focus() {
      this.$refs.swSelectInput.focus();
    },

    blur() {
      this.$refs.swSelectInput.blur();
    },

    select() {
      this.$refs.swSelectInput.select();
    },

    getFocusEl() {
      return this.$refs.swSelectInput;
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

.sw-select-selection-list {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  width: calc(100% - 30px);

  .sw-select-selection-list__item-holder {
    max-width: 220px;
    line-height: 0;

    .sw-label {
      margin: 8px 6px 0 0;
    }
  }

  .sw-select-selection-list__load-more-button {
    padding: 2px 10px;
    margin: 8px 6px 0 0;
    color: blue;
    line-height: 26px;
  }

  .sw-select-selection-list__input {
    display: inline-block;
    min-width: 200px;
    padding: 12px 16px 12px 8px;

    &::placeholder {
      color: lighten(gray, 25%);
    }
  }
}

.sw-field--medium .sw-select-selection-list {
  .sw-select-selection-list__item-holder .sw-label {
    margin: 4px 6px 0 0;
  }

  input {
    padding: 4px 16px 8px 8px;
  }

  .sw-select-selection-list__load-more-button {
    padding: 4px 12px;
    margin: 4px 6px 0 0;
    line-height: 14px;
  }
}

.sw-field--small .sw-select-selection-list {
  .sw-select-selection-list__item-holder .sw-label {
    margin: 4px 6px 0 0;
  }

  input {
    padding: 2px 16px 4px 8px;
  }

  .sw-select-selection-list__load-more-button {
    padding: 0 12px;
    margin: -1px 6px 0 0;
    line-height: 14px;
  }
}
</style>