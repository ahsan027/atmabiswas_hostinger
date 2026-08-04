const path = require('path');
const authRoutes = require('./routes/authRoutes');
const dashboardRoutes = require('./routes/dashboardRoutes');
const userRoutes = require('./routes/userRoutes');

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors({
  origin: process.env.CLIENT_URL || 'http://localhost:5173',
  credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve existing uploaded files and media directories without modifying files
app.use('/uploads', express.static(path.join(__dirname, '../../uploads')));
app.use('/pdf', express.static(path.join(__dirname, '../../pdf')));
app.use('/LOGO', express.static(path.join(__dirname, '../../LOGO')));
app.use('/Photos', express.static(path.join(__dirname, '../../Photos')));

// API Routes
app.use('/api/v1/auth', authRoutes);
app.use('/api/v1/dashboard', dashboardRoutes);
app.use('/api/v1/users', userRoutes);

// Health check endpoint
app.get('/api/health', (req, res) => {
  res.json({
    status: 'OK',
    timestamp: new Date(),
    service: 'ATMABISWAS Express API'
  });
});

// 404 Handler
app.use((req, res) => {
  res.status(404).json({
    success: false,
    message: 'Endpoint not found.'
  });
});

// Error Handler
app.use((err, req, res, next) => {
  console.error('Unhandled Error:', err);
  res.status(500).json({
    success: false,
    message: 'Internal Server Error.'
  });
});

// Start Server if not imported by test
if (require.main === module) {
  app.listen(PORT, () => {
    console.log(`🚀 ATMABISWAS Express API running on port ${PORT}`);
  });
}

module.exports = app;
