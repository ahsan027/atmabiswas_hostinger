const bcrypt = require('bcryptjs');

/**
 * Hash plain text password using bcrypt
 */
const hashPassword = async (password) => {
  const salt = await bcrypt.genSalt(10);
  return bcrypt.hash(password, salt);
};

/**
 * Compare plain text password against hashed or plain text password
 */
const comparePassword = async (password, storedPassword) => {
  if (!password || !storedPassword) return false;

  // Direct match if legacy plain text password
  if (password === storedPassword) return true;

  // Check if storedPassword is bcrypt format ($2a$, $2b$, $2y$)
  if (storedPassword.startsWith('$2a$') || storedPassword.startsWith('$2b$') || storedPassword.startsWith('$2y$')) {
    const formattedHash = storedPassword.replace(/^\$2y\$/, '$2a$');
    return bcrypt.compare(password, formattedHash);
  }

  return false;
};

module.exports = {
  hashPassword,
  comparePassword
};
