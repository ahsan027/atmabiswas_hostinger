import axiosInstance from './axiosInstance';

export const jobApi = {
  getJobs: (params) => axiosInstance.get('/jobs', { params }),
  getMetaData: () => axiosInstance.get('/jobs/metadata'),
  getJobById: (id) => axiosInstance.get(`/jobs/${id}`),
  createJob: (data) => axiosInstance.post('/jobs', data),
  updateJob: (id, data) => axiosInstance.put(`/jobs/${id}`, data),
  deleteJob: (id) => axiosInstance.delete(`/jobs/${id}`),
  createJobPosition: (data) => axiosInstance.post('/jobs/positions', data),
  submitApplication: (formData) => axiosInstance.post('/jobs/apply', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  getApplications: (params) => axiosInstance.get('/jobs/applications/all', { params }),
  deleteApplication: (id) => axiosInstance.delete(`/jobs/applications/${id}`)
};
