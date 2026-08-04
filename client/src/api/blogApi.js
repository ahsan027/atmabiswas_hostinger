import axiosInstance from './axiosInstance';

export const blogApi = {
  getBlogs: (params) => axiosInstance.get('/blogs', { params }),
  getBlogById: (id) => axiosInstance.get(`/blogs/${id}`),
  createBlog: (formData) => axiosInstance.post('/blogs', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  updateBlog: (id, formData) => axiosInstance.put(`/blogs/${id}`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  toggleFeatured: (id) => axiosInstance.patch(`/blogs/${id}/featured`),
  toggleStatus: (id) => axiosInstance.patch(`/blogs/${id}/status`),
  deleteBlog: (id) => axiosInstance.delete(`/blogs/${id}`)
};
