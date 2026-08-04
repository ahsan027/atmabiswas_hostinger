const jobService = require('../services/jobService');

/**
 * Get sectors and job positions for dropdown select
 */
const getMetaData = async (req, res) => {
  try {
    const sectors = await jobService.getSectors();
    const positions = await jobService.getJobPositions();

    return res.status(200).json({
      success: true,
      sectors,
      positions
    });
  } catch (error) {
    console.error('Get Metadata Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve metadata.'
    });
  }
};

/**
 * Add new job position / code
 */
const createJobPosition = async (req, res) => {
  try {
    const { JobTitle, JobCode } = req.body;

    if (!JobTitle || !JobCode) {
      return res.status(400).json({
        success: false,
        message: 'Job Title and Job Code are required.'
      });
    }

    const id = await jobService.createJobPosition(JobTitle, JobCode);

    return res.status(201).json({
      success: true,
      message: 'Job position created successfully.',
      id
    });
  } catch (error) {
    console.error('Create Job Position Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to create job position.'
    });
  }
};

/**
 * Get paginated list of job circulars
 */
const getJobs = async (req, res) => {
  try {
    const { page, limit, search, dept, apply_enabled } = req.query;
    const result = await jobService.getJobs({ page, limit, search, dept, apply_enabled });

    return res.status(200).json({
      success: true,
      data: result.jobs,
      pagination: result.pagination
    });
  } catch (error) {
    console.error('Get Jobs Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve jobs list.'
    });
  }
};

/**
 * Get single job circular details
 */
const getJobById = async (req, res) => {
  try {
    const { id } = req.params;
    const job = await jobService.getJobById(id);

    if (!job) {
      return res.status(404).json({
        success: false,
        message: 'Job circular not found.'
      });
    }

    return res.status(200).json({
      success: true,
      job
    });
  } catch (error) {
    console.error('Get Job By Id Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve job circular.'
    });
  }
};

/**
 * Create a new job circular
 */
const createJob = async (req, res) => {
  try {
    const { job_code, job_title, deadline, job_dept, job_location, salary_range, job_experience, job_skillset, job_description, job_req, job_benefits, vacancy, bdjobs_link, apply_enabled } = req.body;

    if (!job_title || !deadline || !job_dept || !job_location || !salary_range || !job_description) {
      return res.status(400).json({
        success: false,
        message: 'Required fields missing: Title, Deadline, Department, Location, Salary, Description.'
      });
    }

    const job_id = await jobService.createJob({
      job_code,
      job_title,
      deadline,
      job_dept,
      job_location,
      salary_range,
      job_experience,
      job_skillset,
      job_description,
      job_req,
      job_benefits,
      vacancy,
      bdjobs_link,
      apply_enabled: apply_enabled === 'true' || apply_enabled === true || apply_enabled === '1' || apply_enabled === 1
    });

    return res.status(201).json({
      success: true,
      message: 'Job circular published successfully.',
      job_id
    });
  } catch (error) {
    console.error('Create Job Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to create job circular.'
    });
  }
};

/**
 * Update an existing job circular
 */
const updateJob = async (req, res) => {
  try {
    const { id } = req.params;
    const { job_code, job_title, deadline, job_dept, job_location, salary_range, job_experience, job_skillset, job_description, job_req, job_benefits, vacancy, bdjobs_link, apply_enabled } = req.body;

    const updated = await jobService.updateJob(id, {
      job_code,
      job_title,
      deadline,
      job_dept,
      job_location,
      salary_range,
      job_experience,
      job_skillset,
      job_description,
      job_req,
      job_benefits,
      vacancy,
      bdjobs_link,
      apply_enabled: apply_enabled === 'true' || apply_enabled === true || apply_enabled === '1' || apply_enabled === 1
    });

    if (!updated) {
      return res.status(404).json({
        success: false,
        message: 'Job circular not found or no changes made.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Job circular updated successfully.'
    });
  } catch (error) {
    console.error('Update Job Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to update job circular.'
    });
  }
};

/**
 * Delete a job circular
 */
const deleteJob = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await jobService.deleteJob(id);

    if (!deleted) {
      return res.status(404).json({
        success: false,
        message: 'Job circular not found or already deleted.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Job circular deleted successfully.'
    });
  } catch (error) {
    console.error('Delete Job Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to delete job circular.'
    });
  }
};

/**
 * Submit candidate job application with CV file
 */
const submitApplication = async (req, res) => {
  try {
    const { jobId, job_title, fullname, email, phone_no, experience } = req.body;

    if (!job_title || !fullname || !email || !phone_no || !req.file) {
      return res.status(400).json({
        success: false,
        message: 'Full name, email, phone number, job title, and CV resume file are required.'
      });
    }

    const cv_file = `/uploads/cv/${req.file.filename}`;

    const applicationId = await jobService.submitApplication({
      jobId,
      job_title,
      fullname,
      email,
      phone_no,
      experience,
      cv_file
    });

    return res.status(201).json({
      success: true,
      message: 'Application submitted successfully! Our HR team will contact you.',
      applicationId
    });
  } catch (error) {
    console.error('Submit Application Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to submit application.'
    });
  }
};

/**
 * Get candidate applications for admin
 */
const getApplications = async (req, res) => {
  try {
    const { jobId, search } = req.query;
    const applications = await jobService.getApplications({ jobId, search });

    return res.status(200).json({
      success: true,
      applications
    });
  } catch (error) {
    console.error('Get Applications Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve candidate applications.'
    });
  }
};

/**
 * Delete candidate application
 */
const deleteApplication = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await jobService.deleteApplication(id);

    if (!deleted) {
      return res.status(404).json({
        success: false,
        message: 'Application entry not found or already deleted.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Application deleted successfully.'
    });
  } catch (error) {
    console.error('Delete Application Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to delete application.'
    });
  }
};

module.exports = {
  getMetaData,
  createJobPosition,
  getJobs,
  getJobById,
  createJob,
  updateJob,
  deleteJob,
  submitApplication,
  getApplications,
  deleteApplication
};
