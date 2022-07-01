<template>
  <h3 v-if="!success" class="text-center mb-3">Change password</h3>
  <div
    v-if="!success"
    class="align-self-center card contain"
    style="max-width: 500px"
  >
    <form class="card-body" @submit.prevent="submitForm(user)">
      <div class="mb-3">
        <label for="passwordInput" class="form-label">Password</label>
        <input
          id="passwordInput"
          v-model="user.data.password"
          type="password"
          required
          placeholder="Password"
          class="form-control"
          :class="{ 'is-invalid': user.validationErrors.password }"
        />
        <FormFeedback :errors="user.validationErrors.password" />
      </div>
      <div class="mb-3">
        <label for="confirmPasswordInput" class="form-label">
          Confirm password
        </label>
        <input
          id="confirmPasswordInput"
          v-model="user.data.confirmPassword"
          type="password"
          placeholder="Confirm password"
          required
          class="form-control"
          :class="{
            'is-invalid': user.validationErrors.confirmPassword,
          }"
        />
        <FormFeedback :errors="user.validationErrors.confirmPassword" />
      </div>
      <button
        type="submit"
        class="btn btn-primary w-100"
        :disabled="user.isSubmitting"
      >
        Submit
      </button>
    </form>
  </div>
  <div v-else class="text-center">
    <h3>Password has been reset</h3>
    <p>Log in with the new password.</p>
    <RouterLink :to="{ name: 'home' }">Return to the home page</RouterLink>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRoute } from 'vue-router';
import FormFeedback from '@/components/form/FormFeedback.vue';
import { submitForm } from '@/utils/form';
import { useSecurity } from '@/state/security.js';
import { useHead } from '@vueuse/head';

const success = ref(false);

const route = useRoute();

const { logout } = useSecurity();

const user = reactive({
  to: '/api/password-reset/complete',
  data: {
    token: route.params.token,
    password: null,
    confirmPassword: null,
  },
  isSubmitting: false,
  validationErrors: {},
  postSave: () => {
    success.value = true;
    logout(route);
  },
});

useHead({
  title: 'Enter New Password - Verspiel',
  meta: [
    {
      name: 'robots',
      content: 'noindex',
    },
  ],
});
</script>
