-- ============================================================
-- Migration: Add divisions table
-- Run this against your Hostinger MySQL database.
-- Safe to run multiple times (IF NOT EXISTS / INSERT IGNORE).
-- ============================================================

-- 1. Create the divisions table
CREATE TABLE IF NOT EXISTS `divisions` (
    `id`            INT           AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(100)  NOT NULL,
    `status`        TINYINT(1)    NOT NULL DEFAULT 1,
    `display_order` INT           NOT NULL DEFAULT 0,
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE  KEY `uq_name`          (`name`),
    INDEX         `idx_status`        (`status`),
    INDEX         `idx_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Seed from existing branch data (skips duplicates)
INSERT IGNORE INTO `divisions` (`name`, `status`, `display_order`)
SELECT DISTINCT `division`, 1, 0
FROM   `branches`
WHERE  `division` IS NOT NULL
  AND  `division` != ''
ORDER  BY `division` ASC;

-- 3. Verify — shows the result
SELECT id, name, status, display_order, created_at
FROM   `divisions`
ORDER  BY display_order ASC, name ASC;
