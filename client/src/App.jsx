import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';

// Auth, Dashboard, User Management, Blog, Career & Directory Pages
import Login from './pages/Login';
import ForgotPassword from './pages/ForgotPassword';
import ResetPassword from './pages/ResetPassword';
import Profile from './pages/Profile';
import ChangePassword from './pages/ChangePassword';
import Dashboard from './pages/Dashboard';
import UserManagement from './pages/UserManagement';
import BlogManager from './pages/BlogManager';
import BlogEditor from './pages/BlogEditor';
import JobManager from './pages/JobManager';
import ApplicationsManager from './pages/ApplicationsManager';
import PublicCareer from './pages/PublicCareer';
import RegionalOffices from './pages/RegionalOffices';
import BranchManager from './pages/BranchManager';
import DivisionManager from './pages/DivisionManager';

function App() {
  return (
    <AuthProvider>
      <Router>
        <Toaster
          position="top-right"
          toastOptions={{
            duration: 4000,
            style: {
              background: '#0f172a',
              color: '#fff',
              border: '1px solid #334155'
            }
          }}
        />
        <Routes>
          {/* Public Routes */}
          <Route path="/career" element={<PublicCareer />} />

          {/* Public Auth Routes */}
          <Route path="/login" element={<Login />} />
          <Route path="/forgot-password" element={<ForgotPassword />} />
          <Route path="/reset-password" element={<ResetPassword />} />

          {/* Protected Routes */}
          <Route element={<ProtectedRoute />}>
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/regional-offices" element={<RegionalOffices />} />
            <Route path="/branches" element={<BranchManager />} />
            <Route path="/divisions" element={<DivisionManager />} />
            <Route path="/jobs" element={<JobManager />} />
            <Route path="/manage-jobs" element={<JobManager />} />
            <Route path="/applications" element={<ApplicationsManager />} />
            <Route path="/blogs" element={<BlogManager />} />
            <Route path="/blog-manager" element={<BlogManager />} />
            <Route path="/blog-editor" element={<BlogEditor />} />
            <Route path="/blog-editor/:id" element={<BlogEditor />} />
            <Route path="/users" element={<UserManagement />} />
            <Route path="/manage-admins" element={<UserManagement />} />
            <Route path="/create-admin" element={<UserManagement />} />
            <Route path="/profile" element={<Profile />} />
            <Route path="/change-password" element={<ChangePassword />} />
          </Route>

          {/* Catch-all redirect */}
          <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
