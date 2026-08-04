const express = require('express');
const router = express.Router();
const blogController = require('../controllers/blogController');
const { authenticateToken } = require('../middleware/authMiddleware');
const upload = require('../middleware/uploadMiddleware');

// Public endpoints (for public press page listing)
router.get('/', blogController.getBlogs);
router.get('/:id', blogController.getBlogById);

// Protected endpoints (Requires JWT Token)
router.post('/', authenticateToken, upload.single('cover_img'), blogController.createBlog);
router.put('/:id', authenticateToken, upload.single('cover_img'), blogController.updateBlog);
router.patch('/:id/featured', authenticateToken, blogController.toggleFeatured);
router.patch('/:id/status', authenticateToken, blogController.toggleStatus);
router.delete('/:id', authenticateToken, blogController.deleteBlog);

module.exports = router;
