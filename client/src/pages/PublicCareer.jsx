import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { jobApi } from '../api/jobApi';
import Navbar from '../components/Navbar';
import { Briefcase, MapPin, DollarSign, Calendar, Clock, Upload, Send, CheckCircle, X, ChevronRight } from 'lucide-react';
import toast from 'react-hot-toast';

const PublicCareer = () => {
  const [jobs, setJobs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedJob, setSelectedJob] = useState(null);
  const [isApplyModalOpen, setIsApplyModalOpen] = useState(false);
  const [resumeFile, setResumeFile] = useState(null);
  const [submitting, setSubmitting] = useState(false);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors }
  } = useForm();

  const fetchPublicJobs = async () => {
    setLoading(true);
    try {
      const res = await jobApi.getJobs({ page: 1, limit: 50 });
      if (res.data.success) {
        setJobs(res.data.data || []);
      }
    } catch (err) {
      toast.error('Failed to load career openings');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchPublicJobs();
  }, []);

  const openApplyModal = (jobItem) => {
    setSelectedJob(jobItem);
    setResumeFile(null);
    reset({
      fullname: '',
      email: '',
      phone_no: '',
      experience: ''
    });
    setIsApplyModalOpen(true);
  };

  const onSubmitApplication = async (formData) => {
    if (!resumeFile) {
      toast.error('Please upload your CV / resume (PDF or DOC)');
      return;
    }

    setSubmitting(true);
    try {
      const data = new FormData();
      data.append('jobId', selectedJob.job_id);
      data.append('job_title', selectedJob.job_title);
      data.append('fullname', formData.fullname);
      data.append('email', formData.email);
      data.append('phone_no', formData.phone_no);
      data.append('experience', formData.experience);
      data.append('cv_file', resumeFile);

      const res = await jobApi.submitApplication(data);
      if (res.data.success) {
        toast.success('Application submitted successfully!');
        setIsApplyModalOpen(false);
      }
    } catch (err) {
      toast.error(err.response?.data?.message || 'Submission failed');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      <Navbar />

      {/* Hero Banner */}
      <div className="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 border-b border-slate-800 py-16 px-4 sm:px-6 lg:px-8 text-center">
        <h1 className="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">Join The ATMABISWAS Team</h1>
        <p className="max-w-2xl mx-auto text-sm sm:text-base text-slate-400 mt-3">
          Explore career opportunities and build a meaningful career empowering communities across Bangladesh.
        </p>
      </div>

      <main className="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <h2 className="text-xl font-bold text-white mb-1">Open Job Circulars</h2>
          <p className="text-xs text-slate-400">Apply online for open positions below</p>
        </div>

        {loading ? (
          <div className="flex justify-center py-12">
            <div className="w-8 h-8 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
          </div>
        ) : jobs.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {jobs.map((job) => (
              <div key={job.job_id} className="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between transition-all">
                <div>
                  <div className="flex items-center justify-between gap-2 mb-3">
                    <span className="px-2.5 py-1 rounded-md text-[11px] font-semibold uppercase bg-cyan-950 text-cyan-400 border border-cyan-800">
                      {job.job_dept}
                    </span>
                    <span className="text-xs font-mono text-slate-500">{job.job_code}</span>
                  </div>

                  <h3 className="text-lg font-bold text-white mb-2">{job.job_title}</h3>

                  <p className="text-xs text-slate-300 line-clamp-3 mb-4 leading-relaxed">
                    {job.job_description}
                  </p>

                  <div className="grid grid-cols-2 gap-2 text-xs text-slate-400 mb-6 bg-slate-950/60 p-3 rounded-xl border border-slate-850">
                    <div className="flex items-center space-x-1.5">
                      <MapPin className="w-3.5 h-3.5 text-slate-500" />
                      <span>{job.job_location}</span>
                    </div>
                    <div className="flex items-center space-x-1.5">
                      <DollarSign className="w-3.5 h-3.5 text-slate-500" />
                      <span>{job.salary_range}</span>
                    </div>
                    <div className="flex items-center space-x-1.5">
                      <Clock className="w-3.5 h-3.5 text-slate-500" />
                      <span>Exp: {job.job_experience || 'Freshers welcome'}</span>
                    </div>
                    <div className="flex items-center space-x-1.5">
                      <Calendar className="w-3.5 h-3.5 text-slate-500" />
                      <span>Deadline: {job.deadline ? new Date(job.deadline).toLocaleDateString() : 'Open'}</span>
                    </div>
                  </div>
                </div>

                <div className="flex items-center justify-between border-t border-slate-800/80 pt-4">
                  <span className="text-xs text-slate-400">{job.vacancy || 1} Open Vacancy</span>
                  <button
                    onClick={() => openApplyModal(job)}
                    className="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-bold text-xs transition-all shadow-lg shadow-cyan-500/20"
                  >
                    <span>Apply Now</span>
                    <ChevronRight className="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center text-slate-500">
            No active job circulars open right now. Check back soon!
          </div>
        )}
      </main>

      {/* Candidate Application Modal */}
      {isApplyModalOpen && selectedJob && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
          <div className="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl relative">
            <button
              onClick={() => setIsApplyModalOpen(false)}
              className="absolute top-4 right-4 text-slate-400 hover:text-white"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-white mb-1">Submit Job Application</h2>
            <p className="text-xs text-cyan-400 font-medium mb-6">Applying for: {selectedJob.job_title} ({selectedJob.job_code})</p>

            <form onSubmit={handleSubmit(onSubmitApplication)} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Full Name *</label>
                <input
                  type="text"
                  placeholder="Your full name"
                  className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('fullname', { required: 'Full name is required' })}
                />
                {errors.fullname && <p className="text-xs text-red-400 mt-1">{errors.fullname.message}</p>}
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Email Address *</label>
                  <input
                    type="email"
                    placeholder="name@example.com"
                    className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('email', { required: 'Email is required' })}
                  />
                  {errors.email && <p className="text-xs text-red-400 mt-1">{errors.email.message}</p>}
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Phone Number *</label>
                  <input
                    type="tel"
                    placeholder="+8801700000000"
                    className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                    {...register('phone_no', { required: 'Phone number is required' })}
                  />
                  {errors.phone_no && <p className="text-xs text-red-400 mt-1">{errors.phone_no.message}</p>}
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Years of Experience</label>
                <input
                  type="text"
                  placeholder="e.g. 3 years in microfinance"
                  className="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white focus:outline-none focus:border-cyan-500"
                  {...register('experience')}
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-300 uppercase mb-1">Upload Resume / CV (PDF/DOC) *</label>
                <label className="cursor-pointer flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-800 hover:border-cyan-500 rounded-lg bg-slate-950 text-slate-400 hover:text-cyan-400 transition-colors">
                  <Upload className="w-6 h-6 mb-1" />
                  <span className="text-xs font-medium">{resumeFile ? resumeFile.name : 'Click to upload CV document (Max 15MB)'}</span>
                  <input
                    type="file"
                    accept=".pdf,.doc,.docx"
                    onChange={(e) => setResumeFile(e.target.files[0])}
                    className="hidden"
                  />
                </label>
              </div>

              <div className="flex justify-end space-x-3 pt-4 border-t border-slate-800">
                <button
                  type="button"
                  onClick={() => setIsApplyModalOpen(false)}
                  className="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={submitting}
                  className="inline-flex items-center space-x-2 px-5 py-2 bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-bold text-xs rounded-lg disabled:opacity-50 transition-all shadow-lg shadow-cyan-500/20"
                >
                  <Send className="w-3.5 h-3.5" />
                  <span>{submitting ? 'Submitting...' : 'Submit Application'}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default PublicCareer;
