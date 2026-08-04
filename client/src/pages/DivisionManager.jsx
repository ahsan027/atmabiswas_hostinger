import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { officeApi } from '../api/officeApi';
import Navbar from '../components/Navbar';
import { Plus, Trash2, Edit3, X, RefreshCw, Layers, GitBranch, AlertTriangle } from 'lucide-react';
import toast from 'react-hot-toast';

const DivisionManager = () => {
  const [divisions, setDivisions] = useState([]);
  const [loading, setLoading] = useState(true);

  // Modal
  const [isModalOpen, setIsModalOpen] = useState(false);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors }
  } = useForm();

  const fetchDivisions = async () => {
    setLoading(true);
    try {
      const res = await officeApi.getDivisions();
      if (res.data.success) {
        setDivisions(res.data.divisions || []);
      }
    } catch (err) {
      toast.error('Failed to load divisions');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDivisions();
  }, []);

  const onSubmit = async (formData) => {
    try {
      const res = await officeApi.createDivision(formData);
      if (res.data.success) {
        toast.success('Division created successfully');
        setIsModalOpen(false);
        reset();
        fetchDivisions();
      }
    } catch (err) {
      toast.error('Failed to create division');
    }
  };

  const handleToggleStatus = async (id) => {
    try {
      const res = await officeApi.toggleDivisionStatus(id);
      if (res.data.success) {
        toast.success('Division status updated');
        fetchDivisions();
      }
    } catch (err) {
      toast.error('Failed to toggle division status');
    }
  };

  const handleDelete = async (div) => {
    if (div.branch_count > 0) {
      toast.error(`Cannot delete: ${div.branch_count} branch(es) belong to this division. Reassign branches first.`);
      return;
    }

    if (!window.confirm(`Are you sure you want to delete division "${div.name}"?`)) return;

    try {
      const res = await officeApi.deleteDivision(div.id);
      if (res.data.success) {
        toast.success('Division deleted successfully');
        fetchDivisions();
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to delete division');
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Division Management</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Manage administrative divisions and active branch allocations</p>
          </div>
          <div className="flex items-center space-x-3">
            <button
              onClick={() => setIsModalOpen(true)}
              className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-xs transition-all shadow-lg shadow-cyan-500/20"
            >
              <Plus className="w-4 h-4" />
              <span>Add New Division</span>
            </button>
            <button
              onClick={fetchDivisions}
              className="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700"
              title="Refresh"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
          </div>
        </div>

        {/* Divisions Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-6 py-3.5 font-semibold">ID</th>
                  <th className="px-6 py-3.5 font-semibold">Division Name</th>
                  <th className="px-6 py-3.5 font-semibold">Allocated Branches</th>
                  <th className="px-6 py-3.5 font-semibold">Status</th>
                  <th className="px-6 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {divisions.length > 0 ? (
                  divisions.map((d) => (
                    <tr key={d.id} className="hover:bg-slate-850/50 transition-colors">
                      <td className="px-6 py-4 text-xs font-mono text-slate-400">#{d.id}</td>
                      <td className="px-6 py-4 font-semibold text-white text-xs">
                        <div className="flex items-center space-x-2">
                          <Layers className="w-4 h-4 text-purple-400" />
                          <span>{d.name} Division</span>
                        </div>
                      </td>
                      <td className="px-6 py-4 text-xs text-slate-300">
                        <span className="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded text-[11px] font-semibold bg-cyan-950 text-cyan-400 border border-cyan-800">
                          <GitBranch className="w-3 h-3" />
                          <span>{d.branch_count || 0} active branches</span>
                        </span>
                      </td>
                      <td className="px-6 py-4">
                        <button
                          onClick={() => handleToggleStatus(d.id)}
                          className={`px-2.5 py-1 rounded-full text-[10px] font-semibold border transition-all ${
                            d.status
                              ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'
                              : 'bg-slate-800 text-slate-400 border-slate-700'
                          }`}
                        >
                          {d.status ? 'Active' : 'Inactive'}
                        </button>
                      </td>
                      <td className="px-6 py-4 text-right">
                        <button
                          onClick={() => handleDelete(d)}
                          disabled={d.branch_count > 0}
                          className={`p-1.5 rounded-lg transition-colors ${
                            d.branch_count > 0
                              ? 'text-slate-600 cursor-not-allowed'
                              : 'text-slate-400 hover:text-red-400 hover:bg-red-500/10'
                          }`}
                          title={
                            d.branch_count > 0
                              ? `Cannot delete: ${d.branch_count} branch(es) belong to this division`
                              : 'Delete Division'
                          }
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={5} className="px-6 py-10 text-center text-slate-500 text-xs">
                      No divisions found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </main>

      {/* Modal for Add Division */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
          <div className="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl relative">
            <button
              onClick={() => setIsModalOpen(false)}
              className="absolute top-4 right-4 text-slate-400 hover:text-white"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-white mb-1">Add New Division</h2>
            <p className="text-xs text-slate-400 mb-6">Create a new administrative division zone</p>

            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Division Name *</label>
                <input
                  type="text"
                  placeholder="e.g. Dhaka"
                  className="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('name', { required: 'Division name is required' })}
                />
                {errors.name && <p className="text-xs text-red-400 mt-1">{errors.name.message}</p>}
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
                  Create Division
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default DivisionManager;
