import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { blogApi } from '../api/blogApi';
import Navbar from '../components/Navbar';
import { ArrowLeft, Save, Upload, Video, Globe, FileText, Image as ImageIcon } from 'lucide-react';
import toast from 'react-hot-toast';

const BlogEditor = () => {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(isEdit);
  const [imagePreview, setImagePreview] = useState(null);
  const [selectedFile, setSelectedFile] = useState(null);

  const {
    register,
    handleSubmit,
    setValue,
    formState: { errors }
  } = useForm({
    defaultValues: {
      category: 'news',
      status: 'published',
      featured: false,
      blog_author: 'ATMABISWAS'
    }
  });

  useEffect(() => {
    if (isEdit) {
      blogApi.getBlogById(id)
        .then((res) => {
          if (res.data.success && res.data.blog) {
            const b = res.data.blog;
            setValue('blog_title', b.blog_title || '');
            setValue('blog_content', b.blog_content || '');
            setValue('blog_author', b.blog_author || 'ATMABISWAS');
            setValue('summary', b.summary || '');
            setValue('category', b.category || 'news');
            setValue('source_link', b.source_link || '');
            setValue('status', b.status || 'published');
            setValue('featured', Boolean(b.featured));
            setValue('tags', b.tags || '');
            setValue('seo_title', b.seo_title || '');
            setValue('seo_description', b.seo_description || '');
            setValue('seo_keywords', b.seo_keywords || '');
            setValue('focus_keyword', b.focus_keyword || '');
            setValue('canonical_url', b.canonical_url || '');

            if (b.cover_img) {
              setImagePreview(b.cover_img);
            }
          }
        })
        .catch(() => toast.error('Failed to load article data'))
        .finally(() => setFetching(false));
    }
  }, [id, isEdit, setValue]);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setSelectedFile(file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const onSubmit = async (formData) => {
    setLoading(true);
    try {
      const data = new FormData();
      Object.keys(formData).forEach((key) => {
        if (formData[key] !== undefined && formData[key] !== null) {
          data.append(key, formData[key]);
        }
      });

      if (selectedFile) {
        data.append('cover_img', selectedFile);
      }

      if (isEdit) {
        const res = await blogApi.updateBlog(id, data);
        if (res.data.success) {
          toast.success('Article updated successfully!');
          navigate('/blogs');
        }
      } else {
        const res = await blogApi.createBlog(data);
        if (res.data.success) {
          toast.success('Article published successfully!');
          navigate('/blogs');
        }
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Error saving article');
    } finally {
      setLoading(false);
    }
  };

  if (fetching) {
    return (
      <div className="min-h-screen bg-slate-950 text-white">
        <Navbar />
        <div className="flex items-center justify-center h-[calc(100vh-4rem)]">
          <div className="w-8 h-8 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      <main className="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between mb-6">
          <Link to="/blogs" className="inline-flex items-center text-xs font-medium text-slate-400 hover:text-white transition-colors">
            <ArrowLeft className="w-4 h-4 mr-1" /> Back to Press Manager
          </Link>
          <span className="text-xs font-mono text-cyan-400">{isEdit ? `Editing Article #${id}` : 'New Press Post'}</span>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
          {/* Main Card */}
          <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">
            <h1 className="text-2xl font-bold text-white mb-6 border-b border-slate-800 pb-4">
              {isEdit ? 'Edit Article & Media' : 'Publish New Article'}
            </h1>

            <div className="space-y-5">
              {/* Title */}
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Article Title *</label>
                <input
                  type="text"
                  placeholder="Enter headline..."
                  className={`w-full px-3.5 py-2.5 bg-slate-950 border ${
                    errors.blog_title ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-sm text-white focus:outline-none`}
                  {...register('blog_title', { required: 'Article title is required' })}
                />
                {errors.blog_title && <p className="text-xs text-red-400 mt-1">{errors.blog_title.message}</p>}
              </div>

              {/* Grid Options */}
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Category</label>
                  <select
                    className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('category')}
                  >
                    <option value="news">News</option>
                    <option value="press">Press Release</option>
                    <option value="event">Event</option>
                    <option value="announcement">Announcement</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Status</label>
                  <select
                    className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('status')}
                  >
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Author</label>
                  <input
                    type="text"
                    className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('blog_author')}
                  />
                </div>
              </div>

              {/* Cover Image Upload & YouTube Link */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Cover Image</label>
                  <div className="flex items-start space-x-4">
                    {imagePreview && (
                      <img src={imagePreview} alt="Preview" className="w-24 h-16 object-cover rounded-lg border border-slate-800" />
                    )}
                    <label className="flex-1 cursor-pointer flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-800 hover:border-cyan-500 rounded-lg bg-slate-950/60 text-slate-400 hover:text-cyan-400 transition-colors">
                      <Upload className="w-5 h-5 mb-1" />
                      <span className="text-xs font-medium">Click to upload cover photo</span>
                      <input type="file" accept="image/*" onChange={handleImageChange} className="hidden" />
                    </label>
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">YouTube Video Link (Optional)</label>
                  <div className="relative">
                    <Video className="absolute left-3 top-3 w-4 h-4 text-slate-500" />
                    <input
                      type="url"
                      placeholder="https://www.youtube.com/watch?v=..."
                      className="w-full pl-9 pr-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                      {...register('source_link')}
                    />
                  </div>
                </div>
              </div>

              {/* Summary */}
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Summary / Excerpt</label>
                <textarea
                  rows={2}
                  placeholder="Short brief summary..."
                  className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('summary')}
                ></textarea>
              </div>

              {/* Article Content */}
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Article Content *</label>
                <textarea
                  rows={10}
                  placeholder="Write complete article content here (HTML supported)..."
                  className={`w-full px-3.5 py-2.5 bg-slate-950 border ${
                    errors.blog_content ? 'border-red-500' : 'border-slate-800 focus:border-cyan-500'
                  } rounded-lg text-sm text-white focus:outline-none font-mono`}
                  {...register('blog_content', { required: 'Article content is required' })}
                ></textarea>
                {errors.blog_content && <p className="text-xs text-red-400 mt-1">{errors.blog_content.message}</p>}
              </div>

              {/* Checkbox Featured */}
              <div className="flex items-center space-x-2 pt-2">
                <input
                  id="featured"
                  type="checkbox"
                  className="w-4 h-4 rounded bg-slate-950 border-slate-800 text-cyan-500 focus:ring-cyan-500"
                  {...register('featured')}
                />
                <label htmlFor="featured" className="text-xs font-medium text-slate-300">
                  Feature this article on homepage hero section
                </label>
              </div>
            </div>
          </div>

          {/* SEO Metadata Card */}
          <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">
            <h2 className="text-lg font-bold text-white mb-4 flex items-center space-x-2 border-b border-slate-800 pb-3">
              <Globe className="w-5 h-5 text-cyan-400" />
              <span>SEO & Meta Configuration</span>
            </h2>

            <div className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">SEO Meta Title</label>
                  <input
                    type="text"
                    placeholder="Custom Google title..."
                    className="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-cyan-500"
                    {...register('seo_title')}
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Focus Keyword</label>
                  <input
                    type="text"
                    placeholder="Main target keyword..."
                    className="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-cyan-500"
                    {...register('focus_keyword')}
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">SEO Description</label>
                <textarea
                  rows={2}
                  placeholder="Meta description for search engines..."
                  className="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-cyan-500"
                  {...register('seo_description')}
                ></textarea>
              </div>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="flex justify-end space-x-4">
            <Link
              to="/blogs"
              className="px-5 py-2.5 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-lg text-sm font-semibold"
            >
              Cancel
            </Link>
            <button
              type="submit"
              disabled={loading}
              className="inline-flex items-center space-x-2 px-6 py-2.5 bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-bold text-sm rounded-lg shadow-lg shadow-cyan-500/20 disabled:opacity-50 transition-all"
            >
              <Save className="w-4 h-4" />
              <span>{loading ? 'Saving Article...' : isEdit ? 'Update Article' : 'Publish Article'}</span>
            </button>
          </div>
        </form>
      </main>
    </div>
  );
};

export default BlogEditor;
