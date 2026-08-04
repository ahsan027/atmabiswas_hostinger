import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { authApi } from '../api/authApi';
import Navbar from '../components/Navbar';
import { Lock, Eye, EyeOff, ShieldCheck, Key } from 'lucide-react';
import toast from 'react-hot-toast';

const ChangePassword = () => {
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const {
    register,
    handleSubmit,
    watch,
    reset,
    formState: { errors }
  } = useForm();

  const newPassword = watch('newPassword');

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const res = await authApi.changePassword(data);
      if (res.data.success) {
        toast.success('Password changed successfully!');
        reset();
      } else {
        toast.error(res.data.message || 'Failed to change password');
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Error changing password');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-2xl mx-auto py-10 px-4 sm:px-6">
        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">
          <div className="flex items-center space-x-3 border-b border-slate-800 pb-6 mb-6">
            <div className="w-12 h-12 bg-cyan-500/10 border border-cyan-500/30 rounded-xl flex items-center justify-center text-cyan-400">
              <Key className="w-6 h-6" />
            </div>
            <div>
              <h1 className="text-xl font-bold text-white">Change Password</h1>
              <p className="text-xs text-slate-400">Update your admin login password securely</p>
            </div>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            {/* Current Password */}
            <div>
              <label className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Current Password
              </label>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock className="h-4 w-4" />
                </div>
                <input
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Enter current password"
                  className={`block w-full pl-10 pr-10 py-2.5 bg-slate-950 border ${
                    errors.currentPassword ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                  {...register('currentPassword', { required: 'Current password is required' })}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-200"
                >
                  {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                </button>
              </div>
              {errors.currentPassword && <p className="mt-1 text-xs text-red-400">{errors.currentPassword.message}</p>}
            </div>

            {/* New Password */}
            <div>
              <label className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                New Password
              </label>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock className="h-4 w-4" />
                </div>
                <input
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Enter new password (min. 6 characters)"
                  className={`block w-full pl-10 pr-10 py-2.5 bg-slate-950 border ${
                    errors.newPassword ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                  {...register('newPassword', {
                    required: 'New password is required',
                    minLength: {
                      value: 6,
                      message: 'New password must be at least 6 characters'
                    }
                  })}
                />
              </div>
              {errors.newPassword && <p className="mt-1 text-xs text-red-400">{errors.newPassword.message}</p>}
            </div>

            {/* Confirm New Password */}
            <div>
              <label className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Confirm New Password
              </label>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock className="h-4 w-4" />
                </div>
                <input
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Confirm new password"
                  className={`block w-full pl-10 pr-10 py-2.5 bg-slate-950 border ${
                    errors.confirmPassword ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                  {...register('confirmPassword', {
                    required: 'Please confirm new password',
                    validate: (value) => value === newPassword || 'New passwords do not match'
                  })}
                />
              </div>
              {errors.confirmPassword && <p className="mt-1 text-xs text-red-400">{errors.confirmPassword.message}</p>}
            </div>

            <div className="flex justify-end pt-4 border-t border-slate-800">
              <button
                type="submit"
                disabled={loading}
                className="inline-flex items-center space-x-2 px-5 py-2.5 bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-sm rounded-lg transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
              >
                <ShieldCheck className="w-4 h-4" />
                <span>{loading ? 'Updating Password...' : 'Update Password'}</span>
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  );
};

export default ChangePassword;
