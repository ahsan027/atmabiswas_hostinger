import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { jobApi } from '../api/jobApi';
import Navbar from '../components/Navbar';
import { Search, Plus, Briefcase, Calendar, MapPin, DollarSign, Edit3, Trash2, X, ChevronLeft, ChevronRight, RefreshCw, Users, FileCheck } from 'lucide-react';
import toast from 'react-hot-toast';

const JobManager = () => {
  const [jobs, setJobs] = useState([]);
  const [sectors, setSectors] = useState([]);
  const [positions, setPositions] = useState([]);
  const [pagination, setPagination] = useState({ page: 1, limit: 10, totalPages: 1, total: 0 });
  const [loading, setLoading] = useState(true);

  // Filters
  const [search, setSearch] = useState('');
  const [deptFilter, setDeptFilter] = useState('');
  const [currentPage, setCurrentPage] = useState(1);

  // Modals
  const [isJobModalOpen, setIsJobModalOpen] = useState(false);
  const [isPosModalOpen, setIsPosModalOpen] = useState(false);
  const [editingJob, setEditingJob] = useState(null);

  const {
    register,
    handleSubmit,
    reset,
    setValue,
    watch,
    formState: { errors }
  } = useForm({
    defaultValues: {
      vacancy: 1,
      apply_enabled: true
    }
  });

  const {
    register: registerPos,
    handleSubmit: handleSubmitPos,
    reset: resetPos,
    formState: { errors: errorsPos }
  } = useForm();

  const fetchMetaData = async () => {
    try {
      const res = await jobApi.getMetaData();
      if (res.data.success) {
        setSectors(res.data.sectors || []);
        setPositions(res.data.positions || []);
      }
    } catch (err) {
      console.error(err);
    }
  };

  const fetchJobs = async () => {
    setLoading(true);
    try {
      const res = await jobApi.getJobs({
        page: currentPage,
        limit: 10,
        search,
        dept: deptFilter
      });
      if (res.data.success) {
        setJobs(res.data.data);
        setPagination(res.data.pagination);
      }
    } catch (err) {
      toast.error('Failed to load job circulars');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchMetaData();
  }, []);

  useEffect(() => {
    fetchJobs();
  }, [currentPage, search, deptFilter]);

  const openCreateModal = () => {
    setEditingJob(null);
    reset({
      job_code: '',
      job_title: '',
      deadline: '',
      job_dept: sectors[0]?.sector_name || '',
      job_location: '',
      salary_range: '',
      job_experience: '',
      job_skillset: '',
      job_description: '',
      job_req: '',
      job_benefits: '',
      vacancy: 1,
      bdjobs_link: '',
      apply_enabled: true
    });
    setIsJobModalOpen(true);
  };

  const openEditModal = (jobItem) => {
    setEditingJob(jobItem);
    setValue('job_code', jobItem.job_code);
    setValue('job_title', jobItem.job_title);
    setValue('deadline', jobItem.deadline ? jobItem.deadline.split('T')[0] : '');
    setValue('job_dept', jobItem.job_dept);
    setValue('job_location', jobItem.job_location);
    setValue('salary_range', jobItem.salary_range);
    setValue('job_experience', jobItem.job_experience);
    setValue('job_skillset', jobItem.job_skillset || '');
    setValue('job_description', jobItem.job_description);
    setValue('job_req', jobItem.job_req || '');
    setValue('job_benefits', jobItem.job_benefits || '');
    setValue('vacancy', jobItem.vacancy || 1);
    setValue('bdjobs_link', jobItem.bdjobs_link || '');
    setValue('apply_enabled', Boolean(jobItem.apply_enabled));
    setIsJobModalOpen(true);
  };

  const onSubmitJob = async (formData) => {
    try {
      if (editingJob) {
        const res = await jobApi.updateJob(editingJob.job_id, formData);
        if (res.data.success) {
          toast.success('Job circular updated successfully');
          setIsJobModalOpen(false);
          fetchJobs();
        }
      } else {
        const res = await jobApi.createJob(formData);
        if (res.data.success) {
          toast.success('Job circular published successfully');
          setIsJobModalOpen(false);
          fetchJobs();
        }
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Action failed');
    }
  };

  const onSubmitPos = async (posData) => {
    try {
      const res = await jobApi.createJobPosition(posData);
      if (res.data.success) {
        toast.success('New job position code added');
        setIsPosModalOpen(false);
        resetPos();
        fetchMetaData();
      }
    } catch (err) {
      toast.error('Failed to add job position');
    }
  };

  const handleDelete = async (id, title) => {
    if (!window.confirm(`Are you sure you want to delete job circular "${title}"?`)) return;

    try {
      const res = await jobApi.deleteJob(id);
      if (res.data.success) {
        toast.success('Job circular deleted successfully');
        fetchJobs();
      }
    } catch (err) {
      toast.error('Failed to delete job circular');
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Careers & Job Openings</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Manage career circulars, job codes, and departments</p>
          </div>
          <div className="flex items-center space-x-3">
            <button
              onClick={() => setIsPosModalOpen(true)}
              className="inline-flex items-center space-x-2 px-3.5 py-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs border border-slate-700 transition-all"
            >
              <Plus className="w-4 h-4" />
              <span>+ Position Code</span>
            </button>
            <button
              onClick={openCreateModal}
              className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-xs transition-all shadow-lg shadow-cyan-500/20"
            >
              <Briefcase className="w-4 h-4" />
              <span>Create Job Circular</span>
            </button>
          </div>
        </div>

        {/* Search & Filter Bar */}
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
          <div className="relative w-full md:w-80">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search by job title, code, or location..."
              value={search}
              onChange={(e) => {
                setSearch(e.target.value);
                setCurrentPage(1);
              }}
              className="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500"
            />
          </div>

          <div className="flex items-center space-x-3 w-full md:w-auto justify-end">
            <select
              value={deptFilter}
              onChange={(e) => {
                setDeptFilter(e.target.value);
                setCurrentPage(1);
              }}
              className="bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 px-3 py-2 focus:outline-none focus:border-cyan-500"
            >
              <option value="">All Sectors / Depts</option>
              {sectors.map((s) => (
                <option key={s.sector_id} value={s.sector_name}>
                  {s.sector_name}
                </option>
              ))}
            </select>

            <button
              onClick={fetchJobs}
              className="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700"
              title="Refresh"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
          </div>
        </div>

        {/* Jobs Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-4 py-3.5 font-semibold">Code</th>
                  <th className="px-4 py-3.5 font-semibold">Position Title</th>
                  <th className="px-4 py-3.5 font-semibold">Department</th>
                  <th className="px-4 py-3.5 font-semibold">Location</th>
                  <th className="px-4 py-3.5 font-semibold">Salary</th>
                  <th className="px-4 py-3.5 font-semibold">Vacancy</th>
                  <th className="px-4 py-3.5 font-semibold">Deadline</th>
                  <th className="px-4 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {jobs.length > 0 ? (
                  jobs.map((j) => (
                    <tr key={j.job_id} className="hover:bg-slate-850/50 transition-colors">
                      <td className="px-4 py-4 text-xs font-mono text-cyan-400">{j.job_code}</td>
                      <td className="px-4 py-4 font-semibold text-white text-xs">{j.job_title}</td>
                      <td className="px-4 py-4 text-xs text-slate-400">{j.job_dept}</td>
                      <td className="px-4 py-4 text-xs text-slate-300 flex items-center space-x-1 mt-3">
                        <MapPin className="w-3.5 h-3.5 text-slate-500" />
                        <span>{j.job_location}</span>
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-300">{j.salary_range}</td>
                      <td className="px-4 py-4">
                        <span className="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                          {j.vacancy || 1} post
                        </span>
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-400">
                        {j.deadline ? new Date(j.deadline).toLocaleDateString() : 'N/A'}
                      </td>
                      <td className="px-4 py-4 text-right space-x-1.5">
                        <button
                          onClick={() => openEditModal(j)}
                          className="p-1.5 text-slate-400 hover:text-cyan-400 hover:bg-slate-800 rounded-lg transition-colors"
                          title="Edit Job Circular"
                        >
                          <Edit3 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(j.job_id, j.job_title)}
                          className="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                          title="Delete Job Circular"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={8} className="px-4 py-10 text-center text-slate-500 text-xs">
                      No job circulars found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          <div className="bg-slate-950 px-6 py-3.5 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
            <div>
              Showing page <strong className="text-white">{pagination.page}</strong> of <strong className="text-white">{pagination.totalPages}</strong> (Total {pagination.total} records)
            </div>
            <div className="flex items-center space-x-2">
              <button
                onClick={() => setCurrentPage((p) => Math.max(p - 1, 1))}
                disabled={currentPage <= 1}
                className="p-1.5 rounded bg-slate-900 border border-slate-800 text-slate-300 hover:text-white disabled:opacity-40"
              >
                <ChevronLeft className="w-4 h-4" />
              </button>
              <button
                onClick={() => setCurrentPage((p) => Math.min(p + 1, pagination.totalPages))}
                disabled={currentPage >= pagination.totalPages}
                className="p-1.5 rounded bg-slate-900 border border-slate-800 text-slate-300 hover:text-white disabled:opacity-40"
              >
                <ChevronRight className="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </main>

      {/* Modal for Create/Edit Job Circular */}
      {isJobModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto">
          <div className="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-2xl p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto my-8">
            <button
              onClick={() => setIsJobModalOpen(false)}
              className="absolute top-4 right-4 text-slate-400 hover:text-white"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-white mb-1">
              {editingJob ? 'Edit Job Circular' : 'Create New Job Circular'}
            </h2>
            <p className="text-xs text-slate-400 mb-6">
              {editingJob ? `Updating details for ${editingJob.job_code}` : 'Post a new job opening for applicants'}
            </p>

            <form onSubmit={handleSubmit(onSubmitJob)} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Sector</label>
                  <select
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('job_dept', { required: true })}
                  >
                    {sectors.map((s) => (
                      <option key={s.sector_id} value={s.sector_name}>
                        {s.sector_name}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Code</label>
                  <input
                    type="text"
                    placeholder="e.g. SE1"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('job_code', { required: 'Job code is required' })}
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Position Title</label>
                <input
                  type="text"
                  placeholder="Senior Software Engineer..."
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('job_title', { required: 'Job title is required' })}
                />
              </div>

              <div className="grid grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Location</label>
                  <input
                    type="text"
                    placeholder="Dhaka / Remote"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('job_location', { required: true })}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Salary Range</label>
                  <input
                    type="text"
                    placeholder="BDT 50,000 - 80,000"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('salary_range', { required: true })}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Deadline</label>
                  <input
                    type="date"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('deadline', { required: true })}
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Experience Needed</label>
                  <input
                    type="text"
                    placeholder="3-5 years"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('job_experience')}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Vacancy Posts</label>
                  <input
                    type="number"
                    min={1}
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('vacancy')}
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Description</label>
                <textarea
                  rows={3}
                  placeholder="Main responsibilities and overview..."
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('job_description', { required: true })}
                ></textarea>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Requirements</label>
                <textarea
                  rows={2}
                  placeholder="Required educational qualification & skills..."
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('job_req')}
                ></textarea>
              </div>

              <div className="flex justify-end space-x-3 pt-4 border-t border-slate-800">
                <button
                  type="button"
                  onClick={() => setIsJobModalOpen(false)}
                  className="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-cyan-400 hover:bg-cyan-300 text-slate-950 rounded-lg text-xs font-semibold"
                >
                  {editingJob ? 'Update Circular' : 'Publish Circular'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal for Add Job Position Code */}
      {isPosModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
          <div className="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl relative">
            <button
              onClick={() => setIsPosModalOpen(false)}
              className="absolute top-4 right-4 text-slate-400 hover:text-white"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-white mb-1">Add Job Position Code</h2>
            <p className="text-xs text-slate-400 mb-6">Create reusable position titles and code references</p>

            <form onSubmit={handleSubmitPos(onSubmitPos)} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Title</label>
                <input
                  type="text"
                  placeholder="e.g. Finance Officer"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...registerPos('JobTitle', { required: 'Job Title is required' })}
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Job Code</label>
                <input
                  type="text"
                  placeholder="e.g. FO-101"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...registerPos('JobCode', { required: 'Job Code is required' })}
                />
              </div>

              <div className="flex justify-end space-x-3 pt-4 border-t border-slate-800">
                <button
                  type="button"
                  onClick={() => setIsPosModalOpen(false)}
                  className="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-cyan-400 hover:bg-cyan-300 text-slate-950 rounded-lg text-xs font-semibold"
                >
                  Save Position
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default JobManager;
