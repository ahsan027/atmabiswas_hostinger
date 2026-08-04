const blogService = require('../services/blogService');

/**
 * Get paginated list of blogs / press articles
 */
const getBlogs = async (req, res) => {
  try {
    const { page, limit, search, category, status, featured } = req.query;
    const result = await blogService.getBlogs({ page, limit, search, category, status, featured });

    return res.status(200).json({
      success: true,
      data: result.blogs,
      pagination: result.pagination
    });
  } catch (error) {
    console.error('Get Blogs Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve blogs list.'
    });
  }
};

/**
 * Get single blog article by ID
 */
const getBlogById = async (req, res) => {
  try {
    const { id } = req.params;
    const blog = await blogService.getBlogById(id);

    if (!blog) {
      return res.status(404).json({
        success: false,
        message: 'Blog article not found.'
      });
    }

    return res.status(200).json({
      success: true,
      blog
    });
  } catch (error) {
    console.error('Get Blog By Id Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve blog article.'
    });
  }
};

/**
 * Create new blog article
 */
const createBlog = async (req, res) => {
  try {
    const { blog_title, blog_content, blog_author, summary, category, source_link, status, featured, tags, seo_title, seo_description, seo_keywords, focus_keyword, canonical_url, social_image } = req.body;

    if (!blog_title || !blog_content) {
      return res.status(400).json({
        success: false,
        message: 'Article title and content are required.'
      });
    }

    let cover_img = req.body.cover_img || null;
    if (req.file) {
      cover_img = `/uploads/${req.file.filename}`;
    }

    const blog_id = await blogService.createBlog({
      blog_title,
      blog_content,
      blog_author,
      cover_img,
      summary,
      category,
      source_link,
      status,
      featured: featured === 'true' || featured === true || featured === '1' || featured === 1,
      tags,
      seo_title,
      seo_description,
      seo_keywords,
      focus_keyword,
      canonical_url,
      social_image
    });

    return res.status(201).json({
      success: true,
      message: 'Blog article published successfully.',
      blog_id
    });
  } catch (error) {
    console.error('Create Blog Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to create blog article.'
    });
  }
};

/**
 * Update existing blog article
 */
const updateBlog = async (req, res) => {
  try {
    const { id } = req.params;
    const { blog_title, blog_content, blog_author, summary, category, source_link, status, featured, tags, seo_title, seo_description, seo_keywords, focus_keyword, canonical_url, social_image } = req.body;

    if (!blog_title || !blog_content) {
      return res.status(400).json({
        success: false,
        message: 'Article title and content are required.'
      });
    }

    const data = {
      blog_title,
      blog_content,
      blog_author,
      summary,
      category,
      source_link,
      status,
      featured: featured === 'true' || featured === true || featured === '1' || featured === 1,
      tags,
      seo_title,
      seo_description,
      seo_keywords,
      focus_keyword,
      canonical_url,
      social_image
    };

    if (req.file) {
      data.cover_img = `/uploads/${req.file.filename}`;
    } else if (req.body.cover_img !== undefined) {
      data.cover_img = req.body.cover_img;
    }

    const updated = await blogService.updateBlog(id, data);

    if (!updated) {
      return res.status(404).json({
        success: false,
        message: 'Blog article not found or no changes made.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Blog article updated successfully.'
    });
  } catch (error) {
    console.error('Update Blog Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to update blog article.'
    });
  }
};

/**
 * Fast toggle featured status
 */
const toggleFeatured = async (req, res) => {
  try {
    const { id } = req.params;
    const updated = await blogService.toggleFeatured(id);

    if (!updated) {
      return res.status(404).json({
        success: false,
        message: 'Blog article not found.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Featured status toggled successfully.'
    });
  } catch (error) {
    console.error('Toggle Featured Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to toggle featured status.'
    });
  }
};

/**
 * Fast toggle draft/published status
 */
const toggleStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const updated = await blogService.toggleStatus(id);

    if (!updated) {
      return res.status(404).json({
        success: false,
        message: 'Blog article not found.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Article status toggled successfully.'
    });
  } catch (error) {
    console.error('Toggle Status Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to toggle article status.'
    });
  }
};

/**
 * Delete blog article
 */
const deleteBlog = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await blogService.deleteBlog(id);

    if (!deleted) {
      return res.status(404).json({
        success: false,
        message: 'Blog article not found or already deleted.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Blog article deleted successfully.'
    });
  } catch (error) {
    console.error('Delete Blog Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to delete blog article.'
    });
  }
};

module.exports = {
  getBlogs,
  getBlogById,
  createBlog,
  updateBlog,
  toggleFeatured,
  toggleStatus,
  deleteBlog
};
