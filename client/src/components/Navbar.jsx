import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { LogOut, User, Key, LayoutDashboard } from 'lucide-react';
import toast from 'react-hot-toast';

const Navbar = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    toast.success('Logged out successfully');
    navigate('/login');
  };

  return (
    <nav className="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <div className="flex items-center space-x-3">
            <Link to="/dashboard" className="flex items-center space-x-3">
              <img src="/LOGO/NGO_logo_monogram.png" alt="ATMABISWAS" className="h-10 w-10 object-contain" />
              <span className="font-bold text-lg text-white tracking-wide">ATMABISWAS <span className="text-cyan-400 text-xs uppercase px-2 py-0.5 rounded bg-cyan-950/80 border border-cyan-800 ml-2">Admin</span></span>
            </Link>
          </div>

          {user && (
            <div className="flex items-center space-x-4">
              <Link to="/dashboard" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <LayoutDashboard className="w-4 h-4" />
                <span>Dashboard</span>
              </Link>
              <Link to="/regional-offices" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <Building2 className="w-4 h-4" />
                <span>Regional Offices</span>
              </Link>
              <Link to="/branches" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <GitBranch className="w-4 h-4" />
                <span>Branches</span>
              </Link>
              <Link to="/divisions" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <Layers className="w-4 h-4" />
                <span>Divisions</span>
              </Link>
              <Link to="/jobs" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <Briefcase className="w-4 h-4" />
                <span>Jobs</span>
              </Link>
              <Link to="/applications" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <FileCheck className="w-4 h-4" />
                <span>CV Applications</span>
              </Link>
              <Link to="/blogs" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <FileText className="w-4 h-4" />
                <span>Press & News</span>
              </Link>
              <Link to="/manage-admins" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <User className="w-4 h-4" />
                <span>Admins</span>
              </Link>
              <Link to="/profile" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <User className="w-4 h-4" />
                <span>Profile</span>
              </Link>
              <Link to="/change-password" className="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-slate-800 transition-colors text-sm font-medium">
                <Key className="w-4 h-4" />
                <span>Security</span>
              </Link>
              <button
                onClick={handleLogout}
                className="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white border border-red-500/30 transition-all text-sm font-medium"
              >
                <LogOut className="w-4 h-4" />
                <span>Logout</span>
              </button>
            </div>
          )}
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
