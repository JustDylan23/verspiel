<template>
  <small v-if="errors !== undefined" class="invalid-feedback">
    {{ errors.join(', ') }}
  </small>
</template>

<script setup>
defineProps({
  errors: {
    type: Array,
    required: false,
    default: () => undefined,
  },
});
</script>
