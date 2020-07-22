<template>
  <div class="sw-field" :class="swFieldClasses">
    <div class="sw-field__label" v-if="hasLabel">
      <inheritance-switch
        v-if="isInheritanceField"
        :disabled="disableInheritanceToggle"
        class="sw-field__inheritance-icon"
        :isInherited="isInherited"
        v-on="$listeners"
      ></inheritance-switch>

      <label v-if="label" :for="identification" :class="swFieldLabelClasses">{{ label }}</label>

      <!-- <sw-help-text class="sw-field__help-text" :text="helpText" v-if="helpText"></sw-help-text> -->
    </div>
    <slot name="sw-field-input" v-bind="{identification, error, disabled}"></slot>
    <!-- <sw-field-error :error="error"></sw-field-error> -->
  </div>
</template>
<script>
import InheritanceSwitch from "./InheritanceSwitch";

export default {
  name: "BaseField",
  components: {
    InheritanceSwitch
  },
  props: {
    name: {
      type: String,
      required: false,
      default: null
    },

    label: {
      type: String,
      required: false,
      default: null
    },

    helpText: {
      type: String,
      required: false,
      default: null
    },

    isInvalid: {
      type: Boolean,
      required: false,
      default: false
    },

    error: {
      type: [Object],
      required: false,
      default() {
        return null;
      }
    },

    disabled: {
      type: Boolean,
      required: false,
      default: false
    },

    required: {
      type: Boolean,
      required: false,
      default: false
    },

    isInherited: {
      type: Boolean,
      required: false,
      default: false
    },

    isInheritanceField: {
      type: Boolean,
      required: false,
      default: false
    },

    disableInheritanceToggle: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  data() {
    return {
      id: 1215
    };
  },

  computed: {
    identification() {
      if (this.name) {
        return this.name;
      }

      return `sw-field--${this.id}`;
    },

    hasLabel() {
      return !!this.label || !!this.helpText || this.isInheritanceField;
    },

    hasError() {
      return this.isInvalid || !!this.error;
    },

    swFieldClasses() {
      return {
        "has--error": this.hasError,
        "is--disabled": this.disabled,
        "is--inherited": this.isInherited
      };
    },

    swFieldLabelClasses() {
      return {
        "is--required": this.required
      };
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

.sw-field {
  width: 100%;
  margin-bottom: 22px;

  .sw-field__label {
    display: flex;
    line-height: 16px;
    font-size: 14px;
    margin-bottom: 8px;
    color: pink;

    label {
      flex-grow: 1;
    }
  }

  .sw-field__label .is--required::after {
    content: "*";
    color: blue;
  }

  .sw-field__inheritance-icon {
    margin-right: 8px;
  }

  .sw-field__button-restore {
    color: pink;
    padding: 0 8px;
    border: none;
    background: none;
    outline: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
  }

  .sw-field__help-text {
    align-self: center;
  }

  &.is--inherited {
    .sw-field__label {
      color: purple;
    }
  }
}
</style>