import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { officeApi } from '../api/officeApi';
import Navbar from '../components/Navbar';
import { Search, Plus, MapPin, Phone, Mail, User, Edit3, Trash2, X, ChevronLeft, ChevronRight, RefreshCw, GitFork } from 'lucide-react';
import toast from 'react-hot-toast';

const BranchManager = () => {
  const [branches, setBranches] = useState([]);
  const [divisions, setDivisions] = useState([]);
  const [pagination, setPagination] = useState({ page: 1, limit: 10, totalPages: 1, total: 0 });
  const [loading, setLoading] = useState(true);

  // Filters
  const [search, setSearch] = useState('');
  const [divisionFilter, setDivisionFilter] = useState('');
  const [currentPage, setCurrentPage] = useState(1);

  // Modal
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingBranch, setEditingBranch] = useState(null);

  const {
    register,
    handleSubmit,
    reset,
    setValue,
    formState: { errors }
  } = useForm({
    defaultValues: { status: true }
  });

  const fetchDivisions = async () => {
    try {
      const res = await officeApi.getDivisions();
      if (res.data.success) {
        setDivisions(res.data.divisions || []);
      }
    } catch (err) {
      console.error(err);
    }
  };

  const fetchBranches = async () => {
    setLoading(true);
    try {
      const res = await officeApi.getBranches({
        page: currentPage,
        limit: 10,
        search,
        division: divisionFilter
      });
      if (res.data.success) {
        setBranches(res.data.data);
        setPagination(res.data.pagination);
      }
    } catch (err) {
      toast.error('Failed to load branches directory');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDivisions();
  }, []);

  useEffect(() => {
    fetchBranches();
  }, [currentPage, search, divisionFilter]);

  const openCreateModal = () => {
    setEditingBranch(null);
    reset({
      branch_code: '',
      branch_name: '',
      division: divisions[0]?.name || 'Khulna',
      district: '',
      address: '',
      phone: '',
      email: '',
      manager_name: '',
      status: true
    });
    setIsModalOpen(true);
  };

  const openEditModal = (branchItem) => {
    setEditingBranch(branchItem);
    setValue('branch_code', branchItem.branch_code || '');
    setValue('branch_name', branchItem.branch_name);
    setValue('division', branchItem.division);
    setValue('district', branchItem.district || '');
    setValue('address', branchItem.address);
    setValue('phone', branchItem.phone);
    setValue('email', branchItem.email || '');
    setValue('manager_name', branchItem.manager_name || '');
    setValue('status', Boolean(branchItem.status));
    setIsModalOpen(true);
  };

  const onSubmit = async (formData) => {
    try {
      if (editingBranch) {
        const res = await officeApi.updateBranch(editingBranch.id, formData);
        if (res.data.success) {
          toast.success('Branch updated successfully');
          setIsModalOpen(false);
          fetchBranches();
        }
      } else {
        const res = await officeApi.createBranch(formData);
        if (res.data.success) {
          toast.success('Branch created successfully');
          setIsModalOpen(false);
          fetchBranches();
        }
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Action failed');
    }
  };

  const handleToggleStatus = async (id) => {
    try {
      const res = await officeApi.toggleBranchStatus(id);
      if (res.data.success) {
        toast.success('Branch status updated');
        fetchBranches();
      }
    } catch (err) {
      toast.error('Failed to toggle status');
    }
  };

  const handleDelete = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete branch "${name}"?`)) return;

    try {
      const res = await officeApi.deleteBranch(id);
      if (res.data.success) {
        toast.success('Branch deleted successfully');
        fetchBranches();
      }
    } catch (err) {
      toast.error('Failed to delete branch');
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Branch Directory</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Manage NGO branch offices across all divisions and districts</p>
          </div>
          <button
            onClick={openCreateModal}
            className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-xs transition-all shadow-lg shadow-cyan-500/20"
          >
            <Plus className="w-4 h-4" />
            <span>Add New Branch</span>
          </button>
        </div>

        {/* Search & Filter Bar */}
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
          <div className="relative w-full md:w-80">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search branch name, code, district..."
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
              value={divisionFilter}
              onChange={(e) => {
                setDivisionFilter(e.target.value);
                setCurrentPage(1);
              }}
              className="bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 px-3 py-2 focus:outline-none focus:border-cyan-500"
            >
              <option value="">All Divisions</option>
              {divisions.map((d) => (
                <option key={d.id} value={d.name}>
                  {d.name} Division
                </option>
              ))}
            </select>

            <button
              onClick={fetchBranches}
              className="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700"
              title="Refresh"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
          </div>
        </div>

        {/* Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-4 py-3.5 font-semibold">Code</th>
                  <th className="px-4 py-3.5 font-semibold">Branch Name</th>
                  <th className="px-4 py-3.5 font-semibold">Division</th>
                  <th className="px-4 py-3.5 font-semibold">District</th>
                  <th className="px-4 py-3.5 font-semibold">Contact Info</th>
                  <th className="px-4 py-3.5 font-semibold">Branch Manager</th>
                  <th className="px-4 py-3.5 font-semibold">Status</th>
                  <th className="px-4 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {branches.length > 0 ? (
                  branches.map((b) => (
                    <tr key={b.id} className="hover:bg-slate-850/50 transition-colors">
                      <td className="px-4 py-4 text-xs font-mono text-cyan-400">{b.branch_code || `B-${b.id}`}</td>
                      <td className="px-4 py-4 font-semibold text-white text-xs">{b.branch_name}</td>
                      <td className="px-4 py-4 text-xs text-slate-400">
                        <span className="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-slate-950 text-purple-400 border border-purple-900/60">
                          {b.division}
                        </span>
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-300">{b.district || 'N/A'}</td>
                      <td className="px-4 py-4 text-xs text-slate-300 space-y-0.5">
                        <div className="flex items-center space-x-1">
                          <Phone className="w-3 h-3 text-slate-500" />
                          <span>{b.phone}</span>
                        </div>
                        {b.email && (
                          <div className="flex items-center space-x-1 text-slate-400">
                            <Mail className="w-3 h-3 text-slate-500" />
                            <span>{b.email}</span>
                          </div>
                        )}
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-300">{b.manager_name || 'N/A'}</td>
                      <td className="px-4 py-4">
                        <button
                          onClick={() => handleToggleStatus(b.id)}
                          className={`px-2.5 py-1 rounded-full text-[10px] font-semibold border transition-all ${
                            b.status
                              ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'
                              : 'bg-slate-800 text-slate-400 border-slate-700'
                          }`}
                        >
                          {b.status ? 'Active' : 'Inactive'}
                        </button>
                      </td>
                      <td className="px-4 py-4 text-right space-x-1.5">
                        <button
                          onClick={() => openEditModal(b)}
                          className="p-1.5 text-slate-400 hover:text-cyan-400 hover:bg-slate-800 rounded-lg transition-colors"
                          title="Edit Branch"
                        >
                          <Edit3 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(b.id, b.branch_name)}
                          className="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                          title="Delete Branch"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={8} className="px-4 py-10 text-center text-slate-500 text-xs">
                      No branches found matching filter parameters.
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

      {/* Modal for Create/Edit Branch */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
          <div className="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl relative">
            <button
              onClick={() => setIsModalOpen(false)}
              className="absolute top-4 right-4 text-slate-400 hover:text-white"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-white mb-1">
              {editingBranch ? 'Edit Branch' : 'Add New Branch'}
            </h2>
            <p className="text-xs text-slate-400 mb-6">
              {editingBranch ? `Updating branch #${editingBranch.id}` : 'Create a new branch office entry'}
            </p>

            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Branch Code</label>
                  <input
                    type="text"
                    placeholder="e.g. BR-101"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('branch_code')}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Division *</label>
                  <select
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('division', { required: true })}
                  >
                    {divisions.map((d) => (
                      <option key={d.id} value={d.name}>
                        {d.name}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Branch Name *</label>
                <input
                  type="text"
                  placeholder="e.g. Jessore Sadar Branch"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('branch_name', { required: 'Branch name is required' })}
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">District</label>
                  <input
                    type="text"
                    placeholder="Jessore"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('district')}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Phone *</label>
                  <input
                    type="text"
                    placeholder="01700000000"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('phone', { required: 'Phone is required' })}
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Address *</label>
                <textarea
                  rows={2}
                  placeholder="Full branch address..."
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('address', { required: 'Address is required' })}
                ></textarea>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Manager Name</label>
                  <input
                    type="text"
                    placeholder="Branch Manager"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('manager_name')}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Email</label>
                  <input
                    type="email"
                    placeholder="branch@atmabiswas.org"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('email')}
                  />
                </div>
              </div>

              <div className="flex justify-end space-x-3 pt-4 border-t border-slate-800">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-cyan-400 hover:bg-cyan-300 text-slate-950 rounded-lg text-xs font-semibold"
                >
                  {editingBranch ? 'Update Branch' : 'Create Branch'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default BranchManager;
