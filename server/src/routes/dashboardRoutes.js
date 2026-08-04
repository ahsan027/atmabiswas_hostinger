const express = require('express');
const router = express.Router();
const dashboardController = require('../controllers/dashboardController');
const { authenticateToken } = require('../middleware/authMiddleware');

// Protected Dashboard API endpoints
router.get('/overview', authenticateToken, dashboardController.getOverview);
router.delete('/:type/:id', authenticateToken, dashboardController.deleteItem);

module.exports = router;
