-- SCENSOB — all three contact form tables, for one database.
--
-- The three sites use different table names, so they share a single database
-- happily. Free hosting usually limits how many databases you get, which is
-- why this is one file rather than three.
--
-- In phpMyAdmin: select your database, open the SQL tab, paste this in, run.

-- SCENSOB Group
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

-- SCENSOB IT
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

-- SCENSOB Transport & Delivery
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
