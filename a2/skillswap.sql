-- Run only if the database is NOT already created
-- Host: localhost
-- For Jacob 5 use export data as described in the "Exporting a table to RMIT SQL Server" document
-- This script creates users and skills tables

-- Create the SkillSwap database
CREATE DATABASE IF NOT EXISTS skillswap
DEFAULT CHARACTER
SET utf8mb4
DEFAULT
COLLATE utf8mb4_unicode_ci;
USE skillswap;


-- Skills table
CREATE TABLE IF NOT EXISTS skills
(
skill_id     INT AUTO_INCREMENT PRIMARY KEY,
title        VARCHAR(150)  NOT NULL,
description  TEXT          NOT NULL,
category     VARCHAR(50),
image_path   VARCHAR(255),
rate_per_hr  DECIMAL(8,2)  NOT NULL,
level        ENUM('Beginner','Intermediate','Expert') NOT NULL DEFAULT 'Intermediate',
created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

