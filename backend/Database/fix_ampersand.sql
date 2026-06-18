-- Fix &amp; → & in all affected tables
-- Run this once in phpMyAdmin or via MySQL CLI

UPDATE sectors  SET sector_name = REPLACE(sector_name, '&amp;', '&');
UPDATE jobcodes SET JobTitle    = REPLACE(JobTitle,    '&amp;', '&');
UPDATE jobs     SET job_title   = REPLACE(job_title,   '&amp;', '&');
UPDATE jobs     SET job_dept    = REPLACE(job_dept,    '&amp;', '&');
