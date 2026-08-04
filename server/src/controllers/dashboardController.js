const dashboardService = require('../services/dashboardService');

/**
 * Get dashboard overview stats and top 5 item lists
 */
const getOverview = async (req, res) => {
  try {
    const data = await dashboardService.getOverview();
    return res.status(200).json({
      success: true,
      data
    });
  } catch (error) {
    console.error('Dashboard Overview Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to retrieve dashboard overview.'
    });
  }
};

/**
 * Delete a dashboard item by category and ID
 */
const deleteItem = async (req, res) => {
  try {
    const { type, id } = req.params;
    let deleted = false;

    switch (type) {
      case 'job':
        deleted = await dashboardService.deleteJobPosition(id);
        break;
      case 'sector':
        deleted = await dashboardService.deleteSector(id);
        break;
      case 'application':
        deleted = await dashboardService.deleteApplication(id);
        break;
      case 'news':
        deleted = await dashboardService.deleteNews(id);
        break;
      case 'image':
        deleted = await dashboardService.deleteImage(id);
        break;
      case 'notice':
        deleted = await dashboardService.deleteNotice(id);
        break;
      default:
        return res.status(400).json({
          success: false,
          message: 'Invalid item type specified.'
        });
    }

    if (!deleted) {
      return res.status(404).json({
        success: false,
        message: 'Item not found or already deleted.'
      });
    }

    return res.status(200).json({
      success: true,
      message: 'Item deleted successfully.'
    });
  } catch (error) {
    console.error('Dashboard Delete Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Failed to delete item.'
    });
  }
};

module.exports = {
  getOverview,
  deleteItem
};
