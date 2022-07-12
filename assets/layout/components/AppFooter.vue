<template>
  <footer
    class="container py-4 d-none d-sm-flex align-items-center text-white-50"
  >
    <div class="flex-grow-1">
      {{ new Date().getFullYear() }} &copy; Verspiel
    </div>
    <a
      class="btn text-white-50"
      href="https://discord.gg/8URgeTUumB"
      target="_blank"
    >
      <i class="bi bi-discord me-2" />
      Join our discord
    </a>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
};
</script>
