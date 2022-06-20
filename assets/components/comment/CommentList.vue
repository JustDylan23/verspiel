<template>
  <ReplyField
    v-if="replyTo === null"
    :comment-section="commentSection"
    class="mb-4"
    @send="addComment"
  />
  <div ref="commentList" class="comment-list">
    <div
      v-for="(comment, key) in comments"
      :id="'comment-' + comment.id"
      :key="comment.id"
      class="mb-4"
      itemscope
      itemtype="https://schema.org/Comment"
    >
      <div class="card-header d-flex align-items-start">
        <div class="flex-grow-1 d-flex" style="min-width: 0">
          <strong class="flex-shrink-1 text-truncate me-2" itemprop="author">
            {{ comment.author.username }}
          </strong>
          <span
            v-for="(badge, k2) in comment.author.badges"
            :key="k2"
            class="badge bg-primary text-black rounded-pill me-2 text-capitalize"
            style="font-size: 0.7rem; line-height: 1rem"
          >
            {{ badge }}
          </span>
        </div>
        <small class="text-nowrap ms-auto" itemprop="dateCreated">
          {{ formatDateString(comment.createdAt) }}
        </small>
      </div>
      <div class="card-body">
        <div
          :id="'content-' + comment.id"
          class="text-break overflow-hidden comment-content"
          :class="{ collapsed: !comment.expanded }"
          itemprop="text"
        >
          {{ comment.content }}
        </div>
        <div class="d-flex flex-wrap">
          <a
            v-if="comment.isCollapsed"
            class="text-white-50 me-2"
            @click="comment.expanded = !comment.expanded"
          >
            Read {{ comment.expanded ? 'less' : 'more' }}
            <i
              :class="comment.expanded ? 'bi-chevron-up' : 'bi-chevron-down'"
            />
          </a>
          <a
            v-if="depth < 3 && isAuthenticated"
            :href="'#reply-' + comment.id"
            class="text-white-50 me-2"
            data-bs-toggle="collapse"
          >
            Reply
            <i class="bi bi-reply"></i>
          </a>
          <a
            v-if="comment.replyCount > 0 && depth < 3"
            href="#"
            class="me-2"
            @click.prevent="comment.open = !comment.open"
          >
            {{
              comment.replyCount > 1
                ? 'View ' + comment.replyCount + ' responses'
                : 'View response'
            }}
            <i :class="comment.open ? 'bi-chevron-up' : 'bi-chevron-down'" />
          </a>
          <a
            v-if="canDeleteComments"
            class="text-danger"
            @click="deleteComment(comment, key)"
          >
            Delete
            <i class="bi bi-trash" />
          </a>
        </div>
        <div :id="'reply-' + comment.id" class="collapse">
          <ReplyField
            is-reaction
            :comment-section="commentSection"
            :reply-to="comment.id"
            @send="comment.replyCount++"
          />
        </div>
        <div v-if="comment.open" class="ms-5 mt-2">
          <Suspense>
            <CommentList
              ref="reactions"
              :comment-section="commentSection"
              :reply-to="comment.id"
              :total-replies="comment.replyCount"
              :depth="depth + 1"
              @remove="comment.replyCount--"
            />
            <template #fallback>
              <div>
                <span class="spinner-border spinner-border-sm"></span>
                Loading replies...
              </div>
            </template>
          </Suspense>
        </div>
      </div>
    </div>
  </div>
  <button
    v-if="
      cursor !== null &&
      (totalReplies === null || comments.length !== totalReplies)
    "
    class="btn btn-outline-primary w-100"
    :disabled="isLoading"
    @click="onLoadMore"
  >
    Load more
  </button>
  <div
    v-if="comments.length === 0 && replyTo === null"
    class="fs-5 text-center text-white-50"
  >
    Be the first to leave comment!
  </div>
</template>

<script setup>
import { nextTick, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import ReplyField from '@/components/comment/ReplyField.vue';
import { formatDateString } from '@/utils/date';
import replyBus from '@/components/comment/replyBus.js';
import { useDebounceFn } from '@vueuse/core';
import { useSecurity } from '@/state/security';
import { useToast } from '@/utils/notification.js';

const emit = defineEmits(['remove']);

const props = defineProps({
  commentSection: {
    type: Number,
    required: true,
  },
  replyTo: {
    type: Number,
    required: false,
    default: () => null,
  },
  totalReplies: {
    type: Number,
    required: false,
    default: () => null,
  },
  depth: {
    type: Number,
    required: false,
    default: () => 1,
  },
});

const { isAuthenticated, canDeleteComments, securedAxios } = useSecurity();

const reactions = ref();

const { success } = useToast();

const comments = reactive([]);
const cursor = ref(0);
const isLoading = ref(false);
const setData = ({ data }) => {
  comments.push(...data.items);
  cursor.value = data.cursor;
};

await axios
  .get(
    '/api/comment-sections/' +
      props.commentSection +
      '/comments' +
      '?replyTo=' +
      props.replyTo
  )
  .then(setData);

const commentList = ref();
const updateReadMore = () => {
  comments.forEach((comment) => {
    comment.expanded = false;
  });
  nextTick(() => {
    comments.forEach((comment) => {
      const content = document.getElementById('content-' + comment.id);
      comment.isCollapsed = isEllipsisActive(content);
    });
  });
};

const debouncedFn = useDebounceFn(updateReadMore, 500, { maxWait: 2000 });

const isEllipsisActive = (e) => e.clientHeight < e.scrollHeight;
onMounted(() => {
  updateReadMore();
  window.addEventListener('resize', debouncedFn);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', debouncedFn);
});

const addComment = (comment) => {
  comments.unshift(comment);
  nextTick(() => {
    const content = document.getElementById('content-' + comment.id);
    comment.isCollapsed = isEllipsisActive(content);
  });
};

if (props.replyTo) {
  replyBus.on(props.replyTo, addComment);
}

const deleteComment = async (comment, index) => {
  if (confirm('Are you sure you want to delete this comment?')) {
    await securedAxios.delete('/api/comments/' + comment.id);
    emit('remove');
    comments.splice(index, 1);

    success('Deleted comment');
  }
};

const onLoadMore = async () => {
  if (isLoading.value === true || cursor.value === null) {
    return;
  }
  isLoading.value = true;
  await axios
    .get(
      '/api/comment-sections/' +
        props.commentSection +
        '/comments?cursor=' +
        comments[comments.length - 1].id +
        '&replyTo=' +
        props.replyTo
    )
    .then(setData)
    .finally(() => {
      isLoading.value = false;
    });
};
</script>

<style lang="scss">
.comment-content {
  white-space: pre-wrap;

  &.collapsed {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }
}
</style>
