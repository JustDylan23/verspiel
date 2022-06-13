<template>
  <h3>Edit profile</h3>
  <form class="mb-3" @submit.prevent="submitForm(profile)">
    <div class="mb-3">
      <label for="usernameInput" class="form-label">Username</label>
      <input
        id="usernameInput"
        v-model="profile.data.username"
        required
        type="text"
        class="form-control"
        :class="{ 'is-invalid': profile.validationErrors.username }"
      />
      <FormFeedback :errors="profile.validationErrors.username" />
    </div>
    <button
      type="submit"
      class="btn btn-primary w-100"
      :disabled="profile.isSubmitting"
    >
      Save
    </button>
  </form>
  <button
    class="btn btn-light-grey"
    :disabled="isResetting"
    @click="resetPassword"
  >
    Request password reset
  </button>
</template>

<script setup>
import { useSecurity } from '@/state/security';
import { reactive, ref } from 'vue';
import FormFeedback from '@/components/form/FormFeedback.vue';
import { useToast } from '@/utils/notification.js';
import { useHead } from '@vueuse/head';
import { submitForm } from '@/utils/form.js';
import axios from 'axios';

const { user } = useSecurity();
const { toast } = useToast();

const profile = reactive({
  to: '/api/users/me',
  data: {
    username: user.value.username,
  },
  isSubmitting: false,
  validationErrors: {},
  postSave: () => {
    user.value.username = profile.data.username;
    toast('Profile updated');
  },
});

const isResetting = ref(false);
const resetPassword = async () => {
  if (isResetting.value) {
    return;
  }
  isResetting.value = true;
  try {
    await axios.post('/api/password-reset/request', {
      email: user.value.email,
    });
    toast('Password reset request sent');
  } catch (error) {
    // ignored
  }
};

useHead({
  title: 'Profile - Verspiel',
  meta: [
    {
      name: 'robots',
      content: 'noindex',
    },
  ],
});
</script>
