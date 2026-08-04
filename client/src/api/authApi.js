import axiosInstance from './axiosInstance';

export const authApi = {
  login: (credentials) => axiosInstance.post('/auth/login', credentials),
  logout: () => axiosInstance.post('/auth/logout'),
  getProfile: () => axiosInstance.get('/auth/profile'),
  updateProfile: (data) => axiosInstance.put('/auth/profile', data),
  changePassword: (data) => axiosInstance.post('/auth/change-password', data),
  forgotPassword: (data) => axiosInstance.post('/auth/forgot-password', data),
  resetPassword: (data) => axiosInstance.post('/auth/reset-password', data)
};
