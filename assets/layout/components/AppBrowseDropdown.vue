<template>
  <li class="nav-item dropdown d-none d-sm-block">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      role="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      :class="{ abcd: $route.name === 'browse' }"
    >
      Browse
    </a>
    <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1200">
      <li>
        <RouterLink
          class="dropdown-item"
          :to="{ name: 'browse', params: { type: 'novels' } }"
          active-class="active"
        >
          <i class="bi bi-book me-2"></i>
          Novels
        </RouterLink>
      </li>
      <li>
        <RouterLink
          class="dropdown-item"
          :to="{ name: 'browse', params: { type: 'chapters' } }"
          active-class="active"
        >
          <i class="bi bi-bookmark me-2"></i>
          Chapters
        </RouterLink>
      </li>
    </ul>
  </li>
</template>
