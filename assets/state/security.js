import { computed, ref } from 'vue';
import axios from 'axios';

const user = ref(null);
const isAuthenticated = computed(() => user.value !== null);
const logout = () => {
  user.value = null;
  clearSession();
};

const securedAxios = axios.create();

const authenticate = async (credentials) => {
  const { token } = await axios
    .post('/api/token/authenticate', credentials)
    .then((response) => response.data);
  securedAxios.defaults.headers['Authorization'] = `Bearer ${token}`;
  const { data } = await securedAxios.get('/api/users/@me');
  localStorage.setItem('auth', '1');
  user.value = data;
  isAuthenticated.value = true;
};

const refreshUser = async () => {
  try {
    const { token } = await axios
      .post('/api/token/refresh')
      .then((response) => response.data);
    securedAxios.defaults.headers['Authorization'] = `Bearer ${token}`;
    const { data } = await securedAxios.get('/api/users/@me');
    user.value = data;
  } catch (error) {
    clearSession();
  }
};

const refreshToken = async () => {
  try {
    const { token } = await axios
      .post('/api/token/refresh')
      .then((response) => response.data);
    securedAxios.defaults.headers['Authorization'] = `Bearer ${token}`;
    return token;
  } catch (error) {
    logout();
    return null;
  }
};

securedAxios.interceptors.response.use(
  (response) => {
    return response;
  },
  async function (error) {
    const originalRequest = error.config;
    if (
      error.response.status === 401 &&
      error.response.data.message === 'Expired JWT Token' &&
      !originalRequest._retry
    ) {
      originalRequest._retry = true;
      const token = await refreshToken();
      if (token !== null) {
        error.response.config.headers['Authorization'] = `Bearer ${token}`;
        return securedAxios(originalRequest);
      }
    }
    return Promise.reject(error);
  }
);

const hasSession = () => {
  return localStorage.getItem('auth') === '1';
};

const clearSession = () => {
  securedAxios.defaults.headers['Authorization'] = undefined;
  localStorage.removeItem('auth');
};

const isAdmin = computed(
  () => isAuthenticated.value && user.value.badges.includes('admin')
);
const isCommentMod = computed(
  () => user.value !== null && user.value.badges.includes('moderator')
);
const canDeleteComments = computed(() => isAdmin.value || isCommentMod.value);

export const useSecurity = () => {
  return {
    user,
    isAuthenticated,
    authenticate,
    logout,
    hasSession,
    refreshUser,
    securedAxios,
    canDeleteComments,
  };
};
