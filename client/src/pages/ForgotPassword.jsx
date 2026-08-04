import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { Link } from 'react-router-dom';
import { authApi } from '../api/authApi';
import { Mail, ArrowLeft, KeyRound } from 'lucide-react';
import toast from 'react-hot-toast';

const ForgotPassword = () => {
  const [loading, setLoading] = useState(false);
  const [resetTokenInfo, setResetTokenInfo] = useState(null);

  const {
    register,
    handleSubmit,
    formState: { errors }
  } = useForm();

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const res = await authApi.forgotPassword(data);
      if (res.data.success) {
        toast.success(res.data.message);
        if (res.data.resetToken) {
          setResetTokenInfo(res.data.resetToken);
        }
      } else {
        toast.error(res.data.message || 'Failed to process password reset');
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Error sending password reset email');
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
              <KeyRound className="w-6 h-6" />
            </div>
            <h2 className="text-2xl font-bold text-white tracking-tight">Forgot Password</h2>
            <p className="text-sm text-slate-400 mt-1">Enter your admin email to receive a password reset link</p>
          </div>

          {!resetTokenInfo ? (
            <form className="space-y-6" onSubmit={handleSubmit(onSubmit)}>
              <div>
                <label htmlFor="email" className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                  Admin Email Address
                </label>
                <div className="relative rounded-lg shadow-sm">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <Mail className="h-4 w-4" />
                  </div>
                  <input
                    id="email"
                    type="email"
                    placeholder="Enter registered email"
                    className={`block w-full pl-10 pr-3 py-2.5 bg-slate-950 border ${
                      errors.email ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                    } rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500`}
                    {...register('email', {
                      required: 'Email is required',
                      pattern: {
                        value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                        message: 'Invalid email format'
                      }
                    })}
                  />
                </div>
                {errors.email && <p className="mt-1 text-xs text-red-400">{errors.email.message}</p>}
              </div>

              <button
                type="submit"
                disabled={loading}
                className="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-semibold text-slate-950 bg-cyan-400 hover:bg-cyan-300 focus:outline-none disabled:opacity-50 transition-all"
              >
                {loading ? 'Processing...' : 'Generate Reset Link'}
              </button>
            </form>
          ) : (
            <div className="space-y-4 text-center">
              <div className="p-4 bg-slate-950 border border-slate-800 rounded-xl text-left">
                <p className="text-xs text-slate-400 mb-1">Generated Reset Token (Demo/Local):</p>
                <p className="text-xs font-mono text-cyan-400 break-all select-all">{resetTokenInfo}</p>
              </div>
              <Link
                to={`/reset-password?token=${resetTokenInfo}`}
                className="block w-full text-center py-2.5 px-4 rounded-lg bg-cyan-400 text-slate-950 font-semibold text-sm hover:bg-cyan-300"
              >
                Proceed to Reset Password
              </Link>
            </div>
          )}

          <div className="mt-6 text-center">
            <Link to="/login" className="inline-flex items-center text-xs font-medium text-slate-400 hover:text-white transition-colors">
              <ArrowLeft className="w-3.5 h-3.5 mr-1" /> Back to Login
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ForgotPassword;
