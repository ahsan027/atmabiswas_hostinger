const db = require('../config/db');

class AuthService {
  /**
   * Find admin by email or username
   */
  async findByEmail(email) {
    const [rows] = await db.query(
      'SELECT adminId, fullname, email, pswd FROM admins WHERE email = ? OR username = ?',
      [email, email]
    );
    return rows[0] || null;
  }

  /**
   * Find admin by ID
   */
  async findById(adminId) {
    const [rows] = await db.query(
      'SELECT adminId, fullname, email FROM admins WHERE adminId = ?',
      [adminId]
    );
    return rows[0] || null;
  }

  /**
   * Find admin with password hash by ID
   */
  async findByIdWithPassword(adminId) {
    const [rows] = await db.query(
      'SELECT adminId, fullname, email, pswd FROM admins WHERE adminId = ?',
      [adminId]
    );
    return rows[0] || null;
  }

  /**
   * Update admin password by adminId
   */
  async updatePassword(adminId, hashedPassword) {
    const [result] = await db.query(
      'UPDATE admins SET pswd = ? WHERE adminId = ?',
      [hashedPassword, adminId]
    );
    return result.affectedRows > 0;
  }

  /**
   * Update admin profile information
   */
  async updateProfile(adminId, fullname, email) {
    const [result] = await db.query(
      'UPDATE admins SET fullname = ?, email = ?, username = ? WHERE adminId = ?',
      [fullname, email, email, adminId]
    );
    return result.affectedRows > 0;
  }

  /**
   * Store password reset token for admin
   */
  async setResetToken(email, token) {
    const [result] = await db.query(
      'UPDATE admins SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ? OR username = ?',
      [token, email, email]
    );
    return result.affectedRows > 0;
  }

  /**
   * Find admin by valid reset token
   */
  async findByResetToken(token) {
    const [rows] = await db.query(
      'SELECT adminId, fullname, email FROM admins WHERE reset_token = ? AND reset_token_expires > NOW()',
      [token]
    );
    return rows[0] || null;
  }

  /**
   * Clear reset token after password reset
   */
  async clearResetToken(adminId) {
    const [result] = await db.query(
      'UPDATE admins SET reset_token = NULL, reset_token_expires = NULL WHERE adminId = ?',
      [adminId]
    );
    return result.affectedRows > 0;
  }
}

module.exports = new AuthService();
