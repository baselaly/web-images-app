<template>
  <select-base
    class="sw-multi-select"
    :isLoading="isLoading"
    @select-expanded="onSelectExpanded"
    @select-collapsed="onSelectCollapsed"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template #sw-select-selection="{ identification, error, disabled, size, expand, collapse }">
      <select-selection-list
        :selections="visibleValues"
        ref="selectionList"
        :invisibleCount="invisibleValueCount"
        @total-count-click="expandValueLimit"
        @item-remove="remove"
        @last-item-delete="removeLastItem"
        @search-term-change="onSearchTermChange"
        v-bind="{ size, valueProperty, labelProperty, placeholder, searchTerm }"
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
      <select-result-list
        :options="visibleResults"
        :isLoading="isLoading"
        ref="swSelectResultList"
        :emptyMessage="'noo'"
        :focusEl="$refs.selectionList.getFocusEl()"
        @paginate="$emit('paginate')"
        @item-select="addItem"
      >
        <template #before-item-list>
          <slot name="before-item-list"></slot>
        </template>

        <template #result-item="{ item, index }">
          <slot
            name="result-item"
            v-bind="{ item, index, labelProperty, valueProperty, searchTerm, highlightSearchTerm, isSelected, addItem, getKey }"
          >
            <li
              is="sw-select-result"
              :selected="isSelected(item)"
              @item-select="addItem"
              v-bind="{ item, index }"
            >
              <slot
                name="result-label-property"
                v-bind="{ item, index, labelProperty, valueProperty, searchTerm, getKey }"
              >
                <highlight-text
                  v-if="highlightSearchTerm"
                  :text="getKey(item, labelProperty)"
                  :searchTerm="searchTerm"
                ></highlight-text>
                <template>{{ getKey(item, labelProperty) }}</template>
              </slot>
            </li>
          </slot>
        </template>

        <template #after-item-list>
          <slot name="after-item-list"></slot>
        </template>
      </select-result-list>
    </template>
  </select-base>
</template>
<script>
import SelectBase from "./SelectBase";
import SelectResultList from "./SelectResultList";
import SelectSelectionList from "./SelectSelectionList";
import HighlightText from "./HighlightText";
import { get, set } from "../core/object.utils";

export default {
  name: "MultiSelect",
  components: {
    HighlightText,
    SelectBase,
    SelectResultList,
    SelectSelectionList
  },
  model: {
    prop: "value",
    event: "change"
  },

  // mixins: [Mixin.getByName("remove-api-error")],

  props: {
    options: {
      type: Array,
      required: true
    },
    value: {
      required: true,
      validator(value) {
        return Array.isArray(value) || value === null || value === undefined;
      }
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
    placeholder: {
      type: String,
      required: false,
      default: ""
    },
    valueLimit: {
      type: Number,
      required: false,
      default: 5
    },
    isLoading: {
      type: Boolean,
      required: false,
      default: false
    },
    highlightSearchTerm: {
      type: Boolean,
      required: false,
      default: true
    },
    // Used to implement a custom search function.
    // Parameters passed: { options, labelProperty, valueProperty, searchTerm }
    searchFunction: {
      type: Function,
      required: false,
      default({ options, labelProperty, searchTerm }) {
        return options.filter(option => {
          const label = this.getKey(option, labelProperty);
          if (!label) {
            return false;
          }
          return label.toLowerCase().includes(searchTerm.toLowerCase());
        });
      }
    }
  },

  data() {
    return {
      searchTerm: "",
      limit: this.valueLimit
    };
  },

  computed: {
    visibleValues() {
      if (!this.currentValue || this.currentValue.length <= 0) {
        return [];
      }

      return this.options
        .filter(item => {
          return this.currentValue.includes(
            this.getKey(item, this.valueProperty)
          );
        })
        .slice(0, this.limit);
    },

    totalValuesCount() {
      if (this.currentValue.length) {
        return this.currentValue.length;
      }

      return 0;
    },

    invisibleValueCount() {
      if (!this.currentValue) {
        return 0;
      }

      return Math.max(0, this.totalValuesCount - this.limit);
    },

    currentValue: {
      get() {
        if (!this.value) {
          return [];
        }

        return this.value;
      },
      set(newValue) {
        /** @deprecated tag:v6.3.0 Html select don't have an onInput event */
        this.$emit("input", newValue);
        this.$emit("change", newValue);
      }
    },

    visibleResults() {
      if (this.searchTerm) {
        return this.searchFunction({
          options: this.options,
          labelProperty: this.labelProperty,
          valueProperty: this.valueProperty,
          searchTerm: this.searchTerm
        });
      }

      return this.options;
    }
  },

  methods: {
    isSelected(item) {
      return this.currentValue.includes(this.getKey(item, this.valueProperty));
    },

    addItem(item) {
      const identifier = this.getKey(item, this.valueProperty);

      if (this.isSelected(item)) {
        this.remove(item);
        return;
      }

      this.$emit("item-add", item);

      this.currentValue = [...this.currentValue, identifier];

      this.$refs.selectionList.focus();
      this.$refs.selectionList.select();
    },

    remove(item) {
      this.$emit("item-remove", item);

      this.currentValue = this.currentValue.filter(value => {
        return value !== this.getKey(item, this.valueProperty);
      });
    },

    removeLastItem() {
      if (!this.visibleValues.length) {
        return;
      }

      if (this.invisibleValueCount > 0) {
        this.expandValueLimit();
        return;
      }

      const lastSelection = this.visibleValues[this.visibleValues.length - 1];
      this.remove(lastSelection);
    },

    expandValueLimit() {
      this.$emit("display-values-expand");

      this.limit += this.limit;
    },

    onSearchTermChange: function updateSearchTerm(term) {
      this.searchTerm = term;
      this.$emit("search-term-change", this.searchTerm);
      this.resetActiveItem();
    },

    resetActiveItem() {
      this.$refs.swSelectResultList.setActiveItemIndex(0);
    },

    onSelectExpanded() {
      this.$refs.selectionList.focus();
    },

    onSelectCollapsed() {
      this.searchTerm = "";
      this.$refs.selectionList.blur();
    },

    getKey(object, keyPath, defaultValue) {
      return get(object, keyPath, defaultValue);
    }
  }
};
</script>
<style lang="scss">
</style>