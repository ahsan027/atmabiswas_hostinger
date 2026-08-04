import React, { useState, useEffect } from 'react';
import { jobApi } from '../api/jobApi';
import Navbar from '../components/Navbar';
import { Search, Download, Trash2, Mail, Phone, Calendar, Briefcase, RefreshCw, FileText } from 'lucide-react';
import toast from 'react-hot-toast';

const ApplicationsManager = () => {
  const [applications, setApplications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  const fetchApplications = async () => {
    setLoading(true);
    try {
      const res = await jobApi.getApplications({ search });
      if (res.data.success) {
        setApplications(res.data.applications || []);
      }
    } catch (err) {
      toast.error('Failed to load candidate applications');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchApplications();
  }, [search]);

  const handleDelete = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete application from "${name}"?`)) return;

    try {
      const res = await jobApi.deleteApplication(id);
      if (res.data.success) {
        toast.success('Application deleted successfully');
        fetchApplications();
      }
    } catch (err) {
      toast.error('Failed to delete application');
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Candidate CV Applications</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Review job applications, contact candidates, and download resumes</p>
          </div>
          <button
            onClick={fetchApplications}
            className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs border border-slate-700 transition-all"
          >
            <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            <span>Refresh Submissions</span>
          </button>
        </div>

        {/* Search Bar */}
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 shadow-lg">
          <div className="relative w-full sm:w-96">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search candidate name, email, phone, or job title..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500"
            />
          </div>
        </div>

        {/* Applications Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-4 py-3.5 font-semibold">ID</th>
                  <th className="px-4 py-3.5 font-semibold">Candidate Name</th>
                  <th className="px-4 py-3.5 font-semibold">Applied Position</th>
                  <th className="px-4 py-3.5 font-semibold">Contact Info</th>
                  <th className="px-4 py-3.5 font-semibold">Experience</th>
                  <th className="px-4 py-3.5 font-semibold">Resume / CV</th>
                  <th className="px-4 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {applications.length > 0 ? (
                  applications.map((app) => (
                    <tr key={app.applicationId} className="hover:bg-slate-850/50 transition-colors">
                      <td className="px-4 py-4 text-xs font-mono text-slate-400">#{app.applicationId}</td>
                      <td className="px-4 py-4 font-semibold text-white text-xs">{app.fullname}</td>
                      <td className="px-4 py-4 text-xs text-cyan-400 font-medium">
                        <div className="flex items-center space-x-1">
                          <Briefcase className="w-3.5 h-3.5 text-slate-500" />
                          <span>{app.job_title}</span>
                        </div>
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-300 space-y-0.5">
                        <div className="flex items-center space-x-1">
                          <Mail className="w-3 h-3 text-slate-500" />
                          <a href={`mailto:${app.email}`} className="hover:underline">{app.email}</a>
                        </div>
                        <div className="flex items-center space-x-1 text-slate-400">
                          <Phone className="w-3 h-3 text-slate-500" />
                          <span>{app.phone_no}</span>
                        </div>
                      </td>
                      <td className="px-4 py-4 text-xs text-slate-400">
                        {app.experience ? `${app.experience} yrs` : 'N/A'}
                      </td>
                      <td className="px-4 py-4">
                        {app.cv_file ? (
                          <a
                            href={app.cv_file}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center space-x-1.5 px-3 py-1 rounded bg-cyan-950 text-cyan-400 border border-cyan-800 text-xs font-medium hover:bg-cyan-900 transition-colors"
                          >
                            <Download className="w-3.5 h-3.5" />
                            <span>Download CV</span>
                          </a>
                        ) : (
                          <span className="text-xs text-slate-500">No CV</span>
                        )}
                      </td>
                      <td className="px-4 py-4 text-right">
                        <button
                          onClick={() => handleDelete(app.applicationId, app.fullname)}
                          className="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                          title="Delete Application"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={7} className="px-4 py-10 text-center text-slate-500 text-xs">
                      No candidate CV applications found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  );
};

export default ApplicationsManager;
