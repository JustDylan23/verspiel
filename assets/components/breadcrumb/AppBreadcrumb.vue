<template>
  <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
    <ol class="breadcrumb flex-nowrap">
      <RouterLink
        v-for="(navigation, key) in nav"
        :key="key"
        :to="navigation.to"
        class="breadcrumb-item d-block"
        :class="{
          'text-white-50': key + 1 !== nav.length,
          'text-white': key + 1 === nav.length,
          'text-truncate': key !== 0,
        }"
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
