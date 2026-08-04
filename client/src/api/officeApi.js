import axiosInstance from './axiosInstance';

export const officeApi = {
  // Regional Offices
  getRegionalOffices: (params) => axiosInstance.get('/offices/regional', { params }),
  getRegionalOfficeById: (id) => axiosInstance.get(`/offices/regional/${id}`),
  createRegionalOffice: (data) => axiosInstance.post('/offices/regional', data),
  updateRegionalOffice: (id, data) => axiosInstance.put(`/offices/regional/${id}`, data),
  toggleRegionalOfficeStatus: (id) => axiosInstance.patch(`/offices/regional/${id}/toggle`),
  deleteRegionalOffice: (id) => axiosInstance.delete(`/offices/regional/${id}`),

  // Branches
  getBranches: (params) => axiosInstance.get('/offices/branches', { params }),
  getBranchById: (id) => axiosInstance.get(`/offices/branches/${id}`),
  createBranch: (data) => axiosInstance.post('/offices/branches', data),
  updateBranch: (id, data) => axiosInstance.put(`/offices/branches/${id}`, data),
  toggleBranchStatus: (id) => axiosInstance.patch(`/offices/branches/${id}/toggle`),
  deleteBranch: (id) => axiosInstance.delete(`/offices/branches/${id}`),

  // Divisions
  getDivisions: () => axiosInstance.get('/offices/divisions'),
  createDivision: (data) => axiosInstance.post('/offices/divisions', data),
  toggleDivisionStatus: (id) => axiosInstance.patch(`/offices/divisions/${id}/toggle`),
  deleteDivision: (id) => axiosInstance.delete(`/offices/divisions/${id}`)
};
