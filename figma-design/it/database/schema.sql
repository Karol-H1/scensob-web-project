-- SCENSOB IT — quote wizard storage.
-- Run this once against a fresh database before submit.php can insert rows:
--
--   mysql -u root -p < database/schema.sql
--
-- (create the database itself first if it doesn't exist yet, e.g.
--   CREATE DATABASE scensob_it CHARACTER SET utf8mb4;)

CREATE TABLE IF NOT EXISTS it_quote_requests (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  services     JSON NOT NULL COMMENT 'Array of service keys selected in step 1, e.g. ["staffing","cloud"]',
  timeline     VARCHAR(60),
  company_size VARCHAR(60),
  budget       VARCHAR(60),
  brief        TEXT,
  name         VARCHAR(100) NOT NULL,
  company      VARCHAR(150) NOT NULL,
  email        VARCHAR(255) NOT NULL,
  phone        VARCHAR(50),
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
