<template>
  <NotificationContainer />
  <div class="d-flex flex-column min-vh-100 position-relative">
    <AppHeader />
    <main class="flex-grow-1 container d-flex flex-column">
      <RouterView v-slot="{ Component }">
        <template v-if="Component">
          <Suspense>
            <component :is="Component" :key="$route.path"></component>
            <template #fallback>
              <div>Loading...</div>
            </template>
          </Suspense>
        </template>
      </RouterView>
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from '@/layout/components/AppHeader.vue';
import AppFooter from '@/layout/components/AppFooter.vue';
import NotificationContainer from '@/components/toast/NotificationContainer.vue';
import { useHead } from '@vueuse/head';

useHead({
  title: 'Verspiel',
});
</script>
