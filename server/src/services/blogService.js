const db = require('../config/db');

class BlogService {
  /**
   * Calculate reading time in minutes based on content word count
   */
  calculateReadingTime(content = '') {
    const text = content.replace(/<[^>]*>/g, '').trim();
    const wordCount = text ? text.split(/\s+/).length : 0;
    return Math.max(1, Math.ceil(wordCount / 200));
  }

  /**
   * Generate URL friendly slug from title
   */
  generateSlug(title = '') {
    return title
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_-]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }

  /**
   * Fetch paginated list of blog/press articles with search and filters
   */
  async getBlogs({ page = 1, limit = 10, search = '', category = '', status = '', featured = '' }) {
    const offset = (parseInt(page, 10) - 1) * parseInt(limit, 10);
    const parsedLimit = parseInt(limit, 10);

    let query = 'SELECT * FROM blogs WHERE 1=1';
    let countQuery = 'SELECT COUNT(*) AS total FROM blogs WHERE 1=1';
    const params = [];
    const countParams = [];

    if (search && search.trim() !== '') {
      const searchTerm = `%${search.trim()}%`;
      query += ' AND (blog_title LIKE ? OR blog_content LIKE ? OR summary LIKE ?)';
      countQuery += ' AND (blog_title LIKE ? OR blog_content LIKE ? OR summary LIKE ?)';
      params.push(searchTerm, searchTerm, searchTerm);
      countParams.push(searchTerm, searchTerm, searchTerm);
    }

    if (category && category.trim() !== '') {
      query += ' AND category = ?';
      countQuery += ' AND category = ?';
      params.push(category.trim());
      countParams.push(category.trim());
    }

    if (status && status.trim() !== '') {
      query += ' AND status = ?';
      countQuery += ' AND status = ?';
      params.push(status.trim());
      countParams.push(status.trim());
    }

    if (featured !== '' && featured !== undefined) {
      query += ' AND featured = ?';
      countQuery += ' AND featured = ?';
      params.push(parseInt(featured, 10));
      countParams.push(parseInt(featured, 10));
    }

    query += ' ORDER BY blog_id DESC LIMIT ? OFFSET ?';
    params.push(parsedLimit, offset);

    const [[{ total }]] = await db.query(countQuery, countParams);
    const [blogs] = await db.query(query, params);

    return {
      blogs,
      pagination: {
        total,
        page: parseInt(page, 10),
        limit: parsedLimit,
        totalPages: Math.ceil(total / parsedLimit) || 1
      }
    };
  }

  /**
   * Get single blog article by blog_id
   */
  async getBlogById(blog_id) {
    const [rows] = await db.query('SELECT * FROM blogs WHERE blog_id = ?', [blog_id]);
    return rows[0] || null;
  }

  /**
   * Create new blog article
   */
  async createBlog(data) {
    const readingTime = this.calculateReadingTime(data.blog_content);
    const slug = data.slug || this.generateSlug(data.blog_title);

    const query = `
      INSERT INTO blogs (
        blog_title, blog_content, blog_author, cover_img, summary, year, category,
        source_link, slug, status, featured, tags, reading_time, seo_title,
        seo_description, seo_keywords, focus_keyword, canonical_url, social_image
      ) VALUES (?, ?, ?, ?, ?, YEAR(CURRENT_DATE), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;

    const params = [
      data.blog_title,
      data.blog_content,
      data.blog_author || 'ATMABISWAS',
      data.cover_img || null,
      data.summary || null,
      data.category || 'news',
      data.source_link || null,
      slug,
      data.status || 'published',
      data.featured ? 1 : 0,
      data.tags || null,
      readingTime,
      data.seo_title || null,
      data.seo_description || null,
      data.seo_keywords || null,
      data.focus_keyword || null,
      data.canonical_url || null,
      data.social_image || null
    ];

    const [result] = await db.query(query, params);
    return result.insertId;
  }

  /**
   * Update existing blog article
   */
  async updateBlog(blog_id, data) {
    const readingTime = this.calculateReadingTime(data.blog_content);
    const slug = data.slug || this.generateSlug(data.blog_title);

    let query = `
      UPDATE blogs SET
        blog_title = ?, blog_content = ?, blog_author = ?, summary = ?, category = ?,
        source_link = ?, slug = ?, status = ?, featured = ?, tags = ?, reading_time = ?,
        seo_title = ?, seo_description = ?, seo_keywords = ?, focus_keyword = ?,
        canonical_url = ?, social_image = ?
    `;

    const params = [
      data.blog_title,
      data.blog_content,
      data.blog_author || 'ATMABISWAS',
      data.summary || null,
      data.category || 'news',
      data.source_link || null,
      slug,
      data.status || 'published',
      data.featured ? 1 : 0,
      data.tags || null,
      readingTime,
      data.seo_title || null,
      data.seo_description || null,
      data.seo_keywords || null,
      data.focus_keyword || null,
      data.canonical_url || null,
      data.social_image || null
    ];

    if (data.cover_img !== undefined) {
      query += `, cover_img = ?`;
      params.push(data.cover_img);
    }

    query += ` WHERE blog_id = ?`;
    params.push(blog_id);

    const [result] = await db.query(query, params);
    return result.affectedRows > 0;
  }

  /**
   * Toggle featured status for blog
   */
  async toggleFeatured(blog_id) {
    const [result] = await db.query(
      'UPDATE blogs SET featured = CASE WHEN featured = 1 THEN 0 ELSE 1 END WHERE blog_id = ?',
      [blog_id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Toggle draft/published status for blog
   */
  async toggleStatus(blog_id) {
    const [result] = await db.query(
      "UPDATE blogs SET status = CASE WHEN status = 'draft' THEN 'published' ELSE 'draft' END WHERE blog_id = ?",
      [blog_id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Delete blog article
   */
  async deleteBlog(blog_id) {
    const [result] = await db.query('DELETE FROM blogs WHERE blog_id = ?', [blog_id]);
    return result.affectedRows > 0;
  }
}

module.exports = new BlogService();
