<template>
  <div class="text-center">
    <h1>Page not found</h1>
    <RouterLink :to="{ name: 'home' }">Return to the home page</RouterLink>
  </div>
</template>

<script setup>
import { useHead } from '@vueuse/head';

useHead({
  title: 'Page not found - Verspiel',
  meta: [
    {
      name: 'robots',
      content: 'noindex',
    },
  ],
});
</script>
