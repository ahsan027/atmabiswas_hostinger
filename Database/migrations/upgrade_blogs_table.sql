-- ═══════════════════════════════════════════════════════════════
-- ATMABISWAS News & Media Center — Blog Table Upgrade Migration
-- Run each ALTER statement individually in phpMyAdmin or MySQL CLI.
-- If a column already exists, MySQL will return a "Duplicate column"
-- error — that is safe to ignore; skip that line and continue.
-- ═══════════════════════════════════════════════════════════════

-- URL-friendly slug for clean article links
ALTER TABLE blogs ADD COLUMN slug VARCHAR(300) NULL DEFAULT NULL AFTER blog_title;

-- Comma-separated tag keywords
ALTER TABLE blogs ADD COLUMN tags VARCHAR(500) NULL DEFAULT NULL;

-- Mark an article as featured (shows in hero slot)
ALTER TABLE blogs ADD COLUMN featured TINYINT(1) NOT NULL DEFAULT 0;

-- Estimated reading time in minutes (calculated on save)
ALTER TABLE blogs ADD COLUMN reading_time TINYINT UNSIGNED NOT NULL DEFAULT 0;

-- SEO meta fields
ALTER TABLE blogs ADD COLUMN seo_title VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE blogs ADD COLUMN seo_description TEXT NULL DEFAULT NULL;
ALTER TABLE blogs ADD COLUMN seo_keywords VARCHAR(500) NULL DEFAULT NULL;

-- Optional separate social share image
ALTER TABLE blogs ADD COLUMN social_image VARCHAR(500) NULL DEFAULT NULL;

-- Auto-updated on any row change
ALTER TABLE blogs ADD COLUMN last_updated TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

-- ── Indexes ─────────────────────────────────────────────────────
-- Unique slug index (skip if you haven't set slugs yet)
ALTER TABLE blogs ADD UNIQUE INDEX idx_slug (slug);

-- Performance indexes
ALTER TABLE blogs ADD INDEX idx_featured (featured);
ALTER TABLE blogs ADD INDEX idx_category (category);
