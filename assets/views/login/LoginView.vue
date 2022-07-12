<template>
  <h3 class="text-center mb-3">Log in</h3>
  <div class="card align-self-center card contain" style="max-width: 500px">
    <form class="card-body" @submit.prevent="login">
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
      <div class="d-flex flex-column mt-2">
        <RouterLink :to="{ name: 'register' }">
          New around here? Sign up
        </RouterLink>
        <RouterLink :to="{ name: 'password-reset-request' }">
          Forgot password?
        </RouterLink>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useSecurity } from '@/state/security';
import { useRouter } from 'vue-router';
import { useToast } from '@/utils/notification.js';

const isSubmitting = ref(false);
const error = ref(false);
const formData = reactive({
  username: '',
  password: '',
});

const { success } = useToast();

const { authenticate, user } = useSecurity();

const router = useRouter();

const login = async () => {
  isSubmitting.value = true;
  try {
    await authenticate(formData);
    success('Welcome ' + user.value.username);
    await router.push({ name: 'home' });
  } catch (e) {
    error.value = e.response.data.message;
  }
  isSubmitting.value = false;
};
</script>
