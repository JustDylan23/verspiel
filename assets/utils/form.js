import axios from 'axios';
import { useSecurity } from '@/state/security.js';

const { securedAxios } = useSecurity();

export const submitForm = async (form) => {
  if (form.isSubmitting === true) {
    return;
  }
  try {
    form.isSubmitting = true;
    form.error = null;
    form.validationErrors = {};
    const axiosInstance = form.secured ? securedAxios : axios;
    await axiosInstance[form.method ?? 'post'](form.to, form.data);
    form.postSave();
  } catch (e) {
    if (e.response.status === 422) {
      form.validationErrors = e.response.data.data;
    }
  } finally {
    form.isSubmitting = false;
  }
};
