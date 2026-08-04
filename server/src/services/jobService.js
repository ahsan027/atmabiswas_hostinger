const db = require('../config/db');

class JobService {
  /**
   * Fetch all job sectors
   */
  async getSectors() {
    const [rows] = await db.query('SELECT * FROM sectors ORDER BY sector_name ASC');
    return rows;
  }

  /**
   * Fetch all job positions / codes
   */
  async getJobPositions() {
    const [rows] = await db.query('SELECT JobTitle, JobCode FROM jobcodes ORDER BY JobTitle ASC');
    return rows;
  }

  /**
   * Create new job position / code
   */
  async createJobPosition(JobTitle, JobCode) {
    const [result] = await db.query(
      'INSERT INTO jobcodes (JobTitle, JobCode) VALUES (?, ?)',
      [JobTitle, JobCode]
    );
    return result.insertId;
  }

  /**
   * Fetch paginated list of job circulars
   */
  async getJobs({ page = 1, limit = 10, search = '', dept = '', apply_enabled = '' }) {
    const offset = (parseInt(page, 10) - 1) * parseInt(limit, 10);
    const parsedLimit = parseInt(limit, 10);

    let query = 'SELECT * FROM jobs WHERE 1=1';
    let countQuery = 'SELECT COUNT(*) AS total FROM jobs WHERE 1=1';
    const params = [];
    const countParams = [];

    if (search && search.trim() !== '') {
      const searchTerm = `%${search.trim()}%`;
      query += ' AND (job_title LIKE ? OR job_code LIKE ? OR job_dept LIKE ? OR job_location LIKE ?)';
      countQuery += ' AND (job_title LIKE ? OR job_code LIKE ? OR job_dept LIKE ? OR job_location LIKE ?)';
      params.push(searchTerm, searchTerm, searchTerm, searchTerm);
      countParams.push(searchTerm, searchTerm, searchTerm, searchTerm);
    }

    if (dept && dept.trim() !== '') {
      query += ' AND job_dept = ?';
      countQuery += ' AND job_dept = ?';
      params.push(dept.trim());
      countParams.push(dept.trim());
    }

    if (apply_enabled !== '' && apply_enabled !== undefined) {
      query += ' AND apply_enabled = ?';
      countQuery += ' AND apply_enabled = ?';
      params.push(parseInt(apply_enabled, 10));
      countParams.push(parseInt(apply_enabled, 10));
    }

    query += ' ORDER BY job_id DESC LIMIT ? OFFSET ?';
    params.push(parsedLimit, offset);

    const [[{ total }]] = await db.query(countQuery, countParams);
    const [jobs] = await db.query(query, params);

    return {
      jobs,
      pagination: {
        total,
        page: parseInt(page, 10),
        limit: parsedLimit,
        totalPages: Math.ceil(total / parsedLimit) || 1
      }
    };
  }

  /**
   * Get single job by ID or Code
   */
  async getJobById(job_id) {
    const [rows] = await db.query('SELECT * FROM jobs WHERE job_id = ? OR job_code = ?', [job_id, job_id]);
    return rows[0] || null;
  }

  /**
   * Create new job circular
   */
  async createJob(data) {
    const query = `
      INSERT INTO jobs (
        job_code, job_title, deadline, job_dept, job_location, salary_range,
        job_experience, job_skillset, job_description, job_req, job_benefits,
        vacancy, bdjobs_link, apply_enabled
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;

    const params = [
      data.job_code || `JOB-${Date.now()}`,
      data.job_title,
      data.deadline,
      data.job_dept,
      data.job_location,
      data.salary_range,
      data.job_experience,
      data.job_skillset || null,
      data.job_description,
      data.job_req || null,
      data.job_benefits || null,
      data.vacancy || 1,
      data.bdjobs_link || null,
      data.apply_enabled ? 1 : 0
    ];

    const [result] = await db.query(query, params);
    return result.insertId;
  }

  /**
   * Update existing job circular
   */
  async updateJob(job_id, data) {
    const query = `
      UPDATE jobs SET
        job_code = ?, job_title = ?, deadline = ?, job_dept = ?, job_location = ?,
        salary_range = ?, job_experience = ?, job_skillset = ?, job_description = ?,
        job_req = ?, job_benefits = ?, vacancy = ?, bdjobs_link = ?, apply_enabled = ?
      WHERE job_id = ?
    `;

    const params = [
      data.job_code,
      data.job_title,
      data.deadline,
      data.job_dept,
      data.job_location,
      data.salary_range,
      data.job_experience,
      data.job_skillset || null,
      data.job_description,
      data.job_req || null,
      data.job_benefits || null,
      data.vacancy || 1,
      data.bdjobs_link || null,
      data.apply_enabled ? 1 : 0,
      job_id
    ];

    const [result] = await db.query(query, params);
    return result.affectedRows > 0;
  }

  /**
   * Delete job circular
   */
  async deleteJob(job_id) {
    const [result] = await db.query('DELETE FROM jobs WHERE job_id = ?', [job_id]);
    return result.affectedRows > 0;
  }

  /**
   * Submit candidate application with CV file
   */
  async submitApplication({ jobId, job_title, fullname, email, phone_no, experience, cv_file }) {
    const query = `
      INSERT INTO cv_applications (jobId, job_title, fullname, email, phone_no, experience, cv_file)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    `;
    const [result] = await db.query(query, [
      jobId || null,
      job_title,
      fullname,
      email,
      phone_no,
      experience || null,
      cv_file
    ]);
    return result.insertId;
  }

  /**
   * Fetch candidate applications list for admin
   */
  async getApplications({ jobId = '', search = '' } = {}) {
    let query = 'SELECT * FROM cv_applications WHERE 1=1';
    const params = [];

    if (jobId) {
      query += ' AND jobId = ?';
      params.push(jobId);
    }

    if (search) {
      const searchTerm = `%${search.trim()}%`;
      query += ' AND (fullname LIKE ? OR email LIKE ? OR phone_no LIKE ? OR job_title LIKE ?)';
      params.push(searchTerm, searchTerm, searchTerm, searchTerm);
    }

    query += ' ORDER BY applicationId DESC';
    const [rows] = await db.query(query, params);
    return rows;
  }

  /**
   * Delete candidate application entry
   */
  async deleteApplication(applicationId) {
    const [result] = await db.query('DELETE FROM cv_applications WHERE applicationId = ?', [applicationId]);
    return result.affectedRows > 0;
  }
}

module.exports = new JobService();
