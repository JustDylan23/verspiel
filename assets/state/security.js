import { computed, ref } from 'vue';
import axios from 'axios';

const user = ref(null);
const isAuthenticated = computed(() => user.value !== null);
const logout = () => {
  user.value = null;
  clearSession();
};

const authenticate = async (credentials) => {
  const { data } = await axios.post('/api/login', credentials);
  localStorage.setItem('auth', '1');
  user.value = data;
  isAuthenticated.value = true;
};

const refreshUser = async () => {
  try {
    const { data } = await axios.get('/api/users/me');
    user.value = data;
  } catch (error) {
    clearSession();
  }
};

const hasSession = () => {
  return localStorage.getItem('auth') === '1';
};

const clearSession = () => {
  localStorage.removeItem('auth');
};

export const useSecurity = () => {
  return {
    user,
    isAuthenticated,
    authenticate,
    logout,
    hasSession,
    refreshUser,
  };
};
