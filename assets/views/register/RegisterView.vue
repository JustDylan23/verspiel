<template>
  <h3 v-if="!success" class="text-center mb-3">Sign up</h3>
  <div v-if="!success" class="align-self-center card" style="max-width: 500px">
    <form class="card-body" @submit.prevent="submitForm(registration)">
      <div class="mb-3">
        <label for="usernameInput" class="form-label">Username</label>
        <input
          id="usernameInput"
          v-model="registration.data.username"
          type="text"
          class="form-control"
          placeholder="Example"
          required
          :class="{ 'is-invalid': registration.validationErrors.username }"
        />
        <FormFeedback :errors="registration.validationErrors.username" />
      </div>
      <div class="mb-3">
        <label for="emailInput" class="form-label">Email</label>
        <input
          id="emailInput"
          v-model="registration.data.email"
          type="email"
          placeholder="email@address.com"
          required
          class="form-control"
          :class="{ 'is-invalid': registration.validationErrors.email }"
        />
        <FormFeedback :errors="registration.validationErrors.email" />
        <small class="form-text">
          We'll never share your email with anyone else.
        </small>
      </div>
      <div class="mb-3">
        <label for="passwordInput" class="form-label">Password</label>
        <input
          id="passwordInput"
          v-model="registration.data.password"
          type="password"
          required
          placeholder="Password"
          class="form-control"
          :class="{ 'is-invalid': registration.validationErrors.password }"
        />
        <FormFeedback :errors="registration.validationErrors.password" />
      </div>
      <div class="mb-3">
        <label for="confirmPasswordInput" class="form-label">
          Confirm password
        </label>
        <input
          id="confirmPasswordInput"
          v-model="registration.data.confirmPassword"
          type="password"
          placeholder="Confirm password"
          required
          class="form-control"
          :class="{
            'is-invalid': registration.validationErrors.confirmPassword,
          }"
        />
        <FormFeedback :errors="registration.validationErrors.confirmPassword" />
      </div>
      <button
        type="submit"
        class="btn btn-primary w-100"
        :disabled="registration.isSubmitting"
      >
        Register
      </button>
    </form>
  </div>
  <div v-else class="text-center">
    <h3>Check your email inbox</h3>
    <p>
      An email verification link has been sent to
      <kbd class="text-bg-light-grey">{{ registration.data.email }}</kbd>
    </p>
    <RouterLink :to="{ name: 'home' }">Return to the home page</RouterLink>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import FormFeedback from '@/components/form/FormFeedback.vue';
import { submitForm } from '@/utils/form';
import { useHead } from '@vueuse/head';

const success = ref(false);

const registration = reactive({
  to: '/api/users/register',
  data: {
    username: null,
    email: null,
    password: null,
    confirmPassword: null,
  },
  isSubmitting: false,
  validationErrors: {},
  postSave: () => {
    success.value = true;
  },
});

useHead({
  title: 'Sign Up - Verspiel',
  meta: [
    {
      name: 'description',
      content:
        'Experience the power of feedback when you create an account using username and password of your choice',
    },
  ],
});
</script>
