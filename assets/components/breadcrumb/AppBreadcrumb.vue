<template>
  <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <RouterLink
        v-for="(navigation, key) in nav"
        :key="key"
        :to="navigation.to"
        class="breadcrumb-item"
        :class="key + 1 !== nav.length ? 'text-white-50' : 'text-white'"
      >
        {{ navigation.name }}
      </RouterLink>
    </ol>
  </nav>
</template>

<script setup>
defineProps({
  nav: {
    type: Array,
    required: true,
  },
});
</script>
