<template>
  <div class="reply-field mt-2">
    <small v-if="!isAuthenticated" class="text-white-50 fst-italic">
      Log in to leave comments
    </small>
    <div v-else class="position-relative">
      <textarea
        v-model="text"
        type="text"
        class="form-control bg-transparent text-light pe-5"
        :placeholder="isReaction ? 'Add a reaction...' : 'Leave a comment...'"
        :rows="newLines"
        style="resize: none"
        maxlength="500"
        @keydown.ctrl.enter="send"
      />
      <button
        ref="btn"
        class="btn btn-outline-primary border-0 position-absolute bottom-0 end-0"
        :disabled="isSubmitting"
        @click="send"
      >
        <i class="bi bi-send" /> Send
      </button>
    </div>
    <small class="text-danger fst-italic">{{ error }}</small>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';
import useSecurity from '@/state/security.js';
import axios from 'axios';
import replyBus from '@/components/comment/replyBus.js';

const emit = defineEmits(['send']);
const isSubmitting = ref(false);

const props = defineProps({
  isReaction: {
    type: Boolean,
    default: false,
  },
  commentSection: {
    type: Number,
    required: true,
  },
  replyTo: {
    type: Number,
    required: false,
    default: () => undefined,
  },
});

const { isAuthenticated, user } = useSecurity();

const error = ref(null);
const text = ref('');
const btn = ref();
const newLines = computed(() => text.value.split('\n').length);

const send = async () => {
  if (text.value.trim()) {
    isSubmitting.value = true;
    error.value = null;
    try {
      const { data } = await axios.post('/api/comments', {
        commentSection: props.commentSection,
        replyTo: props.replyTo,
        content: text.value.trim(),
        author: user.value.id,
      });

      const event = reactive({
        id: data.id,
        content: text.value.trim(),
        author: user.value,
        createdAt: new Date().getTime(),
        replyCount: 0,
      });
      if (props.replyTo) {
        replyBus.emit(props.replyTo, event);
      }
      emit('send', event);
    } catch (e) {
      error.value = e.response.data.message;
    } finally {
      isSubmitting.value = false;
      text.value = '';
      btn.value.blur();
    }
  }
};
</script>

<style lang="scss">
.reply-field {
  button {
    display: none;
  }

  input {
    padding-right: 6rem;
  }
}

.reply-field:focus-within button {
  display: block;
}
</style>
