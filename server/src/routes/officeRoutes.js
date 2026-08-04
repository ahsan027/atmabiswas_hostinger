const express = require('express');
const router = express.Router();
const officeController = require('../controllers/officeController');
const { authenticateToken } = require('../middleware/authMiddleware');

// Public Read Endpoints
router.get('/regional', officeController.getRegionalOffices);
router.get('/regional/:id', officeController.getRegionalOfficeById);
router.get('/branches', officeController.getBranches);
router.get('/branches/:id', officeController.getBranchById);
router.get('/divisions', officeController.getDivisions);

// Protected Admin Endpoints (JWT Guard)
router.post('/regional', authenticateToken, officeController.createRegionalOffice);
router.put('/regional/:id', authenticateToken, officeController.updateRegionalOffice);
router.patch('/regional/:id/toggle', authenticateToken, officeController.toggleRegionalOfficeStatus);
router.delete('/regional/:id', authenticateToken, officeController.deleteRegionalOffice);

router.post('/branches', authenticateToken, officeController.createBranch);
router.put('/branches/:id', authenticateToken, officeController.updateBranch);
router.patch('/branches/:id/toggle', authenticateToken, officeController.toggleBranchStatus);
router.delete('/branches/:id', authenticateToken, officeController.deleteBranch);

router.post('/divisions', authenticateToken, officeController.createDivision);
router.patch('/divisions/:id/toggle', authenticateToken, officeController.toggleDivisionStatus);
router.delete('/divisions/:id', authenticateToken, officeController.deleteDivision);

module.exports = router;
