const userService = require('../services/userService');

/**
 * Get paginated list of admin users
 */
const getUsers = async (req, res) => {
  try {
    const { page, limit, search, role, status } = req.query;
    const result = await userService.getUsers({ page, limit, search, role, status });

    return res.status(200).json({
      success: true,
      data: result.users,
      pagination: result.pagination
    });
  } catch (error) {
    console.error('Get Users Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve admin users list.'
    });
  }
};

/**
 * Get single admin user details by ID
 */
const getUserById = async (req, res) => {
  try {
    const { id } = req.params;
    const user = await userService.getUserById(id);

    if (!user) {
      return res.status(404).json({
        success: false,
        message: 'Admin user not found.'
      });
    }

    return res.status(200).json({
      success: true,
      user
    });
  } catch (error) {
    console.error('Get User By Id Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve admin user.'
    });
  }
};

/**
 * Create a new admin user
 */
const createUser = async (req, res) => {
  try {
    const { fullname, email, password, confirmPassword, role, status } = req.body;

    if (!fullname || !email || !password) {
      return res.status(400).json({
        success: false,
        message: 'Full name, email, and password are required.'
      });
    }

    if (confirmPassword && password !== confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'Passwords do not match.'
      });
    }

    if (password.length < 6) {
      return res.status(400).json({
        success: false,
        message: 'Password must be at least 6 characters long.'
      });
    }

    const userId = await userService.createUser({ fullname, email, password, role, status });

    return res.status(201).json({
      success: true,
      message: 'Admin user created successfully.',
      userId
    });
  } catch (error) {
    console.error('Create User Error:', error);
    return res.status(400).json({
      success: false,
      message: error.message || 'Failed to create admin user.'
    });
  }
};

/**
 * Update existing admin user
 */
const updateUser = async (req, res) => {
  try {
    const { id } = req.params;
    const { fullname, email, role, status, password } = req.body;

    if (!fullname || !email) {
      return res.status(400).json({
        success: false,
        message: 'Full name and email are required.'
      });
    }

    const updated = await userService.updateUser(id, { fullname, email, role, status, password });

    if (!updated) {
      return res.status(404).json({
        success: false,
        message: 'Admin user not found or no changes made.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Admin user updated successfully.'
    });
  } catch (error) {
    console.error('Update User Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to update admin user.'
    });
  }
};

/**
 * Delete admin user by ID (Preserving self-deletion prevention business rule)
 */
const deleteUser = async (req, res) => {
  try {
    const { id } = req.params;
    const currentAdminId = req.user.adminId;

    // Self-deletion check matching manageAdmins.php line 36
    if (parseInt(id, 10) === parseInt(currentAdminId, 10)) {
      return res.status(400).json({
        success: false,
        message: 'You cannot delete your own admin account.'
      });
    }

    const deleted = await userService.deleteUser(id);

    if (!deleted) {
      return res.status(404).json({
        success: false,
        message: 'Admin user not found or already deleted.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Admin user deleted successfully.'
    });
  } catch (error) {
    console.error('Delete User Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to delete admin user.'
    });
  }
};

module.exports = {
  getUsers,
  getUserById,
  createUser,
  updateUser,
  deleteUser
};
