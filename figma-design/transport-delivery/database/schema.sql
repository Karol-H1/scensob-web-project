-- SCENSOB Transport & Delivery — contact form storage.
-- Run this once against a fresh database before submit.php can insert rows:
--
--   mysql -u root -p < database/schema.sql
--
-- (create the database itself first if it doesn't exist yet, e.g.
--   CREATE DATABASE scensob_transport CHARACTER SET utf8mb4;)

CREATE TABLE IF NOT EXISTS transport_enquiries (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  enquiry_type VARCHAR(60)  NOT NULL COMMENT 'Step 1 answer, e.g. "I need to hire staff"',
  detail       VARCHAR(120)          COMMENT 'Step 2 answer: sector or urgency, branch-dependent',
  first_name   VARCHAR(100) NOT NULL,
  last_name    VARCHAR(100) NOT NULL,
  company      VARCHAR(150)          COMMENT 'Hire branch only',
  email        VARCHAR(255) NOT NULL,
  phone        VARCHAR(50)  NOT NULL,
  headcount    VARCHAR(50)           COMMENT 'Hire branch only',
  location     VARCHAR(150)          COMMENT 'Hire branch only',
  licence      VARCHAR(100)          COMMENT 'Job branch only',
  notes        TEXT,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
