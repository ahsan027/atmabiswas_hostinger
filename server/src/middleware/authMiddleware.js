const { verifyToken } = require('../utils/token');

/**
 * Middleware to authenticate requests using JWT Bearer token
 */
const authenticateToken = (req, res, next) => {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.startsWith('Bearer ') ? authHeader.split(' ')[1] : null;

  if (!token) {
    return res.status(401).json({
      success: false,
      message: 'Access denied. Authentication token required.'
    });
  }

  const decoded = verifyToken(token);
  if (!decoded) {
    return res.status(403).json({
      success: false,
      message: 'Invalid or expired authentication token.'
    });
  }

  req.user = decoded;
  next();
};

module.exports = {
  authenticateToken
};
