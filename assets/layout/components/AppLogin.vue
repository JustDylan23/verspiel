<template>
  <a
    class="nav-link dropdown-toggle"
    href="#"
    role="button"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    Login
  </a>
  <div
    class="dropdown-menu dropdown-menu-end"
    style="z-index: 1200; min-width: 200px"
  >
    <form
      class="px-4 py-3"
      autocomplete="off"
      @submit.prevent="login"
      @click.stop
    >
      <div class="mb-3">
        <label for="loginFormUsername" class="form-label">Username</label>
        <input
          id="loginFormUsername"
          v-model="formData.username"
          type="text"
          required
          class="form-control"
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
          class="form-control"
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

      <div v-if="error" class="text-danger mt-2">
        {{ error }}
      </div>
    </form>
    <div class="dropdown-divider" />
    <RouterLink class="dropdown-item" :to="{ name: 'register' }">
      New around here? Sign up
    </RouterLink>
    <RouterLink class="dropdown-item" :to="{ name: 'password-reset-request' }">
      Forgot password?
    </RouterLink>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useSecurity } from '@/state/security';
import { useRoute, useRouter } from 'vue-router';

const isSubmitting = ref(false);
const error = ref(false);
const formData = reactive({
  username: '',
  password: '',
});

const { authenticate } = useSecurity();

const route = useRoute();
const router = useRouter();

const login = async () => {
  isSubmitting.value = true;
  try {
    await authenticate(formData);
    if (route?.meta?.auth === false) {
      await router.push({ name: 'home' });
    }
  } catch (e) {
    error.value = e.response.data.message;
  }
  isSubmitting.value = false;
};
</script>
