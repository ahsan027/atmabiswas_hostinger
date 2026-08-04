const officeService = require('../services/officeService');

/* ================= REGIONAL OFFICES ================= */
const getRegionalOffices = async (req, res) => {
  try {
    const { page, limit, search } = req.query;
    const result = await officeService.getRegionalOffices({ page, limit, search });
    return res.status(200).json({ success: true, data: result.offices, pagination: result.pagination });
  } catch (error) {
    console.error('Get Regional Offices Error:', error);
    return res.status(500).json({ success: false, message: 'Failed to retrieve regional offices.' });
  }
};

const getRegionalOfficeById = async (req, res) => {
  try {
    const { id } = req.params;
    const office = await officeService.getRegionalOfficeById(id);
    if (!office) return res.status(404).json({ success: false, message: 'Regional office not found.' });
    return res.status(200).json({ success: true, office });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to retrieve regional office.' });
  }
};

const createRegionalOffice = async (req, res) => {
  try {
    const { region_name, office_name, address, phone, email, incharge_name, incharge_designation, status } = req.body;
    if (!region_name || !address || !phone) {
      return res.status(400).json({ success: false, message: 'Region name, address, and phone are required.' });
    }
    const id = await officeService.createRegionalOffice({ region_name, office_name, address, phone, email, incharge_name, incharge_designation, status });
    return res.status(201).json({ success: true, message: 'Regional office created successfully.', id });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to create regional office.' });
  }
};

const updateRegionalOffice = async (req, res) => {
  try {
    const { id } = req.params;
    const { region_name, office_name, address, phone, email, incharge_name, incharge_designation, status } = req.body;
    const updated = await officeService.updateRegionalOffice(id, { region_name, office_name, address, phone, email, incharge_name, incharge_designation, status });
    if (!updated) return res.status(404).json({ success: false, message: 'Regional office not found or no changes made.' });
    return res.status(200).json({ success: true, message: 'Regional office updated successfully.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to update regional office.' });
  }
};

const toggleRegionalOfficeStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const updated = await officeService.toggleRegionalOfficeStatus(id);
    if (!updated) return res.status(404).json({ success: false, message: 'Regional office not found.' });
    return res.status(200).json({ success: true, message: 'Regional office status toggled.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to toggle status.' });
  }
};

const deleteRegionalOffice = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await officeService.deleteRegionalOffice(id);
    if (!deleted) return res.status(404).json({ success: false, message: 'Regional office not found.' });
    return res.status(200).json({ success: true, message: 'Regional office deleted successfully.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to delete regional office.' });
  }
};

/* ================= BRANCHES ================= */
const getBranches = async (req, res) => {
  try {
    const { page, limit, search, division } = req.query;
    const result = await officeService.getBranches({ page, limit, search, division });
    return res.status(200).json({ success: true, data: result.branches, pagination: result.pagination });
  } catch (error) {
    console.error('Get Branches Error:', error);
    return res.status(500).json({ success: false, message: 'Failed to retrieve branches.' });
  }
};

const getBranchById = async (req, res) => {
  try {
    const { id } = req.params;
    const branch = await officeService.getBranchById(id);
    if (!branch) return res.status(404).json({ success: false, message: 'Branch not found.' });
    return res.status(200).json({ success: true, branch });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to retrieve branch.' });
  }
};

const createBranch = async (req, res) => {
  try {
    const { branch_code, branch_name, division, district, address, phone, email, manager_name, status } = req.body;
    if (!branch_name || !division || !address || !phone) {
      return res.status(400).json({ success: false, message: 'Branch name, division, address, and phone are required.' });
    }
    const id = await officeService.createBranch({ branch_code, branch_name, division, district, address, phone, email, manager_name, status });
    return res.status(201).json({ success: true, message: 'Branch created successfully.', id });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to create branch.' });
  }
};

const updateBranch = async (req, res) => {
  try {
    const { id } = req.params;
    const { branch_code, branch_name, division, district, address, phone, email, manager_name, status } = req.body;
    const updated = await officeService.updateBranch(id, { branch_code, branch_name, division, district, address, phone, email, manager_name, status });
    if (!updated) return res.status(404).json({ success: false, message: 'Branch not found or no changes made.' });
    return res.status(200).json({ success: true, message: 'Branch updated successfully.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to update branch.' });
  }
};

const toggleBranchStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const updated = await officeService.toggleBranchStatus(id);
    if (!updated) return res.status(404).json({ success: false, message: 'Branch not found.' });
    return res.status(200).json({ success: true, message: 'Branch status toggled.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to toggle status.' });
  }
};

const deleteBranch = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await officeService.deleteBranch(id);
    if (!deleted) return res.status(404).json({ success: false, message: 'Branch not found.' });
    return res.status(200).json({ success: true, message: 'Branch deleted successfully.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to delete branch.' });
  }
};

/* ================= DIVISIONS ================= */
const getDivisions = async (req, res) => {
  try {
    const divisions = await officeService.getDivisions();
    return res.status(200).json({ success: true, divisions });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to retrieve divisions.' });
  }
};

const createDivision = async (req, res) => {
  try {
    const { name } = req.body;
    if (!name) return res.status(400).json({ success: false, message: 'Division name is required.' });
    const id = await officeService.createDivision(name);
    return res.status(201).json({ success: true, message: 'Division created successfully.', id });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to create division.' });
  }
};

const toggleDivisionStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const updated = await officeService.toggleDivisionStatus(id);
    if (!updated) return res.status(404).json({ success: false, message: 'Division not found.' });
    return res.status(200).json({ success: true, message: 'Division status toggled.' });
  } catch (error) {
    return res.status(500).json({ success: false, message: 'Failed to toggle division status.' });
  }
};

const deleteDivision = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await officeService.deleteDivision(id);
    if (!deleted) return res.status(404).json({ success: false, message: 'Division not found.' });
    return res.status(200).json({ success: true, message: 'Division deleted successfully.' });
  } catch (error) {
    return res.status(400).json({ success: false, message: error.message || 'Failed to delete division.' });
  }
};

module.exports = {
  getRegionalOffices, getRegionalOfficeById, createRegionalOffice, updateRegionalOffice, toggleRegionalOfficeStatus, deleteRegionalOffice,
  getBranches, getBranchById, createBranch, updateBranch, toggleBranchStatus, deleteBranch,
  getDivisions, createDivision, toggleDivisionStatus, deleteDivision
};
