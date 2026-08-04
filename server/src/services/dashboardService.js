const db = require('../config/db');

class DashboardService {
  /**
   * Fetch counts and top 5 items for all dashboard overview sections
   */
  async getOverview() {
    // 1. Available Jobs Count & top 5 items from jobCodes
    let total_jobs = 0;
    let jobs = [];
    try {
      const [[res]] = await db.query('SELECT COUNT(*) AS total FROM jobCodes');
      total_jobs = res ? res.total : 0;
      const [rows] = await db.query('SELECT * FROM jobCodes ORDER BY jobid DESC LIMIT 5');
      jobs = rows;
    } catch (e) {
      try {
        const [[res]] = await db.query('SELECT COUNT(*) AS total FROM jobs');
        total_jobs = res ? res.total : 0;
        const [rows] = await db.query('SELECT job_id AS jobid, job_title AS JobTitle, job_code AS JobCode FROM jobs ORDER BY job_id DESC LIMIT 5');
        jobs = rows;
      } catch (err) {
        // Table fallback
      }
    }

    // 2. Job Applications Count & top 5 items from cv_applications
    let total_applications = 0;
    let applications = [];
    try {
      const [[res]] = await db.query('SELECT COUNT(*) AS total FROM cv_applications');
      total_applications = res ? res.total : 0;
      const [rows] = await db.query('SELECT * FROM cv_applications ORDER BY applicationId DESC LIMIT 5');
      applications = rows;
    } catch (e) {
      // Table fallback
    }

    // 3. Job Sectors Count & top 5 items from sectors
    let total_sectors = 0;
    let sectors = [];
    try {
      const [[res]] = await db.query('SELECT COUNT(*) AS total FROM sectors');
      total_sectors = res ? res.total : 0;
      const [rows] = await db.query('SELECT * FROM sectors ORDER BY sector_id DESC LIMIT 5');
      sectors = rows;
    } catch (e) {
      // Table fallback
    }

    // 4. Content Overview Counts
    let total_news = 0;
    let total_notices = 0;
    let total_images = 0;

    try {
      const [[res]] = await db.query('SELECT COUNT(*) AS total FROM blogs');
      total_news = res ? res.total : 0;
    } catch (e) {}

    try {
      const [[res]] = await db.query('SELECT COUNT(*) AS total FROM pdsfiles');
      total_notices = res ? res.total : 0;
    } catch (e) {}

    try {
      const [[res]] = await db.query('SELECT COUNT(*) AS total FROM img_upload');
      total_images = res ? res.total : 0;
    } catch (e) {}

    // 5. Published News top 5 items
    let news = [];
    try {
      const [rows] = await db.query('SELECT * FROM blogs ORDER BY blog_id DESC LIMIT 5');
      news = rows;
    } catch (e) {}

    // 6. Uploaded Images top 5 items
    let images = [];
    try {
      const [rows] = await db.query('SELECT * FROM img_upload ORDER BY img_id DESC LIMIT 5');
      images = rows;
    } catch (e) {}

    // 7. Uploaded Notices top 5 items
    let notices = [];
    try {
      const [rows] = await db.query('SELECT * FROM pdsfiles ORDER BY pdf_id DESC LIMIT 5');
      notices = rows;
    } catch (e) {}

    return {
      stats: {
        total_jobs,
        total_applications,
        total_sectors,
        total_news,
        total_notices,
        total_images
      },
      jobs,
      applications,
      sectors,
      news,
      images,
      notices
    };
  }

  /**
   * Delete job position by jobid
   */
  async deleteJobPosition(jobid) {
    try {
      const [result] = await db.query('DELETE FROM jobCodes WHERE jobid = ?', [jobid]);
      return result.affectedRows > 0;
    } catch (e) {
      const [result] = await db.query('DELETE FROM jobs WHERE job_id = ?', [jobid]);
      return result.affectedRows > 0;
    }
  }

  /**
   * Delete sector by sector_id
   */
  async deleteSector(sector_id) {
    const [result] = await db.query('DELETE FROM sectors WHERE sector_id = ?', [sector_id]);
    return result.affectedRows > 0;
  }

  /**
   * Delete application by applicationId
   */
  async deleteApplication(applicationId) {
    const [result] = await db.query('DELETE FROM cv_applications WHERE applicationId = ?', [applicationId]);
    return result.affectedRows > 0;
  }

  /**
   * Delete news article by blog_id
   */
  async deleteNews(blog_id) {
    const [result] = await db.query('DELETE FROM blogs WHERE blog_id = ?', [blog_id]);
    return result.affectedRows > 0;
  }

  /**
   * Delete image by img_id
   */
  async deleteImage(img_id) {
    const [result] = await db.query('DELETE FROM img_upload WHERE img_id = ?', [img_id]);
    return result.affectedRows > 0;
  }

  /**
   * Delete notice PDF by pdf_id
   */
  async deleteNotice(pdf_id) {
    const [result] = await db.query('DELETE FROM pdsfiles WHERE pdf_id = ?', [pdf_id]);
    return result.affectedRows > 0;
  }
}

module.exports = new DashboardService();
