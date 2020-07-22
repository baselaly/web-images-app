<template>
  <div class="sw-inheritance-switch" :class="{ 'sw-inheritance-switch--disabled': disabled }">
    <icon
      v-if="isInherited"
      key="inherit-icon"
      :multicolor="true"
      :name="disabled ? 'custom-inherited-disabled' : 'custom-inherited'"
      v-tooltip="{ message: 'no', disabled: disabled }"
      @click="onClickRemoveInheritance"
      size="16"
    ></icon>
    <icon
      v-else
      key="uninherit-icon"
      :class="unInheritClasses"
      :multicolor="true"
      name="custom-uninherited"
      v-tooltip="{ message: 'global.sw-field.tooltipRestoreInheritance', disabled: disabled }"
      @click="onClickRestoreInheritance"
      size="16"
    ></icon>
  </div>
</template>
<script>
import Icon from "./Icon";

export default {
  name: "InheritanceSwitch",
  components: {
    Icon
  },
  props: {
    isInherited: {
      type: Boolean,
      required: true,
      default: false
    },

    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  computed: {
    unInheritClasses() {
      return { "is--clickable": !this.disabled };
    }
  },

  methods: {
    onClickRestoreInheritance() {
      if (this.disabled) {
        return;
      }
      this.$emit("inheritance-restore");
    },

    onClickRemoveInheritance() {
      if (this.disabled) {
        return;
      }
      this.$emit("inheritance-remove");
    }
  }
};
</script>
<style lang="scss">
.sw-inheritance-switch {
  cursor: pointer;
  margin-top: -1px;

  &.sw-inheritance-switch--disabled {
    cursor: default;
  }
}
</style>