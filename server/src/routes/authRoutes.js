const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { authenticateToken } = require('../middleware/authMiddleware');
const { checkRole } = require('../middleware/roleMiddleware');

// Public auth endpoints
router.post('/login', authController.login);
router.post('/logout', authController.logout);
router.post('/forgot-password', authController.forgotPassword);
router.post('/reset-password', authController.resetPassword);

// Protected auth endpoints (Requires JWT Token)
router.get('/profile', authenticateToken, authController.getProfile);
router.put('/profile', authenticateToken, authController.updateProfile);
router.post('/change-password', authenticateToken, authController.changePassword);

// Role permission check endpoint example
router.get('/check-role', authenticateToken, checkRole(['admin', 'superadmin']), (req, res) => {
  res.json({
    success: true,
    message: 'Authorized role access verified.',
    user: req.user
  });
});

module.exports = router;
