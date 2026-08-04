import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { useAuth } from '../context/AuthContext';
import { userApi } from '../api/userApi';
import Navbar from '../components/Navbar';
import { Search, UserPlus, Shield, Edit2, Trash2, X, ChevronLeft, ChevronRight, RefreshCw, CheckCircle, AlertCircle } from 'lucide-react';
import toast from 'react-hot-toast';

const UserManagement = () => {
  const { user: currentUser } = useAuth();
  const [users, setUsers] = useState([]);
  const [pagination, setPagination] = useState({ page: 1, limit: 10, totalPages: 1, total: 0 });
  const [loading, setLoading] = useState(true);

  // Search & Filters
  const [search, setSearch] = useState('');
  const [roleFilter, setRoleFilter] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const [currentPage, setCurrentPage] = useState(1);

  // Modal State
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingUser, setEditingUser] = useState(null);

  const {
    register,
    handleSubmit,
    reset,
    setValue,
    formState: { errors }
  } = useForm();

  const fetchUsers = async () => {
    setLoading(true);
    try {
      const res = await userApi.getUsers({
        page: currentPage,
        limit: 10,
        search,
        role: roleFilter,
        status: statusFilter
      });
      if (res.data.success) {
        setUsers(res.data.data);
        setPagination(res.data.pagination);
      }
    } catch (err) {
      toast.error('Failed to load admin users');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers();
  }, [currentPage, search, roleFilter, statusFilter]);

  const openCreateModal = () => {
    setEditingUser(null);
    reset({
      fullname: '',
      email: '',
      password: '',
      confirmPassword: '',
      role: 'admin',
      status: 'active'
    });
    setIsModalOpen(true);
  };

  const openEditModal = (userItem) => {
    setEditingUser(userItem);
    setValue('fullname', userItem.fullname);
    setValue('email', userItem.email);
    setValue('role', userItem.role || 'admin');
    setValue('status', userItem.status || 'active');
    setValue('password', '');
    setValue('confirmPassword', '');
    setIsModalOpen(true);
  };

  const onSubmit = async (formData) => {
    try {
      if (editingUser) {
        const res = await userApi.updateUser(editingUser.adminId, formData);
        if (res.data.success) {
          toast.success('Admin user updated successfully');
          setIsModalOpen(false);
          fetchUsers();
        }
      } else {
        const res = await userApi.createUser(formData);
        if (res.data.success) {
          toast.success('Admin user created successfully');
          setIsModalOpen(false);
          fetchUsers();
        }
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Action failed');
    }
  };

  const handleDelete = async (userItem) => {
    if (parseInt(userItem.adminId, 10) === parseInt(currentUser?.adminId, 10)) {
      toast.error('You cannot delete your own account');
      return;
    }

    if (!window.confirm(`Are you sure you want to delete admin account "${userItem.fullname}"?`)) {
      return;
    }

    try {
      const res = await userApi.deleteUser(userItem.adminId);
      if (res.data.success) {
        toast.success('Admin user deleted successfully');
        fetchUsers();
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to delete user');
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">User Management</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Manage admin accounts, search, filter, and roles</p>
          </div>
          <button
            onClick={openCreateModal}
            className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-xs transition-all shadow-lg shadow-cyan-500/20"
          >
            <UserPlus className="w-4 h-4" />
            <span>Create New Admin</span>
          </button>
        </div>

        {/* Search & Filter Bar */}
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
          <div className="relative w-full md:w-80">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search by name or email..."
              value={search}
              onChange={(e) => {
                setSearch(e.target.value);
                setCurrentPage(1);
              }}
              className="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500"
            />
          </div>

          <div className="flex items-center space-x-3 w-full md:w-auto justify-end">
            {/* Role Filter */}
            <select
              value={roleFilter}
              onChange={(e) => {
                setRoleFilter(e.target.value);
                setCurrentPage(1);
              }}
              className="bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 px-3 py-2 focus:outline-none focus:border-cyan-500"
            >
              <option value="">All Roles</option>
              <option value="admin">Admin</option>
              <option value="superadmin">Super Admin</option>
              <option value="editor">Editor</option>
            </select>

            {/* Status Filter */}
            <select
              value={statusFilter}
              onChange={(e) => {
                setStatusFilter(e.target.value);
                setCurrentPage(1);
              }}
              className="bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 px-3 py-2 focus:outline-none focus:border-cyan-500"
            >
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>

            <button
              onClick={fetchUsers}
              className="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700"
              title="Refresh"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
          </div>
        </div>

        {/* Users Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-6 py-3.5 font-semibold">Admin ID</th>
                  <th className="px-6 py-3.5 font-semibold">Full Name</th>
                  <th className="px-6 py-3.5 font-semibold">Email</th>
                  <th className="px-6 py-3.5 font-semibold">Role</th>
                  <th className="px-6 py-3.5 font-semibold">Status</th>
                  <th className="px-6 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {users.length > 0 ? (
                  users.map((u) => {
                    const isSelf = parseInt(u.adminId, 10) === parseInt(currentUser?.adminId, 10);
                    return (
                      <tr key={u.adminId} className="hover:bg-slate-850/50 transition-colors">
                        <td className="px-6 py-4 text-xs font-mono text-slate-400">#{u.adminId}</td>
                        <td className="px-6 py-4 font-semibold text-white text-xs">
                          {u.fullname}
                          {isSelf && <span className="ml-2 text-[10px] px-1.5 py-0.5 rounded bg-cyan-950 text-cyan-400 border border-cyan-800 font-normal">You</span>}
                        </td>
                        <td className="px-6 py-4 text-xs text-slate-300">{u.email || u.username}</td>
                        <td className="px-6 py-4">
                          <span className="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20 uppercase">
                            {u.role || 'admin'}
                          </span>
                        </td>
                        <td className="px-6 py-4">
                          <span className={`px-2.5 py-1 rounded-md text-[11px] font-semibold ${
                            u.status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'
                          }`}>
                            {u.status || 'active'}
                          </span>
                        </td>
                        <td className="px-6 py-4 text-right space-x-2">
                          <button
                            onClick={() => openEditModal(u)}
                            className="p-1.5 text-slate-400 hover:text-cyan-400 hover:bg-slate-800 rounded-lg transition-colors"
                            title="Edit Admin"
                          >
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => handleDelete(u)}
                            disabled={isSelf}
                            className={`p-1.5 rounded-lg transition-colors ${
                              isSelf
                                ? 'text-slate-600 cursor-not-allowed'
                                : 'text-slate-400 hover:text-red-400 hover:bg-red-500/10'
                            }`}
                            title={isSelf ? 'You cannot delete your own account' : 'Delete Admin'}
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </td>
                      </tr>
                    );
                  })
                ) : (
                  <tr>
                    <td colSpan={6} className="px-6 py-10 text-center text-slate-500 text-xs">
                      No admin users found matching filter parameters.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {/* Pagination Footer */}
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

      {/* Modal for Create/Edit Admin */}
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
              {editingUser ? 'Edit Admin Account' : 'Create New Admin Account'}
            </h2>
            <p className="text-xs text-slate-400 mb-6">
              {editingUser ? `Updating details for #${editingUser.adminId}` : 'Add a new administrator to system'}
            </p>

            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Full Name</label>
                <input
                  type="text"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('fullname', { required: 'Full name is required' })}
                />
                {errors.fullname && <p className="text-xs text-red-400 mt-1">{errors.fullname.message}</p>}
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Email Address</label>
                <input
                  type="email"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('email', { required: 'Email address is required' })}
                />
                {errors.email && <p className="text-xs text-red-400 mt-1">{errors.email.message}</p>}
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Role</label>
                  <select
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('role')}
                  >
                    <option value="admin">Admin</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="editor">Editor</option>
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Status</label>
                  <select
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('status')}
                  >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">
                  {editingUser ? 'New Password (Optional)' : 'Password'}
                </label>
                <input
                  type="password"
                  placeholder={editingUser ? 'Leave blank to keep existing password' : 'Enter password'}
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('password', {
                    required: editingUser ? false : 'Password is required',
                    minLength: { value: 6, message: 'Minimum 6 characters' }
                  })}
                />
                {errors.password && <p className="text-xs text-red-400 mt-1">{errors.password.message}</p>}
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
                  {editingUser ? 'Update Admin' : 'Create Admin'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default UserManagement;
