import axios from 'axios';

export const submitForm = async (form) => {
  if (form.isSubmitting === true) {
    return;
  }
  try {
    form.isSubmitting = true;
    form.error = null;
    form.validationErrors = {};
    await axios.post(form.to, form.data);
    form.postSave();
  } catch (e) {
    if (e.response.status === 422) {
      form.validationErrors = e.response.data.data;
    }
  } finally {
    form.isSubmitting = false;
  }
};
