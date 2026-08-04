const express = require('express');
const router = express.Router();
const jobController = require('../controllers/jobController');
const { authenticateToken } = require('../middleware/authMiddleware');
const cvUpload = require('../middleware/cvUploadMiddleware');

// Public Careers & Jobs Endpoints
router.get('/', jobController.getJobs);
router.get('/metadata', jobController.getMetaData);
router.get('/:id', jobController.getJobById);
router.post('/apply', cvUpload.single('cv_file'), jobController.submitApplication);

// Protected Admin Endpoints
router.post('/', authenticateToken, jobController.createJob);
router.put('/:id', authenticateToken, jobController.updateJob);
router.delete('/:id', authenticateToken, jobController.deleteJob);
router.post('/positions', authenticateToken, jobController.createJobPosition);

// Candidate Applications Admin Endpoints
router.get('/applications/all', authenticateToken, jobController.getApplications);
router.delete('/applications/:id', authenticateToken, jobController.deleteApplication);

module.exports = router;
