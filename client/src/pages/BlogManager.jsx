import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { blogApi } from '../api/blogApi';
import Navbar from '../components/Navbar';
import { Search, Plus, Edit3, Trash2, Star, Eye, ChevronLeft, ChevronRight, RefreshCw, FileText } from 'lucide-react';
import toast from 'react-hot-toast';

const BlogManager = () => {
  const [blogs, setBlogs] = useState([]);
  const [pagination, setPagination] = useState({ page: 1, limit: 10, totalPages: 1, total: 0 });
  const [loading, setLoading] = useState(true);

  // Filters
  const [search, setSearch] = useState('');
  const [category, setCategory] = useState('');
  const [status, setStatus] = useState('');
  const [currentPage, setCurrentPage] = useState(1);

  const navigate = useNavigate();

  const fetchBlogs = async () => {
    setLoading(true);
    try {
      const res = await blogApi.getBlogs({
        page: currentPage,
        limit: 10,
        search,
        category,
        status
      });
      if (res.data.success) {
        setBlogs(res.data.data);
        setPagination(res.data.pagination);
      }
    } catch (err) {
      toast.error('Failed to load press & blog articles');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchBlogs();
  }, [currentPage, search, category, status]);

  const handleToggleFeatured = async (id) => {
    try {
      const res = await blogApi.toggleFeatured(id);
      if (res.data.success) {
        toast.success('Featured status updated');
        fetchBlogs();
      }
    } catch (err) {
      toast.error('Failed to update featured status');
    }
  };

  const handleToggleStatus = async (id) => {
    try {
      const res = await blogApi.toggleStatus(id);
      if (res.data.success) {
        toast.success('Article status updated');
        fetchBlogs();
      }
    } catch (err) {
      toast.error('Failed to update article status');
    }
  };

  const handleDelete = async (id, title) => {
    if (!window.confirm(`Are you sure you want to delete press post "${title}"?`)) return;

    try {
      const res = await blogApi.deleteBlog(id);
      if (res.data.success) {
        toast.success('Article deleted successfully');
        fetchBlogs();
      }
    } catch (err) {
      toast.error('Failed to delete article');
    }
  };

  const getYoutubeThumb = (url) => {
    if (!url) return null;
    const match = url.match(/(?:youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*)/);
    return (match && match[1].length === 11) ? `https://img.youtube.com/vi/${match[1]}/mqdefault.jpg` : null;
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {/* Page Header */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Press & News Manager</h1>
            <p className="text-xs sm:text-sm text-slate-400 mt-1">Manage, edit, feature, and publish press posts & articles</p>
          </div>
          <Link
            to="/blog-editor"
            className="inline-flex items-center space-x-2 px-4 py-2.5 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-xs transition-all shadow-lg shadow-cyan-500/20"
          >
            <Plus className="w-4 h-4" />
            <span>Create New Article</span>
          </Link>
        </div>

        {/* Search & Filter Bar */}
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
          <div className="relative w-full md:w-80">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search by article title or summary..."
              value={search}
              onChange={(e) => {
                setSearch(e.target.value);
                setCurrentPage(1);
              }}
              className="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500"
            />
          </div>

          <div className="flex items-center space-x-3 w-full md:w-auto justify-end">
            <select
              value={category}
              onChange={(e) => {
                setCategory(e.target.value);
                setCurrentPage(1);
              }}
              className="bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 px-3 py-2 focus:outline-none focus:border-cyan-500"
            >
              <option value="">All Categories</option>
              <option value="news">News</option>
              <option value="press">Press Release</option>
              <option value="event">Event</option>
              <option value="announcement">Announcement</option>
            </select>

            <select
              value={status}
              onChange={(e) => {
                setStatus(e.target.value);
                setCurrentPage(1);
              }}
              className="bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 px-3 py-2 focus:outline-none focus:border-cyan-500"
            >
              <option value="">All Status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>

            <button
              onClick={fetchBlogs}
              className="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700"
              title="Refresh"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
          </div>
        </div>

        {/* Articles Table */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden mb-6">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="text-xs uppercase bg-slate-950 text-slate-400 border-b border-slate-800">
                <tr>
                  <th className="px-4 py-3.5 font-semibold">ID</th>
                  <th className="px-4 py-3.5 font-semibold">Media</th>
                  <th className="px-4 py-3.5 font-semibold">Title</th>
                  <th className="px-4 py-3.5 font-semibold">Category</th>
                  <th className="px-4 py-3.5 font-semibold">Featured</th>
                  <th className="px-4 py-3.5 font-semibold">Status</th>
                  <th className="px-4 py-3.5 font-semibold">Date</th>
                  <th className="px-4 py-3.5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/80">
                {blogs.length > 0 ? (
                  blogs.map((b) => {
                    const ytThumb = getYoutubeThumb(b.source_link);
                    const thumbSrc = b.cover_img || ytThumb;
                    return (
                      <tr key={b.blog_id} className="hover:bg-slate-850/50 transition-colors">
                        <td className="px-4 py-4 text-xs font-mono text-slate-400">#{b.blog_id}</td>
                        <td className="px-4 py-4">
                          {thumbSrc ? (
                            <img src={thumbSrc} alt="" className="w-12 h-9 object-cover rounded-md border border-slate-800" />
                          ) : (
                            <div className="w-12 h-9 bg-slate-950 rounded-md border border-slate-800 flex items-center justify-center text-slate-600">
                              <FileText className="w-4 h-4" />
                            </div>
                          )}
                        </td>
                        <td className="px-4 py-4">
                          <p className="text-xs font-semibold text-white line-clamp-1">{b.blog_title}</p>
                          <p className="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{b.summary || b.blog_author}</p>
                        </td>
                        <td className="px-4 py-4">
                          <span className="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-slate-950 text-cyan-400 border border-cyan-900/60">
                            {b.category || 'news'}
                          </span>
                        </td>
                        <td className="px-4 py-4">
                          <button
                            onClick={() => handleToggleFeatured(b.blog_id)}
                            className={`p-1.5 rounded-lg border transition-all ${
                              b.featured ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-slate-950 text-slate-600 border-slate-800 hover:text-slate-400'
                            }`}
                            title="Toggle Featured"
                          >
                            <Star className={`w-4 h-4 ${b.featured ? 'fill-amber-400' : ''}`} />
                          </button>
                        </td>
                        <td className="px-4 py-4">
                          <button
                            onClick={() => handleToggleStatus(b.blog_id)}
                            className={`px-2.5 py-1 rounded-full text-[10px] font-semibold tracking-wide border transition-all ${
                              b.status === 'published'
                                ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'
                                : 'bg-slate-800 text-slate-400 border-slate-700'
                            }`}
                          >
                            {b.status || 'published'}
                          </button>
                        </td>
                        <td className="px-4 py-4 text-xs text-slate-400">
                          {b.upload_date ? new Date(b.upload_date).toLocaleDateString() : 'N/A'}
                        </td>
                        <td className="px-4 py-4 text-right space-x-1.5">
                          <button
                            onClick={() => navigate(`/blog-editor/${b.blog_id}`)}
                            className="p-1.5 text-slate-400 hover:text-cyan-400 hover:bg-slate-800 rounded-lg transition-colors"
                            title="Edit Article"
                          >
                            <Edit3 className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => handleDelete(b.blog_id, b.blog_title)}
                            className="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                            title="Delete Article"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </td>
                      </tr>
                    );
                  })
                ) : (
                  <tr>
                    <td colSpan={8} className="px-4 py-10 text-center text-slate-500 text-xs">
                      No press or news articles found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          <div className="bg-slate-950 px-6 py-3.5 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
            <div>
              Showing page <strong className="text-white">{pagination.page}</strong> of <strong className="text-white">{pagination.totalPages}</strong> (Total {pagination.total} records)
            </div>
            <div className="flex items-center space-x-2">
              <button
                onClick={() => setCurrentPage((p) => Math.max(p - 1, 1))}
                disabled={currentPage <= 1}
                className="p-1.5 rounded bg-slate-900 border border-slate-800 text-slate-300 hover:text-white disabled:opacity-40"
              >
                <ChevronLeft className="w-4 h-4" />
              </button>
              <button
                onClick={() => setCurrentPage((p) => Math.min(p + 1, pagination.totalPages))}
                disabled={currentPage >= pagination.totalPages}
                className="p-1.5 rounded bg-slate-900 border border-slate-800 text-slate-300 hover:text-white disabled:opacity-40"
              >
                <ChevronRight className="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
};

export default BlogManager;
