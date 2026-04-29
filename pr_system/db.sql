CREATE TABLE `users` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `profile_pic` VARCHAR(255) DEFAULT 'default.png',
  `first_name` VARCHAR(100) NOT NULL,
  `middle_initial` VARCHAR(5) DEFAULT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `nickname` VARCHAR(50) DEFAULT NULL,
  `id_number` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `catering_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `catering_date` DATE,
  `title` VARCHAR(255),
  `menu` VARCHAR(255),
  `quarter` VARCHAR(50),
  `unit_pax` VARCHAR(100),
  `description` TEXT,
  `qty` INT,
  `unit_cost` DECIMAL(15,2),
  `suppliers` VARCHAR(255),
  `total_cost` DECIMAL(15,2),
  `payment_mode` ENUM('Upon Completion', 'Progress Billing'),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),

  -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `office_supplies` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255),
  `items` VARCHAR(255),
  `quarter` VARCHAR(50),
  `articles` VARCHAR(255),
  `brand` VARCHAR(100),
  `qty` INT(11),
  `unit` VARCHAR(50),
  `unit_cost` DECIMAL(10,2),
  `suppliers` VARCHAR(255),
  `total_cost` VARCHAR(100),
  `payment_mode` VARCHAR(100),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),

  -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `ict_devices` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255),
  `items` VARCHAR(255),
  `quarter` VARCHAR(50),
  `brand` VARCHAR(100),
  `specs` TEXT,
  `qty` INT(11),
  `unit` VARCHAR(50),
  `unit_cost` DECIMAL(15,2),
  `suppliers` VARCHAR(255),
  `total_cost` VARCHAR(100),
  `payment_mode` VARCHAR(100),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),
  
   -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `furnitures` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255),
  `quarter` VARCHAR(50),
  `items` VARCHAR(255),
  `specs` TEXT,
  `qty` INT,
  `unit` VARCHAR(50),
  `unit_cost` DECIMAL(15,2),
  `suppliers` VARCHAR(255),
  `total_cost` VARCHAR(100),
  `payment_mode` ENUM('Upon Completion', 'Progress Billing'),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),
  
  -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `heavy_equipment` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255),
  `item` VARCHAR(255),
  `quarter` VARCHAR(50),
  `brand` VARCHAR(100),
  `specs` TEXT,
  `qty` INT,
  `unit` VARCHAR(50),
  `unit_cost` DECIMAL(15,2),
  `suppliers` VARCHAR(255),
  `total_cost` VARCHAR(100),
  `payment_mode` ENUM('Upon Completion', 'Progress Billing'),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),
  
  -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `appliances` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255),
  `item` VARCHAR(255),
  `quarter` VARCHAR(50),
  `brand` VARCHAR(100),
  `specs` TEXT,
  `qty` INT,
  `unit` VARCHAR(50),
  `unit_cost` DECIMAL(15,2),
  `suppliers` VARCHAR(255),
  `total_cost` VARCHAR(100),
  `payment_mode` ENUM('Upon Completion', 'Progress Billing'),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),
  
  -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `fixtures` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255),
  `item` VARCHAR(255),
  `quarter` VARCHAR(50),
  `brand` VARCHAR(100),
  `specs` TEXT,
  `qty` INT,
  `unit` VARCHAR(50),
  `unit_cost` DECIMAL(15,2),
  `suppliers` VARCHAR(255),
  `total_cost` VARCHAR(100),
  `payment_mode` ENUM('Upon Completion', 'Progress Billing'),
  `remarks` TEXT,
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),
  
  -- Individual Checklist Columns --
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255),
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255),
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255),
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255),
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255),
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255),
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255),
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255),
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255),

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `fabrication_installation` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_date` DATE NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `item` VARCHAR(255),
  `quarter` VARCHAR(50),
  `scope_of_work` TEXT,
  `specs` TEXT,
  `qty` INT DEFAULT 0,
  `unit` VARCHAR(50),
  /* Use DECIMAL for money to allow mathematical sorting and calculations */
  `unit_cost` DECIMAL(15,2) DEFAULT 0.00,
  `total_cost` DECIMAL(15,2) DEFAULT 0.00, 
  `suppliers` VARCHAR(255),
  `payment_mode` ENUM('Upon Completion', 'Progress Billing') DEFAULT 'Upon Completion',
  `remarks` TEXT,
  
  /* Tracking Numbers */
  `pr_no` VARCHAR(100),
  `nc_no` VARCHAR(100),
  `po_no` VARCHAR(100),
  `padmo_no` VARCHAR(100),
  `go_finance_no` VARCHAR(100),
  
  /* Checklist Statuses & Remarks (9 Items) */
  `status_pr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pr` VARCHAR(255) DEFAULT 'Please compile the PR document.',
  
  `status_abc` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_abc` VARCHAR(255) DEFAULT 'Please compile the ABC document.',
  
  `status_ppmp` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_ppmp` VARCHAR(255) DEFAULT 'Please compile the PPMP document.',
  
  `status_act_des` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_act_des` VARCHAR(255) DEFAULT 'Please compile the ACT DES document.',
  
  `status_iar_are` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_iar_are` VARCHAR(255) DEFAULT 'Please compile the IAR/ARE document.',
  
  `status_pdrs` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_pdrs` VARCHAR(255) DEFAULT 'Please compile the PDRS document.',
  
  `status_app` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_app` VARCHAR(255) DEFAULT 'Please compile the APP document.',
  
  `status_letter` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_letter` VARCHAR(255) DEFAULT 'Please compile the LETTER REQUEST document.',
  
  `status_obr` VARCHAR(50) DEFAULT 'Not Complete',
  `remarks_obr` VARCHAR(255) DEFAULT 'Please compile the MANUAL OBR document.',

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE journal_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    note_content TEXT,
    note_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- For the notification list below the calendar
CREATE TABLE procurement_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doc_type ENUM('bidoc','pr','abc','ppmp','act_des','iar_are','pdrs','app','letter_request','obr'),
    title VARCHAR(255),
    is_complete TINYINT(1) DEFAULT 0,
    due_date DATE
);