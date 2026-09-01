-- SCENSOB Group — contact form storage.
--
-- Run this once against a fresh database before submit.php can insert rows:
--
--   CREATE DATABASE scensob_group CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
--   mysql -u root -p scensob_group < database/schema.sql
--
-- This is a separate database from the two division sites (scensob_transport
-- and scensob_it). The group form asks different questions, so it gets its own
-- table rather than sharing theirs.

CREATE TABLE IF NOT EXISTS group_enquiries (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  enquiry_type VARCHAR(60)           COMMENT 'Which button was picked, e.g. "IT services or staffing". Optional on the form.',
  name         VARCHAR(150) NOT NULL COMMENT 'Full name, single field on this form',
  company      VARCHAR(150)          COMMENT 'Optional',
  email        VARCHAR(255) NOT NULL,
  phone        VARCHAR(50)           COMMENT 'Optional',
  message      TEXT         NOT NULL,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
