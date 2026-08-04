import axiosInstance from './axiosInstance';

export const dashboardApi = {
  getOverview: () => axiosInstance.get('/dashboard/overview'),
  deleteItem: (type, id) => axiosInstance.delete(`/dashboard/${type}/${id}`)
};
