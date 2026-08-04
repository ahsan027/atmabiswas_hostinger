const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const { authenticateToken } = require('../middleware/authMiddleware');
const { checkRole } = require('../middleware/roleMiddleware');

// Protected Admin User Management routes (JWT + Role check)
router.get('/', authenticateToken, checkRole(['admin', 'superadmin']), userController.getUsers);
router.get('/:id', authenticateToken, checkRole(['admin', 'superadmin']), userController.getUserById);
router.post('/', authenticateToken, checkRole(['admin', 'superadmin']), userController.createUser);
router.put('/:id', authenticateToken, checkRole(['admin', 'superadmin']), userController.updateUser);
router.delete('/:id', authenticateToken, checkRole(['admin', 'superadmin']), userController.deleteUser);

module.exports = router;
