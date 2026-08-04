const multer = require('multer');
const path = require('path');
const fs = require('fs');

const cvDir = path.join(__dirname, '../../../uploads/cv');

if (!fs.existsSync(cvDir)) {
  fs.mkdirSync(cvDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, cvDir);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
    const ext = path.extname(file.originalname);
    cb(null, 'cv-' + uniqueSuffix + ext);
  }
});

const fileFilter = (req, file, cb) => {
  const allowedTypes = /pdf|doc|docx/;
  const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
  const mimetype = allowedTypes.test(file.mimetype) || file.mimetype.includes('document') || file.mimetype.includes('pdf');

  if (extname && mimetype) {
    return cb(null, true);
  } else {
    cb(new Error('Only PDF, DOC, and DOCX resume files are allowed!'));
  }
};

const cvUpload = multer({
  storage,
  limits: { fileSize: 15 * 1024 * 1024 }, // 15MB limit
  fileFilter
});

module.exports = cvUpload;
