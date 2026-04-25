-- Add time fields to cw_patrol_schedule table
USE `cwp_roster`;

-- Add start_time and end_time columns if they don't exist
ALTER TABLE `cw_patrol_schedule` 
ADD COLUMN IF NOT EXISTS `start_time` TIME DEFAULT '09:00:00',
ADD COLUMN IF NOT EXISTS `end_time` TIME DEFAULT '17:00:00';

-- Update existing records with default times if they have NULL
UPDATE `cw_patrol_schedule` SET `start_time` = '09:00:00' WHERE `start_time` IS NULL;
UPDATE `cw_patrol_schedule` SET `end_time` = '17:00:00' WHERE `end_time` IS NULL;
