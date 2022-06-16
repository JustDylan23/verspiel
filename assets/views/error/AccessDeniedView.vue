<template>
  <div class="text-center">
    <h1>Access denied</h1>
    <p>Log in to view this page</p>
    <RouterLink :to="{ name: 'home' }">Return to the home page</RouterLink>
  </div>
</template>

<script setup>
import { useHead } from '@vueuse/head';

useHead({
  title: 'Access denied - Verspiel',
  meta: [
    {
      name: 'robots',
      content: 'noindex',
    },
  ],
});
</script>
