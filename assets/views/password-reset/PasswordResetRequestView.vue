<template>
  <h3 v-if="!success" class="text-center mb-3">Reset password</h3>
  <div v-if="!success" class="card align-self-center" style="max-width: 500px">
    <div class="card-body">
      <form autocomplete="off" @submit.prevent="reset()">
        <div class="mb-3">
          <label for="resetEmailInput" class="form-label">Email address</label>
          <input
            id="resetEmailInput"
            v-model="email"
            type="email"
            class="form-control"
            required
            placeholder="example@address.com"
          />
          <small class="form-text">
            You will receive an email with a link to reset your password.
          </small>
        </div>
        <button class="btn btn-primary w-100" :disabled="isSubmitting">
          Request password reset
        </button>
      </form>
    </div>
  </div>
  <div v-else class="text-center">
    <h3>Check your email inbox</h3>
    <p>
      A link has been sent to reset your email address if we found an account
      that matches the email address you provided.
      <kbd class="text-bg-light-grey">{{ email }}</kbd>
    </p>
    <RouterLink :to="{ name: 'home' }">Return to the home page</RouterLink>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useHead } from '@vueuse/head';

const success = ref(false);
const isSubmitting = ref(false);

const email = ref('');

const reset = async () => {
  if (isSubmitting.value) {
    return;
  }
  isSubmitting.value = true;
  try {
    await axios.post('/api/password-reset/request', {
      email: email.value,
    });
    success.value = true;
  } catch (e) {
    // ignored
  } finally {
    isSubmitting.value = false;
  }
};

useHead({
  title: 'Request Password Reset - Verspiel',
});
</script>
