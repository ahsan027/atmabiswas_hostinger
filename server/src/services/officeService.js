const db = require('../config/db');

class OfficeService {
  /* ================= REGIONAL OFFICES ================= */
  async getRegionalOffices({ page = 1, limit = 10, search = '' }) {
    const offset = (parseInt(page, 10) - 1) * parseInt(limit, 10);
    const parsedLimit = parseInt(limit, 10);

    let query = 'SELECT * FROM regional_offices WHERE 1=1';
    let countQuery = 'SELECT COUNT(*) AS total FROM regional_offices WHERE 1=1';
    const params = [];
    const countParams = [];

    if (search && search.trim() !== '') {
      const searchTerm = `%${search.trim()}%`;
      query += ' AND (region_name LIKE ? OR address LIKE ? OR phone LIKE ? OR incharge_name LIKE ?)';
      countQuery += ' AND (region_name LIKE ? OR address LIKE ? OR phone LIKE ? OR incharge_name LIKE ?)';
      params.push(searchTerm, searchTerm, searchTerm, searchTerm);
      countParams.push(searchTerm, searchTerm, searchTerm, searchTerm);
    }

    query += ' ORDER BY id ASC LIMIT ? OFFSET ?';
    params.push(parsedLimit, offset);

    const [[{ total }]] = await db.query(countQuery, countParams);
    const [offices] = await db.query(query, params);

    return {
      offices,
      pagination: {
        total,
        page: parseInt(page, 10),
        limit: parsedLimit,
        totalPages: Math.ceil(total / parsedLimit) || 1
      }
    };
  }

  async getRegionalOfficeById(id) {
    const [rows] = await db.query('SELECT * FROM regional_offices WHERE id = ?', [id]);
    return rows[0] || null;
  }

  async createRegionalOffice(data) {
    const query = `
      INSERT INTO regional_offices (region_name, office_name, address, phone, email, incharge_name, incharge_designation, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    `;
    const params = [
      data.region_name,
      data.office_name || null,
      data.address,
      data.phone,
      data.email || null,
      data.incharge_name || null,
      data.incharge_designation || null,
      data.status !== undefined ? (data.status ? 1 : 0) : 1
    ];
    const [result] = await db.query(query, params);
    return result.insertId;
  }

  async updateRegionalOffice(id, data) {
    const query = `
      UPDATE regional_offices SET
        region_name = ?, office_name = ?, address = ?, phone = ?, email = ?,
        incharge_name = ?, incharge_designation = ?, status = ?
      WHERE id = ?
    `;
    const params = [
      data.region_name,
      data.office_name || null,
      data.address,
      data.phone,
      data.email || null,
      data.incharge_name || null,
      data.incharge_designation || null,
      data.status !== undefined ? (data.status ? 1 : 0) : 1,
      id
    ];
    const [result] = await db.query(query, params);
    return result.affectedRows > 0;
  }

  async toggleRegionalOfficeStatus(id) {
    const [result] = await db.query('UPDATE regional_offices SET status = IF(status = 1, 0, 1) WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }

  async deleteRegionalOffice(id) {
    const [result] = await db.query('DELETE FROM regional_offices WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }

  /* ================= BRANCHES ================= */
  async getBranches({ page = 1, limit = 10, search = '', division = '' }) {
    const offset = (parseInt(page, 10) - 1) * parseInt(limit, 10);
    const parsedLimit = parseInt(limit, 10);

    let query = 'SELECT * FROM branches WHERE 1=1';
    let countQuery = 'SELECT COUNT(*) AS total FROM branches WHERE 1=1';
    const params = [];
    const countParams = [];

    if (search && search.trim() !== '') {
      const searchTerm = `%${search.trim()}%`;
      query += ' AND (branch_name LIKE ? OR address LIKE ? OR district LIKE ? OR manager_name LIKE ?)';
      countQuery += ' AND (branch_name LIKE ? OR address LIKE ? OR district LIKE ? OR manager_name LIKE ?)';
      params.push(searchTerm, searchTerm, searchTerm, searchTerm);
      countParams.push(searchTerm, searchTerm, searchTerm, searchTerm);
    }

    if (division && division.trim() !== '') {
      query += ' AND division = ?';
      countQuery += ' AND division = ?';
      params.push(division.trim());
      countParams.push(division.trim());
    }

    query += ' ORDER BY division ASC, branch_name ASC LIMIT ? OFFSET ?';
    params.push(parsedLimit, offset);

    const [[{ total }]] = await db.query(countQuery, countParams);
    const [branches] = await db.query(query, params);

    return {
      branches,
      pagination: {
        total,
        page: parseInt(page, 10),
        limit: parsedLimit,
        totalPages: Math.ceil(total / parsedLimit) || 1
      }
    };
  }

  async getBranchById(id) {
    const [rows] = await db.query('SELECT * FROM branches WHERE id = ?', [id]);
    return rows[0] || null;
  }

  async createBranch(data) {
    const query = `
      INSERT INTO branches (branch_code, branch_name, division, district, address, phone, email, manager_name, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;
    const params = [
      data.branch_code || null,
      data.branch_name,
      data.division,
      data.district || null,
      data.address,
      data.phone,
      data.email || null,
      data.manager_name || null,
      data.status !== undefined ? (data.status ? 1 : 0) : 1
    ];
    const [result] = await db.query(query, params);
    return result.insertId;
  }

  async updateBranch(id, data) {
    const query = `
      UPDATE branches SET
        branch_code = ?, branch_name = ?, division = ?, district = ?, address = ?,
        phone = ?, email = ?, manager_name = ?, status = ?
      WHERE id = ?
    `;
    const params = [
      data.branch_code || null,
      data.branch_name,
      data.division,
      data.district || null,
      data.address,
      data.phone,
      data.email || null,
      data.manager_name || null,
      data.status !== undefined ? (data.status ? 1 : 0) : 1,
      id
    ];
    const [result] = await db.query(query, params);
    return result.affectedRows > 0;
  }

  async toggleBranchStatus(id) {
    const [result] = await db.query('UPDATE branches SET status = IF(status = 1, 0, 1) WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }

  async deleteBranch(id) {
    const [result] = await db.query('DELETE FROM branches WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }

  /* ================= DIVISIONS ================= */
  async getDivisions() {
    const query = `
      SELECT d.id, d.name, d.status,
             (SELECT COUNT(*) FROM branches WHERE division = d.name) AS branch_count
      FROM divisions d
      ORDER BY d.name ASC
    `;
    const [rows] = await db.query(query);
    return rows;
  }

  async createDivision(name) {
    const [result] = await db.query('INSERT INTO divisions (name, status) VALUES (?, 1)', [name]);
    return result.insertId;
  }

  async toggleDivisionStatus(id) {
    const [result] = await db.query('UPDATE divisions SET status = IF(status = 1, 0, 1) WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }

  async deleteDivision(id) {
    // Check branch dependency
    const [[div]] = await db.query('SELECT name FROM divisions WHERE id = ?', [id]);
    if (div && div.name) {
      const [[{ branch_count }]] = await db.query('SELECT COUNT(*) AS branch_count FROM branches WHERE division = ?', [div.name]);
      if (branch_count > 0) {
        throw new Error(`Cannot delete: ${branch_count} branch(es) belong to this division. Reassign branches first.`);
      }
    }

    const [result] = await db.query('DELETE FROM divisions WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }
}

module.exports = new OfficeService();
