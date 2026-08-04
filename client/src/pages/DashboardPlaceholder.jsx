import React from 'react';
import Navbar from '../components/Navbar';
import { useAuth } from '../context/AuthContext';
import { LayoutDashboard, Users, FileText, Briefcase, Building, CheckCircle } from 'lucide-react';
import { Link } from 'react-router-dom';

const DashboardPlaceholder = () => {
  const { user } = useAuth();

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div className="bg-gradient-to-r from-slate-900 via-slate-900 to-cyan-950/40 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl mb-8">
          <div className="flex items-center space-x-3 mb-2">
            <span className="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span>
            <span className="text-xs uppercase font-semibold text-cyan-400 tracking-wider">Phase 1 Authentication Active</span>
          </div>
          <h1 className="text-3xl font-bold text-white tracking-tight">Welcome to ATMABISWAS Admin Portal</h1>
          <p className="text-sm text-slate-400 mt-1">Logged in as <strong className="text-white">{user?.email}</strong> ({user?.fullname})</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <div className="flex items-center justify-between mb-3">
              <span className="text-xs font-semibold text-slate-400 uppercase">Authentication</span>
              <CheckCircle className="w-5 h-5 text-emerald-400" />
            </div>
            <p className="text-lg font-bold text-white">JWT Authentication</p>
            <p className="text-xs text-slate-400 mt-1">Secure token-based sessions & password encryption active.</p>
          </div>

          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <div className="flex items-center justify-between mb-3">
              <span className="text-xs font-semibold text-slate-400 uppercase">Profile Management</span>
              <CheckCircle className="w-5 h-5 text-emerald-400" />
            </div>
            <p className="text-lg font-bold text-white">Admin Profile & Security</p>
            <p className="text-xs text-slate-400 mt-1">Update profile info & change password modules verified.</p>
          </div>

          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <div className="flex items-center justify-between mb-3">
              <span className="text-xs font-semibold text-slate-400 uppercase">Next Phase</span>
              <LayoutDashboard className="w-5 h-5 text-cyan-400" />
            </div>
            <p className="text-lg font-bold text-white">Phase 2: Dashboard</p>
            <p className="text-xs text-slate-400 mt-1">Ready for Phase 2 widgets, reports & analytics migration.</p>
          </div>
        </div>

        <div className="flex space-x-4">
          <Link to="/profile" className="px-4 py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500 rounded-lg text-sm text-slate-200 hover:text-white transition-colors">
            Manage Profile
          </Link>
          <Link to="/change-password" className="px-4 py-2 bg-slate-900 border border-slate-800 hover:border-cyan-500 rounded-lg text-sm text-slate-200 hover:text-white transition-colors">
            Security & Password
          </Link>
        </div>
      </main>
    </div>
  );
};

export default DashboardPlaceholder;
