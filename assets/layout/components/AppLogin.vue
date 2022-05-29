<template>
  <a
    id="navbarDropdown"
    class="nav-link dropdown-toggle"
    href="#"
    role="button"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    Login
  </a>
  <div class="dropdown-menu dropdown-menu-end" style="z-index: 1200">
    <form class="px-4 py-3" @submit.prevent="login" @click.stop>
      <div class="mb-3">
        <label for="loginFormUsername" class="form-label">Username</label>
        <input
          id="loginFormUsername"
          v-model="formData.username"
          type="text"
          required
          class="form-control border-primary"
          :class="{ 'is-invalid': error }"
          placeholder="Example"
        />
      </div>
      <div class="mb-3">
        <label for="loginFormPassword" class="form-label">Password</label>
        <input
          id="loginFormPassword"
          v-model="formData.password"
          type="password"
          required
          class="form-control border-primary"
          :class="{ 'is-invalid': error }"
          placeholder="Password"
        />
      </div>
      <button
        type="submit"
        :disabled="isSubmitting"
        class="btn btn-primary w-100"
      >
        Sign in
      </button>

      <div v-if="error" class="text-danger mt-2 fst-italic">
        {{ error }}
      </div>
    </form>
    <div class="dropdown-divider" />
    <a class="dropdown-item" href="#">New around here? Sign up</a>
    <a class="dropdown-item" href="#">Forgot password?</a>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import useSecurity from '@/state/security';

const isSubmitting = ref(false);
const error = ref(false);
const formData = reactive({
  username: '',
  password: '',
});

const { authenticate } = useSecurity();

const login = async () => {
  isSubmitting.value = true;
  try {
    await authenticate(formData);
  } catch (e) {
    error.value = e.response.data.error;
  }
  isSubmitting.value = false;
};
</script>
