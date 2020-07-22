<template>
  <base-field class="sw-block-field" :class="swBlockFieldClasses" v-bind="$attrs" v-on="$listeners">
    <template #sw-field-input="{ identification, error, disabled }">
      <div class="sw-block-field__block">
        <slot
          name="sw-field-input"
          v-bind="{ identification, error, disabled, size, setFocusClass, removeFocusClass }"
        ></slot>
      </div>
    </template>
  </base-field>
</template>
<script>
import BaseField from "./BaseField";
export default {
  name: "BlockField",
  components: {
    BaseField
  },
  props: {
    size: {
      type: String,
      required: false,
      default: "default",
      validValues: ["small", "medium", "default"],
      validator(val) {
        return ["small", "medium", "default"].includes(val);
      }
    }
  },

  data() {
    return {
      hasFocus: false
    };
  },

  computed: {
    swBlockSize() {
      return `sw-field--${this.size}`;
    },

    swBlockFieldClasses() {
      return [
        {
          "has--focus": this.hasFocus
        },
        this.swBlockSize
      ];
    }
  },

  methods: {
    setFocusClass() {
      this.hasFocus = true;
    },

    removeFocusClass() {
      this.hasFocus = false;
    }
  }
};
</script>
<style lang="scss">
@import "../scss/variables";

$sw-field-transition: border-color 0.3s ease-out, background 0.3s ease;

.sw-block-field {
  .sw-block-field__block {
    border: 1px solid black;
    border-radius: 3;
    overflow: hidden;
  }

  input,
  select,
  textarea {
    display: block;
    width: 100%;
    min-width: 0;
    padding: 12px 16px;
    border: none;
    background: white;
    font-size: $font-size-sm;
    font-family: $font-family-base;
    line-height: 22px;
    transition: $sw-field-transition;
    color: #80808061;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;

    &:invalid,
    &:-moz-submit-invalid,
    &:-moz-ui-invalid {
      box-shadow: none;
    }

    &:disabled {
      background: #80808061;
      border-color: black;
      cursor: default !important;
    }

    &::placeholder {
      color: lighten(gray, 25%);
    }
  }

  &.has--focus {
    .sw-block-field__block {
      border-color: blue;
      box-shadow: 0 0 4px lighten(blue, 30%);
    }
  }

  &.has--error {
    .sw-block-field__block {
      border-color: crimson;
    }
  }

  &.has--error.has--focus {
    .sw-block-field__block {
      box-shadow: 0 0 4px lighten(crimson, 30%);
    }
  }

  &.sw-field--small {
    margin-bottom: 0;

    input,
    textarea,
    select {
      padding: 4px 16px;
    }
  }

  &.sw-field--medium {
    margin-bottom: 6px;

    input,
    textarea,
    select {
      padding: 8px 16px;
    }
  }
}
</style>