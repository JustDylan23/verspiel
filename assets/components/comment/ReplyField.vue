<template>
  <div class="reply-field mt-2">
    <small v-if="!isAuthenticated" class="text-white-50">
      Log in to leave comments
    </small>
    <div v-else class="d-flex w-100">
      <textarea
        v-model="text"
        type="text"
        class="form-control bg-transparent text-light"
        :placeholder="isReaction ? 'Add a reaction...' : 'Leave a comment...'"
        :rows="newLines"
        style="resize: none"
        maxlength="500"
        @keydown.ctrl.enter="send"
      />
      <button
        ref="btn"
        class="btn btn-outline-primary border-0 text-nowrap"
        :disabled="isSubmitting"
        @click="send"
      >
        <span class="align-self-end">
          <i class="bi bi-send" />
          Send
        </span>
      </button>
    </div>
    <small class="text-danger">{{ error }}</small>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';
import { useSecurity } from '@/state/security';
import replyBus from '@/components/comment/replyBus.js';
import { useToast } from '@/utils/notification.js';
import axios from 'axios';

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

const { success } = useToast();

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

      success('Comment sent');

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
  display: flex;
}
</style>
