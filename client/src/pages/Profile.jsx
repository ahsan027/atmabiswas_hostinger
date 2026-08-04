import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { useAuth } from '../context/AuthContext';
import { authApi } from '../api/authApi';
import Navbar from '../components/Navbar';
import { User, Mail, Shield, Save } from 'lucide-react';
import toast from 'react-hot-toast';

const Profile = () => {
  const { user, updateProfileState } = useAuth();
  const [loading, setLoading] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors }
  } = useForm({
    defaultValues: {
      fullname: user?.fullname || '',
      email: user?.email || ''
    }
  });

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const res = await authApi.updateProfile(data);
      if (res.data.success) {
        toast.success('Profile updated successfully!');
        updateProfileState(res.data.user);
      } else {
        toast.error(res.data.message || 'Failed to update profile');
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Error updating profile');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">
          <div className="flex items-center space-x-4 border-b border-slate-800 pb-6 mb-6">
            <div className="w-16 h-16 bg-cyan-500/10 border border-cyan-500/30 rounded-2xl flex items-center justify-center text-cyan-400 font-bold text-2xl">
              {user?.fullname ? user.fullname.charAt(0).toUpperCase() : 'A'}
            </div>
            <div>
              <h1 className="text-2xl font-bold text-white">{user?.fullname || 'Admin User'}</h1>
              <p className="text-xs text-slate-400 font-mono mt-0.5">Role: <span className="text-cyan-400 uppercase font-semibold">{user?.role || 'admin'}</span></p>
            </div>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
              {/* Full Name */}
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                  Full Name
                </label>
                <div className="relative rounded-lg shadow-sm">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <User className="h-4 w-4" />
                  </div>
                  <input
                    type="text"
                    className={`block w-full pl-10 pr-3 py-2.5 bg-slate-950 border ${
                      errors.fullname ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                    } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                    {...register('fullname', { required: 'Full name is required' })}
                  />
                </div>
                {errors.fullname && <p className="mt-1 text-xs text-red-400">{errors.fullname.message}</p>}
              </div>

              {/* Email Address */}
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                  Email Address
                </label>
                <div className="relative rounded-lg shadow-sm">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <Mail className="h-4 w-4" />
                  </div>
                  <input
                    type="email"
                    className={`block w-full pl-10 pr-3 py-2.5 bg-slate-950 border ${
                      errors.email ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                    } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                    {...register('email', {
                      required: 'Email address is required',
                      pattern: {
                        value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                        message: 'Invalid email address format'
                      }
                    })}
                  />
                </div>
                {errors.email && <p className="mt-1 text-xs text-red-400">{errors.email.message}</p>}
              </div>
            </div>

            <div className="flex justify-end pt-4 border-t border-slate-800">
              <button
                type="submit"
                disabled={loading}
                className="inline-flex items-center space-x-2 px-5 py-2.5 bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-sm rounded-lg transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
              >
                <Save className="w-4 h-4" />
                <span>{loading ? 'Saving Changes...' : 'Save Profile'}</span>
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  );
};

export default Profile;
