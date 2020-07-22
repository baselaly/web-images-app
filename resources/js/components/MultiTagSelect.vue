<template>
  <select-base
    class="sw-multi-tag-select"
    :isLoading="isLoading"
    :error="errorObject"
    v-bind="$attrs"
    v-on="$listeners"
    @select-expanded="setDropDown(true)"
    @select-collapsed="setDropDown(false)"
  >
    <template #sw-select-selection="{ identification, error, disabled, size, expand, collapse }">
      <select-selection-list
        :selections="objectValues"
        ref="selectionList"
        @item-remove="remove"
        @last-item-delete="removeLastItem"
        @search-term-change="onSearchTermChange"
        labelProperty="value"
        valueProperty="value"
        v-bind="{ size, placeholder, searchTerm }"
      >
        <template #label-property="{ item, index, labelProperty, valueProperty }">
          <slot
            name="selection-label-property"
            v-bind="{ item, index, labelProperty, valueProperty}"
          >{{ getKey(item, labelProperty) }}</slot>
        </template>
      </select-selection-list>
    </template>

    <template #results-list>
      <div v-if="hasFocus" class="">
        <popover
          class=""
          popoverClass="sw-multi-tag-select-validation-popover"
          :zIndex="1100"
          :resizeWidth="true"
        >
          <div class="sw-select-result-list__content">
            <div @click="addItem" v-if="inputIsValid" class="sw-multi-tag-select-valid">
              <slot name="message-add-data">
                <span>add item</span>
              </slot>
            </div>

            <div v-else class="sw-multi-tag-select-invalid">
              <slot name="message-enter-valid-data">
                <span>enter data</span>
              </slot>
            </div>
          </div>
        </popover>
      </div>
    </template>
  </select-base>
</template>
<script>
import SelectBase from "./SelectBase";
import popover from "./popover";
import SelectSelectionList from "./SelectSelectionList";
import { get, set } from "../core/object.utils";

export default {
  components: {
    popover,
    SelectBase,
    SelectSelectionList
  },
  name: "MultiTagSelect",
  inheritAttrs: false,

  model: {
    prop: "value",
    event: "change"
  },

  //   mixins: [Mixin.getByName("remove-api-error")],

  props: {
    value: {
      type: Array,
      required: true
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

    validMessage: {
      type: String,
      required: false,
      default: ""
    },

    invalidMessage: {
      type: String,
      required: false,
      default: ""
    },

    validate: {
      type: Function,
      required: false,
      default: searchTerm => searchTerm.length > 0
    }
  },

  data() {
    return {
      searchTerm: "",
      hasFocus: false
    };
  },

  computed: {
    objectValues() {
      return this.value.map(entry => ({ value: entry }));
    },

    errorObject() {
      return null;
    },

    inputIsValid() {
      return this.validate(this.searchTerm);
    }
  },

  mounted() {
    this.mountedComponent();
  },

  beforeDestroy() {
    this.beforeDestroyComponent();
  },

  methods: {
    mountedComponent() {
      this.$refs.selectionList
        .getFocusEl()
        .addEventListener("keydown", this.onKeyDown);
    },

    beforeDestroyComponent() {
      this.$refs.selectionList
        .getFocusEl()
        .removeEventListener("keydown", this.onKeyDown);
    },

    onKeyDown({ key }) {
      if (key.toUpperCase() === "ENTER") {
        this.addItem();
      }
    },

    addItem() {
      this.$emit("add-item-is-valid", this.inputIsValid);

      if (!this.inputIsValid) {
        return;
      }

      this.$emit("change", [...this.value, this.searchTerm]);
      this.searchTerm = "";
    },

    remove({ value }) {
      this.$emit(
        "change",
        this.value.filter(entry => entry !== value)
      );
    },

    removeLastItem() {
      this.$emit("change", this.value.slice(0, -1));
    },

    onSearchTermChange(term) {
      this.searchTerm = term;
    },

    /* istanbul ignore next */
    getKey: get,

    setDropDown(open = true) {
      this.hasFocus = open;

      if (open) {
        return;
      }

      this.addItem();
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

.sw-select.sw-multi-tag-select {
  .sw-select__selection-indicators {
    display: none;
  }

  .sw-select-selection-list {
    width: 100%;
  }

  .sw-select-selection-list .sw-select-selection-list__item-holder {
    max-width: 16rem;
  }
}

.sw-multi-tag-select-validation-popover {
  .sw-multi-tag-select-valid,
  .sw-multi-tag-select-invalid {
    padding: 0.3rem 0.5rem;
    margin: 0.3rem 0.5rem;
    border-radius: 3;
  }

  .sw-multi-tag-select-valid {
    cursor: pointer;
    background: blue;
  }

  .sw-multi-tag-select-invalid {
    background: #80808061;
  }
}
</style>