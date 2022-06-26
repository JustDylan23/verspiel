import { computed, ref } from 'vue';
import axios from 'axios';

const user = ref(null);
const isAuthenticated = computed(() => user.value !== null);
const logout = () => {
  axios.post('/api/token/revoke', getRefreshToken());
  clearSession();
};

const securedAxios = axios.create();

const authenticate = async (credentials) => {
  const { token, refresh_token } = await axios
    .post('/api/token/authenticate', credentials)
    .then((response) => response.data);
  setJwtToken(token);
  const { data } = await securedAxios.get('/api/users/@me');
  setRefreshToken(refresh_token);
  user.value = data;
  isAuthenticated.value = true;
};

const refreshUser = async () => {
  try {
    const { token } = await axios
      .post('/api/token/refresh', getRefreshToken())
      .then((response) => response.data);
    setJwtToken(token);
    const { data } = await securedAxios.get('/api/users/@me');
    user.value = data;
  } catch (error) {
    clearSession();
  }
};

const refreshToken = async () => {
  try {
    const { token } = await axios
      .post('/api/token/refresh', getRefreshToken())
      .then((response) => response.data);
    setJwtToken(token);
    return token;
  } catch (error) {
    clearSession();
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
        console.log('retry');
        return securedAxios(originalRequest);
      }
    }
    return Promise.reject(error);
  }
);

const hasSession = () => {
  return localStorage.getItem('refresh_token') !== null;
};

const getRefreshToken = () => {
  return localStorage.getItem('refresh_token');
};

const setRefreshToken = (token) => {
  return localStorage.setItem('refresh_token', token);
};

const clearSession = () => {
  user.value = null;
  setJwtToken(undefined);
  localStorage.removeItem('refresh_token');
};

const setJwtToken = (token) => {
  if (token === undefined) {
    delete securedAxios.defaults.headers['Authorization'];
  } else {
    securedAxios.defaults.headers['Authorization'] = `Bearer ${token}`;
  }
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
