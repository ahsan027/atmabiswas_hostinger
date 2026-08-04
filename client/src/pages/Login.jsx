import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Mail, Lock, Eye, EyeOff, ShieldCheck } from 'lucide-react';
import toast from 'react-hot-toast';

const Login = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors }
  } = useForm();

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const res = await login(data.email, data.password);
      if (res.success) {
        toast.success('Login successful! Redirecting...');
        navigate('/dashboard');
      } else {
        toast.error(res.message || 'Invalid credentials');
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Login failed. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
      {/* Background glowing effects */}
      <div className="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-600/15 rounded-full blur-3xl pointer-events-none"></div>
      <div className="absolute bottom-10 right-10 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

      {/* Top Banner Notice */}
      <div className="max-w-md w-full mx-auto mb-6 px-4 sm:px-0">
        <div className="bg-slate-900/90 backdrop-blur border border-slate-800 rounded-xl p-3 flex items-center justify-between text-xs text-slate-300 shadow-lg">
          <div className="flex items-center space-x-2">
            <ShieldCheck className="w-4 h-4 text-cyan-400 shrink-0" />
            <span>Need help? Contact <strong className="text-white">ATMABISWAS IT</strong> via <a href="mailto:support@atmabiswas.org" className="text-cyan-400 hover:underline">support@atmabiswas.org</a></span>
          </div>
        </div>
      </div>

      <div className="sm:mx-auto sm:w-full sm:max-w-md">
        <div className="bg-slate-900/95 backdrop-blur border border-slate-800/80 py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
          {/* Header */}
          <div className="text-center mb-8">
            <img
              className="mx-auto h-16 w-auto object-contain mb-3"
              src="/LOGO/NGO_logo_monogram.png"
              alt="ATMABISWAS Logo"
            />
            <h2 className="text-2xl font-bold text-white tracking-tight">Welcome Back</h2>
            <p className="text-sm text-slate-400 mt-1">Sign in to your admin dashboard</p>
          </div>

          <form className="space-y-6" onSubmit={handleSubmit(onSubmit)} novalidate>
            {/* Email Input */}
            <div>
              <label htmlFor="email" className="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Email Address
              </label>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Mail className="h-4 w-4" />
                </div>
                <input
                  id="email"
                  type="email"
                  placeholder="Enter your email"
                  className={`block w-full pl-10 pr-3 py-2.5 bg-slate-950 border ${
                    errors.email ? 'border-red-500 focus:ring-red-500' : 'border-slate-800 focus:border-cyan-500 focus:ring-cyan-500'
                  } rounded-lg text-white text-sm placeholder-slate-500 focus:outline-none focus:ring-1 transition-all`}
                  {...register('email', {
                    required: 'Email address is required',
                    pattern: {
                      value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                      message: 'Invalid email address format'
                    }
                  })}
                />
              </div>
              {errors.email && (
                <p className="mt-1 text-xs text-red-400">{errors.email.message}</p>
              )}
            </div>

            {/* Password Input */}
            <div>
              <div className="flex items-center justify-between mb-2">
                <label htmlFor="password" className="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                  Password
                </label>
                <Link to="/forgot-password" className="text-xs font-medium text-cyan-400 hover:text-cyan-300 transition-colors">
                  Forgot password?
                </Link>
              </div>
              <div className="relative rounded-lg shadow-sm">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock className="h-4 w-4" />
                </div>
                <input
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Enter your password"
                  className={`block w-full pl-10 pr-10 py-2.5 bg-slate-950 border ${
                    errors.password ? 'border-red-500 focus:ring-red-500' : 'border-slate-800 focus:border-cyan-500 focus:ring-cyan-500'
                  } rounded-lg text-white text-sm placeholder-slate-500 focus:outline-none focus:ring-1 transition-all`}
                  {...register('password', {
                    required: 'Password is required',
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
              {errors.password && (
                <p className="mt-1 text-xs text-red-400">{errors.password.message}</p>
              )}
            </div>

            {/* Submit Button */}
            <div>
              <button
                type="submit"
                disabled={loading}
                className="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-semibold text-slate-950 bg-cyan-400 hover:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150 shadow-lg shadow-cyan-500/20"
              >
                {loading ? (
                  <span className="flex items-center space-x-2">
                    <span className="w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full animate-spin"></span>
                    <span>Signing in...</span>
                  </span>
                ) : (
                  'Sign In'
                )}
              </button>
            </div>
          </form>

          {/* Footer Copyright */}
          <div className="mt-8 text-center text-xs text-slate-500 border-t border-slate-800/80 pt-4">
            &copy; {new Date().getFullYear()} ATMABISWAS. All rights reserved.
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;
