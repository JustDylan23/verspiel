<template>
  <div class="dropdown">
    <button
      type="button"
      class="btn text-white btn-sm dropdown-toggle"
      :class="{ 'btn-dark': isTop }"
      data-bs-toggle="dropdown"
      data-bs-auto-close="outside"
      aria-expanded="false"
    >
      <i class="bi bi-gear"></i>
    </button>
    <form
      class="dropdown-menu dropdown-menu-end"
      style="width: 300px"
      @submit.prevent
    >
      <div class="dropdown-header d-flex align-items-center">
        <span class="flex-grow-1">Reader settings</span>
        <small class="btn btn-sm btn-dark" @click="reset">reset</small>
      </div>
      <li><hr class="dropdown-divider" /></li>
      <div class="p-3">
        <div class="mb-2">
          <label for="widthModel" class="form-label">
            Width: {{ width }}%
          </label>
          <input
            id="widthModel"
            v-model="widthModel"
            type="range"
            class="form-range"
            min="0"
            max="100"
          />
        </div>
        <div class="mb-2">
          <label for="fontSizeModel" class="form-label">
            Font size: {{ fontSize }}rem
          </label>
          <input
            id="fontSizeModel"
            v-model="fontSizeModel"
            type="range"
            class="form-range"
            min="0.2"
            max="10"
            step="0.2"
          />
        </div>
        <div>
          <label for="lineHeightModel" class="form-label">
            Line height: {{ lineHeight }}
          </label>
          <input
            id="lineHeightModel"
            v-model="lineHeightModel"
            type="range"
            class="form-range"
            min="1"
            max="10"
            step="0.1"
          />
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useVModel } from '@vueuse/core';

const emit = defineEmits([
  'update:width',
  'update:fontSize',
  'update:lineHeight',
]);

const props = defineProps({
  isTop: {
    type: Boolean,
    required: true,
  },
  width: {
    type: [Number, String],
    required: true,
  },
  fontSize: {
    type: [Number, String],
    required: true,
  },
  lineHeight: {
    type: [Number, String],
    required: true,
  },
});

const widthModel = useVModel(props, 'width', emit);
const fontSizeModel = useVModel(props, 'fontSize', emit);
const lineHeightModel = useVModel(props, 'lineHeight', emit);

const reset = () => {
  widthModel.value = 100;
  fontSizeModel.value = 1;
  lineHeightModel.value = 1.5;
};
</script>
