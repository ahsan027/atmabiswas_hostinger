const db = require('../config/db');

class UserService {
  /**
   * Fetch paginated list of admins with search and filter support
   */
  async getUsers({ page = 1, limit = 10, search = '', role = '', status = '' }) {
    const offset = (parseInt(page, 10) - 1) * parseInt(limit, 10);
    const parsedLimit = parseInt(limit, 10);

    let query = 'SELECT adminId, fullname, email, username, role, status, created_at FROM admins WHERE 1=1';
    let countQuery = 'SELECT COUNT(*) AS total FROM admins WHERE 1=1';
    const params = [];
    const countParams = [];

    if (search && search.trim() !== '') {
      const searchTerm = `%${search.trim()}%`;
      query += ' AND (fullname LIKE ? OR email LIKE ? OR username LIKE ?)';
      countQuery += ' AND (fullname LIKE ? OR email LIKE ? OR username LIKE ?)';
      params.push(searchTerm, searchTerm, searchTerm);
      countParams.push(searchTerm, searchTerm, searchTerm);
    }

    if (role && role.trim() !== '') {
      query += ' AND role = ?';
      countQuery += ' AND role = ?';
      params.push(role.trim());
      countParams.push(role.trim());
    }

    if (status && status.trim() !== '') {
      query += ' AND status = ?';
      countQuery += ' AND status = ?';
      params.push(status.trim());
      countParams.push(status.trim());
    }

    query += ' ORDER BY adminId DESC LIMIT ? OFFSET ?';
    params.push(parsedLimit, offset);

    const [[{ total }]] = await db.query(countQuery, countParams);
    const [users] = await db.query(query, params);

    return {
      users,
      pagination: {
        total,
        page: parseInt(page, 10),
        limit: parsedLimit,
        totalPages: Math.ceil(total / parsedLimit) || 1
      }
    };
  }

  /**
   * Find admin user by ID
   */
  async getUserById(adminId) {
    const [rows] = await db.query(
      'SELECT adminId, fullname, email, username, role, status, created_at FROM admins WHERE adminId = ?',
      [adminId]
    );
    return rows[0] || null;
  }

  /**
   * Create new admin user
   */
  async createUser({ fullname, email, password, role = 'admin', status = 'active' }) {
    // Check duplicate
    const [existing] = await db.query(
      'SELECT adminId FROM admins WHERE email = ? OR username = ?',
      [email, email]
    );

    if (existing.length > 0) {
      throw new Error('An admin user with this email address already exists.');
    }

    const { hashPassword } = require('../utils/hash');
    const hashedPassword = await hashPassword(password);

    const [result] = await db.query(
      'INSERT INTO admins (fullname, email, username, pswd, role, status) VALUES (?, ?, ?, ?, ?, ?)',
      [fullname, email, email, hashedPassword, role, status]
    );

    return result.insertId;
  }

  /**
   * Update existing admin user details
   */
  async updateUser(adminId, { fullname, email, role, status, password }) {
    let query = 'UPDATE admins SET fullname = ?, email = ?, username = ?, role = ?, status = ?';
    const params = [fullname, email, email, role, status];

    if (password && password.trim().length >= 6) {
      const { hashPassword } = require('../utils/hash');
      const hashedPassword = await hashPassword(password.trim());
      query += ', pswd = ?';
      params.push(hashedPassword);
    }

    query += ' WHERE adminId = ?';
    params.push(adminId);

    const [result] = await db.query(query, params);
    return result.affectedRows > 0;
  }

  /**
   * Delete admin user by ID
   */
  async deleteUser(adminId) {
    const [result] = await db.query('DELETE FROM admins WHERE adminId = ?', [adminId]);
    return result.affectedRows > 0;
  }
}

module.exports = new UserService();
