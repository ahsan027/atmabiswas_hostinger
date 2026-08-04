const crypto = require('crypto');
const authService = require('../services/authService');
const { comparePassword, hashPassword } = require('../utils/hash');
const { generateToken } = require('../utils/token');

/**
 * Login admin user
 */
const login = async (req, res) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({
        success: false,
        message: 'Email and password are required.'
      });
    }

    const user = await authService.findByEmail(email);
    if (!user) {
      return res.status(401).json({
        success: false,
        message: 'Invalid email or password credentials.'
      });
    }

    const isMatch = await comparePassword(password, user.pswd);
    if (!isMatch) {
      return res.status(401).json({
        success: false,
        message: 'Invalid email or password credentials.'
      });
    }

    const payload = {
      adminId: user.adminId,
      fullname: user.fullname,
      email: user.email,
      role: 'admin'
    };

    const token = generateToken(payload);

    return res.status(200).json({
      success: true,
      message: 'Login successful.',
      token,
      user: {
        adminId: user.adminId,
        fullname: user.fullname,
        email: user.email,
        role: 'admin'
      }
    });
  } catch (error) {
    console.error('Login Error:', error);
    return res.status(500).json({
      success: false,
      message: 'An error occurred during authentication.'
    });
  }
};

/**
 * Logout admin (Client side clears token)
 */
const logout = async (req, res) => {
  return res.status(200).json({
    success: true,
    message: 'Logged out successfully.'
  });
};

/**
 * Get current user profile
 */
const getProfile = async (req, res) => {
  try {
    const user = await authService.findById(req.user.adminId);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: 'User profile not found.'
      });
    }

    return res.status(200).json({
      success: true,
      user: {
        ...user,
        role: 'admin'
      }
    });
  } catch (error) {
    console.error('Get Profile Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve profile.'
    });
  }
};

/**
 * Update current user profile (fullname, email)
 */
const updateProfile = async (req, res) => {
  try {
    const { fullname, email } = req.body;
    const adminId = req.user.adminId;

    if (!fullname || !email) {
      return res.status(400).json({
        success: false,
        message: 'Full name and email are required.'
      });
    }

    const updated = await authService.updateProfile(adminId, fullname, email);
    if (!updated) {
      return res.status(400).json({
        success: false,
        message: 'Failed to update profile.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Profile updated successfully.',
      user: {
        adminId,
        fullname,
        email,
        role: 'admin'
      }
    });
  } catch (error) {
    console.error('Update Profile Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to update profile.'
    });
  }
};

/**
 * Change admin password
 */
const changePassword = async (req, res) => {
  try {
    const { currentPassword, newPassword, confirmPassword } = req.body;
    const adminId = req.user.adminId;

    if (!currentPassword || !newPassword || !confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'All fields are required.'
      });
    }

    if (newPassword !== confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'New passwords do not match.'
      });
    }

    if (newPassword.length < 6) {
      return res.status(400).json({
        success: false,
        message: 'New password must be at least 6 characters long.'
      });
    }

    const user = await authService.findByIdWithPassword(adminId);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: 'User not found.'
      });
    }

    const isMatch = await comparePassword(currentPassword, user.pswd);
    if (!isMatch) {
      return res.status(400).json({
        success: false,
        message: 'Current password is incorrect.'
      });
    }

    const newHashedPassword = await hashPassword(newPassword);
    await authService.updatePassword(adminId, newHashedPassword);

    return res.status(200).json({
      success: true,
      message: 'Password changed successfully.'
    });
  } catch (error) {
    console.error('Change Password Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to change password.'
    });
  }
};

/**
 * Request password reset
 */
const forgotPassword = async (req, res) => {
  try {
    const { email } = req.body;
    if (!email) {
      return res.status(400).json({
        success: false,
        message: 'Email address is required.'
      });
    }

    const user = await authService.findByEmail(email);
    if (!user) {
      // Don't reveal user non-existence for security
      return res.status(200).json({
        success: true,
        message: 'If the email exists in our system, a reset link will be sent.'
      });
    }

    const resetToken = crypto.randomBytes(32).toString('hex');
    await authService.setResetToken(email, resetToken);

    return res.status(200).json({
      success: true,
      message: 'Password reset token generated successfully.',
      resetToken // In production, send via email. Returned here for test verification.
    });
  } catch (error) {
    console.error('Forgot Password Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to process password reset request.'
    });
  }
};

/**
 * Reset password using token
 */
const resetPassword = async (req, res) => {
  try {
    const { token, newPassword, confirmPassword } = req.body;

    if (!token || !newPassword || !confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'Reset token, new password, and confirm password are required.'
      });
    }

    if (newPassword !== confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'Passwords do not match.'
      });
    }

    if (newPassword.length < 6) {
      return res.status(400).json({
        success: false,
        message: 'Password must be at least 6 characters long.'
      });
    }

    const user = await authService.findByResetToken(token);
    if (!user) {
      return res.status(400).json({
        success: false,
        message: 'Invalid or expired password reset token.'
      });
    }

    const newHashedPassword = await hashPassword(newPassword);
    await authService.updatePassword(user.adminId, newHashedPassword);
    await authService.clearResetToken(user.adminId);

    return res.status(200).json({
      success: true,
      message: 'Password reset successful. You can now log in with your new password.'
    });
  } catch (error) {
    console.error('Reset Password Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to reset password.'
    });
  }
};

module.exports = {
  login,
  logout,
  getProfile,
  updateProfile,
  changePassword,
  forgotPassword,
  resetPassword
};
