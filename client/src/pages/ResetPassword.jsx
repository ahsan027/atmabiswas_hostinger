import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { useSearchParams, useNavigate, Link } from 'react-router-dom';
import { authApi } from '../api/authApi';
import { Lock, Eye, EyeOff, CheckCircle2 } from 'lucide-react';
import toast from 'react-hot-toast';

const ResetPassword = () => {
  const [searchParams] = useSearchParams();
  const token = searchParams.get('token') || '';
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const {
    register,
    handleSubmit,
    watch,
    formState: { errors }
  } = useForm({
    defaultValues: {
      token
    }
  });

  const newPassword = watch('newPassword');

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const res = await authApi.resetPassword(data);
      if (res.data.success) {
        toast.success(res.data.message);
        navigate('/login');
      } else {
        toast.error(res.data.message || 'Failed to reset password');
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Error resetting password');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
      <div className="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div className="sm:mx-auto sm:w-full sm:max-w-md">
        <div className="bg-slate-900/95 backdrop-blur border border-slate-800/80 py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
          <div className="text-center mb-8">
            <div className="w-12 h-12 bg-cyan-500/10 border border-cyan-500/30 rounded-xl flex items-center justify-center mx-auto mb-3 text-cyan-400">
              <CheckCircle2 className="w-6 h-6" />
            </div>
            <h2 className="text-2xl font-bold text-white tracking-tight">Reset Password</h2>
            <p className="text-sm text-slate-400 mt-1">Create a new secure password for your admin account</p>
          </div>

          <form className="space-y-6" onSubmit={handleSubmit(onSubmit)}>
            {/* Token field */}
            <div>
              <label htmlFor="token" className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Reset Token
              </label>
              <input
                id="token"
                type="text"
                className="block w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-white font-mono text-xs focus:outline-none"
                {...register('token', { required: 'Reset token is required' })}
              />
              {errors.token && <p className="mt-1 text-xs text-red-400">{errors.token.message}</p>}
            </div>

            {/* New Password */}
            <div>
              <label htmlFor="newPassword" className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                New Password
              </label>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock className="h-4 w-4" />
                </div>
                <input
                  id="newPassword"
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Enter new password"
                  className={`block w-full pl-10 pr-10 py-2.5 bg-slate-950 border ${
                    errors.newPassword ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                  {...register('newPassword', {
                    required: 'New password is required',
                    minLength: {
                      value: 6,
                      message: 'Password must be at least 6 characters'
                    }
                  })}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-200"
                >
                  {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                </button>
              </div>
              {errors.newPassword && <p className="mt-1 text-xs text-red-400">{errors.newPassword.message}</p>}
            </div>

            {/* Confirm Password */}
            <div>
              <label htmlFor="confirmPassword" className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Confirm New Password
              </label>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock className="h-4 w-4" />
                </div>
                <input
                  id="confirmPassword"
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Confirm new password"
                  className={`block w-full pl-10 pr-3 py-2.5 bg-slate-950 border ${
                    errors.confirmPassword ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                  {...register('confirmPassword', {
                    required: 'Please confirm password',
                    validate: (value) => value === newPassword || 'Passwords do not match'
                  })}
                />
              </div>
              {errors.confirmPassword && <p className="mt-1 text-xs text-red-400">{errors.confirmPassword.message}</p>}
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-semibold text-slate-950 bg-cyan-400 hover:bg-cyan-300 focus:outline-none disabled:opacity-50 transition-all shadow-lg shadow-cyan-500/20"
            >
              {loading ? 'Resetting Password...' : 'Reset Password'}
            </button>
          </form>

          <div className="mt-6 text-center">
            <Link to="/login" className="text-xs font-medium text-slate-400 hover:text-white transition-colors">
              Back to Login
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ResetPassword;
