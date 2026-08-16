-- Bill Locking Feature Migration
-- Run this SQL on the production database

-- 1. Add allow_edit_all_bills parameter to society_parameters
ALTER TABLE `society_parameters`
ADD COLUMN `allow_edit_all_bills` TINYINT(1) NOT NULL DEFAULT 0
COMMENT '0=No (only last bill editable), 1=Yes (all bills editable with audit log)';

-- 2. Create bill audit log table
CREATE TABLE IF NOT EXISTS `bill_audit_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `society_id` INT(11) NOT NULL,
  `member_id` INT(11) DEFAULT NULL,
  `bill_summary_id` INT(11) DEFAULT NULL,
  `bill_no` VARCHAR(50) DEFAULT NULL,
  `bill_month` VARCHAR(20) DEFAULT NULL,
  `action_type` VARCHAR(20) NOT NULL COMMENT 'edit, delete, bulk_delete',
  `field_name` VARCHAR(100) DEFAULT NULL,
  `old_value` TEXT DEFAULT NULL,
  `new_value` TEXT DEFAULT NULL,
  `changed_by` INT(11) DEFAULT NULL,
  `changed_at` DATETIME NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `remarks` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_society_id` (`society_id`),
  KEY `idx_bill_summary_id` (`bill_summary_id`),
  KEY `idx_changed_at` (`changed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Audit trail for bill edits and deletions';
