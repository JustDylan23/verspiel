import { ref } from 'vue';

const notifications = ref([]);

export const useNotifications = () => ({ notifications });

const toast = (message, variant = 'dark-grey') => {
  notifications.value.unshift({
    message,
    variant,
  });
  setTimeout(() => {
    notifications.value.pop();
  }, 3000);
};

const success = (message) => toast(message, 'success');

const error = (message) => toast(message, 'danger');

export const useToast = () => ({
  toast,
  success,
  error,
});
