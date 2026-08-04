import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { dashboardApi } from '../api/dashboardApi';
import Navbar from '../components/Navbar';
import StatsCard from '../components/StatsCard';
import DashboardTable from '../components/DashboardTable';
import { Briefcase, FileText, LayoutGrid, BookOpen, UserPlus, Users, RefreshCw } from 'lucide-react';
import { Link } from 'react-router-dom';
import toast from 'react-hot-toast';

const Dashboard = () => {
  const { user } = useAuth();
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);

  const fetchOverview = async () => {
    setLoading(true);
    try {
      const res = await dashboardApi.getOverview();
      if (res.data.success) {
        setData(res.data.data);
      } else {
        toast.error('Failed to load dashboard data');
      }
    } catch (err) {
      toast.error('Error connecting to dashboard API');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOverview();
  }, []);

  const handleDelete = async (type, id, itemName) => {
    if (!window.confirm(`Are you sure you want to delete ${itemName}?`)) return;

    try {
      const res = await dashboardApi.deleteItem(type, id);
      if (res.data.success) {
        toast.success(`${itemName} deleted successfully`);
        fetchOverview();
      } else {
        toast.error(res.data.message || 'Failed to delete item');
      }
    } catch (err) {
      toast.error('Error deleting item');
    }
  };

  const truncate = (text, maxLength = 60) => {
    if (!text) return '';
    return text.length > maxLength ? text.substring(0, maxLength) + '....' : text;
  };

  if (loading && !data) {
    return (
      <div className="min-h-screen bg-slate-950 text-white">
        <Navbar />
        <div className="flex items-center justify-center h-[calc(100vh-4rem)]">
          <div className="flex flex-col items-center space-y-3">
            <div className="w-10 h-10 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
            <p className="text-sm font-medium text-cyan-400">Loading Dashboard Overview...</p>
          </div>
        </div>
      </div>
    );
  }

  const stats = data?.stats || {};

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Header section matching dashboard.php */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Dashboard Overview</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">
              Welcome back, <strong className="text-cyan-400">{user?.fullname || user?.email}</strong>! Here's what's happening today.
            </p>
          </div>
          <div className="flex items-center space-x-3">
            <button
              onClick={fetchOverview}
              className="p-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 transition-all"
              title="Refresh Dashboard"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
            <Link
              to="/change-password"
              className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 text-xs font-semibold transition-all"
            >
              <Users className="w-4 h-4" />
              <span>Manage Admins</span>
            </Link>
          </div>
        </div>

        {/* Stats Grid matching dashboard.php */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatsCard
            title="Available Jobs"
            subtitle="Active job positions"
            number={stats.total_jobs || 0}
            label="Active Positions"
            icon={Briefcase}
            colorClass="jobs"
          />
          <StatsCard
            title="Job Applications"
            subtitle="Pending applications"
            number={stats.total_applications || 0}
            label="Pending Requests"
            icon={FileText}
            colorClass="applications"
          />
          <StatsCard
            title="Job Sectors"
            subtitle="Available sectors"
            number={stats.total_sectors || 0}
            label="Total Sectors"
            icon={LayoutGrid}
            colorClass="sectors"
          />
          <StatsCard
            title="Content Overview"
            subtitle="Published content"
            icon={BookOpen}
            colorClass="content"
            contentItems={[
              { number: stats.total_news || 0, label: 'News' },
              { number: stats.total_notices || 0, label: 'Notices' },
              { number: stats.total_images || 0, label: 'Images' }
            ]}
          />
        </div>

        {/* 1. Job Positions Table */}
        <DashboardTable
          title="Job Positions"
          subtitle="Active job positions and codes"
          columns={[
            { header: 'Job ID', accessor: 'jobid' },
            { header: 'Job Position', render: (r) => truncate(r.JobTitle || r.job_title) },
            {
              header: 'Job Code',
              render: (r) => (
                <span className="px-2.5 py-1 rounded bg-cyan-500/10 text-cyan-400 font-mono text-xs border border-cyan-500/20 font-semibold">
                  {r.JobCode || r.job_code || 'N/A'}
                </span>
              )
            }
          ]}
          data={data?.jobs}
          onDelete={(r) => handleDelete('job', r.jobid || r.job_id, `Job Position #${r.jobid || r.job_id}`)}
        />

        {/* 2. Job Sectors Table */}
        <DashboardTable
          title="Job Sectors"
          subtitle="Available job sectors"
          columns={[
            { header: 'Sector ID', accessor: 'sector_id' },
            { header: 'Sector Name', accessor: 'sector_name' }
          ]}
          data={data?.sectors}
          onDelete={(r) => handleDelete('sector', r.sector_id, `Sector #${r.sector_id}`)}
        />

        {/* 3. Pending Applications Table */}
        <DashboardTable
          title="Pending Applications"
          subtitle="Job applications awaiting review"
          columns={[
            { header: 'App ID', accessor: 'applicationId' },
            { header: 'Job ID', accessor: 'jobId' },
            { header: 'Job Title', accessor: 'job_title' },
            { header: 'Full Name', accessor: 'fullname' },
            { header: 'Email', accessor: 'email' },
            { header: 'Phone', render: (r) => `+88${r.phone_no}` },
            { header: 'Applied At', render: (r) => new Date(r.appliedAt).toLocaleDateString() }
          ]}
          data={data?.applications}
          onDelete={(r) => handleDelete('application', r.applicationId, `Application #${r.applicationId}`)}
        />

        {/* 4. Published News Table */}
        <DashboardTable
          title="Published News"
          subtitle="Latest published press posts"
          columns={[
            { header: 'Press ID', accessor: 'blog_id' },
            { header: 'Press Title', render: (r) => truncate(r.blog_title) },
            {
              header: 'Author',
              render: (r) => (
                <span className="px-2 py-0.5 rounded bg-purple-500/10 text-purple-400 text-xs border border-purple-500/20 font-medium">
                  {r.blog_author}
                </span>
              )
            },
            { header: 'Published Date', render: (r) => new Date(r.upload_date).toLocaleDateString() }
          ]}
          data={data?.news}
          onDelete={(r) => handleDelete('news', r.blog_id, `Press Article #${r.blog_id}`)}
        />

        {/* 5. Uploaded Images Table */}
        <DashboardTable
          title="Uploaded Images"
          subtitle="Recently uploaded image gallery"
          columns={[
            { header: 'Image ID', accessor: 'img_id' },
            { header: 'Image Title', accessor: 'img_title' },
            { header: 'Description', render: (r) => truncate(r.img_description) },
            { header: 'Image Path', render: (r) => truncate(r.img_path) },
            { header: 'Upload Date', render: (r) => r.uploaded_on ? new Date(r.uploaded_on).toLocaleDateString() : 'N/A' }
          ]}
          data={data?.images}
          onDelete={(r) => handleDelete('image', r.img_id, `Image #${r.img_id}`)}
        />

        {/* 6. Uploaded Notices Table */}
        <DashboardTable
          title="Uploaded Notices"
          subtitle="PDF notice files"
          columns={[
            { header: 'PDF ID', accessor: 'pdf_id' },
            { header: 'PDF Title', accessor: 'pdf_title' },
            { header: 'PDF Path', accessor: 'pdf_path' },
            { header: 'Upload Date', render: (r) => r.upload_date ? new Date(r.upload_date).toLocaleDateString() : 'N/A' }
          ]}
          data={data?.notices}
          onDelete={(r) => handleDelete('notice', r.pdf_id, `Notice PDF #${r.pdf_id}`)}
        />
      </main>
    </div>
  );
};

export default Dashboard;
