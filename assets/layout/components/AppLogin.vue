<template>
  <a
    class="nav-link dropdown-toggle"
    href="#"
    role="button"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    Account
  </a>
  <div
    class="dropdown-menu dropdown-menu-end"
    style="z-index: 1200; min-width: 200px"
  >
    <RouterLink class="dropdown-item" :to="{ name: 'login' }">
      <i class="bi bi-box-arrow-in-left me-2"></i>
      Login
    </RouterLink>
    <RouterLink class="dropdown-item" :to="{ name: 'register' }">
      <i class="bi bi-person-plus me-2" />
      Sign up
    </RouterLink>
    <RouterLink class="dropdown-item" :to="{ name: 'password-reset-request' }">
      <i class="bi bi-question-circle me-2" />
      Forgot password
    </RouterLink>
  </div>
</template>
