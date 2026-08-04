import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { officeApi } from '../api/officeApi';
import Navbar from '../components/Navbar';
import { Search, Plus, MapPin, Phone, Mail, User, Edit3, Trash2, X, ChevronLeft, ChevronRight, RefreshCw, Building2 } from 'lucide-react';
import toast from 'react-hot-toast';

const RegionalOffices = () => {
  const [offices, setOffices] = useState([]);
  const [pagination, setPagination] = useState({ page: 1, limit: 10, totalPages: 1, total: 0 });
  const [loading, setLoading] = useState(true);

  // Filters
  const [search, setSearch] = useState('');
  const [currentPage, setCurrentPage] = useState(1);

  // Modal
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingOffice, setEditingOffice] = useState(null);

  const {
    register,
    handleSubmit,
    reset,
    setValue,
    formState: { errors }
  } = useForm({
    defaultValues: { status: true }
  });

  const fetchOffices = async () => {
    setLoading(true);
    try {
      const res = await officeApi.getRegionalOffices({
        page: currentPage,
        limit: 10,
        search
      });
      if (res.data.success) {
        setOffices(res.data.data);
        setPagination(res.data.pagination);
      }
    } catch (err) {
      toast.error('Failed to load regional offices');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOffices();
  }, [currentPage, search]);

  const openCreateModal = () => {
    setEditingOffice(null);
    reset({
      region_name: '',
      office_name: '',
      address: '',
      phone: '',
      email: '',
      incharge_name: '',
      incharge_designation: '',
      status: true
    });
    setIsModalOpen(true);
  };

  const openEditModal = (officeItem) => {
    setEditingOffice(officeItem);
    setValue('region_name', officeItem.region_name);
    setValue('office_name', officeItem.office_name || '');
    setValue('address', officeItem.address);
    setValue('phone', officeItem.phone);
    setValue('email', officeItem.email || '');
    setValue('incharge_name', officeItem.incharge_name || '');
    setValue('incharge_designation', officeItem.incharge_designation || '');
    setValue('status', Boolean(officeItem.status));
    setIsModalOpen(true);
  };

  const onSubmit = async (formData) => {
    try {
      if (editingOffice) {
        const res = await officeApi.updateRegionalOffice(editingOffice.id, formData);
        if (res.data.success) {
          toast.success('Regional office updated successfully');
          setIsModalOpen(false);
          fetchOffices();
        }
      } else {
        const res = await officeApi.createRegionalOffice(formData);
        if (res.data.success) {
          toast.success('Regional office created successfully');
          setIsModalOpen(false);
          fetchOffices();
        }
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Action failed');
    }
  };

  const handleToggleStatus = async (id) => {
    try {
      const res = await officeApi.toggleRegionalOfficeStatus(id);
      if (res.data.success) {
        toast.success('Regional office status updated');
        fetchOffices();
      }
    } catch (err) {
      toast.error('Failed to toggle status');
    }
  };

  const handleDelete = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete regional office "${name}"?`)) return;

    try {
      const res = await officeApi.deleteRegionalOffice(id);
      if (res.data.success) {
        toast.success('Regional office deleted successfully');
        fetchOffices();
      }
    } catch (err) {
      toast.error('Failed to delete regional office');
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Regional Offices Directory</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Manage regional office locations, addresses, and incharge contacts</p>
          </div>
          <button
            onClick={openCreateModal}
            className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-xs transition-all shadow-lg shadow-cyan-500/20"
          >
            <Plus className="w-4 h-4" />
            <span>Add Regional Office</span>
          </button>
        </div>

        {/* Search Bar */}
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
          <div className="relative w-full md:w-80">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search region, address, or phone..."
              value={search}
              onChange={(e) => {
                setSearch(e.target.value);
                setCurrentPage(1);
              }}
              className="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500"
            />
          </div>

          <button
            onClick={fetchOffices}
            className="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700"
            title="Refresh"
          >
            <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
          </button>
        </div>

        {/* Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-4 py-3.5 font-semibold">ID</th>
                  <th className="px-4 py-3.5 font-semibold">Region Name</th>
                  <th className="px-4 py-3.5 font-semibold">Address</th>
                  <th className="px-4 py-3.5 font-semibold">Contact Info</th>
                  <th className="px-4 py-3.5 font-semibold">Incharge Person</th>
                  <th className="px-4 py-3.5 font-semibold">Status</th>
                  <th className="px-4 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {offices.length > 0 ? (
                  offices.map((o) => (
                    <tr key={o.id} className="hover:bg-slate-850/50 transition-colors">
                      <td className="px-4 py-4 text-xs font-mono text-slate-400">#{o.id}</td>
                      <td className="px-4 py-4 font-semibold text-white text-xs">
                        <div className="flex items-center space-x-1.5">
                          <Building2 className="w-4 h-4 text-cyan-400" />
                          <span>{o.region_name}</span>
                        </div>
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-300">{o.address}</td>
                      <td className="px-4 py-4 text-xs text-slate-300 space-y-0.5">
                        <div className="flex items-center space-x-1">
                          <Phone className="w-3 h-3 text-slate-500" />
                          <span>{o.phone}</span>
                        </div>
                        {o.email && (
                          <div className="flex items-center space-x-1 text-slate-400">
                            <Mail className="w-3 h-3 text-slate-500" />
                            <span>{o.email}</span>
                          </div>
                        )}
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-300">
                        {o.incharge_name ? (
                          <div>
                            <p className="font-semibold text-white">{o.incharge_name}</p>
                            <p className="text-[11px] text-slate-400">{o.incharge_designation || 'Incharge'}</p>
                          </div>
                        ) : (
                          <span className="text-slate-500">N/A</span>
                        )}
                      </td>
                      <td className="px-4 py-4">
                        <button
                          onClick={() => handleToggleStatus(o.id)}
                          className={`px-2.5 py-1 rounded-full text-[10px] font-semibold border transition-all ${
                            o.status
                              ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'
                              : 'bg-slate-800 text-slate-400 border-slate-700'
                          }`}
                        >
                          {o.status ? 'Active' : 'Inactive'}
                        </button>
                      </td>
                      <td className="px-4 py-4 text-right space-x-1.5">
                        <button
                          onClick={() => openEditModal(o)}
                          className="p-1.5 text-slate-400 hover:text-cyan-400 hover:bg-slate-800 rounded-lg transition-colors"
                          title="Edit Regional Office"
                        >
                          <Edit3 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(o.id, o.region_name)}
                          className="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                          title="Delete Regional Office"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={7} className="px-4 py-10 text-center text-slate-500 text-xs">
                      No regional offices found.
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

      {/* Modal for Create/Edit Regional Office */}
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
              {editingOffice ? 'Edit Regional Office' : 'Add Regional Office'}
            </h2>
            <p className="text-xs text-slate-400 mb-6">
              {editingOffice ? `Updating details for #${editingOffice.id}` : 'Create a new regional office entry'}
            </p>

            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Region Name *</label>
                <input
                  type="text"
                  placeholder="e.g. Khulna Region"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('region_name', { required: 'Region name is required' })}
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Address *</label>
                <textarea
                  rows={2}
                  placeholder="Full office location address..."
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('address', { required: 'Address is required' })}
                ></textarea>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Phone *</label>
                  <input
                    type="text"
                    placeholder="01700000000"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('phone', { required: 'Phone is required' })}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Email</label>
                  <input
                    type="email"
                    placeholder="office@atmabiswas.org"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('email')}
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Incharge Person</label>
                  <input
                    type="text"
                    placeholder="Name"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('incharge_name')}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Designation</label>
                  <input
                    type="text"
                    placeholder="Regional Manager"
                    className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('incharge_designation')}
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
                  {editingOffice ? 'Update Office' : 'Create Office'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default RegionalOffices;
