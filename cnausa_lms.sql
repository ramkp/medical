-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2016 at 12:35 PM
-- Server version: 5.6.29
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cnausa_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assign`
--

CREATE TABLE IF NOT EXISTS `mdl_assign` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `alwaysshowdescription` tinyint(2) NOT NULL DEFAULT '0',
  `nosubmissions` tinyint(2) NOT NULL DEFAULT '0',
  `submissiondrafts` tinyint(2) NOT NULL DEFAULT '0',
  `sendnotifications` tinyint(2) NOT NULL DEFAULT '0',
  `sendlatenotifications` tinyint(2) NOT NULL DEFAULT '0',
  `duedate` bigint(10) NOT NULL DEFAULT '0',
  `allowsubmissionsfromdate` bigint(10) NOT NULL DEFAULT '0',
  `grade` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `requiresubmissionstatement` tinyint(2) NOT NULL DEFAULT '0',
  `completionsubmit` tinyint(2) NOT NULL DEFAULT '0',
  `cutoffdate` bigint(10) NOT NULL DEFAULT '0',
  `teamsubmission` tinyint(2) NOT NULL DEFAULT '0',
  `requireallteammemberssubmit` tinyint(2) NOT NULL DEFAULT '0',
  `teamsubmissiongroupingid` bigint(10) NOT NULL DEFAULT '0',
  `blindmarking` tinyint(2) NOT NULL DEFAULT '0',
  `revealidentities` tinyint(2) NOT NULL DEFAULT '0',
  `attemptreopenmethod` varchar(10) NOT NULL DEFAULT 'none',
  `maxattempts` mediumint(6) NOT NULL DEFAULT '-1',
  `markingworkflow` tinyint(2) NOT NULL DEFAULT '0',
  `markingallocation` tinyint(2) NOT NULL DEFAULT '0',
  `sendstudentnotifications` tinyint(2) NOT NULL DEFAULT '1',
  `preventsubmissionnotingroup` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assi_cou_ix` (`course`),
  KEY `mdl_assi_tea_ix` (`teamsubmissiongroupingid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table saves information about an instance of mod_assign' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignfeedback_comments`
--

CREATE TABLE IF NOT EXISTS `mdl_assignfeedback_comments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `grade` bigint(10) NOT NULL DEFAULT '0',
  `commenttext` longtext,
  `commentformat` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assicomm_ass_ix` (`assignment`),
  KEY `mdl_assicomm_gra_ix` (`grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Text feedback for submitted assignments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignfeedback_editpdf_annot`
--

CREATE TABLE IF NOT EXISTS `mdl_assignfeedback_editpdf_annot` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `gradeid` bigint(10) NOT NULL DEFAULT '0',
  `pageno` bigint(10) NOT NULL DEFAULT '0',
  `x` bigint(10) DEFAULT '0',
  `y` bigint(10) DEFAULT '0',
  `endx` bigint(10) DEFAULT '0',
  `endy` bigint(10) DEFAULT '0',
  `path` longtext,
  `type` varchar(10) DEFAULT 'line',
  `colour` varchar(10) DEFAULT 'black',
  `draft` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_assieditanno_grapag_ix` (`gradeid`,`pageno`),
  KEY `mdl_assieditanno_gra_ix` (`gradeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='stores annotations added to pdfs submitted by students' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignfeedback_editpdf_cmnt`
--

CREATE TABLE IF NOT EXISTS `mdl_assignfeedback_editpdf_cmnt` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `gradeid` bigint(10) NOT NULL DEFAULT '0',
  `x` bigint(10) DEFAULT '0',
  `y` bigint(10) DEFAULT '0',
  `width` bigint(10) DEFAULT '120',
  `rawtext` longtext,
  `pageno` bigint(10) NOT NULL DEFAULT '0',
  `colour` varchar(10) DEFAULT 'black',
  `draft` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_assieditcmnt_grapag_ix` (`gradeid`,`pageno`),
  KEY `mdl_assieditcmnt_gra_ix` (`gradeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores comments added to pdfs' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignfeedback_editpdf_quick`
--

CREATE TABLE IF NOT EXISTS `mdl_assignfeedback_editpdf_quick` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `rawtext` longtext NOT NULL,
  `width` bigint(10) NOT NULL DEFAULT '120',
  `colour` varchar(10) DEFAULT 'yellow',
  PRIMARY KEY (`id`),
  KEY `mdl_assieditquic_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores teacher specified quicklist comments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignfeedback_file`
--

CREATE TABLE IF NOT EXISTS `mdl_assignfeedback_file` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `grade` bigint(10) NOT NULL DEFAULT '0',
  `numfiles` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assifile_ass2_ix` (`assignment`),
  KEY `mdl_assifile_gra_ix` (`grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores info about the number of files submitted by a student' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignment`
--

CREATE TABLE IF NOT EXISTS `mdl_assignment` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `assignmenttype` varchar(50) NOT NULL DEFAULT '',
  `resubmit` tinyint(2) NOT NULL DEFAULT '0',
  `preventlate` tinyint(2) NOT NULL DEFAULT '0',
  `emailteachers` tinyint(2) NOT NULL DEFAULT '0',
  `var1` bigint(10) DEFAULT '0',
  `var2` bigint(10) DEFAULT '0',
  `var3` bigint(10) DEFAULT '0',
  `var4` bigint(10) DEFAULT '0',
  `var5` bigint(10) DEFAULT '0',
  `maxbytes` bigint(10) NOT NULL DEFAULT '100000',
  `timedue` bigint(10) NOT NULL DEFAULT '0',
  `timeavailable` bigint(10) NOT NULL DEFAULT '0',
  `grade` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assi_cou2_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines assignments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignment_submissions`
--

CREATE TABLE IF NOT EXISTS `mdl_assignment_submissions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `numfiles` bigint(10) NOT NULL DEFAULT '0',
  `data1` longtext,
  `data2` longtext,
  `grade` bigint(11) NOT NULL DEFAULT '0',
  `submissioncomment` longtext NOT NULL,
  `format` smallint(4) NOT NULL DEFAULT '0',
  `teacher` bigint(10) NOT NULL DEFAULT '0',
  `timemarked` bigint(10) NOT NULL DEFAULT '0',
  `mailed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assisubm_use2_ix` (`userid`),
  KEY `mdl_assisubm_mai_ix` (`mailed`),
  KEY `mdl_assisubm_tim_ix` (`timemarked`),
  KEY `mdl_assisubm_ass2_ix` (`assignment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about submitted assignments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignment_upgrade`
--

CREATE TABLE IF NOT EXISTS `mdl_assignment_upgrade` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `oldcmid` bigint(10) NOT NULL DEFAULT '0',
  `oldinstance` bigint(10) NOT NULL DEFAULT '0',
  `newcmid` bigint(10) NOT NULL DEFAULT '0',
  `newinstance` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assiupgr_old_ix` (`oldcmid`),
  KEY `mdl_assiupgr_old2_ix` (`oldinstance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about upgraded assignments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignsubmission_file`
--

CREATE TABLE IF NOT EXISTS `mdl_assignsubmission_file` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `submission` bigint(10) NOT NULL DEFAULT '0',
  `numfiles` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assifile_ass_ix` (`assignment`),
  KEY `mdl_assifile_sub_ix` (`submission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about file submissions for assignments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assignsubmission_onlinetext`
--

CREATE TABLE IF NOT EXISTS `mdl_assignsubmission_onlinetext` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `submission` bigint(10) NOT NULL DEFAULT '0',
  `onlinetext` longtext,
  `onlineformat` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assionli_ass_ix` (`assignment`),
  KEY `mdl_assionli_sub_ix` (`submission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about onlinetext submission' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assign_grades`
--

CREATE TABLE IF NOT EXISTS `mdl_assign_grades` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `grader` bigint(10) NOT NULL DEFAULT '0',
  `grade` decimal(10,5) DEFAULT '0.00000',
  `attemptnumber` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_assigrad_assuseatt_uix` (`assignment`,`userid`,`attemptnumber`),
  KEY `mdl_assigrad_use_ix` (`userid`),
  KEY `mdl_assigrad_att_ix` (`attemptnumber`),
  KEY `mdl_assigrad_ass_ix` (`assignment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Grading information about a single assignment submission.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assign_plugin_config`
--

CREATE TABLE IF NOT EXISTS `mdl_assign_plugin_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `plugin` varchar(28) NOT NULL DEFAULT '',
  `subtype` varchar(28) NOT NULL DEFAULT '',
  `name` varchar(28) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_assiplugconf_plu_ix` (`plugin`),
  KEY `mdl_assiplugconf_sub_ix` (`subtype`),
  KEY `mdl_assiplugconf_nam_ix` (`name`),
  KEY `mdl_assiplugconf_ass_ix` (`assignment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Config data for an instance of a plugin in an assignment.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assign_submission`
--

CREATE TABLE IF NOT EXISTS `mdl_assign_submission` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `status` varchar(10) DEFAULT NULL,
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `attemptnumber` bigint(10) NOT NULL DEFAULT '0',
  `latest` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_assisubm_assusegroatt_uix` (`assignment`,`userid`,`groupid`,`attemptnumber`),
  KEY `mdl_assisubm_use_ix` (`userid`),
  KEY `mdl_assisubm_att_ix` (`attemptnumber`),
  KEY `mdl_assisubm_assusegrolat_ix` (`assignment`,`userid`,`groupid`,`latest`),
  KEY `mdl_assisubm_ass_ix` (`assignment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table keeps information about student interactions with' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assign_user_flags`
--

CREATE TABLE IF NOT EXISTS `mdl_assign_user_flags` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `locked` bigint(10) NOT NULL DEFAULT '0',
  `mailed` smallint(4) NOT NULL DEFAULT '0',
  `extensionduedate` bigint(10) NOT NULL DEFAULT '0',
  `workflowstate` varchar(20) DEFAULT NULL,
  `allocatedmarker` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assiuserflag_mai_ix` (`mailed`),
  KEY `mdl_assiuserflag_use_ix` (`userid`),
  KEY `mdl_assiuserflag_ass_ix` (`assignment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of flags that can be set for a single user in a single ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_assign_user_mapping`
--

CREATE TABLE IF NOT EXISTS `mdl_assign_user_mapping` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assignment` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_assiusermapp_ass_ix` (`assignment`),
  KEY `mdl_assiusermapp_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Map an assignment specific id number to a user' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_backup_controllers`
--

CREATE TABLE IF NOT EXISTS `mdl_backup_controllers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `backupid` varchar(32) NOT NULL DEFAULT '',
  `operation` varchar(20) NOT NULL DEFAULT 'backup',
  `type` varchar(10) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL,
  `format` varchar(20) NOT NULL DEFAULT '',
  `interactive` smallint(4) NOT NULL,
  `purpose` smallint(4) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `status` smallint(4) NOT NULL,
  `execution` smallint(4) NOT NULL,
  `executiontime` bigint(10) NOT NULL,
  `checksum` varchar(32) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `controller` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_backcont_bac_uix` (`backupid`),
  KEY `mdl_backcont_typite_ix` (`type`,`itemid`),
  KEY `mdl_backcont_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store the backup_controllers as they are used' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_backup_courses`
--

CREATE TABLE IF NOT EXISTS `mdl_backup_courses` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `laststarttime` bigint(10) NOT NULL DEFAULT '0',
  `lastendtime` bigint(10) NOT NULL DEFAULT '0',
  `laststatus` varchar(1) NOT NULL DEFAULT '5',
  `nextstarttime` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_backcour_cou_uix` (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store every course backup status' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_backup_logs`
--

CREATE TABLE IF NOT EXISTS `mdl_backup_logs` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `backupid` varchar(32) NOT NULL DEFAULT '',
  `loglevel` smallint(4) NOT NULL,
  `message` longtext NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_backlogs_bacid_uix` (`backupid`,`id`),
  KEY `mdl_backlogs_bac_ix` (`backupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store all the logs from backup and restore operations (by' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge`
--

CREATE TABLE IF NOT EXISTS `mdl_badge` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `usercreated` bigint(10) NOT NULL,
  `usermodified` bigint(10) NOT NULL,
  `issuername` varchar(255) NOT NULL DEFAULT '',
  `issuerurl` varchar(255) NOT NULL DEFAULT '',
  `issuercontact` varchar(255) DEFAULT NULL,
  `expiredate` bigint(10) DEFAULT NULL,
  `expireperiod` bigint(10) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `courseid` bigint(10) DEFAULT NULL,
  `message` longtext NOT NULL,
  `messagesubject` longtext NOT NULL,
  `attachment` tinyint(1) NOT NULL DEFAULT '1',
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `nextcron` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_badg_typ_ix` (`type`),
  KEY `mdl_badg_cou_ix` (`courseid`),
  KEY `mdl_badg_use_ix` (`usermodified`),
  KEY `mdl_badg_use2_ix` (`usercreated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines badge' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_backpack`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_backpack` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL DEFAULT '',
  `backpackurl` varchar(255) NOT NULL DEFAULT '',
  `backpackuid` bigint(10) NOT NULL,
  `autosync` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_badgback_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines settings for connecting external backpack' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_criteria`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_criteria` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `badgeid` bigint(10) NOT NULL DEFAULT '0',
  `criteriatype` bigint(10) DEFAULT NULL,
  `method` tinyint(1) NOT NULL DEFAULT '1',
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_badgcrit_badcri_uix` (`badgeid`,`criteriatype`),
  KEY `mdl_badgcrit_cri_ix` (`criteriatype`),
  KEY `mdl_badgcrit_bad_ix` (`badgeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines criteria for issuing badges' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_criteria_met`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_criteria_met` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `issuedid` bigint(10) DEFAULT NULL,
  `critid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `datemet` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_badgcritmet_cri_ix` (`critid`),
  KEY `mdl_badgcritmet_use_ix` (`userid`),
  KEY `mdl_badgcritmet_iss_ix` (`issuedid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines criteria that were met for an issued badge' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_criteria_param`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_criteria_param` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `critid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_badgcritpara_cri_ix` (`critid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines parameters for badges criteria' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_external`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_external` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `backpackid` bigint(10) NOT NULL,
  `collectionid` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_badgexte_bac_ix` (`backpackid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Setting for external badges display' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_issued`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_issued` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `badgeid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `uniquehash` longtext NOT NULL,
  `dateissued` bigint(10) NOT NULL DEFAULT '0',
  `dateexpire` bigint(10) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `issuernotified` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_badgissu_baduse_uix` (`badgeid`,`userid`),
  KEY `mdl_badgissu_bad_ix` (`badgeid`),
  KEY `mdl_badgissu_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines issued badges' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_badge_manual_award`
--

CREATE TABLE IF NOT EXISTS `mdl_badge_manual_award` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `badgeid` bigint(10) NOT NULL,
  `recipientid` bigint(10) NOT NULL,
  `issuerid` bigint(10) NOT NULL,
  `issuerrole` bigint(10) NOT NULL,
  `datemet` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_badgmanuawar_bad_ix` (`badgeid`),
  KEY `mdl_badgmanuawar_rec_ix` (`recipientid`),
  KEY `mdl_badgmanuawar_iss_ix` (`issuerid`),
  KEY `mdl_badgmanuawar_iss2_ix` (`issuerrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Track manual award criteria for badges' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_block`
--

CREATE TABLE IF NOT EXISTS `mdl_block` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `cron` bigint(10) NOT NULL DEFAULT '0',
  `lastcron` bigint(10) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_bloc_nam_uix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='contains all installed blocks' AUTO_INCREMENT=41 ;

--
-- Dumping data for table `mdl_block`
--

INSERT INTO `mdl_block` (`id`, `name`, `cron`, `lastcron`, `visible`) VALUES
(1, 'activity_modules', 0, 0, 1),
(2, 'activity_results', 0, 0, 1),
(3, 'admin_bookmarks', 0, 0, 1),
(4, 'badges', 0, 0, 1),
(5, 'blog_menu', 0, 0, 1),
(6, 'blog_recent', 0, 0, 1),
(7, 'blog_tags', 0, 0, 1),
(8, 'calendar_month', 0, 0, 1),
(9, 'calendar_upcoming', 0, 0, 1),
(10, 'comments', 0, 0, 1),
(11, 'community', 0, 0, 1),
(12, 'completionstatus', 0, 0, 1),
(13, 'course_list', 0, 0, 1),
(14, 'course_overview', 0, 0, 1),
(15, 'course_summary', 0, 0, 1),
(16, 'feedback', 0, 0, 0),
(17, 'glossary_random', 0, 0, 1),
(18, 'html', 0, 0, 1),
(19, 'login', 0, 0, 1),
(20, 'mentees', 0, 0, 1),
(21, 'messages', 0, 0, 1),
(22, 'mnet_hosts', 0, 0, 1),
(23, 'myprofile', 0, 0, 1),
(24, 'navigation', 0, 0, 1),
(25, 'news_items', 0, 0, 1),
(26, 'online_users', 0, 0, 1),
(27, 'participants', 0, 0, 1),
(28, 'private_files', 0, 0, 1),
(29, 'quiz_results', 0, 0, 0),
(30, 'recent_activity', 86400, 0, 1),
(31, 'rss_client', 300, 0, 1),
(32, 'search_forums', 0, 0, 1),
(33, 'section_links', 0, 0, 1),
(34, 'selfcompletion', 0, 0, 1),
(35, 'settings', 0, 0, 1),
(36, 'site_main_menu', 0, 0, 1),
(37, 'social_activities', 0, 0, 1),
(38, 'tag_flickr', 0, 0, 1),
(39, 'tag_youtube', 0, 0, 0),
(40, 'tags', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_block_community`
--

CREATE TABLE IF NOT EXISTS `mdl_block_community` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `coursename` varchar(255) NOT NULL DEFAULT '',
  `coursedescription` longtext,
  `courseurl` varchar(255) NOT NULL DEFAULT '',
  `imageurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Community block' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_block_instances`
--

CREATE TABLE IF NOT EXISTS `mdl_block_instances` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `blockname` varchar(40) NOT NULL DEFAULT '',
  `parentcontextid` bigint(10) NOT NULL,
  `showinsubcontexts` smallint(4) NOT NULL,
  `pagetypepattern` varchar(64) NOT NULL DEFAULT '',
  `subpagepattern` varchar(16) DEFAULT NULL,
  `defaultregion` varchar(16) NOT NULL DEFAULT '',
  `defaultweight` bigint(10) NOT NULL,
  `configdata` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_blocinst_parshopagsub_ix` (`parentcontextid`,`showinsubcontexts`,`pagetypepattern`,`subpagepattern`),
  KEY `mdl_blocinst_par_ix` (`parentcontextid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table stores block instances. The type of block this is' AUTO_INCREMENT=72 ;

--
-- Dumping data for table `mdl_block_instances`
--

INSERT INTO `mdl_block_instances` (`id`, `blockname`, `parentcontextid`, `showinsubcontexts`, `pagetypepattern`, `subpagepattern`, `defaultregion`, `defaultweight`, `configdata`) VALUES
(1, 'site_main_menu', 2, 0, 'site-index', NULL, 'side-pre', 0, ''),
(2, 'course_summary', 2, 0, 'site-index', NULL, 'side-post', 0, ''),
(3, 'calendar_month', 2, 0, 'site-index', NULL, 'side-post', 1, ''),
(4, 'navigation', 1, 1, '*', NULL, 'side-pre', 0, ''),
(5, 'settings', 1, 1, '*', NULL, 'side-pre', 1, ''),
(6, 'admin_bookmarks', 1, 0, 'admin-*', NULL, 'side-pre', 2, ''),
(7, 'private_files', 1, 0, 'my-index', '2', 'side-post', 0, ''),
(8, 'online_users', 1, 0, 'my-index', '2', 'side-post', 1, ''),
(9, 'badges', 1, 0, 'my-index', '2', 'side-post', 2, ''),
(10, 'calendar_month', 1, 0, 'my-index', '2', 'side-post', 3, ''),
(11, 'calendar_upcoming', 1, 0, 'my-index', '2', 'side-post', 4, ''),
(12, 'course_overview', 1, 0, 'my-index', '2', 'content', 0, ''),
(24, 'search_forums', 34, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(25, 'news_items', 34, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(26, 'calendar_upcoming', 34, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(27, 'recent_activity', 34, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(28, 'search_forums', 39, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(29, 'news_items', 39, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(30, 'calendar_upcoming', 39, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(31, 'recent_activity', 39, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(36, 'search_forums', 49, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(37, 'news_items', 49, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(38, 'calendar_upcoming', 49, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(39, 'recent_activity', 49, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(44, 'search_forums', 63, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(45, 'news_items', 63, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(46, 'calendar_upcoming', 63, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(47, 'recent_activity', 63, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(48, 'search_forums', 68, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(49, 'news_items', 68, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(50, 'calendar_upcoming', 68, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(51, 'recent_activity', 68, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(52, 'search_forums', 73, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(53, 'news_items', 73, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(54, 'calendar_upcoming', 73, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(55, 'recent_activity', 73, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(56, 'search_forums', 78, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(57, 'news_items', 78, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(58, 'calendar_upcoming', 78, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(59, 'recent_activity', 78, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(60, 'search_forums', 83, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(61, 'news_items', 83, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(62, 'calendar_upcoming', 83, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(63, 'recent_activity', 83, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(64, 'search_forums', 88, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(65, 'news_items', 88, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(66, 'calendar_upcoming', 88, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(67, 'recent_activity', 88, 0, 'course-view-*', NULL, 'side-post', 3, ''),
(68, 'search_forums', 93, 0, 'course-view-*', NULL, 'side-post', 0, ''),
(69, 'news_items', 93, 0, 'course-view-*', NULL, 'side-post', 1, ''),
(70, 'calendar_upcoming', 93, 0, 'course-view-*', NULL, 'side-post', 2, ''),
(71, 'recent_activity', 93, 0, 'course-view-*', NULL, 'side-post', 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_block_positions`
--

CREATE TABLE IF NOT EXISTS `mdl_block_positions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `blockinstanceid` bigint(10) NOT NULL,
  `contextid` bigint(10) NOT NULL,
  `pagetype` varchar(64) NOT NULL DEFAULT '',
  `subpage` varchar(16) NOT NULL DEFAULT '',
  `visible` smallint(4) NOT NULL,
  `region` varchar(16) NOT NULL DEFAULT '',
  `weight` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_blocposi_bloconpagsub_uix` (`blockinstanceid`,`contextid`,`pagetype`,`subpage`),
  KEY `mdl_blocposi_blo_ix` (`blockinstanceid`),
  KEY `mdl_blocposi_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the position of a sticky block_instance on a another ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_block_recent_activity`
--

CREATE TABLE IF NOT EXISTS `mdl_block_recent_activity` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `cmid` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `action` tinyint(1) NOT NULL,
  `modname` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_blocreceacti_coutim_ix` (`courseid`,`timecreated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Recent activity block' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_block_rss_client`
--

CREATE TABLE IF NOT EXISTS `mdl_block_rss_client` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `title` longtext NOT NULL,
  `preferredtitle` varchar(64) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `shared` tinyint(2) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `skiptime` bigint(10) NOT NULL DEFAULT '0',
  `skipuntil` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Remote news feed information. Contains the news feed id, the' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_blog_association`
--

CREATE TABLE IF NOT EXISTS `mdl_blog_association` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `blogid` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_blogasso_con_ix` (`contextid`),
  KEY `mdl_blogasso_blo_ix` (`blogid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Associations of blog entries with courses and module instanc' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_blog_external`
--

CREATE TABLE IF NOT EXISTS `mdl_blog_external` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `url` longtext NOT NULL,
  `filtertags` varchar(255) DEFAULT NULL,
  `failedlastsync` tinyint(1) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) DEFAULT NULL,
  `timefetched` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_blogexte_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='External blog links used for RSS copying of blog entries to ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_book`
--

CREATE TABLE IF NOT EXISTS `mdl_book` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `numbering` smallint(4) NOT NULL DEFAULT '0',
  `navstyle` smallint(4) NOT NULL DEFAULT '1',
  `customtitles` tinyint(2) NOT NULL DEFAULT '0',
  `revision` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines book' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_book_chapters`
--

CREATE TABLE IF NOT EXISTS `mdl_book_chapters` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `bookid` bigint(10) NOT NULL DEFAULT '0',
  `pagenum` bigint(10) NOT NULL DEFAULT '0',
  `subchapter` bigint(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `contentformat` smallint(4) NOT NULL DEFAULT '0',
  `hidden` tinyint(2) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `importsrc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines book_chapters' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_cache_filters`
--

CREATE TABLE IF NOT EXISTS `mdl_cache_filters` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `filter` varchar(32) NOT NULL DEFAULT '',
  `version` bigint(10) NOT NULL DEFAULT '0',
  `md5key` varchar(32) NOT NULL DEFAULT '',
  `rawtext` longtext NOT NULL,
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_cachfilt_filmd5_ix` (`filter`,`md5key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='For keeping information about cached data' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_cache_flags`
--

CREATE TABLE IF NOT EXISTS `mdl_cache_flags` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `flagtype` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `value` longtext NOT NULL,
  `expiry` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_cachflag_fla_ix` (`flagtype`),
  KEY `mdl_cachflag_nam_ix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Cache of time-sensitive flags' AUTO_INCREMENT=41 ;

--
-- Dumping data for table `mdl_cache_flags`
--

INSERT INTO `mdl_cache_flags` (`id`, `flagtype`, `name`, `timemodified`, `value`, `expiry`) VALUES
(1, 'userpreferenceschanged', '2', 1455293290, '1', 1455300490),
(2, 'accesslib/dirtycontexts', '/1/5/23', 1452676042, '1', 1452683242),
(3, 'userpreferenceschanged', '3', 1453362673, '1', 1453369873),
(4, 'accesslib/dirtycontexts', '/1/23/29', 1453292327, '1', 1453299527),
(5, 'accesslib/dirtycontexts', '/1/23/24', 1453292336, '1', 1453299536),
(6, 'accesslib/dirtycontexts', '/1/23/25', 1453292349, '1', 1453299549),
(7, 'accesslib/dirtycontexts', '/1/23/26', 1453292356, '1', 1453299556),
(8, 'accesslib/dirtycontexts', '/1/23/27', 1453292364, '1', 1453299564),
(9, 'accesslib/dirtycontexts', '/1/23/28', 1453292372, '1', 1453299572),
(10, 'accesslib/dirtycontexts', '/1/30', 1453371729, '1', 1453378929),
(11, 'accesslib/dirtycontexts', '/1/31', 1453371775, '1', 1453378975),
(12, 'accesslib/dirtycontexts', '/1/32', 1453371811, '1', 1453379011),
(13, 'accesslib/dirtycontexts', '/1/33', 1453371854, '1', 1453379054),
(14, 'accesslib/dirtycontexts', '/1/3', 1453371892, '1', 1453379092),
(15, 'accesslib/dirtycontexts', '/1/30/34', 1453375088, '1', 1453382288),
(16, 'accesslib/dirtycontexts', '/1/30/39', 1453375118, '1', 1453382318),
(17, 'accesslib/dirtycontexts', '/1/30/44', 1453375152, '1', 1453382352),
(18, 'accesslib/dirtycontexts', '/1/30/49', 1453375182, '1', 1453382382),
(19, 'accesslib/dirtycontexts', '/1/31/54', 1453375220, '1', 1453382420),
(20, 'accesslib/dirtycontexts', '/1/5/18', 1453448105, '1', 1453455305),
(21, 'accesslib/dirtycontexts', '/1/5/22', 1453448120, '1', 1453455320),
(22, 'accesslib/dirtycontexts', '/1/5/19', 1453448128, '1', 1453455328),
(23, 'accesslib/dirtycontexts', '/1/5/20', 1453448139, '1', 1453455339),
(24, 'accesslib/dirtycontexts', '/1/5/21', 1453448151, '1', 1453455351),
(25, 'accesslib/dirtycontexts', '/1/30/44/45', 1453448201, '1', 1453455401),
(26, 'accesslib/dirtycontexts', '/1/30/44/46', 1453448211, '1', 1453455411),
(27, 'accesslib/dirtycontexts', '/1/30/44/47', 1453448219, '1', 1453455419),
(28, 'accesslib/dirtycontexts', '/1/30/44/48', 1453448231, '1', 1453455431),
(29, 'accesslib/dirtycontexts', '/1/31/54/55', 1453448284, '1', 1453455484),
(30, 'accesslib/dirtycontexts', '/1/31/54/56', 1453448293, '1', 1453455493),
(31, 'accesslib/dirtycontexts', '/1/31/54/57', 1453448303, '1', 1453455503),
(32, 'accesslib/dirtycontexts', '/1/31/54/58', 1453448314, '1', 1453455514),
(33, 'accesslib/dirtycontexts', '/1/33/63', 1453652021, '1', 1453659221),
(34, 'accesslib/dirtycontexts', '/1/33/68', 1453652063, '1', 1453659263),
(35, 'accesslib/dirtycontexts', '/1/33/73', 1453652130, '1', 1453659330),
(36, 'accesslib/dirtycontexts', '/1/33/78', 1453652167, '1', 1453659367),
(37, 'accesslib/dirtycontexts', '/1/33/83', 1453652203, '1', 1453659403),
(38, 'accesslib/dirtycontexts', '/1/32/88', 1453652251, '1', 1453659451),
(39, 'accesslib/dirtycontexts', '/1/32/93', 1453652285, '1', 1453659485),
(40, 'accesslib/dirtycontexts', '/1', 1455291805, '1', 1455299005);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_capabilities`
--

CREATE TABLE IF NOT EXISTS `mdl_capabilities` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `captype` varchar(50) NOT NULL DEFAULT '',
  `contextlevel` bigint(10) NOT NULL DEFAULT '0',
  `component` varchar(100) NOT NULL DEFAULT '',
  `riskbitmask` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_capa_nam_uix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='this defines all capabilities' AUTO_INCREMENT=529 ;

--
-- Dumping data for table `mdl_capabilities`
--

INSERT INTO `mdl_capabilities` (`id`, `name`, `captype`, `contextlevel`, `component`, `riskbitmask`) VALUES
(1, 'moodle/site:config', 'write', 10, 'moodle', 62),
(2, 'moodle/site:readallmessages', 'read', 10, 'moodle', 8),
(3, 'moodle/site:deleteanymessage', 'write', 10, 'moodle', 32),
(4, 'moodle/site:sendmessage', 'write', 10, 'moodle', 16),
(5, 'moodle/site:deleteownmessage', 'write', 10, 'moodle', 0),
(6, 'moodle/site:approvecourse', 'write', 10, 'moodle', 4),
(7, 'moodle/backup:backupcourse', 'write', 50, 'moodle', 28),
(8, 'moodle/backup:backupsection', 'write', 50, 'moodle', 28),
(9, 'moodle/backup:backupactivity', 'write', 70, 'moodle', 28),
(10, 'moodle/backup:backuptargethub', 'write', 50, 'moodle', 28),
(11, 'moodle/backup:backuptargetimport', 'write', 50, 'moodle', 28),
(12, 'moodle/backup:downloadfile', 'write', 50, 'moodle', 28),
(13, 'moodle/backup:configure', 'write', 50, 'moodle', 28),
(14, 'moodle/backup:userinfo', 'read', 50, 'moodle', 8),
(15, 'moodle/backup:anonymise', 'read', 50, 'moodle', 8),
(16, 'moodle/restore:restorecourse', 'write', 50, 'moodle', 28),
(17, 'moodle/restore:restoresection', 'write', 50, 'moodle', 28),
(18, 'moodle/restore:restoreactivity', 'write', 50, 'moodle', 28),
(19, 'moodle/restore:viewautomatedfilearea', 'write', 50, 'moodle', 28),
(20, 'moodle/restore:restoretargethub', 'write', 50, 'moodle', 28),
(21, 'moodle/restore:restoretargetimport', 'write', 50, 'moodle', 28),
(22, 'moodle/restore:uploadfile', 'write', 50, 'moodle', 28),
(23, 'moodle/restore:configure', 'write', 50, 'moodle', 28),
(24, 'moodle/restore:rolldates', 'write', 50, 'moodle', 0),
(25, 'moodle/restore:userinfo', 'write', 50, 'moodle', 30),
(26, 'moodle/restore:createuser', 'write', 10, 'moodle', 24),
(27, 'moodle/site:manageblocks', 'write', 80, 'moodle', 20),
(28, 'moodle/site:accessallgroups', 'read', 50, 'moodle', 0),
(29, 'moodle/site:viewfullnames', 'read', 50, 'moodle', 0),
(30, 'moodle/site:viewuseridentity', 'read', 50, 'moodle', 0),
(31, 'moodle/site:viewreports', 'read', 50, 'moodle', 8),
(32, 'moodle/site:trustcontent', 'write', 50, 'moodle', 4),
(33, 'moodle/site:uploadusers', 'write', 10, 'moodle', 24),
(34, 'moodle/filter:manage', 'write', 50, 'moodle', 0),
(35, 'moodle/user:create', 'write', 10, 'moodle', 24),
(36, 'moodle/user:delete', 'write', 10, 'moodle', 8),
(37, 'moodle/user:update', 'write', 10, 'moodle', 24),
(38, 'moodle/user:viewdetails', 'read', 50, 'moodle', 0),
(39, 'moodle/user:viewalldetails', 'read', 30, 'moodle', 8),
(40, 'moodle/user:viewlastip', 'read', 30, 'moodle', 8),
(41, 'moodle/user:viewhiddendetails', 'read', 50, 'moodle', 8),
(42, 'moodle/user:loginas', 'write', 50, 'moodle', 30),
(43, 'moodle/user:managesyspages', 'write', 10, 'moodle', 0),
(44, 'moodle/user:manageblocks', 'write', 30, 'moodle', 0),
(45, 'moodle/user:manageownblocks', 'write', 10, 'moodle', 0),
(46, 'moodle/user:manageownfiles', 'write', 10, 'moodle', 0),
(47, 'moodle/user:ignoreuserquota', 'write', 10, 'moodle', 0),
(48, 'moodle/my:configsyspages', 'write', 10, 'moodle', 0),
(49, 'moodle/role:assign', 'write', 50, 'moodle', 28),
(50, 'moodle/role:review', 'read', 50, 'moodle', 8),
(51, 'moodle/role:override', 'write', 50, 'moodle', 28),
(52, 'moodle/role:safeoverride', 'write', 50, 'moodle', 16),
(53, 'moodle/role:manage', 'write', 10, 'moodle', 28),
(54, 'moodle/role:switchroles', 'read', 50, 'moodle', 12),
(55, 'moodle/category:manage', 'write', 40, 'moodle', 4),
(56, 'moodle/category:viewhiddencategories', 'read', 40, 'moodle', 0),
(57, 'moodle/cohort:manage', 'write', 40, 'moodle', 0),
(58, 'moodle/cohort:assign', 'write', 40, 'moodle', 0),
(59, 'moodle/cohort:view', 'read', 50, 'moodle', 0),
(60, 'moodle/course:create', 'write', 40, 'moodle', 4),
(61, 'moodle/course:request', 'write', 10, 'moodle', 0),
(62, 'moodle/course:delete', 'write', 50, 'moodle', 32),
(63, 'moodle/course:update', 'write', 50, 'moodle', 4),
(64, 'moodle/course:view', 'read', 50, 'moodle', 0),
(65, 'moodle/course:enrolreview', 'read', 50, 'moodle', 8),
(66, 'moodle/course:enrolconfig', 'write', 50, 'moodle', 8),
(67, 'moodle/course:reviewotherusers', 'read', 50, 'moodle', 0),
(68, 'moodle/course:bulkmessaging', 'write', 50, 'moodle', 16),
(69, 'moodle/course:viewhiddenuserfields', 'read', 50, 'moodle', 8),
(70, 'moodle/course:viewhiddencourses', 'read', 50, 'moodle', 0),
(71, 'moodle/course:visibility', 'write', 50, 'moodle', 0),
(72, 'moodle/course:managefiles', 'write', 50, 'moodle', 4),
(73, 'moodle/course:ignorefilesizelimits', 'write', 50, 'moodle', 0),
(74, 'moodle/course:manageactivities', 'write', 70, 'moodle', 4),
(75, 'moodle/course:activityvisibility', 'write', 70, 'moodle', 0),
(76, 'moodle/course:viewhiddenactivities', 'write', 70, 'moodle', 0),
(77, 'moodle/course:viewparticipants', 'read', 50, 'moodle', 0),
(78, 'moodle/course:changefullname', 'write', 50, 'moodle', 4),
(79, 'moodle/course:changeshortname', 'write', 50, 'moodle', 4),
(80, 'moodle/course:changeidnumber', 'write', 50, 'moodle', 4),
(81, 'moodle/course:changecategory', 'write', 50, 'moodle', 4),
(82, 'moodle/course:changesummary', 'write', 50, 'moodle', 4),
(83, 'moodle/site:viewparticipants', 'read', 10, 'moodle', 0),
(84, 'moodle/course:isincompletionreports', 'read', 50, 'moodle', 0),
(85, 'moodle/course:viewscales', 'read', 50, 'moodle', 0),
(86, 'moodle/course:managescales', 'write', 50, 'moodle', 0),
(87, 'moodle/course:managegroups', 'write', 50, 'moodle', 0),
(88, 'moodle/course:reset', 'write', 50, 'moodle', 32),
(89, 'moodle/course:viewsuspendedusers', 'read', 10, 'moodle', 0),
(90, 'moodle/course:tag', 'write', 50, 'moodle', 16),
(91, 'moodle/blog:view', 'read', 10, 'moodle', 0),
(92, 'moodle/blog:search', 'read', 10, 'moodle', 0),
(93, 'moodle/blog:viewdrafts', 'read', 10, 'moodle', 8),
(94, 'moodle/blog:create', 'write', 10, 'moodle', 16),
(95, 'moodle/blog:manageentries', 'write', 10, 'moodle', 16),
(96, 'moodle/blog:manageexternal', 'write', 10, 'moodle', 16),
(97, 'moodle/blog:associatecourse', 'write', 50, 'moodle', 0),
(98, 'moodle/blog:associatemodule', 'write', 70, 'moodle', 0),
(99, 'moodle/calendar:manageownentries', 'write', 50, 'moodle', 16),
(100, 'moodle/calendar:managegroupentries', 'write', 50, 'moodle', 16),
(101, 'moodle/calendar:manageentries', 'write', 50, 'moodle', 16),
(102, 'moodle/user:editprofile', 'write', 30, 'moodle', 24),
(103, 'moodle/user:editownprofile', 'write', 10, 'moodle', 16),
(104, 'moodle/user:changeownpassword', 'write', 10, 'moodle', 0),
(105, 'moodle/user:readuserposts', 'read', 30, 'moodle', 0),
(106, 'moodle/user:readuserblogs', 'read', 30, 'moodle', 0),
(107, 'moodle/user:viewuseractivitiesreport', 'read', 30, 'moodle', 8),
(108, 'moodle/user:editmessageprofile', 'write', 30, 'moodle', 16),
(109, 'moodle/user:editownmessageprofile', 'write', 10, 'moodle', 0),
(110, 'moodle/question:managecategory', 'write', 50, 'moodle', 20),
(111, 'moodle/question:add', 'write', 50, 'moodle', 20),
(112, 'moodle/question:editmine', 'write', 50, 'moodle', 20),
(113, 'moodle/question:editall', 'write', 50, 'moodle', 20),
(114, 'moodle/question:viewmine', 'read', 50, 'moodle', 0),
(115, 'moodle/question:viewall', 'read', 50, 'moodle', 0),
(116, 'moodle/question:usemine', 'read', 50, 'moodle', 0),
(117, 'moodle/question:useall', 'read', 50, 'moodle', 0),
(118, 'moodle/question:movemine', 'write', 50, 'moodle', 0),
(119, 'moodle/question:moveall', 'write', 50, 'moodle', 0),
(120, 'moodle/question:config', 'write', 10, 'moodle', 2),
(121, 'moodle/question:flag', 'write', 50, 'moodle', 0),
(122, 'moodle/site:doclinks', 'read', 10, 'moodle', 0),
(123, 'moodle/course:sectionvisibility', 'write', 50, 'moodle', 0),
(124, 'moodle/course:useremail', 'write', 50, 'moodle', 0),
(125, 'moodle/course:viewhiddensections', 'write', 50, 'moodle', 0),
(126, 'moodle/course:setcurrentsection', 'write', 50, 'moodle', 0),
(127, 'moodle/course:movesections', 'write', 50, 'moodle', 0),
(128, 'moodle/site:mnetlogintoremote', 'read', 10, 'moodle', 0),
(129, 'moodle/grade:viewall', 'read', 50, 'moodle', 8),
(130, 'moodle/grade:view', 'read', 50, 'moodle', 0),
(131, 'moodle/grade:viewhidden', 'read', 50, 'moodle', 8),
(132, 'moodle/grade:import', 'write', 50, 'moodle', 12),
(133, 'moodle/grade:export', 'read', 50, 'moodle', 8),
(134, 'moodle/grade:manage', 'write', 50, 'moodle', 12),
(135, 'moodle/grade:edit', 'write', 50, 'moodle', 12),
(136, 'moodle/grade:managegradingforms', 'write', 50, 'moodle', 12),
(137, 'moodle/grade:sharegradingforms', 'write', 10, 'moodle', 4),
(138, 'moodle/grade:managesharedforms', 'write', 10, 'moodle', 4),
(139, 'moodle/grade:manageoutcomes', 'write', 50, 'moodle', 0),
(140, 'moodle/grade:manageletters', 'write', 50, 'moodle', 0),
(141, 'moodle/grade:hide', 'write', 50, 'moodle', 0),
(142, 'moodle/grade:lock', 'write', 50, 'moodle', 0),
(143, 'moodle/grade:unlock', 'write', 50, 'moodle', 0),
(144, 'moodle/my:manageblocks', 'write', 10, 'moodle', 0),
(145, 'moodle/notes:view', 'read', 50, 'moodle', 0),
(146, 'moodle/notes:manage', 'write', 50, 'moodle', 16),
(147, 'moodle/tag:manage', 'write', 10, 'moodle', 16),
(148, 'moodle/tag:edit', 'write', 10, 'moodle', 16),
(149, 'moodle/tag:flag', 'write', 10, 'moodle', 16),
(150, 'moodle/tag:editblocks', 'write', 10, 'moodle', 0),
(151, 'moodle/block:view', 'read', 80, 'moodle', 0),
(152, 'moodle/block:edit', 'write', 80, 'moodle', 20),
(153, 'moodle/portfolio:export', 'read', 10, 'moodle', 0),
(154, 'moodle/comment:view', 'read', 50, 'moodle', 0),
(155, 'moodle/comment:post', 'write', 50, 'moodle', 24),
(156, 'moodle/comment:delete', 'write', 50, 'moodle', 32),
(157, 'moodle/webservice:createtoken', 'write', 10, 'moodle', 62),
(158, 'moodle/webservice:createmobiletoken', 'write', 10, 'moodle', 24),
(159, 'moodle/rating:view', 'read', 50, 'moodle', 0),
(160, 'moodle/rating:viewany', 'read', 50, 'moodle', 8),
(161, 'moodle/rating:viewall', 'read', 50, 'moodle', 8),
(162, 'moodle/rating:rate', 'write', 50, 'moodle', 0),
(163, 'moodle/course:publish', 'write', 10, 'moodle', 24),
(164, 'moodle/course:markcomplete', 'write', 50, 'moodle', 0),
(165, 'moodle/community:add', 'write', 10, 'moodle', 0),
(166, 'moodle/community:download', 'write', 10, 'moodle', 0),
(167, 'moodle/badges:manageglobalsettings', 'write', 10, 'moodle', 34),
(168, 'moodle/badges:viewbadges', 'read', 50, 'moodle', 0),
(169, 'moodle/badges:manageownbadges', 'write', 30, 'moodle', 0),
(170, 'moodle/badges:viewotherbadges', 'read', 30, 'moodle', 0),
(171, 'moodle/badges:earnbadge', 'write', 50, 'moodle', 0),
(172, 'moodle/badges:createbadge', 'write', 50, 'moodle', 16),
(173, 'moodle/badges:deletebadge', 'write', 50, 'moodle', 32),
(174, 'moodle/badges:configuredetails', 'write', 50, 'moodle', 16),
(175, 'moodle/badges:configurecriteria', 'write', 50, 'moodle', 4),
(176, 'moodle/badges:configuremessages', 'write', 50, 'moodle', 16),
(177, 'moodle/badges:awardbadge', 'write', 50, 'moodle', 16),
(178, 'moodle/badges:viewawarded', 'read', 50, 'moodle', 8),
(179, 'moodle/site:forcelanguage', 'read', 10, 'moodle', 0),
(180, 'mod/assign:view', 'read', 70, 'mod_assign', 0),
(181, 'mod/assign:submit', 'write', 70, 'mod_assign', 0),
(182, 'mod/assign:grade', 'write', 70, 'mod_assign', 4),
(183, 'mod/assign:exportownsubmission', 'read', 70, 'mod_assign', 0),
(184, 'mod/assign:addinstance', 'write', 50, 'mod_assign', 4),
(185, 'mod/assign:editothersubmission', 'write', 70, 'mod_assign', 41),
(186, 'mod/assign:grantextension', 'write', 70, 'mod_assign', 0),
(187, 'mod/assign:revealidentities', 'write', 70, 'mod_assign', 0),
(188, 'mod/assign:reviewgrades', 'write', 70, 'mod_assign', 0),
(189, 'mod/assign:releasegrades', 'write', 70, 'mod_assign', 0),
(190, 'mod/assign:managegrades', 'write', 70, 'mod_assign', 0),
(191, 'mod/assign:manageallocations', 'write', 70, 'mod_assign', 0),
(192, 'mod/assign:viewgrades', 'read', 70, 'mod_assign', 0),
(193, 'mod/assign:viewblinddetails', 'write', 70, 'mod_assign', 8),
(194, 'mod/assign:receivegradernotifications', 'read', 70, 'mod_assign', 0),
(195, 'mod/assignment:view', 'read', 70, 'mod_assignment', 0),
(196, 'mod/assignment:addinstance', 'write', 50, 'mod_assignment', 4),
(197, 'mod/assignment:submit', 'write', 70, 'mod_assignment', 0),
(198, 'mod/assignment:grade', 'write', 70, 'mod_assignment', 4),
(199, 'mod/assignment:exportownsubmission', 'read', 70, 'mod_assignment', 0),
(200, 'mod/book:addinstance', 'write', 50, 'mod_book', 4),
(201, 'mod/book:read', 'read', 70, 'mod_book', 0),
(202, 'mod/book:viewhiddenchapters', 'read', 70, 'mod_book', 0),
(203, 'mod/book:edit', 'write', 70, 'mod_book', 4),
(204, 'mod/chat:addinstance', 'write', 50, 'mod_chat', 4),
(205, 'mod/chat:chat', 'write', 70, 'mod_chat', 16),
(206, 'mod/chat:readlog', 'read', 70, 'mod_chat', 0),
(207, 'mod/chat:deletelog', 'write', 70, 'mod_chat', 0),
(208, 'mod/chat:exportparticipatedsession', 'read', 70, 'mod_chat', 8),
(209, 'mod/chat:exportsession', 'read', 70, 'mod_chat', 8),
(210, 'mod/choice:addinstance', 'write', 50, 'mod_choice', 4),
(211, 'mod/choice:choose', 'write', 70, 'mod_choice', 0),
(212, 'mod/choice:readresponses', 'read', 70, 'mod_choice', 0),
(213, 'mod/choice:deleteresponses', 'write', 70, 'mod_choice', 0),
(214, 'mod/choice:downloadresponses', 'read', 70, 'mod_choice', 0),
(215, 'mod/data:addinstance', 'write', 50, 'mod_data', 4),
(216, 'mod/data:viewentry', 'read', 70, 'mod_data', 0),
(217, 'mod/data:writeentry', 'write', 70, 'mod_data', 16),
(218, 'mod/data:comment', 'write', 70, 'mod_data', 16),
(219, 'mod/data:rate', 'write', 70, 'mod_data', 0),
(220, 'mod/data:viewrating', 'read', 70, 'mod_data', 0),
(221, 'mod/data:viewanyrating', 'read', 70, 'mod_data', 8),
(222, 'mod/data:viewallratings', 'read', 70, 'mod_data', 8),
(223, 'mod/data:approve', 'write', 70, 'mod_data', 16),
(224, 'mod/data:manageentries', 'write', 70, 'mod_data', 16),
(225, 'mod/data:managecomments', 'write', 70, 'mod_data', 16),
(226, 'mod/data:managetemplates', 'write', 70, 'mod_data', 20),
(227, 'mod/data:viewalluserpresets', 'read', 70, 'mod_data', 0),
(228, 'mod/data:manageuserpresets', 'write', 70, 'mod_data', 20),
(229, 'mod/data:exportentry', 'read', 70, 'mod_data', 8),
(230, 'mod/data:exportownentry', 'read', 70, 'mod_data', 0),
(231, 'mod/data:exportallentries', 'read', 70, 'mod_data', 8),
(232, 'mod/data:exportuserinfo', 'read', 70, 'mod_data', 8),
(233, 'mod/feedback:addinstance', 'write', 50, 'mod_feedback', 4),
(234, 'mod/feedback:view', 'read', 70, 'mod_feedback', 0),
(235, 'mod/feedback:complete', 'write', 70, 'mod_feedback', 16),
(236, 'mod/feedback:viewanalysepage', 'read', 70, 'mod_feedback', 8),
(237, 'mod/feedback:deletesubmissions', 'write', 70, 'mod_feedback', 0),
(238, 'mod/feedback:mapcourse', 'write', 70, 'mod_feedback', 0),
(239, 'mod/feedback:edititems', 'write', 70, 'mod_feedback', 20),
(240, 'mod/feedback:createprivatetemplate', 'write', 70, 'mod_feedback', 16),
(241, 'mod/feedback:createpublictemplate', 'write', 70, 'mod_feedback', 16),
(242, 'mod/feedback:deletetemplate', 'write', 70, 'mod_feedback', 0),
(243, 'mod/feedback:viewreports', 'read', 70, 'mod_feedback', 8),
(244, 'mod/feedback:receivemail', 'read', 70, 'mod_feedback', 8),
(245, 'mod/folder:addinstance', 'write', 50, 'mod_folder', 4),
(246, 'mod/folder:view', 'read', 70, 'mod_folder', 0),
(247, 'mod/folder:managefiles', 'write', 70, 'mod_folder', 16),
(248, 'mod/forum:addinstance', 'write', 50, 'mod_forum', 4),
(249, 'mod/forum:viewdiscussion', 'read', 70, 'mod_forum', 0),
(250, 'mod/forum:viewhiddentimedposts', 'read', 70, 'mod_forum', 0),
(251, 'mod/forum:startdiscussion', 'write', 70, 'mod_forum', 16),
(252, 'mod/forum:replypost', 'write', 70, 'mod_forum', 16),
(253, 'mod/forum:addnews', 'write', 70, 'mod_forum', 16),
(254, 'mod/forum:replynews', 'write', 70, 'mod_forum', 16),
(255, 'mod/forum:viewrating', 'read', 70, 'mod_forum', 0),
(256, 'mod/forum:viewanyrating', 'read', 70, 'mod_forum', 8),
(257, 'mod/forum:viewallratings', 'read', 70, 'mod_forum', 8),
(258, 'mod/forum:rate', 'write', 70, 'mod_forum', 0),
(259, 'mod/forum:createattachment', 'write', 70, 'mod_forum', 16),
(260, 'mod/forum:deleteownpost', 'read', 70, 'mod_forum', 0),
(261, 'mod/forum:deleteanypost', 'read', 70, 'mod_forum', 0),
(262, 'mod/forum:splitdiscussions', 'read', 70, 'mod_forum', 0),
(263, 'mod/forum:movediscussions', 'read', 70, 'mod_forum', 0),
(264, 'mod/forum:editanypost', 'write', 70, 'mod_forum', 16),
(265, 'mod/forum:viewqandawithoutposting', 'read', 70, 'mod_forum', 0),
(266, 'mod/forum:viewsubscribers', 'read', 70, 'mod_forum', 0),
(267, 'mod/forum:managesubscriptions', 'read', 70, 'mod_forum', 16),
(268, 'mod/forum:postwithoutthrottling', 'write', 70, 'mod_forum', 16),
(269, 'mod/forum:exportdiscussion', 'read', 70, 'mod_forum', 8),
(270, 'mod/forum:exportpost', 'read', 70, 'mod_forum', 8),
(271, 'mod/forum:exportownpost', 'read', 70, 'mod_forum', 8),
(272, 'mod/forum:addquestion', 'write', 70, 'mod_forum', 16),
(273, 'mod/forum:allowforcesubscribe', 'read', 70, 'mod_forum', 0),
(274, 'mod/forum:canposttomygroups', 'write', 70, 'mod_forum', 0),
(275, 'mod/glossary:addinstance', 'write', 50, 'mod_glossary', 4),
(276, 'mod/glossary:view', 'read', 70, 'mod_glossary', 0),
(277, 'mod/glossary:write', 'write', 70, 'mod_glossary', 16),
(278, 'mod/glossary:manageentries', 'write', 70, 'mod_glossary', 16),
(279, 'mod/glossary:managecategories', 'write', 70, 'mod_glossary', 16),
(280, 'mod/glossary:comment', 'write', 70, 'mod_glossary', 16),
(281, 'mod/glossary:managecomments', 'write', 70, 'mod_glossary', 16),
(282, 'mod/glossary:import', 'write', 70, 'mod_glossary', 16),
(283, 'mod/glossary:export', 'read', 70, 'mod_glossary', 0),
(284, 'mod/glossary:approve', 'write', 70, 'mod_glossary', 16),
(285, 'mod/glossary:rate', 'write', 70, 'mod_glossary', 0),
(286, 'mod/glossary:viewrating', 'read', 70, 'mod_glossary', 0),
(287, 'mod/glossary:viewanyrating', 'read', 70, 'mod_glossary', 8),
(288, 'mod/glossary:viewallratings', 'read', 70, 'mod_glossary', 8),
(289, 'mod/glossary:exportentry', 'read', 70, 'mod_glossary', 8),
(290, 'mod/glossary:exportownentry', 'read', 70, 'mod_glossary', 0),
(291, 'mod/imscp:view', 'read', 70, 'mod_imscp', 0),
(292, 'mod/imscp:addinstance', 'write', 50, 'mod_imscp', 4),
(293, 'mod/label:addinstance', 'write', 50, 'mod_label', 4),
(294, 'mod/lesson:addinstance', 'write', 50, 'mod_lesson', 4),
(295, 'mod/lesson:edit', 'write', 70, 'mod_lesson', 4),
(296, 'mod/lesson:grade', 'write', 70, 'mod_lesson', 20),
(297, 'mod/lesson:viewreports', 'read', 70, 'mod_lesson', 8),
(298, 'mod/lesson:manage', 'write', 70, 'mod_lesson', 0),
(299, 'mod/lesson:manageoverrides', 'write', 70, 'mod_lesson', 0),
(300, 'mod/lti:view', 'read', 70, 'mod_lti', 0),
(301, 'mod/lti:addinstance', 'write', 50, 'mod_lti', 4),
(302, 'mod/lti:manage', 'write', 70, 'mod_lti', 8),
(303, 'mod/lti:addcoursetool', 'write', 50, 'mod_lti', 0),
(304, 'mod/lti:requesttooladd', 'write', 50, 'mod_lti', 0),
(305, 'mod/page:view', 'read', 70, 'mod_page', 0),
(306, 'mod/page:addinstance', 'write', 50, 'mod_page', 4),
(307, 'mod/quiz:view', 'read', 70, 'mod_quiz', 0),
(308, 'mod/quiz:addinstance', 'write', 50, 'mod_quiz', 4),
(309, 'mod/quiz:attempt', 'write', 70, 'mod_quiz', 16),
(310, 'mod/quiz:reviewmyattempts', 'read', 70, 'mod_quiz', 0),
(311, 'mod/quiz:manage', 'write', 70, 'mod_quiz', 16),
(312, 'mod/quiz:manageoverrides', 'write', 70, 'mod_quiz', 0),
(313, 'mod/quiz:preview', 'write', 70, 'mod_quiz', 0),
(314, 'mod/quiz:grade', 'write', 70, 'mod_quiz', 20),
(315, 'mod/quiz:regrade', 'write', 70, 'mod_quiz', 16),
(316, 'mod/quiz:viewreports', 'read', 70, 'mod_quiz', 8),
(317, 'mod/quiz:deleteattempts', 'write', 70, 'mod_quiz', 32),
(318, 'mod/quiz:ignoretimelimits', 'read', 70, 'mod_quiz', 0),
(319, 'mod/quiz:emailconfirmsubmission', 'read', 70, 'mod_quiz', 0),
(320, 'mod/quiz:emailnotifysubmission', 'read', 70, 'mod_quiz', 0),
(321, 'mod/quiz:emailwarnoverdue', 'read', 70, 'mod_quiz', 0),
(322, 'mod/resource:view', 'read', 70, 'mod_resource', 0),
(323, 'mod/resource:addinstance', 'write', 50, 'mod_resource', 4),
(324, 'mod/scorm:addinstance', 'write', 50, 'mod_scorm', 4),
(325, 'mod/scorm:viewreport', 'read', 70, 'mod_scorm', 0),
(326, 'mod/scorm:skipview', 'read', 70, 'mod_scorm', 0),
(327, 'mod/scorm:savetrack', 'write', 70, 'mod_scorm', 0),
(328, 'mod/scorm:viewscores', 'read', 70, 'mod_scorm', 0),
(329, 'mod/scorm:deleteresponses', 'write', 70, 'mod_scorm', 0),
(330, 'mod/scorm:deleteownresponses', 'write', 70, 'mod_scorm', 0),
(331, 'mod/survey:addinstance', 'write', 50, 'mod_survey', 4),
(332, 'mod/survey:participate', 'read', 70, 'mod_survey', 0),
(333, 'mod/survey:readresponses', 'read', 70, 'mod_survey', 0),
(334, 'mod/survey:download', 'read', 70, 'mod_survey', 0),
(335, 'mod/url:view', 'read', 70, 'mod_url', 0),
(336, 'mod/url:addinstance', 'write', 50, 'mod_url', 4),
(337, 'mod/wiki:addinstance', 'write', 50, 'mod_wiki', 4),
(338, 'mod/wiki:viewpage', 'read', 70, 'mod_wiki', 0),
(339, 'mod/wiki:editpage', 'write', 70, 'mod_wiki', 16),
(340, 'mod/wiki:createpage', 'write', 70, 'mod_wiki', 16),
(341, 'mod/wiki:viewcomment', 'read', 70, 'mod_wiki', 0),
(342, 'mod/wiki:editcomment', 'write', 70, 'mod_wiki', 16),
(343, 'mod/wiki:managecomment', 'write', 70, 'mod_wiki', 0),
(344, 'mod/wiki:managefiles', 'write', 70, 'mod_wiki', 0),
(345, 'mod/wiki:overridelock', 'write', 70, 'mod_wiki', 0),
(346, 'mod/wiki:managewiki', 'write', 70, 'mod_wiki', 0),
(347, 'mod/workshop:view', 'read', 70, 'mod_workshop', 0),
(348, 'mod/workshop:addinstance', 'write', 50, 'mod_workshop', 4),
(349, 'mod/workshop:switchphase', 'write', 70, 'mod_workshop', 0),
(350, 'mod/workshop:editdimensions', 'write', 70, 'mod_workshop', 4),
(351, 'mod/workshop:submit', 'write', 70, 'mod_workshop', 0),
(352, 'mod/workshop:peerassess', 'write', 70, 'mod_workshop', 0),
(353, 'mod/workshop:manageexamples', 'write', 70, 'mod_workshop', 0),
(354, 'mod/workshop:allocate', 'write', 70, 'mod_workshop', 0),
(355, 'mod/workshop:publishsubmissions', 'write', 70, 'mod_workshop', 0),
(356, 'mod/workshop:viewauthornames', 'read', 70, 'mod_workshop', 0),
(357, 'mod/workshop:viewreviewernames', 'read', 70, 'mod_workshop', 0),
(358, 'mod/workshop:viewallsubmissions', 'read', 70, 'mod_workshop', 0),
(359, 'mod/workshop:viewpublishedsubmissions', 'read', 70, 'mod_workshop', 0),
(360, 'mod/workshop:viewauthorpublished', 'read', 70, 'mod_workshop', 0),
(361, 'mod/workshop:viewallassessments', 'read', 70, 'mod_workshop', 0),
(362, 'mod/workshop:overridegrades', 'write', 70, 'mod_workshop', 0),
(363, 'mod/workshop:ignoredeadlines', 'write', 70, 'mod_workshop', 0),
(364, 'enrol/category:synchronised', 'write', 10, 'enrol_category', 0),
(365, 'enrol/category:config', 'write', 50, 'enrol_category', 0),
(366, 'enrol/cohort:config', 'write', 50, 'enrol_cohort', 0),
(367, 'enrol/cohort:unenrol', 'write', 50, 'enrol_cohort', 0),
(368, 'enrol/database:unenrol', 'write', 50, 'enrol_database', 0),
(369, 'enrol/database:config', 'write', 50, 'enrol_database', 0),
(370, 'enrol/flatfile:manage', 'write', 50, 'enrol_flatfile', 0),
(371, 'enrol/flatfile:unenrol', 'write', 50, 'enrol_flatfile', 0),
(372, 'enrol/guest:config', 'write', 50, 'enrol_guest', 0),
(373, 'enrol/imsenterprise:config', 'write', 50, 'enrol_imsenterprise', 0),
(374, 'enrol/ldap:manage', 'write', 50, 'enrol_ldap', 0),
(375, 'enrol/manual:config', 'write', 50, 'enrol_manual', 0),
(376, 'enrol/manual:enrol', 'write', 50, 'enrol_manual', 0),
(377, 'enrol/manual:manage', 'write', 50, 'enrol_manual', 0),
(378, 'enrol/manual:unenrol', 'write', 50, 'enrol_manual', 0),
(379, 'enrol/manual:unenrolself', 'write', 50, 'enrol_manual', 0),
(380, 'enrol/meta:config', 'write', 50, 'enrol_meta', 0),
(381, 'enrol/meta:selectaslinked', 'read', 50, 'enrol_meta', 0),
(382, 'enrol/meta:unenrol', 'write', 50, 'enrol_meta', 0),
(383, 'enrol/mnet:config', 'write', 50, 'enrol_mnet', 0),
(384, 'enrol/paypal:config', 'write', 50, 'enrol_paypal', 0),
(385, 'enrol/paypal:manage', 'write', 50, 'enrol_paypal', 0),
(386, 'enrol/paypal:unenrol', 'write', 50, 'enrol_paypal', 0),
(387, 'enrol/paypal:unenrolself', 'write', 50, 'enrol_paypal', 0),
(388, 'enrol/self:config', 'write', 50, 'enrol_self', 0),
(389, 'enrol/self:manage', 'write', 50, 'enrol_self', 0),
(390, 'enrol/self:holdkey', 'write', 50, 'enrol_self', 0),
(391, 'enrol/self:unenrolself', 'write', 50, 'enrol_self', 0),
(392, 'enrol/self:unenrol', 'write', 50, 'enrol_self', 0),
(393, 'message/airnotifier:managedevice', 'write', 10, 'message_airnotifier', 0),
(394, 'block/activity_modules:addinstance', 'write', 80, 'block_activity_modules', 20),
(395, 'block/activity_results:addinstance', 'write', 80, 'block_activity_results', 20),
(396, 'block/admin_bookmarks:myaddinstance', 'write', 10, 'block_admin_bookmarks', 0),
(397, 'block/admin_bookmarks:addinstance', 'write', 80, 'block_admin_bookmarks', 20),
(398, 'block/badges:addinstance', 'read', 80, 'block_badges', 0),
(399, 'block/badges:myaddinstance', 'read', 10, 'block_badges', 8),
(400, 'block/blog_menu:addinstance', 'write', 80, 'block_blog_menu', 20),
(401, 'block/blog_recent:addinstance', 'write', 80, 'block_blog_recent', 20),
(402, 'block/blog_tags:addinstance', 'write', 80, 'block_blog_tags', 20),
(403, 'block/calendar_month:myaddinstance', 'write', 10, 'block_calendar_month', 0),
(404, 'block/calendar_month:addinstance', 'write', 80, 'block_calendar_month', 20),
(405, 'block/calendar_upcoming:myaddinstance', 'write', 10, 'block_calendar_upcoming', 0),
(406, 'block/calendar_upcoming:addinstance', 'write', 80, 'block_calendar_upcoming', 20),
(407, 'block/comments:myaddinstance', 'write', 10, 'block_comments', 0),
(408, 'block/comments:addinstance', 'write', 80, 'block_comments', 20),
(409, 'block/community:myaddinstance', 'write', 10, 'block_community', 0),
(410, 'block/community:addinstance', 'write', 80, 'block_community', 20),
(411, 'block/completionstatus:addinstance', 'write', 80, 'block_completionstatus', 20),
(412, 'block/course_list:myaddinstance', 'write', 10, 'block_course_list', 0),
(413, 'block/course_list:addinstance', 'write', 80, 'block_course_list', 20),
(414, 'block/course_overview:myaddinstance', 'write', 10, 'block_course_overview', 0),
(415, 'block/course_overview:addinstance', 'write', 80, 'block_course_overview', 20),
(416, 'block/course_summary:addinstance', 'write', 80, 'block_course_summary', 20),
(417, 'block/feedback:addinstance', 'write', 80, 'block_feedback', 20),
(418, 'block/glossary_random:myaddinstance', 'write', 10, 'block_glossary_random', 0),
(419, 'block/glossary_random:addinstance', 'write', 80, 'block_glossary_random', 20),
(420, 'block/html:myaddinstance', 'write', 10, 'block_html', 0),
(421, 'block/html:addinstance', 'write', 80, 'block_html', 20),
(422, 'block/login:addinstance', 'write', 80, 'block_login', 20),
(423, 'block/mentees:myaddinstance', 'write', 10, 'block_mentees', 0),
(424, 'block/mentees:addinstance', 'write', 80, 'block_mentees', 20),
(425, 'block/messages:myaddinstance', 'write', 10, 'block_messages', 0),
(426, 'block/messages:addinstance', 'write', 80, 'block_messages', 20),
(427, 'block/mnet_hosts:myaddinstance', 'write', 10, 'block_mnet_hosts', 0),
(428, 'block/mnet_hosts:addinstance', 'write', 80, 'block_mnet_hosts', 20),
(429, 'block/myprofile:myaddinstance', 'write', 10, 'block_myprofile', 0),
(430, 'block/myprofile:addinstance', 'write', 80, 'block_myprofile', 20),
(431, 'block/navigation:myaddinstance', 'write', 10, 'block_navigation', 0),
(432, 'block/navigation:addinstance', 'write', 80, 'block_navigation', 20),
(433, 'block/news_items:myaddinstance', 'write', 10, 'block_news_items', 0),
(434, 'block/news_items:addinstance', 'write', 80, 'block_news_items', 20),
(435, 'block/online_users:myaddinstance', 'write', 10, 'block_online_users', 0),
(436, 'block/online_users:addinstance', 'write', 80, 'block_online_users', 20),
(437, 'block/online_users:viewlist', 'read', 80, 'block_online_users', 0),
(438, 'block/participants:addinstance', 'write', 80, 'block_participants', 20),
(439, 'block/private_files:myaddinstance', 'write', 10, 'block_private_files', 0),
(440, 'block/private_files:addinstance', 'write', 80, 'block_private_files', 20),
(441, 'block/quiz_results:addinstance', 'write', 80, 'block_quiz_results', 20),
(442, 'block/recent_activity:addinstance', 'write', 80, 'block_recent_activity', 20),
(443, 'block/recent_activity:viewaddupdatemodule', 'read', 50, 'block_recent_activity', 0),
(444, 'block/recent_activity:viewdeletemodule', 'read', 50, 'block_recent_activity', 0),
(445, 'block/rss_client:myaddinstance', 'write', 10, 'block_rss_client', 0),
(446, 'block/rss_client:addinstance', 'write', 80, 'block_rss_client', 20),
(447, 'block/rss_client:manageownfeeds', 'write', 80, 'block_rss_client', 0),
(448, 'block/rss_client:manageanyfeeds', 'write', 80, 'block_rss_client', 16),
(449, 'block/search_forums:addinstance', 'write', 80, 'block_search_forums', 20),
(450, 'block/section_links:addinstance', 'write', 80, 'block_section_links', 20),
(451, 'block/selfcompletion:addinstance', 'write', 80, 'block_selfcompletion', 20),
(452, 'block/settings:myaddinstance', 'write', 10, 'block_settings', 0),
(453, 'block/settings:addinstance', 'write', 80, 'block_settings', 20),
(454, 'block/site_main_menu:addinstance', 'write', 80, 'block_site_main_menu', 20),
(455, 'block/social_activities:addinstance', 'write', 80, 'block_social_activities', 20),
(456, 'block/tag_flickr:addinstance', 'write', 80, 'block_tag_flickr', 20),
(457, 'block/tag_youtube:addinstance', 'write', 80, 'block_tag_youtube', 20),
(458, 'block/tags:myaddinstance', 'write', 10, 'block_tags', 0),
(459, 'block/tags:addinstance', 'write', 80, 'block_tags', 20),
(460, 'report/completion:view', 'read', 50, 'report_completion', 8),
(461, 'report/courseoverview:view', 'read', 10, 'report_courseoverview', 8),
(462, 'report/log:view', 'read', 50, 'report_log', 8),
(463, 'report/log:viewtoday', 'read', 50, 'report_log', 8),
(464, 'report/loglive:view', 'read', 50, 'report_loglive', 8),
(465, 'report/outline:view', 'read', 50, 'report_outline', 8),
(466, 'report/participation:view', 'read', 50, 'report_participation', 8),
(467, 'report/performance:view', 'read', 10, 'report_performance', 2),
(468, 'report/progress:view', 'read', 50, 'report_progress', 8),
(469, 'report/questioninstances:view', 'read', 10, 'report_questioninstances', 0),
(470, 'report/security:view', 'read', 10, 'report_security', 2),
(471, 'report/stats:view', 'read', 50, 'report_stats', 8),
(472, 'report/usersessions:manageownsessions', 'write', 30, 'report_usersessions', 0),
(473, 'gradeexport/ods:view', 'read', 50, 'gradeexport_ods', 8),
(474, 'gradeexport/ods:publish', 'read', 50, 'gradeexport_ods', 8),
(475, 'gradeexport/txt:view', 'read', 50, 'gradeexport_txt', 8),
(476, 'gradeexport/txt:publish', 'read', 50, 'gradeexport_txt', 8),
(477, 'gradeexport/xls:view', 'read', 50, 'gradeexport_xls', 8),
(478, 'gradeexport/xls:publish', 'read', 50, 'gradeexport_xls', 8),
(479, 'gradeexport/xml:view', 'read', 50, 'gradeexport_xml', 8),
(480, 'gradeexport/xml:publish', 'read', 50, 'gradeexport_xml', 8),
(481, 'gradeimport/csv:view', 'write', 50, 'gradeimport_csv', 0),
(482, 'gradeimport/direct:view', 'write', 50, 'gradeimport_direct', 0),
(483, 'gradeimport/xml:view', 'write', 50, 'gradeimport_xml', 0),
(484, 'gradeimport/xml:publish', 'write', 50, 'gradeimport_xml', 0),
(485, 'gradereport/grader:view', 'read', 50, 'gradereport_grader', 8),
(486, 'gradereport/history:view', 'read', 50, 'gradereport_history', 8),
(487, 'gradereport/outcomes:view', 'read', 50, 'gradereport_outcomes', 8),
(488, 'gradereport/overview:view', 'read', 50, 'gradereport_overview', 8),
(489, 'gradereport/singleview:view', 'read', 50, 'gradereport_singleview', 8),
(490, 'gradereport/user:view', 'read', 50, 'gradereport_user', 8),
(491, 'webservice/amf:use', 'read', 50, 'webservice_amf', 0),
(492, 'webservice/rest:use', 'read', 50, 'webservice_rest', 0),
(493, 'webservice/soap:use', 'read', 50, 'webservice_soap', 0),
(494, 'webservice/xmlrpc:use', 'read', 50, 'webservice_xmlrpc', 0),
(495, 'repository/alfresco:view', 'read', 70, 'repository_alfresco', 0),
(496, 'repository/areafiles:view', 'read', 70, 'repository_areafiles', 0),
(497, 'repository/boxnet:view', 'read', 70, 'repository_boxnet', 0),
(498, 'repository/coursefiles:view', 'read', 70, 'repository_coursefiles', 0),
(499, 'repository/dropbox:view', 'read', 70, 'repository_dropbox', 0),
(500, 'repository/equella:view', 'read', 70, 'repository_equella', 0),
(501, 'repository/filesystem:view', 'read', 70, 'repository_filesystem', 0),
(502, 'repository/flickr:view', 'read', 70, 'repository_flickr', 0),
(503, 'repository/flickr_public:view', 'read', 70, 'repository_flickr_public', 0),
(504, 'repository/googledocs:view', 'read', 70, 'repository_googledocs', 0),
(505, 'repository/local:view', 'read', 70, 'repository_local', 0),
(506, 'repository/merlot:view', 'read', 70, 'repository_merlot', 0),
(507, 'repository/picasa:view', 'read', 70, 'repository_picasa', 0),
(508, 'repository/recent:view', 'read', 70, 'repository_recent', 0),
(509, 'repository/s3:view', 'read', 70, 'repository_s3', 0),
(510, 'repository/skydrive:view', 'read', 70, 'repository_skydrive', 0),
(511, 'repository/upload:view', 'read', 70, 'repository_upload', 0),
(512, 'repository/url:view', 'read', 70, 'repository_url', 0),
(513, 'repository/user:view', 'read', 70, 'repository_user', 0),
(514, 'repository/webdav:view', 'read', 70, 'repository_webdav', 0),
(515, 'repository/wikimedia:view', 'read', 70, 'repository_wikimedia', 0),
(516, 'repository/youtube:view', 'read', 70, 'repository_youtube', 0),
(517, 'tool/customlang:view', 'read', 10, 'tool_customlang', 2),
(518, 'tool/customlang:edit', 'write', 10, 'tool_customlang', 6),
(519, 'tool/monitor:subscribe', 'read', 50, 'tool_monitor', 8),
(520, 'tool/monitor:managerules', 'write', 50, 'tool_monitor', 4),
(521, 'tool/monitor:managetool', 'write', 10, 'tool_monitor', 4),
(522, 'tool/uploaduser:uploaduserpictures', 'write', 10, 'tool_uploaduser', 16),
(523, 'booktool/exportimscp:export', 'read', 70, 'booktool_exportimscp', 0),
(524, 'booktool/importhtml:import', 'write', 70, 'booktool_importhtml', 4),
(525, 'booktool/print:print', 'read', 70, 'booktool_print', 0),
(526, 'quiz/grading:viewstudentnames', 'read', 70, 'quiz_grading', 0),
(527, 'quiz/grading:viewidnumber', 'read', 70, 'quiz_grading', 0),
(528, 'quiz/statistics:view', 'read', 70, 'quiz_statistics', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_chat`
--

CREATE TABLE IF NOT EXISTS `mdl_chat` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `keepdays` bigint(11) NOT NULL DEFAULT '0',
  `studentlogs` smallint(4) NOT NULL DEFAULT '0',
  `chattime` bigint(10) NOT NULL DEFAULT '0',
  `schedule` smallint(4) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_chat_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each of these is a chat room' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_chat_messages`
--

CREATE TABLE IF NOT EXISTS `mdl_chat_messages` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `chatid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `timestamp` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_chatmess_use_ix` (`userid`),
  KEY `mdl_chatmess_gro_ix` (`groupid`),
  KEY `mdl_chatmess_timcha_ix` (`timestamp`,`chatid`),
  KEY `mdl_chatmess_cha_ix` (`chatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores all the actual chat messages' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_chat_messages_current`
--

CREATE TABLE IF NOT EXISTS `mdl_chat_messages_current` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `chatid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `timestamp` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_chatmesscurr_use_ix` (`userid`),
  KEY `mdl_chatmesscurr_gro_ix` (`groupid`),
  KEY `mdl_chatmesscurr_timcha_ix` (`timestamp`,`chatid`),
  KEY `mdl_chatmesscurr_cha_ix` (`chatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores current session' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_chat_users`
--

CREATE TABLE IF NOT EXISTS `mdl_chat_users` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `chatid` bigint(11) NOT NULL DEFAULT '0',
  `userid` bigint(11) NOT NULL DEFAULT '0',
  `groupid` bigint(11) NOT NULL DEFAULT '0',
  `version` varchar(16) NOT NULL DEFAULT '',
  `ip` varchar(45) NOT NULL DEFAULT '',
  `firstping` bigint(10) NOT NULL DEFAULT '0',
  `lastping` bigint(10) NOT NULL DEFAULT '0',
  `lastmessageping` bigint(10) NOT NULL DEFAULT '0',
  `sid` varchar(32) NOT NULL DEFAULT '',
  `course` bigint(10) NOT NULL DEFAULT '0',
  `lang` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_chatuser_use_ix` (`userid`),
  KEY `mdl_chatuser_las_ix` (`lastping`),
  KEY `mdl_chatuser_gro_ix` (`groupid`),
  KEY `mdl_chatuser_cha_ix` (`chatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Keeps track of which users are in which chat rooms' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_choice`
--

CREATE TABLE IF NOT EXISTS `mdl_choice` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `publish` tinyint(2) NOT NULL DEFAULT '0',
  `showresults` tinyint(2) NOT NULL DEFAULT '0',
  `display` smallint(4) NOT NULL DEFAULT '0',
  `allowupdate` tinyint(2) NOT NULL DEFAULT '0',
  `allowmultiple` tinyint(2) NOT NULL DEFAULT '0',
  `showunanswered` tinyint(2) NOT NULL DEFAULT '0',
  `includeinactive` tinyint(2) NOT NULL DEFAULT '1',
  `limitanswers` tinyint(2) NOT NULL DEFAULT '0',
  `timeopen` bigint(10) NOT NULL DEFAULT '0',
  `timeclose` bigint(10) NOT NULL DEFAULT '0',
  `showpreview` tinyint(2) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `completionsubmit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_choi_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Available choices are stored here' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_choice_answers`
--

CREATE TABLE IF NOT EXISTS `mdl_choice_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `choiceid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `optionid` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_choiansw_use_ix` (`userid`),
  KEY `mdl_choiansw_cho_ix` (`choiceid`),
  KEY `mdl_choiansw_opt_ix` (`optionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='choices performed by users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_choice_options`
--

CREATE TABLE IF NOT EXISTS `mdl_choice_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `choiceid` bigint(10) NOT NULL DEFAULT '0',
  `text` longtext,
  `maxanswers` bigint(10) DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_choiopti_cho_ix` (`choiceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='available options to choice' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_cohort`
--

CREATE TABLE IF NOT EXISTS `mdl_cohort` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `name` varchar(254) NOT NULL DEFAULT '',
  `idnumber` varchar(100) DEFAULT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `component` varchar(100) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_coho_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each record represents one cohort (aka site-wide group).' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_cohort_members`
--

CREATE TABLE IF NOT EXISTS `mdl_cohort_members` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `cohortid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timeadded` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_cohomemb_cohuse_uix` (`cohortid`,`userid`),
  KEY `mdl_cohomemb_coh_ix` (`cohortid`),
  KEY `mdl_cohomemb_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Link a user to a cohort.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_comments`
--

CREATE TABLE IF NOT EXISTS `mdl_comments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `component` varchar(255) DEFAULT NULL,
  `commentarea` varchar(255) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL,
  `content` longtext NOT NULL,
  `format` tinyint(2) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='moodle comments module' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_config`
--

CREATE TABLE IF NOT EXISTS `mdl_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_conf_nam_uix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Moodle configuration variables' AUTO_INCREMENT=474 ;

--
-- Dumping data for table `mdl_config`
--

INSERT INTO `mdl_config` (`id`, `name`, `value`) VALUES
(2, 'rolesactive', '1'),
(3, 'auth', 'email'),
(4, 'auth_pop3mailbox', 'INBOX'),
(5, 'enrol_plugins_enabled', 'manual,self,guest,cohort'),
(6, 'theme', 'lambda'),
(7, 'filter_multilang_converted', '1'),
(8, 'siteidentifier', 'DsPShqYVFCGzeFwhmNUzHIj0DbxGMlW6mycodebusters.com'),
(9, 'backup_version', '2008111700'),
(10, 'backup_release', '2.0 dev'),
(11, 'mnet_dispatcher_mode', 'off'),
(12, 'sessiontimeout', '7200'),
(13, 'stringfilters', ''),
(14, 'filterall', '0'),
(15, 'texteditors', 'atto,tinymce,textarea'),
(16, 'upgrade_minmaxgradestepignored', '1'),
(17, 'upgrade_extracreditweightsstepignored', '1'),
(18, 'upgrade_calculatedgradeitemsignored', '1'),
(19, 'mnet_localhost_id', '1'),
(20, 'mnet_all_hosts_id', '2'),
(21, 'siteguest', '1'),
(22, 'siteadmins', '2'),
(23, 'themerev', '1453287272'),
(24, 'jsrev', '1453287272'),
(25, 'gdversion', '2'),
(26, 'licenses', 'unknown,allrightsreserved,public,cc,cc-nd,cc-nc-nd,cc-nc,cc-nc-sa,cc-sa'),
(27, 'version', '2015111601.01'),
(28, 'enableoutcomes', '0'),
(29, 'usecomments', '1'),
(30, 'usetags', '1'),
(31, 'enablenotes', '1'),
(32, 'enableportfolios', '0'),
(33, 'enablewebservices', '0'),
(34, 'messaging', '1'),
(35, 'messaginghidereadnotifications', '0'),
(36, 'messagingdeletereadnotificationsdelay', '604800'),
(37, 'messagingallowemailoverride', '0'),
(38, 'enablestats', '0'),
(39, 'enablerssfeeds', '0'),
(40, 'enableblogs', '1'),
(41, 'enablecompletion', '0'),
(42, 'completiondefault', '1'),
(43, 'enableavailability', '0'),
(44, 'enableplagiarism', '0'),
(45, 'enablebadges', '1'),
(46, 'defaultpreference_maildisplay', '2'),
(47, 'defaultpreference_mailformat', '1'),
(48, 'defaultpreference_maildigest', '0'),
(49, 'defaultpreference_autosubscribe', '1'),
(50, 'defaultpreference_trackforums', '0'),
(51, 'autologinguests', '0'),
(52, 'hiddenuserfields', ''),
(53, 'showuseridentity', 'email'),
(54, 'fullnamedisplay', 'language'),
(55, 'alternativefullnameformat', 'language'),
(56, 'maxusersperpage', '100'),
(57, 'enablegravatar', '0'),
(58, 'gravatardefaulturl', 'mm'),
(59, 'enablecourserequests', '0'),
(60, 'defaultrequestcategory', '2'),
(61, 'requestcategoryselection', '0'),
(62, 'courserequestnotify', ''),
(63, 'grade_profilereport', 'user'),
(64, 'grade_aggregationposition', '1'),
(65, 'grade_includescalesinaggregation', '1'),
(66, 'grade_hiddenasdate', '0'),
(67, 'gradepublishing', '0'),
(68, 'grade_export_displaytype', '1'),
(69, 'grade_export_decimalpoints', '2'),
(70, 'grade_navmethod', '0'),
(71, 'grade_export_userprofilefields', 'firstname,lastname,idnumber,institution,department,email'),
(72, 'grade_export_customprofilefields', ''),
(73, 'recovergradesdefault', '0'),
(74, 'gradeexport', ''),
(75, 'unlimitedgrades', '0'),
(76, 'grade_report_showmin', '1'),
(77, 'gradepointmax', '100'),
(78, 'gradepointdefault', '100'),
(79, 'grade_minmaxtouse', '1'),
(80, 'grade_mygrades_report', 'overview'),
(81, 'gradereport_mygradeurl', ''),
(82, 'grade_hideforcedsettings', '1'),
(83, 'grade_aggregation', '13'),
(84, 'grade_aggregation_flag', '0'),
(85, 'grade_aggregations_visible', '13'),
(86, 'grade_aggregateonlygraded', '1'),
(87, 'grade_aggregateonlygraded_flag', '2'),
(88, 'grade_aggregateoutcomes', '0'),
(89, 'grade_aggregateoutcomes_flag', '2'),
(90, 'grade_keephigh', '0'),
(91, 'grade_keephigh_flag', '3'),
(92, 'grade_droplow', '0'),
(93, 'grade_droplow_flag', '2'),
(94, 'grade_overridecat', '1'),
(95, 'grade_displaytype', '1'),
(96, 'grade_decimalpoints', '2'),
(97, 'grade_item_advanced', 'iteminfo,idnumber,gradepass,plusfactor,multfactor,display,decimals,hiddenuntil,locktime'),
(98, 'grade_report_studentsperpage', '100'),
(99, 'grade_report_showonlyactiveenrol', '1'),
(100, 'grade_report_quickgrading', '1'),
(101, 'grade_report_showquickfeedback', '0'),
(102, 'grade_report_meanselection', '1'),
(103, 'grade_report_enableajax', '0'),
(104, 'grade_report_showcalculations', '1'),
(105, 'grade_report_showeyecons', '0'),
(106, 'grade_report_showaverages', '1'),
(107, 'grade_report_showlocks', '0'),
(108, 'grade_report_showranges', '0'),
(109, 'grade_report_showanalysisicon', '1'),
(110, 'grade_report_showuserimage', '1'),
(111, 'grade_report_showactivityicons', '1'),
(112, 'grade_report_shownumberofgrades', '0'),
(113, 'grade_report_averagesdisplaytype', 'inherit'),
(114, 'grade_report_rangesdisplaytype', 'inherit'),
(115, 'grade_report_averagesdecimalpoints', 'inherit'),
(116, 'grade_report_rangesdecimalpoints', 'inherit'),
(117, 'grade_report_historyperpage', '50'),
(118, 'grade_report_overview_showrank', '0'),
(119, 'grade_report_overview_showtotalsifcontainhidden', '0'),
(120, 'grade_report_user_showrank', '0'),
(121, 'grade_report_user_showpercentage', '1'),
(122, 'grade_report_user_showgrade', '1'),
(123, 'grade_report_user_showfeedback', '1'),
(124, 'grade_report_user_showrange', '1'),
(125, 'grade_report_user_showweight', '1'),
(126, 'grade_report_user_showaverage', '0'),
(127, 'grade_report_user_showlettergrade', '0'),
(128, 'grade_report_user_rangedecimals', '0'),
(129, 'grade_report_user_showhiddenitems', '1'),
(130, 'grade_report_user_showtotalsifcontainhidden', '0'),
(131, 'grade_report_user_showcontributiontocoursetotal', '1'),
(132, 'badges_defaultissuername', ''),
(133, 'badges_defaultissuercontact', ''),
(134, 'badges_badgesalt', 'badges1451552893'),
(135, 'badges_allowexternalbackpack', '1'),
(136, 'badges_allowcoursebadges', '1'),
(138, 'forcetimezone', '99'),
(139, 'country', '0'),
(140, 'defaultcity', ''),
(141, 'geoipfile', '/home4/mycodebu/moodledata/geoip/GeoLiteCity.dat'),
(142, 'googlemapkey3', ''),
(143, 'allcountrycodes', ''),
(144, 'autolang', '1'),
(145, 'lang', 'en'),
(146, 'langmenu', '1'),
(147, 'langlist', ''),
(148, 'langrev', '1453287272'),
(149, 'langcache', '1'),
(150, 'langstringcache', '1'),
(151, 'locale', ''),
(152, 'latinexcelexport', '0'),
(153, 'requiremodintro', '0'),
(155, 'authloginviaemail', '0'),
(156, 'allowaccountssameemail', '0'),
(157, 'authpreventaccountcreation', '0'),
(158, 'loginpageautofocus', '0'),
(159, 'guestloginbutton', '1'),
(160, 'limitconcurrentlogins', '0'),
(161, 'alternateloginurl', 'http://cnausa.com/'),
(162, 'forgottenpasswordurl', ''),
(163, 'auth_instructions', ''),
(164, 'allowemailaddresses', ''),
(165, 'denyemailaddresses', ''),
(166, 'verifychangedemail', '1'),
(167, 'recaptchapublickey', ''),
(168, 'recaptchaprivatekey', ''),
(169, 'filteruploadedfiles', '0'),
(170, 'filtermatchoneperpage', '0'),
(171, 'filtermatchonepertext', '0'),
(172, 'sitedefaultlicense', 'allrightsreserved'),
(173, 'portfolio_moderate_filesize_threshold', '1048576'),
(174, 'portfolio_high_filesize_threshold', '5242880'),
(175, 'portfolio_moderate_db_threshold', '20'),
(176, 'portfolio_high_db_threshold', '50'),
(177, 'repositorycacheexpire', '120'),
(178, 'repositorygetfiletimeout', '30'),
(179, 'repositorysyncfiletimeout', '1'),
(180, 'repositorysyncimagetimeout', '3'),
(181, 'repositoryallowexternallinks', '1'),
(182, 'legacyfilesinnewcourses', '0'),
(183, 'legacyfilesaddallowed', '1'),
(184, 'mobilecssurl', ''),
(185, 'enablewsdocumentation', '0'),
(186, 'allowbeforeblock', '0'),
(187, 'allowedip', ''),
(188, 'blockedip', ''),
(189, 'protectusernames', '1'),
(190, 'forcelogin', '0'),
(191, 'forceloginforprofiles', '1'),
(192, 'forceloginforprofileimage', '0'),
(193, 'opentogoogle', '0'),
(194, 'maxbytes', '0'),
(195, 'userquota', '104857600'),
(196, 'allowobjectembed', '0'),
(197, 'enabletrusttext', '0'),
(198, 'maxeditingtime', '1800'),
(199, 'extendedusernamechars', '0'),
(200, 'sitepolicy', ''),
(201, 'sitepolicyguest', ''),
(202, 'keeptagnamecase', '1'),
(203, 'profilesforenrolledusersonly', '1'),
(204, 'cronclionly', '1'),
(205, 'cronremotepassword', ''),
(206, 'lockoutthreshold', '0'),
(207, 'lockoutwindow', '1800'),
(208, 'lockoutduration', '1800'),
(209, 'passwordpolicy', '1'),
(210, 'minpasswordlength', '8'),
(211, 'minpassworddigits', '1'),
(212, 'minpasswordlower', '1'),
(213, 'minpasswordupper', '1'),
(214, 'minpasswordnonalphanum', '1'),
(215, 'maxconsecutiveidentchars', '0'),
(216, 'passwordreuselimit', '0'),
(217, 'pwresettime', '1800'),
(218, 'passwordchangelogout', '0'),
(219, 'groupenrolmentkeypolicy', '1'),
(220, 'disableuserimages', '0'),
(221, 'emailchangeconfirmation', '1'),
(222, 'rememberusername', '2'),
(223, 'strictformsrequired', '0'),
(224, 'loginhttps', '0'),
(225, 'cookiesecure', '0'),
(226, 'cookiehttponly', '0'),
(227, 'allowframembedding', '0'),
(228, 'loginpasswordautocomplete', '0'),
(229, 'displayloginfailures', '0'),
(230, 'notifyloginfailures', ''),
(231, 'notifyloginthreshold', '10'),
(232, 'runclamonupload', '0'),
(233, 'pathtoclam', ''),
(234, 'quarantinedir', ''),
(235, 'clamfailureonupload', 'donothing'),
(236, 'themelist', ''),
(237, 'themedesignermode', '0'),
(238, 'allowuserthemes', '0'),
(239, 'allowcoursethemes', '0'),
(240, 'allowcategorythemes', '0'),
(241, 'allowthemechangeonurl', '0'),
(242, 'allowuserblockhiding', '1'),
(243, 'allowblockstodock', '1'),
(244, 'custommenuitems', ''),
(245, 'customusermenuitems', 'grades,grades|/grade/report/mygrades.php|grades\nmessages,message|/message/index.php|message\npreferences,moodle|/user/preferences.php|preferences'),
(246, 'enabledevicedetection', '1'),
(247, 'devicedetectregex', '[]'),
(248, 'calendartype', 'gregorian'),
(249, 'calendar_adminseesall', '0'),
(250, 'calendar_site_timeformat', '0'),
(251, 'calendar_startwday', '1'),
(252, 'calendar_weekend', '65'),
(253, 'calendar_lookahead', '21'),
(254, 'calendar_maxevents', '10'),
(255, 'enablecalendarexport', '1'),
(256, 'calendar_customexport', '1'),
(257, 'calendar_exportlookahead', '365'),
(258, 'calendar_exportlookback', '5'),
(259, 'calendar_exportsalt', 'O29vvZfJO6ogIbAT9PEFN8QEF3dCy728arocfbLofvKz2IxSfBpph8OEa25Q'),
(260, 'calendar_showicalsource', '1'),
(261, 'useblogassociations', '1'),
(262, 'bloglevel', '4'),
(263, 'useexternalblogs', '1'),
(264, 'externalblogcrontime', '86400'),
(265, 'maxexternalblogsperuser', '1'),
(266, 'blogusecomments', '1'),
(267, 'blogshowcommentscount', '1'),
(268, 'defaulthomepage', '1'),
(269, 'allowguestmymoodle', '1'),
(270, 'navshowfullcoursenames', '0'),
(271, 'navshowcategories', '1'),
(272, 'navshowmycoursecategories', '0'),
(273, 'navshowallcourses', '0'),
(274, 'navexpandmycourses', '1'),
(275, 'navsortmycoursessort', 'sortorder'),
(276, 'navcourselimit', '20'),
(277, 'usesitenameforsitepages', '0'),
(278, 'linkadmincategories', '0'),
(279, 'linkcoursesections', '0'),
(280, 'navshowfrontpagemods', '1'),
(281, 'navadduserpostslinks', '1'),
(282, 'formatstringstriptags', '1'),
(283, 'emoticons', '[{"text":":-)","imagename":"s\\/smiley","imagecomponent":"core","altidentifier":"smiley","altcomponent":"core_pix"},{"text":":)","imagename":"s\\/smiley","imagecomponent":"core","altidentifier":"smiley","altcomponent":"core_pix"},{"text":":-D","imagename":"s\\/biggrin","imagecomponent":"core","altidentifier":"biggrin","altcomponent":"core_pix"},{"text":";-)","imagename":"s\\/wink","imagecomponent":"core","altidentifier":"wink","altcomponent":"core_pix"},{"text":":-\\/","imagename":"s\\/mixed","imagecomponent":"core","altidentifier":"mixed","altcomponent":"core_pix"},{"text":"V-.","imagename":"s\\/thoughtful","imagecomponent":"core","altidentifier":"thoughtful","altcomponent":"core_pix"},{"text":":-P","imagename":"s\\/tongueout","imagecomponent":"core","altidentifier":"tongueout","altcomponent":"core_pix"},{"text":":-p","imagename":"s\\/tongueout","imagecomponent":"core","altidentifier":"tongueout","altcomponent":"core_pix"},{"text":"B-)","imagename":"s\\/cool","imagecomponent":"core","altidentifier":"cool","altcomponent":"core_pix"},{"text":"^-)","imagename":"s\\/approve","imagecomponent":"core","altidentifier":"approve","altcomponent":"core_pix"},{"text":"8-)","imagename":"s\\/wideeyes","imagecomponent":"core","altidentifier":"wideeyes","altcomponent":"core_pix"},{"text":":o)","imagename":"s\\/clown","imagecomponent":"core","altidentifier":"clown","altcomponent":"core_pix"},{"text":":-(","imagename":"s\\/sad","imagecomponent":"core","altidentifier":"sad","altcomponent":"core_pix"},{"text":":(","imagename":"s\\/sad","imagecomponent":"core","altidentifier":"sad","altcomponent":"core_pix"},{"text":"8-.","imagename":"s\\/shy","imagecomponent":"core","altidentifier":"shy","altcomponent":"core_pix"},{"text":":-I","imagename":"s\\/blush","imagecomponent":"core","altidentifier":"blush","altcomponent":"core_pix"},{"text":":-X","imagename":"s\\/kiss","imagecomponent":"core","altidentifier":"kiss","altcomponent":"core_pix"},{"text":"8-o","imagename":"s\\/surprise","imagecomponent":"core","altidentifier":"surprise","altcomponent":"core_pix"},{"text":"P-|","imagename":"s\\/blackeye","imagecomponent":"core","altidentifier":"blackeye","altcomponent":"core_pix"},{"text":"8-[","imagename":"s\\/angry","imagecomponent":"core","altidentifier":"angry","altcomponent":"core_pix"},{"text":"(grr)","imagename":"s\\/angry","imagecomponent":"core","altidentifier":"angry","altcomponent":"core_pix"},{"text":"xx-P","imagename":"s\\/dead","imagecomponent":"core","altidentifier":"dead","altcomponent":"core_pix"},{"text":"|-.","imagename":"s\\/sleepy","imagecomponent":"core","altidentifier":"sleepy","altcomponent":"core_pix"},{"text":"}-]","imagename":"s\\/evil","imagecomponent":"core","altidentifier":"evil","altcomponent":"core_pix"},{"text":"(h)","imagename":"s\\/heart","imagecomponent":"core","altidentifier":"heart","altcomponent":"core_pix"},{"text":"(heart)","imagename":"s\\/heart","imagecomponent":"core","altidentifier":"heart","altcomponent":"core_pix"},{"text":"(y)","imagename":"s\\/yes","imagecomponent":"core","altidentifier":"yes","altcomponent":"core"},{"text":"(n)","imagename":"s\\/no","imagecomponent":"core","altidentifier":"no","altcomponent":"core"},{"text":"(martin)","imagename":"s\\/martin","imagecomponent":"core","altidentifier":"martin","altcomponent":"core_pix"},{"text":"( )","imagename":"s\\/egg","imagecomponent":"core","altidentifier":"egg","altcomponent":"core_pix"}]'),
(284, 'core_media_enable_youtube', '1'),
(285, 'core_media_enable_vimeo', '0'),
(286, 'core_media_enable_mp3', '1'),
(287, 'core_media_enable_flv', '1'),
(288, 'core_media_enable_swf', '1'),
(289, 'core_media_enable_html5audio', '1'),
(290, 'core_media_enable_html5video', '1'),
(291, 'core_media_enable_qt', '1'),
(292, 'core_media_enable_wmp', '1'),
(293, 'core_media_enable_rm', '1'),
(294, 'docroot', 'http://docs.moodle.org'),
(295, 'doclang', ''),
(296, 'doctonewwindow', '0'),
(297, 'courselistshortnames', '0'),
(298, 'coursesperpage', '20'),
(299, 'courseswithsummarieslimit', '10'),
(300, 'courseoverviewfileslimit', '1'),
(301, 'courseoverviewfilesext', '.jpg,.gif,.png'),
(302, 'useexternalyui', '0'),
(303, 'yuicomboloading', '1'),
(304, 'cachejs', '1'),
(305, 'modchooserdefault', '1'),
(306, 'modeditingmenu', '1'),
(307, 'blockeditingmenu', '1'),
(308, 'additionalhtmlhead', ''),
(309, 'additionalhtmltopofbody', ''),
(310, 'additionalhtmlfooter', ''),
(311, 'pathtodu', ''),
(312, 'aspellpath', ''),
(313, 'pathtodot', ''),
(314, 'pathtogs', '/usr/bin/gs'),
(315, 'supportpage', ''),
(316, 'dbsessions', '0'),
(317, 'sessioncookie', ''),
(318, 'sessioncookiepath', ''),
(319, 'sessioncookiedomain', ''),
(320, 'statsfirstrun', 'none'),
(321, 'statsmaxruntime', '0'),
(322, 'statsruntimedays', '31'),
(323, 'statsruntimestarthour', '0'),
(324, 'statsruntimestartminute', '0'),
(325, 'statsuserthreshold', '0'),
(326, 'slasharguments', '1'),
(327, 'getremoteaddrconf', '0'),
(328, 'proxyhost', ''),
(329, 'proxyport', '0'),
(330, 'proxytype', 'HTTP'),
(331, 'proxyuser', ''),
(332, 'proxypassword', ''),
(333, 'proxybypass', 'localhost, 127.0.0.1'),
(334, 'maintenance_enabled', '0'),
(335, 'maintenance_message', ''),
(336, 'deleteunconfirmed', '168'),
(337, 'deleteincompleteusers', '0'),
(338, 'disablegradehistory', '0'),
(339, 'gradehistorylifetime', '0'),
(340, 'tempdatafoldercleanup', '168'),
(341, 'extramemorylimit', '512M'),
(342, 'maxtimelimit', '0'),
(343, 'curlcache', '120'),
(344, 'curltimeoutkbitrate', '56'),
(345, 'updateautocheck', '1'),
(346, 'updateminmaturity', '200'),
(347, 'updatenotifybuilds', '0'),
(348, 'enablesafebrowserintegration', '0'),
(349, 'dndallowtextandlinks', '0'),
(350, 'enablecssoptimiser', '0'),
(351, 'debug', '32767'),
(352, 'debugdisplay', '1'),
(353, 'debugsmtp', '0'),
(354, 'perfdebug', '7'),
(355, 'debugstringids', '0'),
(356, 'debugvalidators', '0'),
(357, 'debugpageinfo', '0'),
(358, 'profilingenabled', '0'),
(359, 'profilingincluded', ''),
(360, 'profilingexcluded', ''),
(361, 'profilingautofrec', '0'),
(362, 'profilingallowme', '0'),
(363, 'profilingallowall', '0'),
(364, 'profilinglifetime', '1440'),
(365, 'profilingimportprefix', '(I)'),
(366, 'release', '3.0.1+ (Build: 20151223)'),
(367, 'branch', '30'),
(368, 'localcachedirpurged', '1453287273'),
(369, 'scheduledtaskreset', '1453287273'),
(370, 'allversionshash', 'ebecbabc7959a2eb0e862f34ea845e81acf2eee9'),
(372, 'notloggedinroleid', '6'),
(373, 'guestroleid', '6'),
(374, 'defaultuserroleid', '7'),
(375, 'creatornewroleid', '3'),
(376, 'restorernewroleid', '3'),
(377, 'gradebookroles', '5'),
(378, 'chat_method', 'ajax'),
(379, 'chat_refresh_userlist', '10'),
(380, 'chat_old_ping', '35'),
(381, 'chat_refresh_room', '5'),
(382, 'chat_normal_updatemode', 'jsupdate'),
(383, 'chat_serverhost', 'mycodebusters.com'),
(384, 'chat_serverip', '127.0.0.1'),
(385, 'chat_serverport', '9111'),
(386, 'chat_servermax', '100'),
(387, 'data_enablerssfeeds', '0'),
(388, 'feedback_allowfullanonymous', '0'),
(389, 'forum_displaymode', '3'),
(390, 'forum_replytouser', '1'),
(391, 'forum_shortpost', '300'),
(392, 'forum_longpost', '600'),
(393, 'forum_manydiscussions', '100'),
(394, 'forum_maxbytes', '512000'),
(395, 'forum_maxattachments', '9'),
(396, 'forum_trackingtype', '1'),
(397, 'forum_trackreadposts', '1'),
(398, 'forum_allowforcedreadtracking', '0'),
(399, 'forum_oldpostdays', '14'),
(400, 'forum_usermarksread', '0'),
(401, 'forum_cleanreadtime', '2'),
(402, 'digestmailtime', '17'),
(403, 'forum_enablerssfeeds', '0'),
(404, 'forum_enabletimedposts', '0'),
(405, 'glossary_entbypage', '10'),
(406, 'glossary_dupentries', '0'),
(407, 'glossary_allowcomments', '0'),
(408, 'glossary_linkbydefault', '1'),
(409, 'glossary_defaultapproval', '1'),
(410, 'glossary_enablerssfeeds', '0'),
(411, 'glossary_linkentries', '0'),
(412, 'glossary_casesensitive', '0'),
(413, 'glossary_fullmatch', '0'),
(414, 'lesson_slideshowwidth', '640'),
(415, 'lesson_slideshowheight', '480'),
(416, 'lesson_slideshowbgcolor', '#FFFFFF'),
(417, 'lesson_mediawidth', '640'),
(418, 'lesson_mediaheight', '480'),
(419, 'lesson_mediaclose', '0'),
(420, 'lesson_maxanswers', '4'),
(421, 'lesson_defaultnextpage', '0'),
(422, 'block_course_list_adminview', 'all'),
(423, 'block_course_list_hideallcourseslink', '0'),
(424, 'block_html_allowcssclasses', '0'),
(425, 'block_online_users_timetosee', '5'),
(426, 'block_rss_client_num_entries', '5'),
(427, 'block_rss_client_timeout', '30'),
(428, 'filter_censor_badwords', ''),
(429, 'filter_multilang_force_old', '0'),
(430, 'logguests', '1'),
(431, 'loglifetime', '0'),
(432, 'airnotifierurl', 'https://messages.moodle.net'),
(433, 'airnotifierport', '443'),
(434, 'airnotifiermobileappname', 'com.moodle.moodlemobile'),
(435, 'airnotifierappname', 'commoodlemoodlemobile'),
(436, 'airnotifieraccesskey', ''),
(437, 'smtphosts', ''),
(438, 'smtpsecure', ''),
(439, 'smtpauthtype', 'LOGIN'),
(440, 'smtpuser', ''),
(441, 'smtppass', ''),
(442, 'smtpmaxbulk', '1'),
(443, 'noreplyaddress', 'noreply@mycodebusters.com'),
(444, 'emailonlyfromnoreplyaddress', '0'),
(445, 'sitemailcharset', '0'),
(446, 'allowusermailcharset', '0'),
(447, 'allowattachments', '1'),
(448, 'mailnewline', 'LF'),
(449, 'jabberhost', ''),
(450, 'jabberserver', ''),
(451, 'jabberusername', ''),
(452, 'jabberpassword', ''),
(453, 'jabberport', '5222'),
(454, 'enablemobilewebservice', '0'),
(455, 'profileroles', '5,4,3'),
(456, 'coursecontact', '3'),
(457, 'frontpage', '6'),
(458, 'frontpageloggedin', '6'),
(459, 'maxcategorydepth', '2'),
(460, 'frontpagecourselimit', '200'),
(461, 'commentsperpage', '15'),
(462, 'defaultfrontpageroleid', '8'),
(463, 'supportname', 'Admin User'),
(464, 'supportemail', 'some@gmail.com'),
(465, 'messageinbound_enabled', '0'),
(466, 'messageinbound_mailbox', ''),
(467, 'messageinbound_domain', ''),
(468, 'messageinbound_host', ''),
(469, 'messageinbound_hostssl', 'ssl'),
(470, 'messageinbound_hostuser', ''),
(471, 'messageinbound_hostpass', ''),
(472, 'timezone', 'Pacific/Wallis'),
(473, 'registerauth', 'email');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_config_log`
--

CREATE TABLE IF NOT EXISTS `mdl_config_log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `plugin` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` longtext,
  `oldvalue` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_conflog_tim_ix` (`timemodified`),
  KEY `mdl_conflog_use_ix` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Changes done in server configuration through admin UI' AUTO_INCREMENT=1025 ;

--
-- Dumping data for table `mdl_config_log`
--

INSERT INTO `mdl_config_log` (`id`, `userid`, `timemodified`, `plugin`, `name`, `value`, `oldvalue`) VALUES
(1, 0, 1451552899, NULL, 'enableoutcomes', '0', NULL),
(2, 0, 1451552899, NULL, 'usecomments', '1', NULL),
(3, 0, 1451552899, NULL, 'usetags', '1', NULL),
(4, 0, 1451552899, NULL, 'enablenotes', '1', NULL),
(5, 0, 1451552899, NULL, 'enableportfolios', '0', NULL),
(6, 0, 1451552899, NULL, 'enablewebservices', '0', NULL),
(7, 0, 1451552899, NULL, 'messaging', '1', NULL),
(8, 0, 1451552899, NULL, 'messaginghidereadnotifications', '0', NULL),
(9, 0, 1451552899, NULL, 'messagingdeletereadnotificationsdelay', '604800', NULL),
(10, 0, 1451552899, NULL, 'messagingallowemailoverride', '0', NULL),
(11, 0, 1451552899, NULL, 'enablestats', '0', NULL),
(12, 0, 1451552899, NULL, 'enablerssfeeds', '0', NULL),
(13, 0, 1451552899, NULL, 'enableblogs', '1', NULL),
(14, 0, 1451552899, NULL, 'enablecompletion', '0', NULL),
(15, 0, 1451552899, NULL, 'completiondefault', '1', NULL),
(16, 0, 1451552899, NULL, 'enableavailability', '0', NULL),
(17, 0, 1451552899, NULL, 'enableplagiarism', '0', NULL),
(18, 0, 1451552899, NULL, 'enablebadges', '1', NULL),
(19, 0, 1451552899, NULL, 'defaultpreference_maildisplay', '2', NULL),
(20, 0, 1451552899, NULL, 'defaultpreference_mailformat', '1', NULL),
(21, 0, 1451552899, NULL, 'defaultpreference_maildigest', '0', NULL),
(22, 0, 1451552899, NULL, 'defaultpreference_autosubscribe', '1', NULL),
(23, 0, 1451552899, NULL, 'defaultpreference_trackforums', '0', NULL),
(24, 0, 1451552899, NULL, 'autologinguests', '0', NULL),
(25, 0, 1451552899, NULL, 'hiddenuserfields', '', NULL),
(26, 0, 1451552899, NULL, 'showuseridentity', 'email', NULL),
(27, 0, 1451552899, NULL, 'fullnamedisplay', 'language', NULL),
(28, 0, 1451552899, NULL, 'alternativefullnameformat', 'language', NULL),
(29, 0, 1451552899, NULL, 'maxusersperpage', '100', NULL),
(30, 0, 1451552899, NULL, 'enablegravatar', '0', NULL),
(31, 0, 1451552899, NULL, 'gravatardefaulturl', 'mm', NULL),
(32, 0, 1451552899, 'moodlecourse', 'visible', '1', NULL),
(33, 0, 1451552899, 'moodlecourse', 'format', 'weeks', NULL),
(34, 0, 1451552899, 'moodlecourse', 'maxsections', '52', NULL),
(35, 0, 1451552899, 'moodlecourse', 'numsections', '10', NULL),
(36, 0, 1451552899, 'moodlecourse', 'hiddensections', '0', NULL),
(37, 0, 1451552899, 'moodlecourse', 'coursedisplay', '0', NULL),
(38, 0, 1451552899, 'moodlecourse', 'lang', '', NULL),
(39, 0, 1451552899, 'moodlecourse', 'newsitems', '5', NULL),
(40, 0, 1451552899, 'moodlecourse', 'showgrades', '1', NULL),
(41, 0, 1451552899, 'moodlecourse', 'showreports', '0', NULL),
(42, 0, 1451552899, 'moodlecourse', 'maxbytes', '0', NULL),
(43, 0, 1451552899, 'moodlecourse', 'enablecompletion', '0', NULL),
(44, 0, 1451552899, 'moodlecourse', 'groupmode', '0', NULL),
(45, 0, 1451552899, 'moodlecourse', 'groupmodeforce', '0', NULL),
(46, 0, 1451552899, NULL, 'enablecourserequests', '0', NULL),
(47, 0, 1451552899, NULL, 'defaultrequestcategory', '1', NULL),
(48, 0, 1451552899, NULL, 'requestcategoryselection', '0', NULL),
(49, 0, 1451552899, NULL, 'courserequestnotify', '', NULL),
(50, 0, 1451552899, 'backup', 'loglifetime', '30', NULL),
(51, 0, 1451552899, 'backup', 'backup_general_users', '1', NULL),
(52, 0, 1451552899, 'backup', 'backup_general_users_locked', '', NULL),
(53, 0, 1451552899, 'backup', 'backup_general_anonymize', '0', NULL),
(54, 0, 1451552899, 'backup', 'backup_general_anonymize_locked', '', NULL),
(55, 0, 1451552899, 'backup', 'backup_general_role_assignments', '1', NULL),
(56, 0, 1451552899, 'backup', 'backup_general_role_assignments_locked', '', NULL),
(57, 0, 1451552899, 'backup', 'backup_general_activities', '1', NULL),
(58, 0, 1451552899, 'backup', 'backup_general_activities_locked', '', NULL),
(59, 0, 1451552899, 'backup', 'backup_general_blocks', '1', NULL),
(60, 0, 1451552899, 'backup', 'backup_general_blocks_locked', '', NULL),
(61, 0, 1451552899, 'backup', 'backup_general_filters', '1', NULL),
(62, 0, 1451552899, 'backup', 'backup_general_filters_locked', '', NULL),
(63, 0, 1451552899, 'backup', 'backup_general_comments', '1', NULL),
(64, 0, 1451552899, 'backup', 'backup_general_comments_locked', '', NULL),
(65, 0, 1451552899, 'backup', 'backup_general_badges', '1', NULL),
(66, 0, 1451552899, 'backup', 'backup_general_badges_locked', '', NULL),
(67, 0, 1451552899, 'backup', 'backup_general_userscompletion', '1', NULL),
(68, 0, 1451552899, 'backup', 'backup_general_userscompletion_locked', '', NULL),
(69, 0, 1451552899, 'backup', 'backup_general_logs', '0', NULL),
(70, 0, 1451552899, 'backup', 'backup_general_logs_locked', '', NULL),
(71, 0, 1451552899, 'backup', 'backup_general_histories', '0', NULL),
(72, 0, 1451552899, 'backup', 'backup_general_histories_locked', '', NULL),
(73, 0, 1451552899, 'backup', 'backup_general_questionbank', '1', NULL),
(74, 0, 1451552899, 'backup', 'backup_general_questionbank_locked', '', NULL),
(75, 0, 1451552899, 'backup', 'backup_general_groups', '1', NULL),
(76, 0, 1451552899, 'backup', 'backup_general_groups_locked', '', NULL),
(77, 0, 1451552899, 'backup', 'import_general_maxresults', '10', NULL),
(78, 0, 1451552899, 'backup', 'backup_auto_active', '0', NULL),
(79, 0, 1451552899, 'backup', 'backup_auto_weekdays', '0000000', NULL),
(80, 0, 1451552899, 'backup', 'backup_auto_hour', '0', NULL),
(81, 0, 1451552899, 'backup', 'backup_auto_minute', '0', NULL),
(82, 0, 1451552899, 'backup', 'backup_auto_storage', '0', NULL),
(83, 0, 1451552900, 'backup', 'backup_auto_destination', '', NULL),
(84, 0, 1451552900, 'backup', 'backup_auto_max_kept', '1', NULL),
(85, 0, 1451552900, 'backup', 'backup_auto_delete_days', '0', NULL),
(86, 0, 1451552900, 'backup', 'backup_auto_min_kept', '0', NULL),
(87, 0, 1451552900, 'backup', 'backup_shortname', '0', NULL),
(88, 0, 1451552900, 'backup', 'backup_auto_skip_hidden', '1', NULL),
(89, 0, 1451552900, 'backup', 'backup_auto_skip_modif_days', '30', NULL),
(90, 0, 1451552900, 'backup', 'backup_auto_skip_modif_prev', '0', NULL),
(91, 0, 1451552900, 'backup', 'backup_auto_users', '1', NULL),
(92, 0, 1451552900, 'backup', 'backup_auto_role_assignments', '1', NULL),
(93, 0, 1451552900, 'backup', 'backup_auto_activities', '1', NULL),
(94, 0, 1451552900, 'backup', 'backup_auto_blocks', '1', NULL),
(95, 0, 1451552900, 'backup', 'backup_auto_filters', '1', NULL),
(96, 0, 1451552900, 'backup', 'backup_auto_comments', '1', NULL),
(97, 0, 1451552900, 'backup', 'backup_auto_badges', '1', NULL),
(98, 0, 1451552900, 'backup', 'backup_auto_userscompletion', '1', NULL),
(99, 0, 1451552900, 'backup', 'backup_auto_logs', '0', NULL),
(100, 0, 1451552900, 'backup', 'backup_auto_histories', '0', NULL),
(101, 0, 1451552900, 'backup', 'backup_auto_questionbank', '1', NULL),
(102, 0, 1451552900, 'backup', 'backup_auto_groups', '1', NULL),
(103, 0, 1451552900, NULL, 'grade_profilereport', 'user', NULL),
(104, 0, 1451552900, NULL, 'grade_aggregationposition', '1', NULL),
(105, 0, 1451552900, NULL, 'grade_includescalesinaggregation', '1', NULL),
(106, 0, 1451552900, NULL, 'grade_hiddenasdate', '0', NULL),
(107, 0, 1451552900, NULL, 'gradepublishing', '0', NULL),
(108, 0, 1451552900, NULL, 'grade_export_displaytype', '1', NULL),
(109, 0, 1451552900, NULL, 'grade_export_decimalpoints', '2', NULL),
(110, 0, 1451552900, NULL, 'grade_navmethod', '0', NULL),
(111, 0, 1451552900, NULL, 'grade_export_userprofilefields', 'firstname,lastname,idnumber,institution,department,email', NULL),
(112, 0, 1451552900, NULL, 'grade_export_customprofilefields', '', NULL),
(113, 0, 1451552900, NULL, 'recovergradesdefault', '0', NULL),
(114, 0, 1451552900, NULL, 'gradeexport', '', NULL),
(115, 0, 1451552900, NULL, 'unlimitedgrades', '0', NULL),
(116, 0, 1451552900, NULL, 'grade_report_showmin', '1', NULL),
(117, 0, 1451552900, NULL, 'gradepointmax', '100', NULL),
(118, 0, 1451552900, NULL, 'gradepointdefault', '100', NULL),
(119, 0, 1451552900, NULL, 'grade_minmaxtouse', '1', NULL),
(120, 0, 1451552900, NULL, 'grade_mygrades_report', 'overview', NULL),
(121, 0, 1451552900, NULL, 'gradereport_mygradeurl', '', NULL),
(122, 0, 1451552900, NULL, 'grade_hideforcedsettings', '1', NULL),
(123, 0, 1451552900, NULL, 'grade_aggregation', '13', NULL),
(124, 0, 1451552900, NULL, 'grade_aggregation_flag', '0', NULL),
(125, 0, 1451552900, NULL, 'grade_aggregations_visible', '13', NULL),
(126, 0, 1451552900, NULL, 'grade_aggregateonlygraded', '1', NULL),
(127, 0, 1451552900, NULL, 'grade_aggregateonlygraded_flag', '2', NULL),
(128, 0, 1451552900, NULL, 'grade_aggregateoutcomes', '0', NULL),
(129, 0, 1451552900, NULL, 'grade_aggregateoutcomes_flag', '2', NULL),
(130, 0, 1451552900, NULL, 'grade_keephigh', '0', NULL),
(131, 0, 1451552900, NULL, 'grade_keephigh_flag', '3', NULL),
(132, 0, 1451552900, NULL, 'grade_droplow', '0', NULL),
(133, 0, 1451552900, NULL, 'grade_droplow_flag', '2', NULL),
(134, 0, 1451552900, NULL, 'grade_overridecat', '1', NULL),
(135, 0, 1451552900, NULL, 'grade_displaytype', '1', NULL),
(136, 0, 1451552900, NULL, 'grade_decimalpoints', '2', NULL),
(137, 0, 1451552900, NULL, 'grade_item_advanced', 'iteminfo,idnumber,gradepass,plusfactor,multfactor,display,decimals,hiddenuntil,locktime', NULL),
(138, 0, 1451552900, NULL, 'grade_report_studentsperpage', '100', NULL),
(139, 0, 1451552900, NULL, 'grade_report_showonlyactiveenrol', '1', NULL),
(140, 0, 1451552900, NULL, 'grade_report_quickgrading', '1', NULL),
(141, 0, 1451552900, NULL, 'grade_report_showquickfeedback', '0', NULL),
(142, 0, 1451552900, NULL, 'grade_report_meanselection', '1', NULL),
(143, 0, 1451552900, NULL, 'grade_report_enableajax', '0', NULL),
(144, 0, 1451552900, NULL, 'grade_report_showcalculations', '1', NULL),
(145, 0, 1451552900, NULL, 'grade_report_showeyecons', '0', NULL),
(146, 0, 1451552900, NULL, 'grade_report_showaverages', '1', NULL),
(147, 0, 1451552900, NULL, 'grade_report_showlocks', '0', NULL),
(148, 0, 1451552900, NULL, 'grade_report_showranges', '0', NULL),
(149, 0, 1451552900, NULL, 'grade_report_showanalysisicon', '1', NULL),
(150, 0, 1451552900, NULL, 'grade_report_showuserimage', '1', NULL),
(151, 0, 1451552900, NULL, 'grade_report_showactivityicons', '1', NULL),
(152, 0, 1451552900, NULL, 'grade_report_shownumberofgrades', '0', NULL),
(153, 0, 1451552900, NULL, 'grade_report_averagesdisplaytype', 'inherit', NULL),
(154, 0, 1451552900, NULL, 'grade_report_rangesdisplaytype', 'inherit', NULL),
(155, 0, 1451552900, NULL, 'grade_report_averagesdecimalpoints', 'inherit', NULL),
(156, 0, 1451552900, NULL, 'grade_report_rangesdecimalpoints', 'inherit', NULL),
(157, 0, 1451552900, NULL, 'grade_report_historyperpage', '50', NULL),
(158, 0, 1451552900, NULL, 'grade_report_overview_showrank', '0', NULL),
(159, 0, 1451552900, NULL, 'grade_report_overview_showtotalsifcontainhidden', '0', NULL),
(160, 0, 1451552900, NULL, 'grade_report_user_showrank', '0', NULL),
(161, 0, 1451552900, NULL, 'grade_report_user_showpercentage', '1', NULL),
(162, 0, 1451552900, NULL, 'grade_report_user_showgrade', '1', NULL),
(163, 0, 1451552900, NULL, 'grade_report_user_showfeedback', '1', NULL),
(164, 0, 1451552900, NULL, 'grade_report_user_showrange', '1', NULL),
(165, 0, 1451552900, NULL, 'grade_report_user_showweight', '1', NULL),
(166, 0, 1451552900, NULL, 'grade_report_user_showaverage', '0', NULL),
(167, 0, 1451552900, NULL, 'grade_report_user_showlettergrade', '0', NULL),
(168, 0, 1451552900, NULL, 'grade_report_user_rangedecimals', '0', NULL),
(169, 0, 1451552900, NULL, 'grade_report_user_showhiddenitems', '1', NULL),
(170, 0, 1451552900, NULL, 'grade_report_user_showtotalsifcontainhidden', '0', NULL),
(171, 0, 1451552900, NULL, 'grade_report_user_showcontributiontocoursetotal', '1', NULL),
(172, 0, 1451552900, NULL, 'badges_defaultissuername', '', NULL),
(173, 0, 1451552900, NULL, 'badges_defaultissuercontact', '', NULL),
(174, 0, 1451552900, NULL, 'badges_badgesalt', 'badges1451552893', NULL),
(175, 0, 1451552900, NULL, 'badges_allowexternalbackpack', '1', NULL),
(176, 0, 1451552900, NULL, 'badges_allowcoursebadges', '1', NULL),
(177, 0, 1451552901, NULL, 'timezone', 'Europe/London', NULL),
(178, 0, 1451552902, NULL, 'forcetimezone', '99', NULL),
(179, 0, 1451552902, NULL, 'country', '0', NULL),
(180, 0, 1451552902, NULL, 'defaultcity', '', NULL),
(181, 0, 1451552902, NULL, 'geoipfile', '/home4/mycodebu/moodledata/geoip/GeoLiteCity.dat', NULL),
(182, 0, 1451552902, NULL, 'googlemapkey3', '', NULL),
(183, 0, 1451552902, NULL, 'allcountrycodes', '', NULL),
(184, 0, 1451552902, NULL, 'autolang', '1', NULL),
(185, 0, 1451552902, NULL, 'lang', 'en', NULL),
(186, 0, 1451552902, NULL, 'langmenu', '1', NULL),
(187, 0, 1451552903, NULL, 'langlist', '', NULL),
(188, 0, 1451552903, NULL, 'langcache', '1', NULL),
(189, 0, 1451552903, NULL, 'langstringcache', '1', NULL),
(190, 0, 1451552903, NULL, 'locale', '', NULL),
(191, 0, 1451552903, NULL, 'latinexcelexport', '0', NULL),
(192, 0, 1451552903, NULL, 'requiremodintro', '0', NULL),
(193, 0, 1451552903, NULL, 'registerauth', '', NULL),
(194, 0, 1451552903, NULL, 'authloginviaemail', '0', NULL),
(195, 0, 1451552903, NULL, 'allowaccountssameemail', '0', NULL),
(196, 0, 1451552903, NULL, 'authpreventaccountcreation', '0', NULL),
(197, 0, 1451552903, NULL, 'loginpageautofocus', '0', NULL),
(198, 0, 1451552903, NULL, 'guestloginbutton', '1', NULL),
(199, 0, 1451552903, NULL, 'limitconcurrentlogins', '0', NULL),
(200, 0, 1451552903, NULL, 'alternateloginurl', '', NULL),
(201, 0, 1451552903, NULL, 'forgottenpasswordurl', '', NULL),
(202, 0, 1451552903, NULL, 'auth_instructions', '', NULL),
(203, 0, 1451552903, NULL, 'allowemailaddresses', '', NULL),
(204, 0, 1451552903, NULL, 'denyemailaddresses', '', NULL),
(205, 0, 1451552903, NULL, 'verifychangedemail', '1', NULL),
(206, 0, 1451552903, NULL, 'recaptchapublickey', '', NULL),
(207, 0, 1451552903, NULL, 'recaptchaprivatekey', '', NULL),
(208, 0, 1451552903, 'cachestore_memcache', 'testservers', '', NULL),
(209, 0, 1451552903, 'cachestore_memcached', 'testservers', '', NULL),
(210, 0, 1451552903, 'cachestore_mongodb', 'testserver', '', NULL),
(211, 0, 1451552903, NULL, 'filteruploadedfiles', '0', NULL),
(212, 0, 1451552903, NULL, 'filtermatchoneperpage', '0', NULL),
(213, 0, 1451552903, NULL, 'filtermatchonepertext', '0', NULL),
(214, 0, 1451552903, NULL, 'sitedefaultlicense', 'allrightsreserved', NULL),
(215, 0, 1451552903, NULL, 'portfolio_moderate_filesize_threshold', '1048576', NULL),
(216, 0, 1451552903, NULL, 'portfolio_high_filesize_threshold', '5242880', NULL),
(217, 0, 1451552903, NULL, 'portfolio_moderate_db_threshold', '20', NULL),
(218, 0, 1451552903, NULL, 'portfolio_high_db_threshold', '50', NULL),
(219, 0, 1451552903, 'question_preview', 'behaviour', 'deferredfeedback', NULL),
(220, 0, 1451552903, 'question_preview', 'correctness', '1', NULL),
(221, 0, 1451552903, 'question_preview', 'marks', '2', NULL),
(222, 0, 1451552903, 'question_preview', 'markdp', '2', NULL),
(223, 0, 1451552903, 'question_preview', 'feedback', '1', NULL),
(224, 0, 1451552903, 'question_preview', 'generalfeedback', '1', NULL),
(225, 0, 1451552903, 'question_preview', 'rightanswer', '1', NULL),
(226, 0, 1451552903, 'question_preview', 'history', '0', NULL),
(227, 0, 1451552903, NULL, 'repositorycacheexpire', '120', NULL),
(228, 0, 1451552903, NULL, 'repositorygetfiletimeout', '30', NULL),
(229, 0, 1451552903, NULL, 'repositorysyncfiletimeout', '1', NULL),
(230, 0, 1451552903, NULL, 'repositorysyncimagetimeout', '3', NULL),
(231, 0, 1451552903, NULL, 'repositoryallowexternallinks', '1', NULL),
(232, 0, 1451552903, NULL, 'legacyfilesinnewcourses', '0', NULL),
(233, 0, 1451552903, NULL, 'legacyfilesaddallowed', '1', NULL),
(234, 0, 1451552903, NULL, 'mobilecssurl', '', NULL),
(235, 0, 1451552903, NULL, 'enablewsdocumentation', '0', NULL),
(236, 0, 1451552903, NULL, 'allowbeforeblock', '0', NULL),
(237, 0, 1451552903, NULL, 'allowedip', '', NULL),
(238, 0, 1451552903, NULL, 'blockedip', '', NULL),
(239, 0, 1451552903, NULL, 'protectusernames', '1', NULL),
(240, 0, 1451552903, NULL, 'forcelogin', '0', NULL),
(241, 0, 1451552903, NULL, 'forceloginforprofiles', '1', NULL),
(242, 0, 1451552903, NULL, 'forceloginforprofileimage', '0', NULL),
(243, 0, 1451552903, NULL, 'opentogoogle', '0', NULL),
(244, 0, 1451552903, NULL, 'maxbytes', '0', NULL),
(245, 0, 1451552903, NULL, 'userquota', '104857600', NULL),
(246, 0, 1451552903, NULL, 'allowobjectembed', '0', NULL),
(247, 0, 1451552903, NULL, 'enabletrusttext', '0', NULL),
(248, 0, 1451552903, NULL, 'maxeditingtime', '1800', NULL),
(249, 0, 1451552903, NULL, 'extendedusernamechars', '0', NULL),
(250, 0, 1451552903, NULL, 'sitepolicy', '', NULL),
(251, 0, 1451552903, NULL, 'sitepolicyguest', '', NULL),
(252, 0, 1451552903, NULL, 'keeptagnamecase', '1', NULL),
(253, 0, 1451552903, NULL, 'profilesforenrolledusersonly', '1', NULL),
(254, 0, 1451552903, NULL, 'cronclionly', '1', NULL),
(255, 0, 1451552903, NULL, 'cronremotepassword', '', NULL),
(256, 0, 1451552903, NULL, 'lockoutthreshold', '0', NULL),
(257, 0, 1451552903, NULL, 'lockoutwindow', '1800', NULL),
(258, 0, 1451552903, NULL, 'lockoutduration', '1800', NULL),
(259, 0, 1451552903, NULL, 'passwordpolicy', '1', NULL),
(260, 0, 1451552903, NULL, 'minpasswordlength', '8', NULL),
(261, 0, 1451552903, NULL, 'minpassworddigits', '1', NULL),
(262, 0, 1451552903, NULL, 'minpasswordlower', '1', NULL),
(263, 0, 1451552903, NULL, 'minpasswordupper', '1', NULL),
(264, 0, 1451552903, NULL, 'minpasswordnonalphanum', '1', NULL),
(265, 0, 1451552903, NULL, 'maxconsecutiveidentchars', '0', NULL),
(266, 0, 1451552903, NULL, 'passwordreuselimit', '0', NULL),
(267, 0, 1451552903, NULL, 'pwresettime', '1800', NULL),
(268, 0, 1451552903, NULL, 'passwordchangelogout', '0', NULL),
(269, 0, 1451552903, NULL, 'groupenrolmentkeypolicy', '1', NULL),
(270, 0, 1451552904, NULL, 'disableuserimages', '0', NULL),
(271, 0, 1451552904, NULL, 'emailchangeconfirmation', '1', NULL),
(272, 0, 1451552904, NULL, 'rememberusername', '2', NULL),
(273, 0, 1451552904, NULL, 'strictformsrequired', '0', NULL),
(274, 0, 1451552904, NULL, 'loginhttps', '0', NULL),
(275, 0, 1451552904, NULL, 'cookiesecure', '0', NULL),
(276, 0, 1451552904, NULL, 'cookiehttponly', '0', NULL),
(277, 0, 1451552904, NULL, 'allowframembedding', '0', NULL),
(278, 0, 1451552904, NULL, 'loginpasswordautocomplete', '0', NULL),
(279, 0, 1451552904, NULL, 'displayloginfailures', '0', NULL),
(280, 0, 1451552904, NULL, 'notifyloginfailures', '', NULL),
(281, 0, 1451552904, NULL, 'notifyloginthreshold', '10', NULL),
(282, 0, 1451552904, NULL, 'runclamonupload', '0', NULL),
(283, 0, 1451552904, NULL, 'pathtoclam', '', NULL),
(284, 0, 1451552904, NULL, 'quarantinedir', '', NULL),
(285, 0, 1451552904, NULL, 'clamfailureonupload', 'donothing', NULL),
(286, 0, 1451552904, NULL, 'themelist', '', NULL),
(287, 0, 1451552904, NULL, 'themedesignermode', '0', NULL),
(288, 0, 1451552904, NULL, 'allowuserthemes', '0', NULL),
(289, 0, 1451552904, NULL, 'allowcoursethemes', '0', NULL),
(290, 0, 1451552904, NULL, 'allowcategorythemes', '0', NULL),
(291, 0, 1451552904, NULL, 'allowthemechangeonurl', '0', NULL),
(292, 0, 1451552904, NULL, 'allowuserblockhiding', '1', NULL),
(293, 0, 1451552904, NULL, 'allowblockstodock', '1', NULL),
(294, 0, 1451552904, NULL, 'custommenuitems', '', NULL),
(295, 0, 1451552904, NULL, 'customusermenuitems', 'grades,grades|/grade/report/mygrades.php|grades\nmessages,message|/message/index.php|message\npreferences,moodle|/user/preferences.php|preferences', NULL),
(296, 0, 1451552904, NULL, 'enabledevicedetection', '1', NULL),
(297, 0, 1451552904, NULL, 'devicedetectregex', '[]', NULL),
(298, 0, 1451552904, 'theme_clean', 'invert', '0', NULL),
(299, 0, 1451552904, 'theme_clean', 'logo', '', NULL),
(300, 0, 1451552904, 'theme_clean', 'customcss', '', NULL),
(301, 0, 1451552904, 'theme_clean', 'footnote', '', NULL),
(302, 0, 1451552904, 'theme_more', 'textcolor', '#333366', NULL),
(303, 0, 1451552904, 'theme_more', 'linkcolor', '#FF6500', NULL),
(304, 0, 1451552904, 'theme_more', 'bodybackground', '', NULL),
(305, 0, 1451552904, 'theme_more', 'backgroundimage', '', NULL),
(306, 0, 1451552904, 'theme_more', 'backgroundrepeat', 'repeat', NULL),
(307, 0, 1451552904, 'theme_more', 'backgroundposition', '0', NULL),
(308, 0, 1451552904, 'theme_more', 'backgroundfixed', '0', NULL),
(309, 0, 1451552904, 'theme_more', 'contentbackground', '#FFFFFF', NULL),
(310, 0, 1451552904, 'theme_more', 'secondarybackground', '#FFFFFF', NULL),
(311, 0, 1451552904, 'theme_more', 'invert', '1', NULL),
(312, 0, 1451552904, 'theme_more', 'logo', '', NULL),
(313, 0, 1451552904, 'theme_more', 'customcss', '', NULL),
(314, 0, 1451552904, 'theme_more', 'footnote', '', NULL),
(315, 0, 1451552904, NULL, 'calendartype', 'gregorian', NULL),
(316, 0, 1451552904, NULL, 'calendar_adminseesall', '0', NULL),
(317, 0, 1451552904, NULL, 'calendar_site_timeformat', '0', NULL),
(318, 0, 1451552904, NULL, 'calendar_startwday', '1', NULL),
(319, 0, 1451552904, NULL, 'calendar_weekend', '65', NULL),
(320, 0, 1451552904, NULL, 'calendar_lookahead', '21', NULL),
(321, 0, 1451552904, NULL, 'calendar_maxevents', '10', NULL),
(322, 0, 1451552904, NULL, 'enablecalendarexport', '1', NULL),
(323, 0, 1451552904, NULL, 'calendar_customexport', '1', NULL),
(324, 0, 1451552904, NULL, 'calendar_exportlookahead', '365', NULL),
(325, 0, 1451552904, NULL, 'calendar_exportlookback', '5', NULL),
(326, 0, 1451552904, NULL, 'calendar_exportsalt', 'O29vvZfJO6ogIbAT9PEFN8QEF3dCy728arocfbLofvKz2IxSfBpph8OEa25Q', NULL),
(327, 0, 1451552904, NULL, 'calendar_showicalsource', '1', NULL),
(328, 0, 1451552904, NULL, 'useblogassociations', '1', NULL),
(329, 0, 1451552904, NULL, 'bloglevel', '4', NULL),
(330, 0, 1451552904, NULL, 'useexternalblogs', '1', NULL),
(331, 0, 1451552904, NULL, 'externalblogcrontime', '86400', NULL),
(332, 0, 1451552904, NULL, 'maxexternalblogsperuser', '1', NULL),
(333, 0, 1451552904, NULL, 'blogusecomments', '1', NULL),
(334, 0, 1451552904, NULL, 'blogshowcommentscount', '1', NULL),
(335, 0, 1451552904, NULL, 'defaulthomepage', '1', NULL),
(336, 0, 1451552904, NULL, 'allowguestmymoodle', '1', NULL),
(337, 0, 1451552904, NULL, 'navshowfullcoursenames', '0', NULL),
(338, 0, 1451552904, NULL, 'navshowcategories', '1', NULL),
(339, 0, 1451552904, NULL, 'navshowmycoursecategories', '0', NULL),
(340, 0, 1451552904, NULL, 'navshowallcourses', '0', NULL),
(341, 0, 1451552904, NULL, 'navexpandmycourses', '1', NULL),
(342, 0, 1451552904, NULL, 'navsortmycoursessort', 'sortorder', NULL),
(343, 0, 1451552904, NULL, 'navcourselimit', '20', NULL),
(344, 0, 1451552904, NULL, 'usesitenameforsitepages', '0', NULL),
(345, 0, 1451552904, NULL, 'linkadmincategories', '0', NULL),
(346, 0, 1451552904, NULL, 'linkcoursesections', '0', NULL),
(347, 0, 1451552904, NULL, 'navshowfrontpagemods', '1', NULL),
(348, 0, 1451552904, NULL, 'navadduserpostslinks', '1', NULL),
(349, 0, 1451552904, NULL, 'formatstringstriptags', '1', NULL),
(350, 0, 1451552904, NULL, 'emoticons', '[{"text":":-)","imagename":"s\\/smiley","imagecomponent":"core","altidentifier":"smiley","altcomponent":"core_pix"},{"text":":)","imagename":"s\\/smiley","imagecomponent":"core","altidentifier":"smiley","altcomponent":"core_pix"},{"text":":-D","imagename":"s\\/biggrin","imagecomponent":"core","altidentifier":"biggrin","altcomponent":"core_pix"},{"text":";-)","imagename":"s\\/wink","imagecomponent":"core","altidentifier":"wink","altcomponent":"core_pix"},{"text":":-\\/","imagename":"s\\/mixed","imagecomponent":"core","altidentifier":"mixed","altcomponent":"core_pix"},{"text":"V-.","imagename":"s\\/thoughtful","imagecomponent":"core","altidentifier":"thoughtful","altcomponent":"core_pix"},{"text":":-P","imagename":"s\\/tongueout","imagecomponent":"core","altidentifier":"tongueout","altcomponent":"core_pix"},{"text":":-p","imagename":"s\\/tongueout","imagecomponent":"core","altidentifier":"tongueout","altcomponent":"core_pix"},{"text":"B-)","imagename":"s\\/cool","imagecomponent":"core","altidentifier":"cool","altcomponent":"core_pix"},{"text":"^-)","imagename":"s\\/approve","imagecomponent":"core","altidentifier":"approve","altcomponent":"core_pix"},{"text":"8-)","imagename":"s\\/wideeyes","imagecomponent":"core","altidentifier":"wideeyes","altcomponent":"core_pix"},{"text":":o)","imagename":"s\\/clown","imagecomponent":"core","altidentifier":"clown","altcomponent":"core_pix"},{"text":":-(","imagename":"s\\/sad","imagecomponent":"core","altidentifier":"sad","altcomponent":"core_pix"},{"text":":(","imagename":"s\\/sad","imagecomponent":"core","altidentifier":"sad","altcomponent":"core_pix"},{"text":"8-.","imagename":"s\\/shy","imagecomponent":"core","altidentifier":"shy","altcomponent":"core_pix"},{"text":":-I","imagename":"s\\/blush","imagecomponent":"core","altidentifier":"blush","altcomponent":"core_pix"},{"text":":-X","imagename":"s\\/kiss","imagecomponent":"core","altidentifier":"kiss","altcomponent":"core_pix"},{"text":"8-o","imagename":"s\\/surprise","imagecomponent":"core","altidentifier":"surprise","altcomponent":"core_pix"},{"text":"P-|","imagename":"s\\/blackeye","imagecomponent":"core","altidentifier":"blackeye","altcomponent":"core_pix"},{"text":"8-[","imagename":"s\\/angry","imagecomponent":"core","altidentifier":"angry","altcomponent":"core_pix"},{"text":"(grr)","imagename":"s\\/angry","imagecomponent":"core","altidentifier":"angry","altcomponent":"core_pix"},{"text":"xx-P","imagename":"s\\/dead","imagecomponent":"core","altidentifier":"dead","altcomponent":"core_pix"},{"text":"|-.","imagename":"s\\/sleepy","imagecomponent":"core","altidentifier":"sleepy","altcomponent":"core_pix"},{"text":"}-]","imagename":"s\\/evil","imagecomponent":"core","altidentifier":"evil","altcomponent":"core_pix"},{"text":"(h)","imagename":"s\\/heart","imagecomponent":"core","altidentifier":"heart","altcomponent":"core_pix"},{"text":"(heart)","imagename":"s\\/heart","imagecomponent":"core","altidentifier":"heart","altcomponent":"core_pix"},{"text":"(y)","imagename":"s\\/yes","imagecomponent":"core","altidentifier":"yes","altcomponent":"core"},{"text":"(n)","imagename":"s\\/no","imagecomponent":"core","altidentifier":"no","altcomponent":"core"},{"text":"(martin)","imagename":"s\\/martin","imagecomponent":"core","altidentifier":"martin","altcomponent":"core_pix"},{"text":"( )","imagename":"s\\/egg","imagecomponent":"core","altidentifier":"egg","altcomponent":"core_pix"}]', NULL),
(351, 0, 1451552904, NULL, 'core_media_enable_youtube', '1', NULL),
(352, 0, 1451552904, NULL, 'core_media_enable_vimeo', '0', NULL),
(353, 0, 1451552904, NULL, 'core_media_enable_mp3', '1', NULL),
(354, 0, 1451552904, NULL, 'core_media_enable_flv', '1', NULL),
(355, 0, 1451552904, NULL, 'core_media_enable_swf', '1', NULL),
(356, 0, 1451552904, NULL, 'core_media_enable_html5audio', '1', NULL),
(357, 0, 1451552904, NULL, 'core_media_enable_html5video', '1', NULL),
(358, 0, 1451552904, NULL, 'core_media_enable_qt', '1', NULL),
(359, 0, 1451552904, NULL, 'core_media_enable_wmp', '1', NULL),
(360, 0, 1451552904, NULL, 'core_media_enable_rm', '1', NULL),
(361, 0, 1451552904, NULL, 'docroot', 'http://docs.moodle.org', NULL),
(362, 0, 1451552904, NULL, 'doclang', '', NULL),
(363, 0, 1451552904, NULL, 'doctonewwindow', '0', NULL),
(364, 0, 1451552904, NULL, 'courselistshortnames', '0', NULL),
(365, 0, 1451552904, NULL, 'coursesperpage', '20', NULL),
(366, 0, 1451552904, NULL, 'courseswithsummarieslimit', '10', NULL),
(367, 0, 1451552904, NULL, 'courseoverviewfileslimit', '1', NULL),
(368, 0, 1451552904, NULL, 'courseoverviewfilesext', '.jpg,.gif,.png', NULL),
(369, 0, 1451552904, NULL, 'useexternalyui', '0', NULL),
(370, 0, 1451552904, NULL, 'yuicomboloading', '1', NULL),
(371, 0, 1451552904, NULL, 'cachejs', '1', NULL),
(372, 0, 1451552904, NULL, 'modchooserdefault', '1', NULL),
(373, 0, 1451552904, NULL, 'modeditingmenu', '1', NULL),
(374, 0, 1451552904, NULL, 'blockeditingmenu', '1', NULL),
(375, 0, 1451552904, NULL, 'additionalhtmlhead', '', NULL),
(376, 0, 1451552904, NULL, 'additionalhtmltopofbody', '', NULL),
(377, 0, 1451552904, NULL, 'additionalhtmlfooter', '', NULL),
(378, 0, 1451552904, NULL, 'pathtodu', '', NULL),
(379, 0, 1451552904, NULL, 'aspellpath', '', NULL),
(380, 0, 1451552904, NULL, 'pathtodot', '', NULL),
(381, 0, 1451552904, NULL, 'pathtogs', '/usr/bin/gs', NULL),
(382, 0, 1451552904, NULL, 'supportpage', '', NULL),
(383, 0, 1451552904, NULL, 'dbsessions', '0', NULL),
(384, 0, 1451552904, NULL, 'sessioncookie', '', NULL),
(385, 0, 1451552904, NULL, 'sessioncookiepath', '', NULL),
(386, 0, 1451552904, NULL, 'sessioncookiedomain', '', NULL),
(387, 0, 1451552904, NULL, 'statsfirstrun', 'none', NULL),
(388, 0, 1451552904, NULL, 'statsmaxruntime', '0', NULL),
(389, 0, 1451552904, NULL, 'statsruntimedays', '31', NULL),
(390, 0, 1451552904, NULL, 'statsruntimestarthour', '0', NULL),
(391, 0, 1451552904, NULL, 'statsruntimestartminute', '0', NULL),
(392, 0, 1451552905, NULL, 'statsuserthreshold', '0', NULL),
(393, 0, 1451552905, NULL, 'slasharguments', '1', NULL),
(394, 0, 1451552905, NULL, 'getremoteaddrconf', '0', NULL),
(395, 0, 1451552905, NULL, 'proxyhost', '', NULL),
(396, 0, 1451552905, NULL, 'proxyport', '0', NULL),
(397, 0, 1451552905, NULL, 'proxytype', 'HTTP', NULL),
(398, 0, 1451552905, NULL, 'proxyuser', '', NULL),
(399, 0, 1451552905, NULL, 'proxypassword', '', NULL),
(400, 0, 1451552905, NULL, 'proxybypass', 'localhost, 127.0.0.1', NULL),
(401, 0, 1451552905, NULL, 'maintenance_enabled', '0', NULL),
(402, 0, 1451552905, NULL, 'maintenance_message', '', NULL),
(403, 0, 1451552905, NULL, 'deleteunconfirmed', '168', NULL),
(404, 0, 1451552905, NULL, 'deleteincompleteusers', '0', NULL),
(405, 0, 1451552905, NULL, 'disablegradehistory', '0', NULL),
(406, 0, 1451552905, NULL, 'gradehistorylifetime', '0', NULL),
(407, 0, 1451552905, NULL, 'tempdatafoldercleanup', '168', NULL),
(408, 0, 1451552905, NULL, 'extramemorylimit', '512M', NULL),
(409, 0, 1451552905, NULL, 'maxtimelimit', '0', NULL),
(410, 0, 1451552905, NULL, 'curlcache', '120', NULL),
(411, 0, 1451552905, NULL, 'curltimeoutkbitrate', '56', NULL),
(412, 0, 1451552905, NULL, 'updateautocheck', '1', NULL),
(413, 0, 1451552905, NULL, 'updateminmaturity', '200', NULL),
(414, 0, 1451552905, NULL, 'updatenotifybuilds', '0', NULL),
(415, 0, 1451552905, NULL, 'enablesafebrowserintegration', '0', NULL),
(416, 0, 1451552905, NULL, 'dndallowtextandlinks', '0', NULL),
(417, 0, 1451552905, NULL, 'enablecssoptimiser', '0', NULL),
(418, 0, 1451552905, NULL, 'debug', '0', NULL),
(419, 0, 1451552905, NULL, 'debugdisplay', '1', NULL),
(420, 0, 1451552905, NULL, 'debugsmtp', '0', NULL),
(421, 0, 1451552905, NULL, 'perfdebug', '7', NULL),
(422, 0, 1451552905, NULL, 'debugstringids', '0', NULL),
(423, 0, 1451552905, NULL, 'debugvalidators', '0', NULL),
(424, 0, 1451552905, NULL, 'debugpageinfo', '0', NULL),
(425, 0, 1451552905, NULL, 'profilingenabled', '0', NULL),
(426, 0, 1451552905, NULL, 'profilingincluded', '', NULL),
(427, 0, 1451552905, NULL, 'profilingexcluded', '', NULL),
(428, 0, 1451552905, NULL, 'profilingautofrec', '0', NULL),
(429, 0, 1451552905, NULL, 'profilingallowme', '0', NULL),
(430, 0, 1451552905, NULL, 'profilingallowall', '0', NULL),
(431, 0, 1451552905, NULL, 'profilinglifetime', '1440', NULL),
(432, 0, 1451552905, NULL, 'profilingimportprefix', '(I)', NULL),
(433, 0, 1451552924, 'activitynames', 'filter_active', '1', ''),
(434, 0, 1451552925, 'mathjaxloader', 'filter_active', '1', ''),
(435, 0, 1451552925, 'mediaplugin', 'filter_active', '1', ''),
(436, 2, 1451553060, NULL, 'notloggedinroleid', '6', NULL),
(437, 2, 1451553060, NULL, 'guestroleid', '6', NULL),
(438, 2, 1451553061, NULL, 'defaultuserroleid', '7', NULL),
(439, 2, 1451553061, NULL, 'creatornewroleid', '3', NULL),
(440, 2, 1451553061, NULL, 'restorernewroleid', '3', NULL),
(441, 2, 1451553061, NULL, 'gradebookroles', '5', NULL),
(442, 2, 1451553061, 'assign', 'feedback_plugin_for_gradebook', 'assignfeedback_comments', NULL),
(443, 2, 1451553061, 'assign', 'showrecentsubmissions', '0', NULL),
(444, 2, 1451553061, 'assign', 'submissionreceipts', '1', NULL),
(445, 2, 1451553061, 'assign', 'submissionstatement', 'This assignment is my own work, except where I have acknowledged the use of the works of other people.', NULL),
(446, 2, 1451553061, 'assign', 'alwaysshowdescription', '1', NULL),
(447, 2, 1451553061, 'assign', 'alwaysshowdescription_adv', '', NULL),
(448, 2, 1451553061, 'assign', 'alwaysshowdescription_locked', '', NULL),
(449, 2, 1451553061, 'assign', 'allowsubmissionsfromdate', '0', NULL),
(450, 2, 1451553061, 'assign', 'allowsubmissionsfromdate_enabled', '1', NULL),
(451, 2, 1451553061, 'assign', 'allowsubmissionsfromdate_adv', '', NULL),
(452, 2, 1451553061, 'assign', 'duedate', '604800', NULL),
(453, 2, 1451553061, 'assign', 'duedate_enabled', '1', NULL),
(454, 2, 1451553061, 'assign', 'duedate_adv', '', NULL),
(455, 2, 1451553061, 'assign', 'cutoffdate', '1209600', NULL),
(456, 2, 1451553061, 'assign', 'cutoffdate_enabled', '', NULL),
(457, 2, 1451553061, 'assign', 'cutoffdate_adv', '', NULL),
(458, 2, 1451553061, 'assign', 'submissiondrafts', '0', NULL),
(459, 2, 1451553061, 'assign', 'submissiondrafts_adv', '', NULL),
(460, 2, 1451553061, 'assign', 'submissiondrafts_locked', '', NULL),
(461, 2, 1451553061, 'assign', 'requiresubmissionstatement', '0', NULL),
(462, 2, 1451553061, 'assign', 'requiresubmissionstatement_adv', '', NULL),
(463, 2, 1451553061, 'assign', 'requiresubmissionstatement_locked', '', NULL),
(464, 2, 1451553061, 'assign', 'attemptreopenmethod', 'none', NULL),
(465, 2, 1451553061, 'assign', 'attemptreopenmethod_adv', '', NULL),
(466, 2, 1451553061, 'assign', 'attemptreopenmethod_locked', '', NULL),
(467, 2, 1451553061, 'assign', 'maxattempts', '-1', NULL),
(468, 2, 1451553061, 'assign', 'maxattempts_adv', '', NULL),
(469, 2, 1451553061, 'assign', 'maxattempts_locked', '', NULL),
(470, 2, 1451553061, 'assign', 'teamsubmission', '0', NULL),
(471, 2, 1451553061, 'assign', 'teamsubmission_adv', '', NULL),
(472, 2, 1451553061, 'assign', 'teamsubmission_locked', '', NULL),
(473, 2, 1451553061, 'assign', 'preventsubmissionnotingroup', '0', NULL),
(474, 2, 1451553061, 'assign', 'preventsubmissionnotingroup_adv', '', NULL),
(475, 2, 1451553061, 'assign', 'preventsubmissionnotingroup_locked', '', NULL),
(476, 2, 1451553061, 'assign', 'requireallteammemberssubmit', '0', NULL),
(477, 2, 1451553061, 'assign', 'requireallteammemberssubmit_adv', '', NULL),
(478, 2, 1451553061, 'assign', 'requireallteammemberssubmit_locked', '', NULL),
(479, 2, 1451553061, 'assign', 'teamsubmissiongroupingid', '', NULL),
(480, 2, 1451553061, 'assign', 'teamsubmissiongroupingid_adv', '', NULL),
(481, 2, 1451553061, 'assign', 'sendnotifications', '0', NULL),
(482, 2, 1451553061, 'assign', 'sendnotifications_adv', '', NULL),
(483, 2, 1451553061, 'assign', 'sendnotifications_locked', '', NULL),
(484, 2, 1451553061, 'assign', 'sendlatenotifications', '0', NULL),
(485, 2, 1451553061, 'assign', 'sendlatenotifications_adv', '', NULL),
(486, 2, 1451553061, 'assign', 'sendlatenotifications_locked', '', NULL),
(487, 2, 1451553061, 'assign', 'sendstudentnotifications', '1', NULL),
(488, 2, 1451553061, 'assign', 'sendstudentnotifications_adv', '', NULL),
(489, 2, 1451553061, 'assign', 'sendstudentnotifications_locked', '', NULL),
(490, 2, 1451553061, 'assign', 'blindmarking', '0', NULL),
(491, 2, 1451553061, 'assign', 'blindmarking_adv', '', NULL),
(492, 2, 1451553061, 'assign', 'blindmarking_locked', '', NULL),
(493, 2, 1451553061, 'assign', 'markingworkflow', '0', NULL),
(494, 2, 1451553061, 'assign', 'markingworkflow_adv', '', NULL),
(495, 2, 1451553061, 'assign', 'markingworkflow_locked', '', NULL),
(496, 2, 1451553061, 'assign', 'markingallocation', '0', NULL),
(497, 2, 1451553061, 'assign', 'markingallocation_adv', '', NULL),
(498, 2, 1451553061, 'assign', 'markingallocation_locked', '', NULL),
(499, 2, 1451553061, 'assignsubmission_file', 'default', '1', NULL),
(500, 2, 1451553061, 'assignsubmission_file', 'maxfiles', '20', NULL),
(501, 2, 1451553061, 'assignsubmission_file', 'maxbytes', '1048576', NULL),
(502, 2, 1451553061, 'assignsubmission_onlinetext', 'default', '0', NULL),
(503, 2, 1451553061, 'assignfeedback_comments', 'default', '1', NULL),
(504, 2, 1451553061, 'assignfeedback_comments', 'inline', '0', NULL),
(505, 2, 1451553061, 'assignfeedback_comments', 'inline_adv', '', NULL),
(506, 2, 1451553061, 'assignfeedback_comments', 'inline_locked', '', NULL),
(507, 2, 1451553061, 'assignfeedback_editpdf', 'stamps', '', NULL),
(508, 2, 1451553061, 'assignfeedback_file', 'default', '0', NULL),
(509, 2, 1451553061, 'assignfeedback_offline', 'default', '0', NULL),
(510, 2, 1451553061, 'book', 'numberingoptions', '0,1,2,3', NULL),
(511, 2, 1451553061, 'book', 'navoptions', '0,1,2', NULL),
(512, 2, 1451553061, 'book', 'numbering', '1', NULL),
(513, 2, 1451553061, 'book', 'navstyle', '1', NULL),
(514, 2, 1451553061, NULL, 'chat_method', 'ajax', NULL),
(515, 2, 1451553061, NULL, 'chat_refresh_userlist', '10', NULL),
(516, 2, 1451553061, NULL, 'chat_old_ping', '35', NULL),
(517, 2, 1451553062, NULL, 'chat_refresh_room', '5', NULL),
(518, 2, 1451553062, NULL, 'chat_normal_updatemode', 'jsupdate', NULL),
(519, 2, 1451553062, NULL, 'chat_serverhost', 'mycodebusters.com', NULL),
(520, 2, 1451553062, NULL, 'chat_serverip', '127.0.0.1', NULL),
(521, 2, 1451553062, NULL, 'chat_serverport', '9111', NULL),
(522, 2, 1451553062, NULL, 'chat_servermax', '100', NULL),
(523, 2, 1451553062, NULL, 'data_enablerssfeeds', '0', NULL),
(524, 2, 1451553062, NULL, 'feedback_allowfullanonymous', '0', NULL),
(525, 2, 1451553062, 'folder', 'showexpanded', '1', NULL),
(526, 2, 1451553062, NULL, 'forum_displaymode', '3', NULL),
(527, 2, 1451553062, NULL, 'forum_replytouser', '1', NULL),
(528, 2, 1451553062, NULL, 'forum_shortpost', '300', NULL),
(529, 2, 1451553062, NULL, 'forum_longpost', '600', NULL),
(530, 2, 1451553062, NULL, 'forum_manydiscussions', '100', NULL),
(531, 2, 1451553062, NULL, 'forum_maxbytes', '512000', NULL),
(532, 2, 1451553062, NULL, 'forum_maxattachments', '9', NULL),
(533, 2, 1451553062, NULL, 'forum_trackingtype', '1', NULL),
(534, 2, 1451553062, NULL, 'forum_trackreadposts', '1', NULL),
(535, 2, 1451553062, NULL, 'forum_allowforcedreadtracking', '0', NULL),
(536, 2, 1451553062, NULL, 'forum_oldpostdays', '14', NULL),
(537, 2, 1451553062, NULL, 'forum_usermarksread', '0', NULL),
(538, 2, 1451553062, NULL, 'forum_cleanreadtime', '2', NULL),
(539, 2, 1451553062, NULL, 'digestmailtime', '17', NULL),
(540, 2, 1451553062, NULL, 'forum_enablerssfeeds', '0', NULL),
(541, 2, 1451553062, NULL, 'forum_enabletimedposts', '0', NULL),
(542, 2, 1451553062, NULL, 'glossary_entbypage', '10', NULL),
(543, 2, 1451553062, NULL, 'glossary_dupentries', '0', NULL),
(544, 2, 1451553062, NULL, 'glossary_allowcomments', '0', NULL),
(545, 2, 1451553062, NULL, 'glossary_linkbydefault', '1', NULL),
(546, 2, 1451553062, NULL, 'glossary_defaultapproval', '1', NULL),
(547, 2, 1451553062, NULL, 'glossary_enablerssfeeds', '0', NULL),
(548, 2, 1451553062, NULL, 'glossary_linkentries', '0', NULL),
(549, 2, 1451553062, NULL, 'glossary_casesensitive', '0', NULL),
(550, 2, 1451553062, NULL, 'glossary_fullmatch', '0', NULL),
(551, 2, 1451553062, 'imscp', 'keepold', '1', NULL),
(552, 2, 1451553062, 'imscp', 'keepold_adv', '', NULL),
(553, 2, 1451553062, 'label', 'dndmedia', '1', NULL),
(554, 2, 1451553062, 'label', 'dndresizewidth', '400', NULL),
(555, 2, 1451553062, 'label', 'dndresizeheight', '400', NULL),
(556, 2, 1451553062, NULL, 'lesson_slideshowwidth', '640', NULL),
(557, 2, 1451553062, NULL, 'lesson_slideshowheight', '480', NULL),
(558, 2, 1451553062, NULL, 'lesson_slideshowbgcolor', '#FFFFFF', NULL),
(559, 2, 1451553062, NULL, 'lesson_mediawidth', '640', NULL),
(560, 2, 1451553062, NULL, 'lesson_mediaheight', '480', NULL),
(561, 2, 1451553062, NULL, 'lesson_mediaclose', '0', NULL),
(562, 2, 1451553062, NULL, 'lesson_maxanswers', '4', NULL),
(563, 2, 1451553062, NULL, 'lesson_defaultnextpage', '0', NULL),
(564, 2, 1451553062, 'page', 'displayoptions', '5', NULL),
(565, 2, 1451553062, 'page', 'printheading', '1', NULL),
(566, 2, 1451553062, 'page', 'printintro', '0', NULL),
(567, 2, 1451553062, 'page', 'display', '5', NULL),
(568, 2, 1451553062, 'page', 'popupwidth', '620', NULL),
(569, 2, 1451553062, 'page', 'popupheight', '450', NULL),
(570, 2, 1451553062, 'quiz', 'timelimit', '0', NULL),
(571, 2, 1451553062, 'quiz', 'timelimit_adv', '', NULL),
(572, 2, 1451553062, 'quiz', 'overduehandling', 'autosubmit', NULL),
(573, 2, 1451553062, 'quiz', 'overduehandling_adv', '', NULL),
(574, 2, 1451553062, 'quiz', 'graceperiod', '86400', NULL),
(575, 2, 1451553062, 'quiz', 'graceperiod_adv', '', NULL),
(576, 2, 1451553062, 'quiz', 'graceperiodmin', '60', NULL),
(577, 2, 1451553062, 'quiz', 'attempts', '0', NULL),
(578, 2, 1451553062, 'quiz', 'attempts_adv', '', NULL),
(579, 2, 1451553062, 'quiz', 'grademethod', '1', NULL),
(580, 2, 1451553062, 'quiz', 'grademethod_adv', '', NULL),
(581, 2, 1451553062, 'quiz', 'maximumgrade', '10', NULL),
(582, 2, 1451553062, 'quiz', 'questionsperpage', '1', NULL),
(583, 2, 1451553062, 'quiz', 'questionsperpage_adv', '', NULL),
(584, 2, 1451553062, 'quiz', 'navmethod', 'free', NULL),
(585, 2, 1451553062, 'quiz', 'navmethod_adv', '1', NULL),
(586, 2, 1451553062, 'quiz', 'shuffleanswers', '1', NULL),
(587, 2, 1451553062, 'quiz', 'shuffleanswers_adv', '', NULL),
(588, 2, 1451553062, 'quiz', 'preferredbehaviour', 'deferredfeedback', NULL),
(589, 2, 1451553062, 'quiz', 'canredoquestions', '0', NULL),
(590, 2, 1451553062, 'quiz', 'canredoquestions_adv', '1', NULL),
(591, 2, 1451553062, 'quiz', 'attemptonlast', '0', NULL),
(592, 2, 1451553062, 'quiz', 'attemptonlast_adv', '1', NULL),
(593, 2, 1451553062, 'quiz', 'reviewattempt', '69904', NULL),
(594, 2, 1451553062, 'quiz', 'reviewcorrectness', '69904', NULL),
(595, 2, 1451553062, 'quiz', 'reviewmarks', '69904', NULL),
(596, 2, 1451553062, 'quiz', 'reviewspecificfeedback', '69904', NULL),
(597, 2, 1451553062, 'quiz', 'reviewgeneralfeedback', '69904', NULL),
(598, 2, 1451553062, 'quiz', 'reviewrightanswer', '69904', NULL),
(599, 2, 1451553062, 'quiz', 'reviewoverallfeedback', '4368', NULL),
(600, 2, 1451553062, 'quiz', 'showuserpicture', '0', NULL),
(601, 2, 1451553062, 'quiz', 'showuserpicture_adv', '', NULL),
(602, 2, 1451553062, 'quiz', 'decimalpoints', '2', NULL),
(603, 2, 1451553062, 'quiz', 'decimalpoints_adv', '', NULL),
(604, 2, 1451553062, 'quiz', 'questiondecimalpoints', '-1', NULL),
(605, 2, 1451553062, 'quiz', 'questiondecimalpoints_adv', '1', NULL),
(606, 2, 1451553062, 'quiz', 'showblocks', '0', NULL),
(607, 2, 1451553062, 'quiz', 'showblocks_adv', '1', NULL),
(608, 2, 1451553062, 'quiz', 'password', '', NULL),
(609, 2, 1451553062, 'quiz', 'password_adv', '', NULL),
(610, 2, 1451553062, 'quiz', 'subnet', '', NULL),
(611, 2, 1451553062, 'quiz', 'subnet_adv', '1', NULL),
(612, 2, 1451553062, 'quiz', 'delay1', '0', NULL),
(613, 2, 1451553062, 'quiz', 'delay1_adv', '1', NULL),
(614, 2, 1451553062, 'quiz', 'delay2', '0', NULL),
(615, 2, 1451553062, 'quiz', 'delay2_adv', '1', NULL),
(616, 2, 1451553062, 'quiz', 'browsersecurity', '-', NULL),
(617, 2, 1451553062, 'quiz', 'browsersecurity_adv', '1', NULL),
(618, 2, 1451553062, 'quiz', 'initialnumfeedbacks', '2', NULL),
(619, 2, 1451553062, 'quiz', 'autosaveperiod', '60', NULL),
(620, 2, 1451553062, 'resource', 'framesize', '130', NULL),
(621, 2, 1451553062, 'resource', 'displayoptions', '0,1,4,5,6', NULL),
(622, 2, 1451553062, 'resource', 'printintro', '1', NULL),
(623, 2, 1451553062, 'resource', 'display', '0', NULL),
(624, 2, 1451553062, 'resource', 'showsize', '0', NULL),
(625, 2, 1451553062, 'resource', 'showtype', '0', NULL),
(626, 2, 1451553062, 'resource', 'showdate', '0', NULL),
(627, 2, 1451553062, 'resource', 'popupwidth', '620', NULL),
(628, 2, 1451553062, 'resource', 'popupheight', '450', NULL),
(629, 2, 1451553062, 'resource', 'filterfiles', '0', NULL),
(630, 2, 1451553062, 'scorm', 'displaycoursestructure', '0', NULL),
(631, 2, 1451553062, 'scorm', 'displaycoursestructure_adv', '', NULL),
(632, 2, 1451553062, 'scorm', 'popup', '0', NULL),
(633, 2, 1451553062, 'scorm', 'popup_adv', '', NULL),
(634, 2, 1451553062, 'scorm', 'displayactivityname', '1', NULL),
(635, 2, 1451553062, 'scorm', 'framewidth', '100', NULL),
(636, 2, 1451553062, 'scorm', 'framewidth_adv', '1', NULL),
(637, 2, 1451553062, 'scorm', 'frameheight', '500', NULL),
(638, 2, 1451553062, 'scorm', 'frameheight_adv', '1', NULL),
(639, 2, 1451553062, 'scorm', 'winoptgrp_adv', '1', NULL),
(640, 2, 1451553062, 'scorm', 'scrollbars', '0', NULL),
(641, 2, 1451553062, 'scorm', 'directories', '0', NULL),
(642, 2, 1451553062, 'scorm', 'location', '0', NULL),
(643, 2, 1451553062, 'scorm', 'menubar', '0', NULL),
(644, 2, 1451553062, 'scorm', 'toolbar', '0', NULL),
(645, 2, 1451553062, 'scorm', 'status', '0', NULL),
(646, 2, 1451553062, 'scorm', 'skipview', '0', NULL),
(647, 2, 1451553062, 'scorm', 'skipview_adv', '1', NULL),
(648, 2, 1451553062, 'scorm', 'hidebrowse', '0', NULL),
(649, 2, 1451553062, 'scorm', 'hidebrowse_adv', '1', NULL),
(650, 2, 1451553062, 'scorm', 'hidetoc', '0', NULL),
(651, 2, 1451553062, 'scorm', 'hidetoc_adv', '1', NULL),
(652, 2, 1451553062, 'scorm', 'nav', '1', NULL),
(653, 2, 1451553062, 'scorm', 'nav_adv', '1', NULL),
(654, 2, 1451553062, 'scorm', 'navpositionleft', '-100', NULL),
(655, 2, 1451553062, 'scorm', 'navpositionleft_adv', '1', NULL),
(656, 2, 1451553062, 'scorm', 'navpositiontop', '-100', NULL),
(657, 2, 1451553062, 'scorm', 'navpositiontop_adv', '1', NULL),
(658, 2, 1451553062, 'scorm', 'collapsetocwinsize', '767', NULL),
(659, 2, 1451553062, 'scorm', 'collapsetocwinsize_adv', '1', NULL),
(660, 2, 1451553062, 'scorm', 'displayattemptstatus', '1', NULL),
(661, 2, 1451553062, 'scorm', 'displayattemptstatus_adv', '', NULL),
(662, 2, 1451553062, 'scorm', 'grademethod', '1', NULL),
(663, 2, 1451553062, 'scorm', 'maxgrade', '100', NULL),
(664, 2, 1451553062, 'scorm', 'maxattempt', '0', NULL),
(665, 2, 1451553062, 'scorm', 'whatgrade', '0', NULL),
(666, 2, 1451553062, 'scorm', 'forcecompleted', '0', NULL),
(667, 2, 1451553062, 'scorm', 'forcenewattempt', '0', NULL),
(668, 2, 1451553062, 'scorm', 'autocommit', '0', NULL),
(669, 2, 1451553062, 'scorm', 'lastattemptlock', '0', NULL),
(670, 2, 1451553062, 'scorm', 'auto', '0', NULL),
(671, 2, 1451553062, 'scorm', 'updatefreq', '0', NULL),
(672, 2, 1451553062, 'scorm', 'scorm12standard', '1', NULL),
(673, 2, 1451553062, 'scorm', 'allowtypeexternal', '0', NULL),
(674, 2, 1451553062, 'scorm', 'allowtypelocalsync', '0', NULL),
(675, 2, 1451553062, 'scorm', 'allowtypeexternalaicc', '0', NULL),
(676, 2, 1451553062, 'scorm', 'allowaicchacp', '0', NULL),
(677, 2, 1451553062, 'scorm', 'aicchacptimeout', '30', NULL),
(678, 2, 1451553062, 'scorm', 'aicchacpkeepsessiondata', '1', NULL),
(679, 2, 1451553062, 'scorm', 'aiccuserid', '1', NULL),
(680, 2, 1451553062, 'scorm', 'forcejavascript', '1', NULL),
(681, 2, 1451553062, 'scorm', 'allowapidebug', '0', NULL),
(682, 2, 1451553063, 'scorm', 'apidebugmask', '.*', NULL),
(683, 2, 1451553063, 'scorm', 'protectpackagedownloads', '0', NULL),
(684, 2, 1451553063, 'url', 'framesize', '130', NULL),
(685, 2, 1451553063, 'url', 'secretphrase', '', NULL),
(686, 2, 1451553063, 'url', 'rolesinparams', '0', NULL),
(687, 2, 1451553063, 'url', 'displayoptions', '0,1,5,6', NULL),
(688, 2, 1451553063, 'url', 'printintro', '1', NULL),
(689, 2, 1451553063, 'url', 'display', '0', NULL),
(690, 2, 1451553063, 'url', 'popupwidth', '620', NULL),
(691, 2, 1451553063, 'url', 'popupheight', '450', NULL),
(692, 2, 1451553063, 'workshop', 'grade', '80', NULL),
(693, 2, 1451553063, 'workshop', 'gradinggrade', '20', NULL),
(694, 2, 1451553063, 'workshop', 'gradedecimals', '0', NULL),
(695, 2, 1451553063, 'workshop', 'maxbytes', '0', NULL),
(696, 2, 1451553063, 'workshop', 'strategy', 'accumulative', NULL),
(697, 2, 1451553063, 'workshop', 'examplesmode', '0', NULL),
(698, 2, 1451553063, 'workshopallocation_random', 'numofreviews', '5', NULL),
(699, 2, 1451553063, 'workshopform_numerrors', 'grade0', 'No', NULL),
(700, 2, 1451553063, 'workshopform_numerrors', 'grade1', 'Yes', NULL),
(701, 2, 1451553063, 'workshopeval_best', 'comparison', '5', NULL),
(702, 2, 1451553063, NULL, 'block_course_list_adminview', 'all', NULL),
(703, 2, 1451553063, NULL, 'block_course_list_hideallcourseslink', '0', NULL),
(704, 2, 1451553063, 'block_course_overview', 'defaultmaxcourses', '10', NULL),
(705, 2, 1451553063, 'block_course_overview', 'forcedefaultmaxcourses', '0', NULL),
(706, 2, 1451553063, 'block_course_overview', 'showchildren', '0', NULL),
(707, 2, 1451553063, 'block_course_overview', 'showwelcomearea', '0', NULL),
(708, 2, 1451553063, 'block_course_overview', 'showcategories', '0', NULL),
(709, 2, 1451553063, NULL, 'block_html_allowcssclasses', '0', NULL),
(710, 2, 1451553063, NULL, 'block_online_users_timetosee', '5', NULL),
(711, 2, 1451553063, NULL, 'block_rss_client_num_entries', '5', NULL),
(712, 2, 1451553063, NULL, 'block_rss_client_timeout', '30', NULL),
(713, 2, 1451553063, 'block_section_links', 'numsections1', '22', NULL),
(714, 2, 1451553063, 'block_section_links', 'incby1', '2', NULL),
(715, 2, 1451553063, 'block_section_links', 'numsections2', '40', NULL),
(716, 2, 1451553063, 'block_section_links', 'incby2', '5', NULL),
(717, 2, 1451553063, 'block_tag_youtube', 'apikey', '', NULL),
(718, 2, 1451553063, 'format_singleactivity', 'activitytype', 'forum', NULL),
(719, 2, 1451553063, 'enrol_cohort', 'roleid', '5', NULL),
(720, 2, 1451553063, 'enrol_cohort', 'unenrolaction', '0', NULL),
(721, 2, 1451553063, 'enrol_database', 'dbtype', '', NULL),
(722, 2, 1451553063, 'enrol_database', 'dbhost', 'localhost', NULL),
(723, 2, 1451553063, 'enrol_database', 'dbuser', '', NULL),
(724, 2, 1451553063, 'enrol_database', 'dbpass', '', NULL),
(725, 2, 1451553063, 'enrol_database', 'dbname', '', NULL),
(726, 2, 1451553063, 'enrol_database', 'dbencoding', 'utf-8', NULL),
(727, 2, 1451553063, 'enrol_database', 'dbsetupsql', '', NULL),
(728, 2, 1451553063, 'enrol_database', 'dbsybasequoting', '0', NULL),
(729, 2, 1451553063, 'enrol_database', 'debugdb', '0', NULL),
(730, 2, 1451553063, 'enrol_database', 'localcoursefield', 'idnumber', NULL),
(731, 2, 1451553063, 'enrol_database', 'localuserfield', 'idnumber', NULL),
(732, 2, 1451553063, 'enrol_database', 'localrolefield', 'shortname', NULL),
(733, 2, 1451553063, 'enrol_database', 'localcategoryfield', 'id', NULL),
(734, 2, 1451553063, 'enrol_database', 'remoteenroltable', '', NULL),
(735, 2, 1451553063, 'enrol_database', 'remotecoursefield', '', NULL),
(736, 2, 1451553063, 'enrol_database', 'remoteuserfield', '', NULL),
(737, 2, 1451553063, 'enrol_database', 'remoterolefield', '', NULL),
(738, 2, 1451553063, 'enrol_database', 'remoteotheruserfield', '', NULL),
(739, 2, 1451553063, 'enrol_database', 'defaultrole', '5', NULL),
(740, 2, 1451553063, 'enrol_database', 'ignorehiddencourses', '0', NULL),
(741, 2, 1451553063, 'enrol_database', 'unenrolaction', '0', NULL),
(742, 2, 1451553063, 'enrol_database', 'newcoursetable', '', NULL),
(743, 2, 1451553063, 'enrol_database', 'newcoursefullname', 'fullname', NULL),
(744, 2, 1451553063, 'enrol_database', 'newcourseshortname', 'shortname', NULL);
INSERT INTO `mdl_config_log` (`id`, `userid`, `timemodified`, `plugin`, `name`, `value`, `oldvalue`) VALUES
(745, 2, 1451553063, 'enrol_database', 'newcourseidnumber', 'idnumber', NULL),
(746, 2, 1451553063, 'enrol_database', 'newcoursecategory', '', NULL),
(747, 2, 1451553063, 'enrol_database', 'defaultcategory', '1', NULL),
(748, 2, 1451553063, 'enrol_database', 'templatecourse', '', NULL),
(749, 2, 1451553063, 'enrol_flatfile', 'location', '', NULL),
(750, 2, 1451553063, 'enrol_flatfile', 'encoding', 'UTF-8', NULL),
(751, 2, 1451553063, 'enrol_flatfile', 'mailstudents', '0', NULL),
(752, 2, 1451553063, 'enrol_flatfile', 'mailteachers', '0', NULL),
(753, 2, 1451553063, 'enrol_flatfile', 'mailadmins', '0', NULL),
(754, 2, 1451553063, 'enrol_flatfile', 'unenrolaction', '3', NULL),
(755, 2, 1451553063, 'enrol_flatfile', 'expiredaction', '3', NULL),
(756, 2, 1451553063, 'enrol_guest', 'requirepassword', '0', NULL),
(757, 2, 1451553063, 'enrol_guest', 'usepasswordpolicy', '0', NULL),
(758, 2, 1451553063, 'enrol_guest', 'showhint', '0', NULL),
(759, 2, 1451553063, 'enrol_guest', 'defaultenrol', '1', NULL),
(760, 2, 1451553063, 'enrol_guest', 'status', '1', NULL),
(761, 2, 1451553063, 'enrol_guest', 'status_adv', '', NULL),
(762, 2, 1451553063, 'enrol_imsenterprise', 'imsfilelocation', '', NULL),
(763, 2, 1451553063, 'enrol_imsenterprise', 'logtolocation', '', NULL),
(764, 2, 1451553063, 'enrol_imsenterprise', 'mailadmins', '0', NULL),
(765, 2, 1451553063, 'enrol_imsenterprise', 'createnewusers', '0', NULL),
(766, 2, 1451553063, 'enrol_imsenterprise', 'imsdeleteusers', '0', NULL),
(767, 2, 1451553063, 'enrol_imsenterprise', 'fixcaseusernames', '0', NULL),
(768, 2, 1451553063, 'enrol_imsenterprise', 'fixcasepersonalnames', '0', NULL),
(769, 2, 1451553063, 'enrol_imsenterprise', 'imssourcedidfallback', '0', NULL),
(770, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap01', '5', NULL),
(771, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap02', '3', NULL),
(772, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap03', '3', NULL),
(773, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap04', '5', NULL),
(774, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap05', '0', NULL),
(775, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap06', '4', NULL),
(776, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap07', '0', NULL),
(777, 2, 1451553063, 'enrol_imsenterprise', 'imsrolemap08', '4', NULL),
(778, 2, 1451553063, 'enrol_imsenterprise', 'truncatecoursecodes', '0', NULL),
(779, 2, 1451553063, 'enrol_imsenterprise', 'createnewcourses', '0', NULL),
(780, 2, 1451553063, 'enrol_imsenterprise', 'createnewcategories', '0', NULL),
(781, 2, 1451553063, 'enrol_imsenterprise', 'imsunenrol', '0', NULL),
(782, 2, 1451553063, 'enrol_imsenterprise', 'imscoursemapshortname', 'coursecode', NULL),
(783, 2, 1451553063, 'enrol_imsenterprise', 'imscoursemapfullname', 'short', NULL),
(784, 2, 1451553063, 'enrol_imsenterprise', 'imscoursemapsummary', 'ignore', NULL),
(785, 2, 1451553063, 'enrol_imsenterprise', 'imsrestricttarget', '', NULL),
(786, 2, 1451553063, 'enrol_imsenterprise', 'imscapitafix', '0', NULL),
(787, 2, 1451553063, 'enrol_manual', 'expiredaction', '1', NULL),
(788, 2, 1451553063, 'enrol_manual', 'expirynotifyhour', '6', NULL),
(789, 2, 1451553063, 'enrol_manual', 'defaultenrol', '1', NULL),
(790, 2, 1451553063, 'enrol_manual', 'status', '0', NULL),
(791, 2, 1451553063, 'enrol_manual', 'roleid', '5', NULL),
(792, 2, 1451553063, 'enrol_manual', 'enrolstart', '4', NULL),
(793, 2, 1451553063, 'enrol_manual', 'enrolperiod', '0', NULL),
(794, 2, 1451553063, 'enrol_manual', 'expirynotify', '0', NULL),
(795, 2, 1451553063, 'enrol_manual', 'expirythreshold', '86400', NULL),
(796, 2, 1451553063, 'enrol_meta', 'nosyncroleids', '', NULL),
(797, 2, 1451553063, 'enrol_meta', 'syncall', '1', NULL),
(798, 2, 1451553063, 'enrol_meta', 'unenrolaction', '3', NULL),
(799, 2, 1451553063, 'enrol_meta', 'coursesort', 'sortorder', NULL),
(800, 2, 1451553063, 'enrol_mnet', 'roleid', '5', NULL),
(801, 2, 1451553063, 'enrol_mnet', 'roleid_adv', '1', NULL),
(802, 2, 1451553063, 'enrol_paypal', 'paypalbusiness', '', NULL),
(803, 2, 1451553063, 'enrol_paypal', 'mailstudents', '0', NULL),
(804, 2, 1451553063, 'enrol_paypal', 'mailteachers', '0', NULL),
(805, 2, 1451553063, 'enrol_paypal', 'mailadmins', '0', NULL),
(806, 2, 1451553063, 'enrol_paypal', 'expiredaction', '3', NULL),
(807, 2, 1451553063, 'enrol_paypal', 'status', '1', NULL),
(808, 2, 1451553063, 'enrol_paypal', 'cost', '0', NULL),
(809, 2, 1451553063, 'enrol_paypal', 'currency', 'USD', NULL),
(810, 2, 1451553063, 'enrol_paypal', 'roleid', '5', NULL),
(811, 2, 1451553063, 'enrol_paypal', 'enrolperiod', '0', NULL),
(812, 2, 1451553063, 'enrol_self', 'requirepassword', '0', NULL),
(813, 2, 1451553063, 'enrol_self', 'usepasswordpolicy', '0', NULL),
(814, 2, 1451553063, 'enrol_self', 'showhint', '0', NULL),
(815, 2, 1451553063, 'enrol_self', 'expiredaction', '1', NULL),
(816, 2, 1451553063, 'enrol_self', 'expirynotifyhour', '6', NULL),
(817, 2, 1451553063, 'enrol_self', 'defaultenrol', '1', NULL),
(818, 2, 1451553063, 'enrol_self', 'status', '1', NULL),
(819, 2, 1451553063, 'enrol_self', 'newenrols', '1', NULL),
(820, 2, 1451553063, 'enrol_self', 'groupkey', '0', NULL),
(821, 2, 1451553063, 'enrol_self', 'roleid', '5', NULL),
(822, 2, 1451553063, 'enrol_self', 'enrolperiod', '0', NULL),
(823, 2, 1451553063, 'enrol_self', 'expirynotify', '0', NULL),
(824, 2, 1451553063, 'enrol_self', 'expirythreshold', '86400', NULL),
(825, 2, 1451553063, 'enrol_self', 'longtimenosee', '0', NULL),
(826, 2, 1451553063, 'enrol_self', 'maxenrolled', '0', NULL),
(827, 2, 1451553063, 'enrol_self', 'sendcoursewelcomemessage', '1', NULL),
(828, 2, 1451553064, NULL, 'filter_censor_badwords', '', NULL),
(829, 2, 1451553064, 'filter_emoticon', 'formats', '1,4,0', NULL),
(830, 2, 1451553064, 'filter_mathjaxloader', 'httpurl', 'http://cdn.mathjax.org/mathjax/2.5-latest/MathJax.js', NULL),
(831, 2, 1451553064, 'filter_mathjaxloader', 'httpsurl', 'https://cdn.mathjax.org/mathjax/2.5-latest/MathJax.js', NULL),
(832, 2, 1451553064, 'filter_mathjaxloader', 'texfiltercompatibility', '0', NULL),
(833, 2, 1451553064, 'filter_mathjaxloader', 'mathjaxconfig', '\nMathJax.Hub.Config({\n    config: ["Accessible.js", "Safe.js"],\n    errorSettings: { message: ["!"] },\n    skipStartupTypeset: true,\n    messageStyle: "none"\n});\n', NULL),
(834, 2, 1451553064, 'filter_mathjaxloader', 'additionaldelimiters', '', NULL),
(835, 2, 1451553064, NULL, 'filter_multilang_force_old', '0', NULL),
(836, 2, 1451553064, 'filter_tex', 'latexpreamble', '\\usepackage[latin1]{inputenc}\n\\usepackage{amsmath}\n\\usepackage{amsfonts}\n\\RequirePackage{amsmath,amssymb,latexsym}\n', NULL),
(837, 2, 1451553064, 'filter_tex', 'latexbackground', '#FFFFFF', NULL),
(838, 2, 1451553064, 'filter_tex', 'density', '120', NULL),
(839, 2, 1451553064, 'filter_tex', 'pathlatex', '/usr/bin/latex', NULL),
(840, 2, 1451553064, 'filter_tex', 'pathdvips', '/usr/bin/dvips', NULL),
(841, 2, 1451553064, 'filter_tex', 'pathconvert', '/usr/bin/convert', NULL),
(842, 2, 1451553064, 'filter_tex', 'pathdvisvgm', '/usr/bin/dvisvgm', NULL),
(843, 2, 1451553064, 'filter_tex', 'pathmimetex', '', NULL),
(844, 2, 1451553064, 'filter_tex', 'convertformat', 'gif', NULL),
(845, 2, 1451553064, 'filter_urltolink', 'formats', '0', NULL),
(846, 2, 1451553064, 'filter_urltolink', 'embedimages', '1', NULL),
(847, 2, 1451553064, 'logstore_database', 'dbdriver', '', NULL),
(848, 2, 1451553064, 'logstore_database', 'dbhost', '', NULL),
(849, 2, 1451553064, 'logstore_database', 'dbuser', '', NULL),
(850, 2, 1451553064, 'logstore_database', 'dbpass', '', NULL),
(851, 2, 1451553064, 'logstore_database', 'dbname', '', NULL),
(852, 2, 1451553064, 'logstore_database', 'dbtable', '', NULL),
(853, 2, 1451553064, 'logstore_database', 'dbpersist', '0', NULL),
(854, 2, 1451553064, 'logstore_database', 'dbsocket', '', NULL),
(855, 2, 1451553064, 'logstore_database', 'dbport', '', NULL),
(856, 2, 1451553064, 'logstore_database', 'dbschema', '', NULL),
(857, 2, 1451553064, 'logstore_database', 'dbcollation', '', NULL),
(858, 2, 1451553064, 'logstore_database', 'buffersize', '50', NULL),
(859, 2, 1451553064, 'logstore_database', 'logguests', '0', NULL),
(860, 2, 1451553064, 'logstore_database', 'includelevels', '1,2,0', NULL),
(861, 2, 1451553064, 'logstore_database', 'includeactions', 'c,r,u,d', NULL),
(862, 2, 1451553064, 'logstore_legacy', 'loglegacy', '0', NULL),
(863, 2, 1451553064, NULL, 'logguests', '1', NULL),
(864, 2, 1451553064, NULL, 'loglifetime', '0', NULL),
(865, 2, 1451553064, 'logstore_standard', 'logguests', '1', NULL),
(866, 2, 1451553064, 'logstore_standard', 'loglifetime', '0', NULL),
(867, 2, 1451553064, 'logstore_standard', 'buffersize', '50', NULL),
(868, 2, 1451553064, NULL, 'airnotifierurl', 'https://messages.moodle.net', NULL),
(869, 2, 1451553064, NULL, 'airnotifierport', '443', NULL),
(870, 2, 1451553064, NULL, 'airnotifiermobileappname', 'com.moodle.moodlemobile', NULL),
(871, 2, 1451553064, NULL, 'airnotifierappname', 'commoodlemoodlemobile', NULL),
(872, 2, 1451553064, NULL, 'airnotifieraccesskey', '', NULL),
(873, 2, 1451553064, NULL, 'smtphosts', '', NULL),
(874, 2, 1451553064, NULL, 'smtpsecure', '', NULL),
(875, 2, 1451553064, NULL, 'smtpauthtype', 'LOGIN', NULL),
(876, 2, 1451553064, NULL, 'smtpuser', '', NULL),
(877, 2, 1451553064, NULL, 'smtppass', '', NULL),
(878, 2, 1451553064, NULL, 'smtpmaxbulk', '1', NULL),
(879, 2, 1451553064, NULL, 'noreplyaddress', 'noreply@mycodebusters.com', NULL),
(880, 2, 1451553064, NULL, 'emailonlyfromnoreplyaddress', '0', NULL),
(881, 2, 1451553064, NULL, 'sitemailcharset', '0', NULL),
(882, 2, 1451553064, NULL, 'allowusermailcharset', '0', NULL),
(883, 2, 1451553064, NULL, 'allowattachments', '1', NULL),
(884, 2, 1451553064, NULL, 'mailnewline', 'LF', NULL),
(885, 2, 1451553064, NULL, 'jabberhost', '', NULL),
(886, 2, 1451553064, NULL, 'jabberserver', '', NULL),
(887, 2, 1451553064, NULL, 'jabberusername', '', NULL),
(888, 2, 1451553064, NULL, 'jabberpassword', '', NULL),
(889, 2, 1451553064, NULL, 'jabberport', '5222', NULL),
(890, 2, 1451553064, 'editor_atto', 'toolbar', 'collapse = collapse\nstyle1 = title, bold, italic\nlist = unorderedlist, orderedlist\nlinks = link\nfiles = image, media, managefiles\nstyle2 = underline, strike, subscript, superscript\nalign = align\nindent = indent\ninsert = equation, charmap, table, clear\nundo = undo\naccessibility = accessibilitychecker, accessibilityhelper\nother = html', NULL),
(891, 2, 1451553064, 'editor_atto', 'autosavefrequency', '60', NULL),
(892, 2, 1451553064, 'atto_collapse', 'showgroups', '5', NULL),
(893, 2, 1451553064, 'atto_equation', 'librarygroup1', '\n\\cdot\n\\times\n\\ast\n\\div\n\\diamond\n\\pm\n\\mp\n\\oplus\n\\ominus\n\\otimes\n\\oslash\n\\odot\n\\circ\n\\bullet\n\\asymp\n\\equiv\n\\subseteq\n\\supseteq\n\\leq\n\\geq\n\\preceq\n\\succeq\n\\sim\n\\simeq\n\\approx\n\\subset\n\\supset\n\\ll\n\\gg\n\\prec\n\\succ\n\\infty\n\\in\n\\ni\n\\forall\n\\exists\n\\neq\n', NULL),
(894, 2, 1451553064, 'atto_equation', 'librarygroup2', '\n\\leftarrow\n\\rightarrow\n\\uparrow\n\\downarrow\n\\leftrightarrow\n\\nearrow\n\\searrow\n\\swarrow\n\\nwarrow\n\\Leftarrow\n\\Rightarrow\n\\Uparrow\n\\Downarrow\n\\Leftrightarrow\n', NULL),
(895, 2, 1451553064, 'atto_equation', 'librarygroup3', '\n\\alpha\n\\beta\n\\gamma\n\\delta\n\\epsilon\n\\zeta\n\\eta\n\\theta\n\\iota\n\\kappa\n\\lambda\n\\mu\n\\nu\n\\xi\n\\pi\n\\rho\n\\sigma\n\\tau\n\\upsilon\n\\phi\n\\chi\n\\psi\n\\omega\n\\Gamma\n\\Delta\n\\Theta\n\\Lambda\n\\Xi\n\\Pi\n\\Sigma\n\\Upsilon\n\\Phi\n\\Psi\n\\Omega\n', NULL),
(896, 2, 1451553064, 'atto_equation', 'librarygroup4', '\n\\sum{a,b}\n\\sqrt[a]{b+c}\n\\int_{a}^{b}{c}\n\\iint_{a}^{b}{c}\n\\iiint_{a}^{b}{c}\n\\oint{a}\n(a)\n[a]\n\\lbrace{a}\\rbrace\n\\left| \\begin{matrix} a_1 & a_2 \\ a_3 & a_4 \\end{matrix} \\right|\n\\frac{a}{b+c}\n\\vec{a}\n\\binom {a} {b}\n{a \\brack b}\n{a \\brace b}\n', NULL),
(897, 2, 1451553064, 'atto_table', 'allowborders', '0', NULL),
(898, 2, 1451553064, 'atto_table', 'allowbackgroundcolour', '0', NULL),
(899, 2, 1451553064, 'atto_table', 'allowwidth', '0', NULL),
(900, 2, 1451553064, 'editor_tinymce', 'customtoolbar', 'wrap,formatselect,wrap,bold,italic,wrap,bullist,numlist,wrap,link,unlink,wrap,image\n\nundo,redo,wrap,underline,strikethrough,sub,sup,wrap,justifyleft,justifycenter,justifyright,wrap,outdent,indent,wrap,forecolor,backcolor,wrap,ltr,rtl\n\nfontselect,fontsizeselect,wrap,code,search,replace,wrap,nonbreaking,charmap,table,wrap,cleanup,removeformat,pastetext,pasteword,wrap,fullscreen', NULL),
(901, 2, 1451553064, 'editor_tinymce', 'fontselectlist', 'Trebuchet=Trebuchet MS,Verdana,Arial,Helvetica,sans-serif;Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;Wingdings=wingdings', NULL),
(902, 2, 1451553064, 'editor_tinymce', 'customconfig', '', NULL),
(903, 2, 1451553064, 'tinymce_moodleemoticon', 'requireemoticon', '1', NULL),
(904, 2, 1451553064, 'tinymce_spellchecker', 'spellengine', '', NULL),
(905, 2, 1451553064, 'tinymce_spellchecker', 'spelllanguagelist', '+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv', NULL),
(906, 2, 1451553064, NULL, 'enablemobilewebservice', '0', NULL),
(907, 2, 1451553064, NULL, 'profileroles', '5,4,3', NULL),
(908, 2, 1451553064, NULL, 'coursecontact', '3', NULL),
(909, 2, 1451553064, NULL, 'frontpage', '6', NULL),
(910, 2, 1451553064, NULL, 'frontpageloggedin', '6', NULL),
(911, 2, 1451553064, NULL, 'maxcategorydepth', '2', NULL),
(912, 2, 1451553064, NULL, 'frontpagecourselimit', '200', NULL),
(913, 2, 1451553064, NULL, 'commentsperpage', '15', NULL),
(914, 2, 1451553064, NULL, 'defaultfrontpageroleid', '8', NULL),
(915, 2, 1451553064, NULL, 'supportname', 'Admin User', NULL),
(916, 2, 1451553064, NULL, 'supportemail', 'some@gmail.com', NULL),
(917, 2, 1451553064, NULL, 'messageinbound_enabled', '0', NULL),
(918, 2, 1451553064, NULL, 'messageinbound_mailbox', '', NULL),
(919, 2, 1451553064, NULL, 'messageinbound_domain', '', NULL),
(920, 2, 1451553064, NULL, 'messageinbound_host', '', NULL),
(921, 2, 1451553064, NULL, 'messageinbound_hostssl', 'ssl', NULL),
(922, 2, 1451553064, NULL, 'messageinbound_hostuser', '', NULL),
(923, 2, 1451553064, NULL, 'messageinbound_hostpass', '', NULL),
(924, 2, 1451553154, NULL, 'timezone', 'Pacific/Wallis', NULL),
(925, 2, 1451553154, NULL, 'registerauth', '', NULL),
(926, 2, 1451892695, 'theme_lambda', 'logo', '', NULL),
(927, 2, 1451892695, 'theme_lambda', 'logo_res', '0', NULL),
(928, 2, 1451892695, 'theme_lambda', 'pagewidth', '1600', NULL),
(929, 2, 1451892695, 'theme_lambda', 'layout', '', NULL),
(930, 2, 1451892695, 'theme_lambda', 'mycourses_dropdown', '', NULL),
(931, 2, 1451892695, 'theme_lambda', 'footnote', '', NULL),
(932, 2, 1451892695, 'theme_lambda', 'customcss', '', NULL),
(933, 2, 1451892695, 'theme_lambda', 'list_bg', '0', NULL),
(934, 2, 1451892695, 'theme_lambda', 'pagebackground', '', NULL),
(935, 2, 1451892695, 'theme_lambda', 'page_bg_repeat', '0', NULL),
(936, 2, 1451892695, 'theme_lambda', 'maincolor', '#e2a500', NULL),
(937, 2, 1451892695, 'theme_lambda', 'mainhovercolor', '#c48f00', NULL),
(938, 2, 1451892695, 'theme_lambda', 'linkcolor', '#966b00', NULL),
(939, 2, 1451892695, 'theme_lambda', 'def_buttoncolor', '#8ec63f', NULL),
(940, 2, 1451892695, 'theme_lambda', 'def_buttonhovercolor', '#77ae29', NULL),
(941, 2, 1451892695, 'theme_lambda', 'menufirstlevelcolor', '#323A45', NULL),
(942, 2, 1451892695, 'theme_lambda', 'menufirstlevel_linkcolor', '#ffffff', NULL),
(943, 2, 1451892695, 'theme_lambda', 'menusecondlevelcolor', '#f4f4f4', NULL),
(944, 2, 1451892695, 'theme_lambda', 'menusecondlevel_linkcolor', '#444444', NULL),
(945, 2, 1451892695, 'theme_lambda', 'footercolor', '#323A45', NULL),
(946, 2, 1451892695, 'theme_lambda', 'footerheadingcolor', '#f9f9f9', NULL),
(947, 2, 1451892695, 'theme_lambda', 'footertextcolor', '#bdc3c7', NULL),
(948, 2, 1451892695, 'theme_lambda', 'copyrightcolor', '#292F38', NULL),
(949, 2, 1451892695, 'theme_lambda', 'copyright_textcolor', '#bdc3c7', NULL),
(950, 2, 1451892695, 'theme_lambda', 'website', '', NULL),
(951, 2, 1451892695, 'theme_lambda', 'socials_mail', '', NULL),
(952, 2, 1451892695, 'theme_lambda', 'facebook', '', NULL),
(953, 2, 1451892695, 'theme_lambda', 'flickr', '', NULL),
(954, 2, 1451892695, 'theme_lambda', 'twitter', '', NULL),
(955, 2, 1451892695, 'theme_lambda', 'googleplus', '', NULL),
(956, 2, 1451892695, 'theme_lambda', 'pinterest', '', NULL),
(957, 2, 1451892695, 'theme_lambda', 'instagram', '', NULL),
(958, 2, 1451892695, 'theme_lambda', 'youtube', '', NULL),
(959, 2, 1451892695, 'theme_lambda', 'socials_color', '#a9a9a9', NULL),
(960, 2, 1451892695, 'theme_lambda', 'socials_position', '0', NULL),
(961, 2, 1451892695, 'theme_lambda', 'font_body', '1', NULL),
(962, 2, 1451892695, 'theme_lambda', 'font_heading', '1', NULL),
(963, 2, 1451892695, 'theme_lambda', 'slide1image', '', NULL),
(964, 2, 1451892695, 'theme_lambda', 'slide1', '', NULL),
(965, 2, 1451892695, 'theme_lambda', 'slide1caption', '', NULL),
(966, 2, 1451892695, 'theme_lambda', 'slide1_url', '', NULL),
(967, 2, 1451892695, 'theme_lambda', 'slide2image', '', NULL),
(968, 2, 1451892695, 'theme_lambda', 'slide2', '', NULL),
(969, 2, 1451892695, 'theme_lambda', 'slide2caption', '', NULL),
(970, 2, 1451892695, 'theme_lambda', 'slide2_url', '', NULL),
(971, 2, 1451892696, 'theme_lambda', 'slide3image', '', NULL),
(972, 2, 1451892696, 'theme_lambda', 'slide3', '', NULL),
(973, 2, 1451892696, 'theme_lambda', 'slide3caption', '', NULL),
(974, 2, 1451892696, 'theme_lambda', 'slide3_url', '', NULL),
(975, 2, 1451892696, 'theme_lambda', 'slide4image', '', NULL),
(976, 2, 1451892696, 'theme_lambda', 'slide4', '', NULL),
(977, 2, 1451892696, 'theme_lambda', 'slide4caption', '', NULL),
(978, 2, 1451892696, 'theme_lambda', 'slide4_url', '', NULL),
(979, 2, 1451892696, 'theme_lambda', 'slide5image', '', NULL),
(980, 2, 1451892696, 'theme_lambda', 'slide5', '', NULL),
(981, 2, 1451892696, 'theme_lambda', 'slide5caption', '', NULL),
(982, 2, 1451892696, 'theme_lambda', 'slide5_url', '', NULL),
(983, 2, 1451892696, 'theme_lambda', 'slideshowpattern', '0', NULL),
(984, 2, 1451892696, 'theme_lambda', 'slideshow_advance', '1', NULL),
(985, 2, 1451892696, 'theme_lambda', 'slideshow_nav', '1', NULL),
(986, 2, 1451892696, 'theme_lambda', 'slideshow_loader', '0', NULL),
(987, 2, 1451892696, 'theme_lambda', 'slideshow_imgfx', 'random', NULL),
(988, 2, 1451892696, 'theme_lambda', 'slideshow_txtfx', 'moveFromLeft', NULL),
(989, 2, 1451892696, 'theme_lambda', 'carousel_position', '1', NULL),
(990, 2, 1451892696, 'theme_lambda', 'carousel_h', '', NULL),
(991, 2, 1451892696, 'theme_lambda', 'carousel_hi', '3', NULL),
(992, 2, 1451892696, 'theme_lambda', 'carousel_add_html', '', NULL),
(993, 2, 1451892696, 'theme_lambda', 'carousel_slides', '4', NULL),
(994, 2, 1451892705, 'theme_lambda', 'carousel_image_1', '', NULL),
(995, 2, 1451892705, 'theme_lambda', 'carousel_heading_1', '', NULL),
(996, 2, 1451892705, 'theme_lambda', 'carousel_caption_1', '', NULL),
(997, 2, 1451892705, 'theme_lambda', 'carousel_url_1', '', NULL),
(998, 2, 1451892705, 'theme_lambda', 'carousel_btntext_1', '', NULL),
(999, 2, 1451892705, 'theme_lambda', 'carousel_color_1', '0', NULL),
(1000, 2, 1451892705, 'theme_lambda', 'carousel_image_2', '', NULL),
(1001, 2, 1451892705, 'theme_lambda', 'carousel_heading_2', '', NULL),
(1002, 2, 1451892705, 'theme_lambda', 'carousel_caption_2', '', NULL),
(1003, 2, 1451892705, 'theme_lambda', 'carousel_url_2', '', NULL),
(1004, 2, 1451892705, 'theme_lambda', 'carousel_btntext_2', '', NULL),
(1005, 2, 1451892706, 'theme_lambda', 'carousel_color_2', '0', NULL),
(1006, 2, 1451892706, 'theme_lambda', 'carousel_image_3', '', NULL),
(1007, 2, 1451892706, 'theme_lambda', 'carousel_heading_3', '', NULL),
(1008, 2, 1451892706, 'theme_lambda', 'carousel_caption_3', '', NULL),
(1009, 2, 1451892706, 'theme_lambda', 'carousel_url_3', '', NULL),
(1010, 2, 1451892706, 'theme_lambda', 'carousel_btntext_3', '', NULL),
(1011, 2, 1451892706, 'theme_lambda', 'carousel_color_3', '0', NULL),
(1012, 2, 1451892706, 'theme_lambda', 'carousel_image_4', '', NULL),
(1013, 2, 1451892706, 'theme_lambda', 'carousel_heading_4', '', NULL),
(1014, 2, 1451892706, 'theme_lambda', 'carousel_caption_4', '', NULL),
(1015, 2, 1451892706, 'theme_lambda', 'carousel_url_4', '', NULL),
(1016, 2, 1451892706, 'theme_lambda', 'carousel_btntext_4', '', NULL),
(1017, 2, 1451892706, 'theme_lambda', 'carousel_color_4', '0', NULL),
(1018, 2, 1452671699, NULL, 'alternateloginurl', 'http://mycodebusters.com/medical', ''),
(1019, 2, 1453187037, NULL, 'alternateloginurl', '', 'http://mycodebusters.com/medical'),
(1020, 2, 1453201478, NULL, 'alternateloginurl', 'http://cnausa.com/', ''),
(1021, 2, 1453286129, NULL, 'debug', '32767', '0'),
(1022, 2, 1453897879, NULL, 'alternateloginurl', 'http://cnausa.com/index.php/login', 'http://cnausa.com/'),
(1023, 2, 1454079112, NULL, 'alternateloginurl', 'http://cnausa.com/', 'http://cnausa.com/index.php/login'),
(1024, 2, 1455293327, NULL, 'registerauth', 'email', '');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_config_plugins`
--

CREATE TABLE IF NOT EXISTS `mdl_config_plugins` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `plugin` varchar(100) NOT NULL DEFAULT 'core',
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_confplug_plunam_uix` (`plugin`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Moodle modules and plugins configuration variables' AUTO_INCREMENT=1217 ;

--
-- Dumping data for table `mdl_config_plugins`
--

INSERT INTO `mdl_config_plugins` (`id`, `plugin`, `name`, `value`) VALUES
(1, 'question', 'multichoice_sortorder', '1'),
(2, 'question', 'truefalse_sortorder', '2'),
(3, 'question', 'match_sortorder', '3'),
(4, 'question', 'shortanswer_sortorder', '4'),
(5, 'question', 'numerical_sortorder', '5'),
(6, 'question', 'essay_sortorder', '6'),
(7, 'moodlecourse', 'visible', '1'),
(8, 'moodlecourse', 'format', 'weeks'),
(9, 'moodlecourse', 'maxsections', '52'),
(10, 'moodlecourse', 'numsections', '10'),
(11, 'moodlecourse', 'hiddensections', '0'),
(12, 'moodlecourse', 'coursedisplay', '0'),
(13, 'moodlecourse', 'lang', ''),
(14, 'moodlecourse', 'newsitems', '5'),
(15, 'moodlecourse', 'showgrades', '1'),
(16, 'moodlecourse', 'showreports', '0'),
(17, 'moodlecourse', 'maxbytes', '0'),
(18, 'moodlecourse', 'enablecompletion', '0'),
(19, 'moodlecourse', 'groupmode', '0'),
(20, 'moodlecourse', 'groupmodeforce', '0'),
(21, 'backup', 'loglifetime', '30'),
(22, 'backup', 'backup_general_users', '1'),
(23, 'backup', 'backup_general_users_locked', ''),
(24, 'backup', 'backup_general_anonymize', '0'),
(25, 'backup', 'backup_general_anonymize_locked', ''),
(26, 'backup', 'backup_general_role_assignments', '1'),
(27, 'backup', 'backup_general_role_assignments_locked', ''),
(28, 'backup', 'backup_general_activities', '1'),
(29, 'backup', 'backup_general_activities_locked', ''),
(30, 'backup', 'backup_general_blocks', '1'),
(31, 'backup', 'backup_general_blocks_locked', ''),
(32, 'backup', 'backup_general_filters', '1'),
(33, 'backup', 'backup_general_filters_locked', ''),
(34, 'backup', 'backup_general_comments', '1'),
(35, 'backup', 'backup_general_comments_locked', ''),
(36, 'backup', 'backup_general_badges', '1'),
(37, 'backup', 'backup_general_badges_locked', ''),
(38, 'backup', 'backup_general_userscompletion', '1'),
(39, 'backup', 'backup_general_userscompletion_locked', ''),
(40, 'backup', 'backup_general_logs', '0'),
(41, 'backup', 'backup_general_logs_locked', ''),
(42, 'backup', 'backup_general_histories', '0'),
(43, 'backup', 'backup_general_histories_locked', ''),
(44, 'backup', 'backup_general_questionbank', '1'),
(45, 'backup', 'backup_general_questionbank_locked', ''),
(46, 'backup', 'backup_general_groups', '1'),
(47, 'backup', 'backup_general_groups_locked', ''),
(48, 'backup', 'import_general_maxresults', '10'),
(49, 'backup', 'backup_auto_active', '0'),
(50, 'backup', 'backup_auto_weekdays', '0000000'),
(51, 'backup', 'backup_auto_hour', '0'),
(52, 'backup', 'backup_auto_minute', '0'),
(53, 'backup', 'backup_auto_storage', '0'),
(54, 'backup', 'backup_auto_destination', ''),
(55, 'backup', 'backup_auto_max_kept', '1'),
(56, 'backup', 'backup_auto_delete_days', '0'),
(57, 'backup', 'backup_auto_min_kept', '0'),
(58, 'backup', 'backup_shortname', '0'),
(59, 'backup', 'backup_auto_skip_hidden', '1'),
(60, 'backup', 'backup_auto_skip_modif_days', '30'),
(61, 'backup', 'backup_auto_skip_modif_prev', '0'),
(62, 'backup', 'backup_auto_users', '1'),
(63, 'backup', 'backup_auto_role_assignments', '1'),
(64, 'backup', 'backup_auto_activities', '1'),
(65, 'backup', 'backup_auto_blocks', '1'),
(66, 'backup', 'backup_auto_filters', '1'),
(67, 'backup', 'backup_auto_comments', '1'),
(68, 'backup', 'backup_auto_badges', '1'),
(69, 'backup', 'backup_auto_userscompletion', '1'),
(70, 'backup', 'backup_auto_logs', '0'),
(71, 'backup', 'backup_auto_histories', '0'),
(72, 'backup', 'backup_auto_questionbank', '1'),
(73, 'backup', 'backup_auto_groups', '1'),
(74, 'cachestore_memcache', 'testservers', ''),
(75, 'cachestore_memcached', 'testservers', ''),
(76, 'cachestore_mongodb', 'testserver', ''),
(77, 'question_preview', 'behaviour', 'deferredfeedback'),
(78, 'question_preview', 'correctness', '1'),
(79, 'question_preview', 'marks', '2'),
(80, 'question_preview', 'markdp', '2'),
(81, 'question_preview', 'feedback', '1'),
(82, 'question_preview', 'generalfeedback', '1'),
(83, 'question_preview', 'rightanswer', '1'),
(84, 'question_preview', 'history', '0'),
(85, 'theme_clean', 'invert', '0'),
(86, 'theme_clean', 'logo', ''),
(87, 'theme_clean', 'customcss', ''),
(88, 'theme_clean', 'footnote', ''),
(89, 'theme_more', 'textcolor', '#333366'),
(90, 'theme_more', 'linkcolor', '#FF6500'),
(91, 'theme_more', 'bodybackground', ''),
(92, 'theme_more', 'backgroundimage', ''),
(93, 'theme_more', 'backgroundrepeat', 'repeat'),
(94, 'theme_more', 'backgroundposition', '0'),
(95, 'theme_more', 'backgroundfixed', '0'),
(96, 'theme_more', 'contentbackground', '#FFFFFF'),
(97, 'theme_more', 'secondarybackground', '#FFFFFF'),
(98, 'theme_more', 'invert', '1'),
(99, 'theme_more', 'logo', ''),
(100, 'theme_more', 'customcss', ''),
(101, 'theme_more', 'footnote', ''),
(102, 'availability_completion', 'version', '2015111600'),
(103, 'availability_date', 'version', '2015111600'),
(104, 'availability_grade', 'version', '2015111600'),
(105, 'availability_group', 'version', '2015111600'),
(106, 'availability_grouping', 'version', '2015111600'),
(107, 'availability_profile', 'version', '2015111600'),
(108, 'qtype_calculated', 'version', '2015111600'),
(109, 'qtype_calculatedmulti', 'version', '2015111600'),
(110, 'qtype_calculatedsimple', 'version', '2015111600'),
(111, 'qtype_ddimageortext', 'version', '2015111600'),
(112, 'qtype_ddmarker', 'version', '2015111600'),
(113, 'qtype_ddwtos', 'version', '2015111600'),
(114, 'qtype_description', 'version', '2015111600'),
(115, 'qtype_essay', 'version', '2015111600'),
(116, 'qtype_gapselect', 'version', '2015111600'),
(117, 'qtype_match', 'version', '2015111600'),
(118, 'qtype_missingtype', 'version', '2015111600'),
(119, 'qtype_multianswer', 'version', '2015111600'),
(120, 'qtype_multichoice', 'version', '2015111600'),
(121, 'qtype_numerical', 'version', '2015111600'),
(122, 'qtype_random', 'version', '2015111600'),
(123, 'qtype_randomsamatch', 'version', '2015111600'),
(124, 'qtype_shortanswer', 'version', '2015111600'),
(125, 'qtype_truefalse', 'version', '2015111600'),
(126, 'mod_assign', 'version', '2015111600'),
(127, 'mod_assignment', 'version', '2015111600'),
(129, 'mod_book', 'version', '2015111600'),
(130, 'mod_chat', 'version', '2015111600'),
(131, 'mod_choice', 'version', '2015111600'),
(132, 'mod_data', 'version', '2015111600'),
(133, 'mod_feedback', 'version', '2015111600'),
(135, 'mod_folder', 'version', '2015111600'),
(137, 'mod_forum', 'version', '2015111600'),
(138, 'mod_glossary', 'version', '2015111600'),
(139, 'mod_imscp', 'version', '2015111600'),
(141, 'mod_label', 'version', '2015111600'),
(142, 'mod_lesson', 'version', '2015111600'),
(143, 'mod_lti', 'version', '2015111600'),
(144, 'mod_page', 'version', '2015111600'),
(146, 'mod_quiz', 'version', '2015111600'),
(147, 'mod_resource', 'version', '2015111600'),
(148, 'mod_scorm', 'version', '2015111601'),
(149, 'mod_survey', 'version', '2015111600'),
(151, 'mod_url', 'version', '2015111600'),
(153, 'mod_wiki', 'version', '2015111600'),
(155, 'mod_workshop', 'version', '2015111600'),
(156, 'auth_cas', 'version', '2015111600'),
(158, 'auth_db', 'version', '2015111600'),
(160, 'auth_email', 'version', '2015111600'),
(161, 'auth_fc', 'version', '2015111600'),
(163, 'auth_imap', 'version', '2015111600'),
(165, 'auth_ldap', 'version', '2015111600'),
(167, 'auth_manual', 'version', '2015111600'),
(168, 'auth_mnet', 'version', '2015111600'),
(170, 'auth_nntp', 'version', '2015111600'),
(172, 'auth_nologin', 'version', '2015111600'),
(173, 'auth_none', 'version', '2015111600'),
(174, 'auth_pam', 'version', '2015111600'),
(176, 'auth_pop3', 'version', '2015111600'),
(178, 'auth_radius', 'version', '2015111600'),
(180, 'auth_shibboleth', 'version', '2015111600'),
(182, 'auth_webservice', 'version', '2015111600'),
(183, 'calendartype_gregorian', 'version', '2015111600'),
(184, 'enrol_category', 'version', '2015111600'),
(186, 'enrol_cohort', 'version', '2015111600'),
(187, 'enrol_database', 'version', '2015111600'),
(189, 'enrol_flatfile', 'version', '2015111600'),
(191, 'enrol_flatfile', 'map_1', 'manager'),
(192, 'enrol_flatfile', 'map_2', 'coursecreator'),
(193, 'enrol_flatfile', 'map_3', 'editingteacher'),
(194, 'enrol_flatfile', 'map_4', 'teacher'),
(195, 'enrol_flatfile', 'map_5', 'student'),
(196, 'enrol_flatfile', 'map_6', 'guest'),
(197, 'enrol_flatfile', 'map_7', 'user'),
(198, 'enrol_flatfile', 'map_8', 'frontpage'),
(199, 'enrol_guest', 'version', '2015111600'),
(200, 'enrol_imsenterprise', 'version', '2015111600'),
(202, 'enrol_ldap', 'version', '2015111600'),
(204, 'enrol_manual', 'version', '2015111600'),
(206, 'enrol_meta', 'version', '2015111600'),
(208, 'enrol_mnet', 'version', '2015111600'),
(209, 'enrol_paypal', 'version', '2015111600'),
(210, 'enrol_self', 'version', '2015111600'),
(212, 'message_airnotifier', 'version', '2015111600'),
(214, 'message', 'airnotifier_provider_enrol_flatfile_flatfile_enrolment_permitted', 'permitted'),
(215, 'message', 'airnotifier_provider_enrol_imsenterprise_imsenterprise_enrolment_permitted', 'permitted'),
(216, 'message', 'airnotifier_provider_enrol_manual_expiry_notification_permitted', 'permitted'),
(217, 'message', 'airnotifier_provider_enrol_paypal_paypal_enrolment_permitted', 'permitted'),
(218, 'message', 'airnotifier_provider_enrol_self_expiry_notification_permitted', 'permitted'),
(219, 'message', 'airnotifier_provider_mod_assign_assign_notification_permitted', 'permitted'),
(220, 'message', 'airnotifier_provider_mod_assignment_assignment_updates_permitted', 'permitted'),
(221, 'message', 'airnotifier_provider_mod_feedback_submission_permitted', 'permitted'),
(222, 'message', 'airnotifier_provider_mod_feedback_message_permitted', 'permitted'),
(223, 'message', 'airnotifier_provider_mod_forum_posts_permitted', 'permitted'),
(224, 'message', 'airnotifier_provider_mod_lesson_graded_essay_permitted', 'permitted'),
(225, 'message', 'airnotifier_provider_mod_quiz_submission_permitted', 'permitted'),
(226, 'message', 'airnotifier_provider_mod_quiz_confirmation_permitted', 'permitted'),
(227, 'message', 'airnotifier_provider_mod_quiz_attempt_overdue_permitted', 'permitted'),
(228, 'message', 'airnotifier_provider_moodle_notices_permitted', 'permitted'),
(229, 'message', 'airnotifier_provider_moodle_errors_permitted', 'permitted'),
(230, 'message', 'airnotifier_provider_moodle_availableupdate_permitted', 'permitted'),
(231, 'message', 'airnotifier_provider_moodle_instantmessage_permitted', 'permitted'),
(232, 'message', 'airnotifier_provider_moodle_backup_permitted', 'permitted'),
(233, 'message', 'airnotifier_provider_moodle_courserequested_permitted', 'permitted'),
(234, 'message', 'airnotifier_provider_moodle_courserequestapproved_permitted', 'permitted'),
(235, 'message', 'airnotifier_provider_moodle_courserequestrejected_permitted', 'permitted'),
(236, 'message', 'airnotifier_provider_moodle_badgerecipientnotice_permitted', 'permitted'),
(237, 'message', 'airnotifier_provider_moodle_badgecreatornotice_permitted', 'permitted'),
(238, 'message_email', 'version', '2015111600'),
(240, 'message', 'email_provider_enrol_flatfile_flatfile_enrolment_permitted', 'permitted'),
(241, 'message', 'message_provider_enrol_flatfile_flatfile_enrolment_loggedin', 'email'),
(242, 'message', 'message_provider_enrol_flatfile_flatfile_enrolment_loggedoff', 'email'),
(243, 'message', 'email_provider_enrol_imsenterprise_imsenterprise_enrolment_permitted', 'permitted'),
(244, 'message', 'message_provider_enrol_imsenterprise_imsenterprise_enrolment_loggedin', 'email'),
(245, 'message', 'message_provider_enrol_imsenterprise_imsenterprise_enrolment_loggedoff', 'email'),
(246, 'message', 'email_provider_enrol_manual_expiry_notification_permitted', 'permitted'),
(247, 'message', 'message_provider_enrol_manual_expiry_notification_loggedin', 'email'),
(248, 'message', 'message_provider_enrol_manual_expiry_notification_loggedoff', 'email'),
(249, 'message', 'email_provider_enrol_paypal_paypal_enrolment_permitted', 'permitted'),
(250, 'message', 'message_provider_enrol_paypal_paypal_enrolment_loggedin', 'email'),
(251, 'message', 'message_provider_enrol_paypal_paypal_enrolment_loggedoff', 'email'),
(252, 'message', 'email_provider_enrol_self_expiry_notification_permitted', 'permitted'),
(253, 'message', 'message_provider_enrol_self_expiry_notification_loggedin', 'email'),
(254, 'message', 'message_provider_enrol_self_expiry_notification_loggedoff', 'email'),
(255, 'message', 'email_provider_mod_assign_assign_notification_permitted', 'permitted'),
(256, 'message', 'message_provider_mod_assign_assign_notification_loggedin', 'email'),
(257, 'message', 'message_provider_mod_assign_assign_notification_loggedoff', 'email'),
(258, 'message', 'email_provider_mod_assignment_assignment_updates_permitted', 'permitted'),
(259, 'message', 'message_provider_mod_assignment_assignment_updates_loggedin', 'email'),
(260, 'message', 'message_provider_mod_assignment_assignment_updates_loggedoff', 'email'),
(261, 'message', 'email_provider_mod_feedback_submission_permitted', 'permitted'),
(262, 'message', 'message_provider_mod_feedback_submission_loggedin', 'email'),
(263, 'message', 'message_provider_mod_feedback_submission_loggedoff', 'email'),
(264, 'message', 'email_provider_mod_feedback_message_permitted', 'permitted'),
(265, 'message', 'message_provider_mod_feedback_message_loggedin', 'email'),
(266, 'message', 'message_provider_mod_feedback_message_loggedoff', 'email'),
(267, 'message', 'email_provider_mod_forum_posts_permitted', 'permitted'),
(268, 'message', 'message_provider_mod_forum_posts_loggedin', 'email'),
(269, 'message', 'message_provider_mod_forum_posts_loggedoff', 'email'),
(270, 'message', 'email_provider_mod_lesson_graded_essay_permitted', 'permitted'),
(271, 'message', 'message_provider_mod_lesson_graded_essay_loggedin', 'email'),
(272, 'message', 'message_provider_mod_lesson_graded_essay_loggedoff', 'email'),
(273, 'message', 'email_provider_mod_quiz_submission_permitted', 'permitted'),
(274, 'message', 'message_provider_mod_quiz_submission_loggedin', 'email'),
(275, 'message', 'message_provider_mod_quiz_submission_loggedoff', 'email'),
(276, 'message', 'email_provider_mod_quiz_confirmation_permitted', 'permitted'),
(277, 'message', 'message_provider_mod_quiz_confirmation_loggedin', 'email'),
(278, 'message', 'message_provider_mod_quiz_confirmation_loggedoff', 'email'),
(279, 'message', 'email_provider_mod_quiz_attempt_overdue_permitted', 'permitted'),
(280, 'message', 'message_provider_mod_quiz_attempt_overdue_loggedin', 'email'),
(281, 'message', 'message_provider_mod_quiz_attempt_overdue_loggedoff', 'email'),
(282, 'message', 'email_provider_moodle_notices_permitted', 'permitted'),
(283, 'message', 'message_provider_moodle_notices_loggedin', 'email'),
(284, 'message', 'message_provider_moodle_notices_loggedoff', 'email'),
(285, 'message', 'email_provider_moodle_errors_permitted', 'permitted'),
(286, 'message', 'message_provider_moodle_errors_loggedin', 'email'),
(287, 'message', 'message_provider_moodle_errors_loggedoff', 'email'),
(288, 'message', 'email_provider_moodle_availableupdate_permitted', 'permitted'),
(289, 'message', 'message_provider_moodle_availableupdate_loggedin', 'email'),
(290, 'message', 'message_provider_moodle_availableupdate_loggedoff', 'email'),
(291, 'message', 'email_provider_moodle_instantmessage_permitted', 'permitted'),
(292, 'message', 'message_provider_moodle_instantmessage_loggedoff', 'popup,email'),
(293, 'message', 'email_provider_moodle_backup_permitted', 'permitted'),
(294, 'message', 'message_provider_moodle_backup_loggedin', 'email'),
(295, 'message', 'message_provider_moodle_backup_loggedoff', 'email'),
(296, 'message', 'email_provider_moodle_courserequested_permitted', 'permitted'),
(297, 'message', 'message_provider_moodle_courserequested_loggedin', 'email'),
(298, 'message', 'message_provider_moodle_courserequested_loggedoff', 'email'),
(299, 'message', 'email_provider_moodle_courserequestapproved_permitted', 'permitted'),
(300, 'message', 'message_provider_moodle_courserequestapproved_loggedin', 'email'),
(301, 'message', 'message_provider_moodle_courserequestapproved_loggedoff', 'email'),
(302, 'message', 'email_provider_moodle_courserequestrejected_permitted', 'permitted'),
(303, 'message', 'message_provider_moodle_courserequestrejected_loggedin', 'email'),
(304, 'message', 'message_provider_moodle_courserequestrejected_loggedoff', 'email'),
(305, 'message', 'email_provider_moodle_badgerecipientnotice_permitted', 'permitted'),
(306, 'message', 'message_provider_moodle_badgerecipientnotice_loggedoff', 'popup,email'),
(307, 'message', 'email_provider_moodle_badgecreatornotice_permitted', 'permitted'),
(308, 'message', 'message_provider_moodle_badgecreatornotice_loggedoff', 'email'),
(309, 'message_jabber', 'version', '2015111600'),
(311, 'message', 'jabber_provider_enrol_flatfile_flatfile_enrolment_permitted', 'permitted'),
(312, 'message', 'jabber_provider_enrol_imsenterprise_imsenterprise_enrolment_permitted', 'permitted'),
(313, 'message', 'jabber_provider_enrol_manual_expiry_notification_permitted', 'permitted'),
(314, 'message', 'jabber_provider_enrol_paypal_paypal_enrolment_permitted', 'permitted'),
(315, 'message', 'jabber_provider_enrol_self_expiry_notification_permitted', 'permitted'),
(316, 'message', 'jabber_provider_mod_assign_assign_notification_permitted', 'permitted'),
(317, 'message', 'jabber_provider_mod_assignment_assignment_updates_permitted', 'permitted'),
(318, 'message', 'jabber_provider_mod_feedback_submission_permitted', 'permitted'),
(319, 'message', 'jabber_provider_mod_feedback_message_permitted', 'permitted'),
(320, 'message', 'jabber_provider_mod_forum_posts_permitted', 'permitted'),
(321, 'message', 'jabber_provider_mod_lesson_graded_essay_permitted', 'permitted'),
(322, 'message', 'jabber_provider_mod_quiz_submission_permitted', 'permitted'),
(323, 'message', 'jabber_provider_mod_quiz_confirmation_permitted', 'permitted'),
(324, 'message', 'jabber_provider_mod_quiz_attempt_overdue_permitted', 'permitted'),
(325, 'message', 'jabber_provider_moodle_notices_permitted', 'permitted'),
(326, 'message', 'jabber_provider_moodle_errors_permitted', 'permitted'),
(327, 'message', 'jabber_provider_moodle_availableupdate_permitted', 'permitted'),
(328, 'message', 'jabber_provider_moodle_instantmessage_permitted', 'permitted'),
(329, 'message', 'jabber_provider_moodle_backup_permitted', 'permitted'),
(330, 'message', 'jabber_provider_moodle_courserequested_permitted', 'permitted'),
(331, 'message', 'jabber_provider_moodle_courserequestapproved_permitted', 'permitted'),
(332, 'message', 'jabber_provider_moodle_courserequestrejected_permitted', 'permitted'),
(333, 'message', 'jabber_provider_moodle_badgerecipientnotice_permitted', 'permitted'),
(334, 'message', 'jabber_provider_moodle_badgecreatornotice_permitted', 'permitted'),
(335, 'message_popup', 'version', '2015111600'),
(337, 'message', 'popup_provider_enrol_flatfile_flatfile_enrolment_permitted', 'permitted'),
(338, 'message', 'popup_provider_enrol_imsenterprise_imsenterprise_enrolment_permitted', 'permitted'),
(339, 'message', 'popup_provider_enrol_manual_expiry_notification_permitted', 'permitted'),
(340, 'message', 'popup_provider_enrol_paypal_paypal_enrolment_permitted', 'permitted'),
(341, 'message', 'popup_provider_enrol_self_expiry_notification_permitted', 'permitted'),
(342, 'message', 'popup_provider_mod_assign_assign_notification_permitted', 'permitted'),
(343, 'message', 'popup_provider_mod_assignment_assignment_updates_permitted', 'permitted'),
(344, 'message', 'popup_provider_mod_feedback_submission_permitted', 'permitted'),
(345, 'message', 'popup_provider_mod_feedback_message_permitted', 'permitted'),
(346, 'message', 'popup_provider_mod_forum_posts_permitted', 'permitted'),
(347, 'message', 'popup_provider_mod_lesson_graded_essay_permitted', 'permitted'),
(348, 'message', 'popup_provider_mod_quiz_submission_permitted', 'permitted'),
(349, 'message', 'popup_provider_mod_quiz_confirmation_permitted', 'permitted'),
(350, 'message', 'popup_provider_mod_quiz_attempt_overdue_permitted', 'permitted'),
(351, 'message', 'popup_provider_moodle_notices_permitted', 'permitted'),
(352, 'message', 'popup_provider_moodle_errors_permitted', 'permitted'),
(353, 'message', 'popup_provider_moodle_availableupdate_permitted', 'permitted'),
(354, 'message', 'popup_provider_moodle_instantmessage_permitted', 'permitted'),
(355, 'message', 'message_provider_moodle_instantmessage_loggedin', 'popup'),
(356, 'message', 'popup_provider_moodle_backup_permitted', 'permitted'),
(357, 'message', 'popup_provider_moodle_courserequested_permitted', 'permitted'),
(358, 'message', 'popup_provider_moodle_courserequestapproved_permitted', 'permitted'),
(359, 'message', 'popup_provider_moodle_courserequestrejected_permitted', 'permitted'),
(360, 'message', 'popup_provider_moodle_badgerecipientnotice_permitted', 'permitted'),
(361, 'message', 'message_provider_moodle_badgerecipientnotice_loggedin', 'popup'),
(362, 'message', 'popup_provider_moodle_badgecreatornotice_permitted', 'permitted'),
(363, 'block_activity_modules', 'version', '2015111600'),
(364, 'block_activity_results', 'version', '2015111600'),
(365, 'block_admin_bookmarks', 'version', '2015111600'),
(366, 'block_badges', 'version', '2015111600'),
(367, 'block_blog_menu', 'version', '2015111600'),
(368, 'block_blog_recent', 'version', '2015111600'),
(369, 'block_blog_tags', 'version', '2015111600'),
(370, 'block_calendar_month', 'version', '2015111600'),
(371, 'block_calendar_upcoming', 'version', '2015111600'),
(372, 'block_comments', 'version', '2015111600'),
(373, 'block_community', 'version', '2015111600'),
(374, 'block_completionstatus', 'version', '2015111600'),
(375, 'block_course_list', 'version', '2015111600'),
(376, 'block_course_overview', 'version', '2015111600'),
(377, 'block_course_summary', 'version', '2015111600'),
(378, 'block_feedback', 'version', '2015111600'),
(380, 'block_glossary_random', 'version', '2015111600'),
(381, 'block_html', 'version', '2015111600'),
(382, 'block_login', 'version', '2015111600'),
(383, 'block_mentees', 'version', '2015111600'),
(384, 'block_messages', 'version', '2015111600'),
(385, 'block_mnet_hosts', 'version', '2015111600'),
(386, 'block_myprofile', 'version', '2015111600'),
(387, 'block_navigation', 'version', '2015111600'),
(388, 'block_news_items', 'version', '2015111600'),
(389, 'block_online_users', 'version', '2015111600'),
(390, 'block_participants', 'version', '2015111600'),
(391, 'block_private_files', 'version', '2015111600'),
(392, 'block_quiz_results', 'version', '2015111600'),
(394, 'block_recent_activity', 'version', '2015111600'),
(395, 'block_rss_client', 'version', '2015111600'),
(396, 'block_search_forums', 'version', '2015111600'),
(397, 'block_section_links', 'version', '2015111600'),
(398, 'block_selfcompletion', 'version', '2015111600'),
(399, 'block_settings', 'version', '2015111600'),
(400, 'block_site_main_menu', 'version', '2015111600'),
(401, 'block_social_activities', 'version', '2015111600'),
(402, 'block_tag_flickr', 'version', '2015111600'),
(403, 'block_tag_youtube', 'version', '2015111600'),
(405, 'block_tags', 'version', '2015111600'),
(406, 'filter_activitynames', 'version', '2015111600'),
(408, 'filter_algebra', 'version', '2015111600'),
(409, 'filter_censor', 'version', '2015111600'),
(410, 'filter_data', 'version', '2015111600'),
(412, 'filter_emailprotect', 'version', '2015111600'),
(413, 'filter_emoticon', 'version', '2015111600'),
(414, 'filter_glossary', 'version', '2015111600'),
(416, 'filter_mathjaxloader', 'version', '2015111600'),
(418, 'filter_mediaplugin', 'version', '2015111600'),
(420, 'filter_multilang', 'version', '2015111600'),
(421, 'filter_tex', 'version', '2015111600'),
(423, 'filter_tidy', 'version', '2015111600'),
(424, 'filter_urltolink', 'version', '2015111600'),
(425, 'editor_atto', 'version', '2015111600'),
(427, 'editor_textarea', 'version', '2015111600'),
(428, 'editor_tinymce', 'version', '2015111600'),
(429, 'format_singleactivity', 'version', '2015111600'),
(430, 'format_social', 'version', '2015111600'),
(431, 'format_topics', 'version', '2015111600'),
(432, 'format_weeks', 'version', '2015111600'),
(433, 'profilefield_checkbox', 'version', '2015111600'),
(434, 'profilefield_datetime', 'version', '2015111600'),
(435, 'profilefield_menu', 'version', '2015111600'),
(436, 'profilefield_text', 'version', '2015111600'),
(437, 'profilefield_textarea', 'version', '2015111600'),
(438, 'report_backups', 'version', '2015111600'),
(439, 'report_completion', 'version', '2015111600'),
(441, 'report_configlog', 'version', '2015111600'),
(442, 'report_courseoverview', 'version', '2015111600'),
(443, 'report_eventlist', 'version', '2015111600'),
(444, 'report_log', 'version', '2015111600'),
(446, 'report_loglive', 'version', '2015111600'),
(447, 'report_outline', 'version', '2015111600'),
(449, 'report_participation', 'version', '2015111600'),
(451, 'report_performance', 'version', '2015111600'),
(452, 'report_progress', 'version', '2015111600'),
(454, 'report_questioninstances', 'version', '2015111600'),
(455, 'report_security', 'version', '2015111600'),
(456, 'report_stats', 'version', '2015111600'),
(458, 'report_usersessions', 'version', '2015111600'),
(459, 'gradeexport_ods', 'version', '2015111600'),
(460, 'gradeexport_txt', 'version', '2015111600'),
(461, 'gradeexport_xls', 'version', '2015111600'),
(462, 'gradeexport_xml', 'version', '2015111600'),
(463, 'gradeimport_csv', 'version', '2015111600'),
(464, 'gradeimport_direct', 'version', '2015111600'),
(465, 'gradeimport_xml', 'version', '2015111600'),
(466, 'gradereport_grader', 'version', '2015111600'),
(467, 'gradereport_history', 'version', '2015111600'),
(468, 'gradereport_outcomes', 'version', '2015111600'),
(469, 'gradereport_overview', 'version', '2015111600'),
(470, 'gradereport_singleview', 'version', '2015111600'),
(471, 'gradereport_user', 'version', '2015111600'),
(472, 'gradingform_guide', 'version', '2015111600'),
(473, 'gradingform_rubric', 'version', '2015111600'),
(474, 'mnetservice_enrol', 'version', '2015111600'),
(475, 'webservice_amf', 'version', '2015111600'),
(476, 'webservice_rest', 'version', '2015111600'),
(477, 'webservice_soap', 'version', '2015111600'),
(478, 'webservice_xmlrpc', 'version', '2015111600'),
(479, 'repository_alfresco', 'version', '2015111600'),
(480, 'repository_areafiles', 'version', '2015111600'),
(482, 'areafiles', 'enablecourseinstances', '0'),
(483, 'areafiles', 'enableuserinstances', '0'),
(484, 'repository_boxnet', 'version', '2015111600'),
(485, 'repository_coursefiles', 'version', '2015111600'),
(486, 'repository_dropbox', 'version', '2015111600'),
(487, 'repository_equella', 'version', '2015111600'),
(488, 'repository_filesystem', 'version', '2015111600'),
(489, 'repository_flickr', 'version', '2015111600'),
(490, 'repository_flickr_public', 'version', '2015111600'),
(491, 'repository_googledocs', 'version', '2015111600'),
(492, 'repository_local', 'version', '2015111600'),
(494, 'local', 'enablecourseinstances', '0'),
(495, 'local', 'enableuserinstances', '0'),
(496, 'repository_merlot', 'version', '2015111600'),
(497, 'repository_picasa', 'version', '2015111600'),
(498, 'repository_recent', 'version', '2015111600'),
(500, 'recent', 'enablecourseinstances', '0'),
(501, 'recent', 'enableuserinstances', '0'),
(502, 'repository_s3', 'version', '2015111600'),
(503, 'repository_skydrive', 'version', '2015111600'),
(504, 'repository_upload', 'version', '2015111600'),
(506, 'upload', 'enablecourseinstances', '0'),
(507, 'upload', 'enableuserinstances', '0'),
(508, 'repository_url', 'version', '2015111600'),
(510, 'url', 'enablecourseinstances', '0'),
(511, 'url', 'enableuserinstances', '0'),
(512, 'repository_user', 'version', '2015111600'),
(514, 'user', 'enablecourseinstances', '0'),
(515, 'user', 'enableuserinstances', '0'),
(516, 'repository_webdav', 'version', '2015111600'),
(517, 'repository_wikimedia', 'version', '2015111600'),
(519, 'wikimedia', 'enablecourseinstances', '0'),
(520, 'wikimedia', 'enableuserinstances', '0'),
(521, 'repository_youtube', 'version', '2015111600'),
(523, 'portfolio_boxnet', 'version', '2015111600'),
(524, 'portfolio_download', 'version', '2015111600'),
(525, 'portfolio_flickr', 'version', '2015111600'),
(526, 'portfolio_googledocs', 'version', '2015111600'),
(527, 'portfolio_mahara', 'version', '2015111600'),
(528, 'portfolio_picasa', 'version', '2015111600'),
(529, 'qbehaviour_adaptive', 'version', '2015111600'),
(530, 'qbehaviour_adaptivenopenalty', 'version', '2015111600'),
(531, 'qbehaviour_deferredcbm', 'version', '2015111600'),
(532, 'qbehaviour_deferredfeedback', 'version', '2015111600'),
(533, 'qbehaviour_immediatecbm', 'version', '2015111600'),
(534, 'qbehaviour_immediatefeedback', 'version', '2015111600'),
(535, 'qbehaviour_informationitem', 'version', '2015111600'),
(536, 'qbehaviour_interactive', 'version', '2015111600'),
(537, 'qbehaviour_interactivecountback', 'version', '2015111600'),
(538, 'qbehaviour_manualgraded', 'version', '2015111600'),
(540, 'question', 'disabledbehaviours', 'manualgraded'),
(541, 'qbehaviour_missing', 'version', '2015111600'),
(542, 'qformat_aiken', 'version', '2015111600'),
(543, 'qformat_blackboard_six', 'version', '2015111600'),
(544, 'qformat_examview', 'version', '2015111600'),
(545, 'qformat_gift', 'version', '2015111600'),
(546, 'qformat_missingword', 'version', '2015111600'),
(547, 'qformat_multianswer', 'version', '2015111600'),
(548, 'qformat_webct', 'version', '2015111600'),
(549, 'qformat_xhtml', 'version', '2015111600'),
(550, 'qformat_xml', 'version', '2015111600'),
(551, 'tool_assignmentupgrade', 'version', '2015111600'),
(552, 'tool_availabilityconditions', 'version', '2015111600'),
(553, 'tool_behat', 'version', '2015111600'),
(554, 'tool_capability', 'version', '2015111600'),
(555, 'tool_customlang', 'version', '2015111600'),
(557, 'tool_dbtransfer', 'version', '2015111600'),
(558, 'tool_filetypes', 'version', '2015111600'),
(559, 'tool_generator', 'version', '2015111600'),
(560, 'tool_health', 'version', '2015111600'),
(561, 'tool_innodb', 'version', '2015111600'),
(562, 'tool_installaddon', 'version', '2015111600'),
(563, 'tool_langimport', 'version', '2015111600'),
(564, 'tool_log', 'version', '2015111600'),
(566, 'tool_log', 'enabled_stores', 'logstore_standard'),
(567, 'tool_messageinbound', 'version', '2015111600'),
(568, 'message', 'airnotifier_provider_tool_messageinbound_invalidrecipienthandler_permitted', 'permitted'),
(569, 'message', 'email_provider_tool_messageinbound_invalidrecipienthandler_permitted', 'permitted'),
(570, 'message', 'jabber_provider_tool_messageinbound_invalidrecipienthandler_permitted', 'permitted'),
(571, 'message', 'popup_provider_tool_messageinbound_invalidrecipienthandler_permitted', 'permitted'),
(572, 'message', 'message_provider_tool_messageinbound_invalidrecipienthandler_loggedin', 'email'),
(573, 'message', 'message_provider_tool_messageinbound_invalidrecipienthandler_loggedoff', 'email'),
(574, 'message', 'airnotifier_provider_tool_messageinbound_messageprocessingerror_permitted', 'permitted'),
(575, 'message', 'email_provider_tool_messageinbound_messageprocessingerror_permitted', 'permitted'),
(576, 'message', 'jabber_provider_tool_messageinbound_messageprocessingerror_permitted', 'permitted'),
(577, 'message', 'popup_provider_tool_messageinbound_messageprocessingerror_permitted', 'permitted'),
(578, 'message', 'message_provider_tool_messageinbound_messageprocessingerror_loggedin', 'email'),
(579, 'message', 'message_provider_tool_messageinbound_messageprocessingerror_loggedoff', 'email'),
(580, 'message', 'airnotifier_provider_tool_messageinbound_messageprocessingsuccess_permitted', 'permitted'),
(581, 'message', 'email_provider_tool_messageinbound_messageprocessingsuccess_permitted', 'permitted'),
(582, 'message', 'jabber_provider_tool_messageinbound_messageprocessingsuccess_permitted', 'permitted'),
(583, 'message', 'popup_provider_tool_messageinbound_messageprocessingsuccess_permitted', 'permitted'),
(584, 'message', 'message_provider_tool_messageinbound_messageprocessingsuccess_loggedin', 'email'),
(585, 'message', 'message_provider_tool_messageinbound_messageprocessingsuccess_loggedoff', 'email'),
(586, 'tool_monitor', 'version', '2015111600'),
(587, 'message', 'airnotifier_provider_tool_monitor_notification_permitted', 'permitted'),
(588, 'message', 'email_provider_tool_monitor_notification_permitted', 'permitted'),
(589, 'message', 'jabber_provider_tool_monitor_notification_permitted', 'permitted'),
(590, 'message', 'popup_provider_tool_monitor_notification_permitted', 'permitted'),
(591, 'message', 'message_provider_tool_monitor_notification_loggedin', 'email'),
(592, 'message', 'message_provider_tool_monitor_notification_loggedoff', 'email'),
(593, 'tool_multilangupgrade', 'version', '2015111600'),
(594, 'tool_phpunit', 'version', '2015111600'),
(595, 'tool_profiling', 'version', '2015111600'),
(596, 'tool_replace', 'version', '2015111600'),
(597, 'tool_spamcleaner', 'version', '2015111600'),
(598, 'tool_task', 'version', '2015111600'),
(599, 'tool_templatelibrary', 'version', '2015111600'),
(600, 'tool_unsuproles', 'version', '2015111600'),
(602, 'tool_uploadcourse', 'version', '2015111600'),
(603, 'tool_uploaduser', 'version', '2015111600'),
(604, 'tool_xmldb', 'version', '2015111600'),
(605, 'cachestore_file', 'version', '2015111600'),
(606, 'cachestore_memcache', 'version', '2015111600'),
(607, 'cachestore_memcached', 'version', '2015111600'),
(608, 'cachestore_mongodb', 'version', '2015111600'),
(609, 'cachestore_session', 'version', '2015111600'),
(610, 'cachestore_static', 'version', '2015111600'),
(611, 'cachelock_file', 'version', '2015111600'),
(612, 'theme_base', 'version', '2015111600'),
(613, 'theme_bootstrapbase', 'version', '2015111600'),
(614, 'theme_canvas', 'version', '2015111600'),
(615, 'theme_clean', 'version', '2015111600'),
(616, 'theme_more', 'version', '2015111600'),
(618, 'assignsubmission_comments', 'version', '2015111600'),
(620, 'assignsubmission_file', 'sortorder', '1'),
(621, 'assignsubmission_comments', 'sortorder', '2'),
(622, 'assignsubmission_onlinetext', 'sortorder', '0'),
(623, 'assignsubmission_file', 'version', '2015111600'),
(624, 'assignsubmission_onlinetext', 'version', '2015111600'),
(626, 'assignfeedback_comments', 'version', '2015111600'),
(628, 'assignfeedback_comments', 'sortorder', '0'),
(629, 'assignfeedback_editpdf', 'sortorder', '1'),
(630, 'assignfeedback_file', 'sortorder', '3'),
(631, 'assignfeedback_offline', 'sortorder', '2'),
(632, 'assignfeedback_editpdf', 'version', '2015111600'),
(634, 'assignfeedback_file', 'version', '2015111600'),
(636, 'assignfeedback_offline', 'version', '2015111600'),
(637, 'assignment_offline', 'version', '2015111600'),
(638, 'assignment_online', 'version', '2015111600'),
(639, 'assignment_upload', 'version', '2015111600'),
(640, 'assignment_uploadsingle', 'version', '2015111600'),
(641, 'booktool_exportimscp', 'version', '2015111600'),
(642, 'booktool_importhtml', 'version', '2015111600'),
(643, 'booktool_print', 'version', '2015111600'),
(644, 'datafield_checkbox', 'version', '2015111600'),
(645, 'datafield_date', 'version', '2015111600'),
(646, 'datafield_file', 'version', '2015111600'),
(647, 'datafield_latlong', 'version', '2015111600'),
(648, 'datafield_menu', 'version', '2015111600'),
(649, 'datafield_multimenu', 'version', '2015111600'),
(650, 'datafield_number', 'version', '2015111600'),
(651, 'datafield_picture', 'version', '2015111600'),
(652, 'datafield_radiobutton', 'version', '2015111600'),
(653, 'datafield_text', 'version', '2015111600'),
(654, 'datafield_textarea', 'version', '2015111600'),
(655, 'datafield_url', 'version', '2015111600'),
(656, 'datapreset_imagegallery', 'version', '2015111600'),
(657, 'ltiservice_memberships', 'version', '2015111600'),
(658, 'ltiservice_profile', 'version', '2015111600'),
(659, 'ltiservice_toolproxy', 'version', '2015111600'),
(660, 'ltiservice_toolsettings', 'version', '2015111600'),
(661, 'quiz_grading', 'version', '2015111600'),
(663, 'quiz_overview', 'version', '2015111600'),
(665, 'quiz_responses', 'version', '2015111600'),
(667, 'quiz_statistics', 'version', '2015111600'),
(669, 'quizaccess_delaybetweenattempts', 'version', '2015111600'),
(670, 'quizaccess_ipaddress', 'version', '2015111600'),
(671, 'quizaccess_numattempts', 'version', '2015111600'),
(672, 'quizaccess_openclosedate', 'version', '2015111600'),
(673, 'quizaccess_password', 'version', '2015111600'),
(674, 'quizaccess_safebrowser', 'version', '2015111600'),
(675, 'quizaccess_securewindow', 'version', '2015111600'),
(676, 'quizaccess_timelimit', 'version', '2015111600'),
(677, 'scormreport_basic', 'version', '2015111600'),
(678, 'scormreport_graphs', 'version', '2015111600'),
(679, 'scormreport_interactions', 'version', '2015111600'),
(680, 'scormreport_objectives', 'version', '2015111600'),
(681, 'workshopform_accumulative', 'version', '2015111600'),
(683, 'workshopform_comments', 'version', '2015111600'),
(685, 'workshopform_numerrors', 'version', '2015111600'),
(687, 'workshopform_rubric', 'version', '2015111600'),
(689, 'workshopallocation_manual', 'version', '2015111600'),
(690, 'workshopallocation_random', 'version', '2015111600'),
(691, 'workshopallocation_scheduled', 'version', '2015111600'),
(692, 'workshopeval_best', 'version', '2015111600'),
(693, 'atto_accessibilitychecker', 'version', '2015111600'),
(694, 'atto_accessibilityhelper', 'version', '2015111600'),
(695, 'atto_align', 'version', '2015111600'),
(696, 'atto_backcolor', 'version', '2015111600'),
(697, 'atto_bold', 'version', '2015111600'),
(698, 'atto_charmap', 'version', '2015111600'),
(699, 'atto_clear', 'version', '2015111600'),
(700, 'atto_collapse', 'version', '2015111600'),
(701, 'atto_emoticon', 'version', '2015111600'),
(702, 'atto_equation', 'version', '2015111600'),
(703, 'atto_fontcolor', 'version', '2015111600'),
(704, 'atto_html', 'version', '2015111600'),
(705, 'atto_image', 'version', '2015111600'),
(706, 'atto_indent', 'version', '2015111600'),
(707, 'atto_italic', 'version', '2015111600'),
(708, 'atto_link', 'version', '2015111600'),
(709, 'atto_managefiles', 'version', '2015111600'),
(710, 'atto_media', 'version', '2015111600'),
(711, 'atto_noautolink', 'version', '2015111600'),
(712, 'atto_orderedlist', 'version', '2015111600'),
(713, 'atto_rtl', 'version', '2015111600'),
(714, 'atto_strike', 'version', '2015111600'),
(715, 'atto_subscript', 'version', '2015111600'),
(716, 'atto_superscript', 'version', '2015111600'),
(717, 'atto_table', 'version', '2015111600'),
(718, 'atto_title', 'version', '2015111600'),
(719, 'atto_underline', 'version', '2015111600'),
(720, 'atto_undo', 'version', '2015111600'),
(721, 'atto_unorderedlist', 'version', '2015111600'),
(722, 'tinymce_ctrlhelp', 'version', '2015111600'),
(723, 'tinymce_managefiles', 'version', '2015111600'),
(724, 'tinymce_moodleemoticon', 'version', '2015111600'),
(725, 'tinymce_moodleimage', 'version', '2015111600'),
(726, 'tinymce_moodlemedia', 'version', '2015111600'),
(727, 'tinymce_moodlenolink', 'version', '2015111600'),
(728, 'tinymce_pdw', 'version', '2015111600'),
(729, 'tinymce_spellchecker', 'version', '2015111600'),
(731, 'tinymce_wrap', 'version', '2015111600'),
(732, 'logstore_database', 'version', '2015111600'),
(733, 'logstore_legacy', 'version', '2015111600'),
(734, 'logstore_standard', 'version', '2015111600'),
(735, 'assign', 'feedback_plugin_for_gradebook', 'assignfeedback_comments'),
(736, 'assign', 'showrecentsubmissions', '0'),
(737, 'assign', 'submissionreceipts', '1'),
(738, 'assign', 'submissionstatement', 'This assignment is my own work, except where I have acknowledged the use of the works of other people.'),
(739, 'assign', 'alwaysshowdescription', '1'),
(740, 'assign', 'alwaysshowdescription_adv', ''),
(741, 'assign', 'alwaysshowdescription_locked', ''),
(742, 'assign', 'allowsubmissionsfromdate', '0'),
(743, 'assign', 'allowsubmissionsfromdate_enabled', '1'),
(744, 'assign', 'allowsubmissionsfromdate_adv', ''),
(745, 'assign', 'duedate', '604800'),
(746, 'assign', 'duedate_enabled', '1'),
(747, 'assign', 'duedate_adv', ''),
(748, 'assign', 'cutoffdate', '1209600'),
(749, 'assign', 'cutoffdate_enabled', ''),
(750, 'assign', 'cutoffdate_adv', ''),
(751, 'assign', 'submissiondrafts', '0'),
(752, 'assign', 'submissiondrafts_adv', ''),
(753, 'assign', 'submissiondrafts_locked', ''),
(754, 'assign', 'requiresubmissionstatement', '0'),
(755, 'assign', 'requiresubmissionstatement_adv', ''),
(756, 'assign', 'requiresubmissionstatement_locked', ''),
(757, 'assign', 'attemptreopenmethod', 'none'),
(758, 'assign', 'attemptreopenmethod_adv', ''),
(759, 'assign', 'attemptreopenmethod_locked', ''),
(760, 'assign', 'maxattempts', '-1'),
(761, 'assign', 'maxattempts_adv', ''),
(762, 'assign', 'maxattempts_locked', ''),
(763, 'assign', 'teamsubmission', '0'),
(764, 'assign', 'teamsubmission_adv', ''),
(765, 'assign', 'teamsubmission_locked', ''),
(766, 'assign', 'preventsubmissionnotingroup', '0'),
(767, 'assign', 'preventsubmissionnotingroup_adv', ''),
(768, 'assign', 'preventsubmissionnotingroup_locked', ''),
(769, 'assign', 'requireallteammemberssubmit', '0'),
(770, 'assign', 'requireallteammemberssubmit_adv', ''),
(771, 'assign', 'requireallteammemberssubmit_locked', ''),
(772, 'assign', 'teamsubmissiongroupingid', ''),
(773, 'assign', 'teamsubmissiongroupingid_adv', ''),
(774, 'assign', 'sendnotifications', '0'),
(775, 'assign', 'sendnotifications_adv', ''),
(776, 'assign', 'sendnotifications_locked', ''),
(777, 'assign', 'sendlatenotifications', '0'),
(778, 'assign', 'sendlatenotifications_adv', ''),
(779, 'assign', 'sendlatenotifications_locked', ''),
(780, 'assign', 'sendstudentnotifications', '1'),
(781, 'assign', 'sendstudentnotifications_adv', ''),
(782, 'assign', 'sendstudentnotifications_locked', ''),
(783, 'assign', 'blindmarking', '0'),
(784, 'assign', 'blindmarking_adv', ''),
(785, 'assign', 'blindmarking_locked', ''),
(786, 'assign', 'markingworkflow', '0'),
(787, 'assign', 'markingworkflow_adv', ''),
(788, 'assign', 'markingworkflow_locked', ''),
(789, 'assign', 'markingallocation', '0'),
(790, 'assign', 'markingallocation_adv', ''),
(791, 'assign', 'markingallocation_locked', ''),
(792, 'assignsubmission_file', 'default', '1'),
(793, 'assignsubmission_file', 'maxfiles', '20'),
(794, 'assignsubmission_file', 'maxbytes', '1048576'),
(795, 'assignsubmission_onlinetext', 'default', '0'),
(796, 'assignfeedback_comments', 'default', '1'),
(797, 'assignfeedback_comments', 'inline', '0'),
(798, 'assignfeedback_comments', 'inline_adv', ''),
(799, 'assignfeedback_comments', 'inline_locked', ''),
(800, 'assignfeedback_editpdf', 'stamps', ''),
(801, 'assignfeedback_file', 'default', '0'),
(802, 'assignfeedback_offline', 'default', '0'),
(803, 'book', 'numberingoptions', '0,1,2,3'),
(804, 'book', 'navoptions', '0,1,2'),
(805, 'book', 'numbering', '1'),
(806, 'book', 'navstyle', '1'),
(807, 'folder', 'showexpanded', '1'),
(808, 'imscp', 'keepold', '1'),
(809, 'imscp', 'keepold_adv', ''),
(810, 'label', 'dndmedia', '1'),
(811, 'label', 'dndresizewidth', '400'),
(812, 'label', 'dndresizeheight', '400'),
(813, 'page', 'displayoptions', '5'),
(814, 'page', 'printheading', '1'),
(815, 'page', 'printintro', '0'),
(816, 'page', 'display', '5'),
(817, 'page', 'popupwidth', '620'),
(818, 'page', 'popupheight', '450'),
(819, 'quiz', 'timelimit', '0'),
(820, 'quiz', 'timelimit_adv', ''),
(821, 'quiz', 'overduehandling', 'autosubmit'),
(822, 'quiz', 'overduehandling_adv', ''),
(823, 'quiz', 'graceperiod', '86400'),
(824, 'quiz', 'graceperiod_adv', ''),
(825, 'quiz', 'graceperiodmin', '60'),
(826, 'quiz', 'attempts', '0'),
(827, 'quiz', 'attempts_adv', ''),
(828, 'quiz', 'grademethod', '1'),
(829, 'quiz', 'grademethod_adv', ''),
(830, 'quiz', 'maximumgrade', '10'),
(831, 'quiz', 'questionsperpage', '1'),
(832, 'quiz', 'questionsperpage_adv', ''),
(833, 'quiz', 'navmethod', 'free'),
(834, 'quiz', 'navmethod_adv', '1'),
(835, 'quiz', 'shuffleanswers', '1'),
(836, 'quiz', 'shuffleanswers_adv', ''),
(837, 'quiz', 'preferredbehaviour', 'deferredfeedback'),
(838, 'quiz', 'canredoquestions', '0'),
(839, 'quiz', 'canredoquestions_adv', '1'),
(840, 'quiz', 'attemptonlast', '0'),
(841, 'quiz', 'attemptonlast_adv', '1'),
(842, 'quiz', 'reviewattempt', '69904'),
(843, 'quiz', 'reviewcorrectness', '69904'),
(844, 'quiz', 'reviewmarks', '69904'),
(845, 'quiz', 'reviewspecificfeedback', '69904'),
(846, 'quiz', 'reviewgeneralfeedback', '69904'),
(847, 'quiz', 'reviewrightanswer', '69904'),
(848, 'quiz', 'reviewoverallfeedback', '4368'),
(849, 'quiz', 'showuserpicture', '0'),
(850, 'quiz', 'showuserpicture_adv', ''),
(851, 'quiz', 'decimalpoints', '2'),
(852, 'quiz', 'decimalpoints_adv', ''),
(853, 'quiz', 'questiondecimalpoints', '-1'),
(854, 'quiz', 'questiondecimalpoints_adv', '1'),
(855, 'quiz', 'showblocks', '0'),
(856, 'quiz', 'showblocks_adv', '1'),
(857, 'quiz', 'password', ''),
(858, 'quiz', 'password_adv', ''),
(859, 'quiz', 'subnet', ''),
(860, 'quiz', 'subnet_adv', '1'),
(861, 'quiz', 'delay1', '0'),
(862, 'quiz', 'delay1_adv', '1'),
(863, 'quiz', 'delay2', '0'),
(864, 'quiz', 'delay2_adv', '1'),
(865, 'quiz', 'browsersecurity', '-'),
(866, 'quiz', 'browsersecurity_adv', '1'),
(867, 'quiz', 'initialnumfeedbacks', '2'),
(868, 'quiz', 'autosaveperiod', '60'),
(869, 'resource', 'framesize', '130'),
(870, 'resource', 'displayoptions', '0,1,4,5,6'),
(871, 'resource', 'printintro', '1'),
(872, 'resource', 'display', '0'),
(873, 'resource', 'showsize', '0'),
(874, 'resource', 'showtype', '0'),
(875, 'resource', 'showdate', '0'),
(876, 'resource', 'popupwidth', '620'),
(877, 'resource', 'popupheight', '450'),
(878, 'resource', 'filterfiles', '0'),
(879, 'scorm', 'displaycoursestructure', '0'),
(880, 'scorm', 'displaycoursestructure_adv', ''),
(881, 'scorm', 'popup', '0'),
(882, 'scorm', 'popup_adv', ''),
(883, 'scorm', 'displayactivityname', '1'),
(884, 'scorm', 'framewidth', '100'),
(885, 'scorm', 'framewidth_adv', '1'),
(886, 'scorm', 'frameheight', '500'),
(887, 'scorm', 'frameheight_adv', '1'),
(888, 'scorm', 'winoptgrp_adv', '1'),
(889, 'scorm', 'scrollbars', '0'),
(890, 'scorm', 'directories', '0'),
(891, 'scorm', 'location', '0'),
(892, 'scorm', 'menubar', '0'),
(893, 'scorm', 'toolbar', '0'),
(894, 'scorm', 'status', '0'),
(895, 'scorm', 'skipview', '0'),
(896, 'scorm', 'skipview_adv', '1'),
(897, 'scorm', 'hidebrowse', '0'),
(898, 'scorm', 'hidebrowse_adv', '1'),
(899, 'scorm', 'hidetoc', '0'),
(900, 'scorm', 'hidetoc_adv', '1'),
(901, 'scorm', 'nav', '1'),
(902, 'scorm', 'nav_adv', '1'),
(903, 'scorm', 'navpositionleft', '-100'),
(904, 'scorm', 'navpositionleft_adv', '1'),
(905, 'scorm', 'navpositiontop', '-100'),
(906, 'scorm', 'navpositiontop_adv', '1'),
(907, 'scorm', 'collapsetocwinsize', '767'),
(908, 'scorm', 'collapsetocwinsize_adv', '1'),
(909, 'scorm', 'displayattemptstatus', '1'),
(910, 'scorm', 'displayattemptstatus_adv', ''),
(911, 'scorm', 'grademethod', '1'),
(912, 'scorm', 'maxgrade', '100'),
(913, 'scorm', 'maxattempt', '0'),
(914, 'scorm', 'whatgrade', '0'),
(915, 'scorm', 'forcecompleted', '0'),
(916, 'scorm', 'forcenewattempt', '0'),
(917, 'scorm', 'autocommit', '0'),
(918, 'scorm', 'lastattemptlock', '0'),
(919, 'scorm', 'auto', '0'),
(920, 'scorm', 'updatefreq', '0'),
(921, 'scorm', 'scorm12standard', '1'),
(922, 'scorm', 'allowtypeexternal', '0'),
(923, 'scorm', 'allowtypelocalsync', '0'),
(924, 'scorm', 'allowtypeexternalaicc', '0'),
(925, 'scorm', 'allowaicchacp', '0'),
(926, 'scorm', 'aicchacptimeout', '30'),
(927, 'scorm', 'aicchacpkeepsessiondata', '1'),
(928, 'scorm', 'aiccuserid', '1'),
(929, 'scorm', 'forcejavascript', '1'),
(930, 'scorm', 'allowapidebug', '0'),
(931, 'scorm', 'apidebugmask', '.*'),
(932, 'scorm', 'protectpackagedownloads', '0'),
(933, 'url', 'framesize', '130'),
(934, 'url', 'secretphrase', ''),
(935, 'url', 'rolesinparams', '0'),
(936, 'url', 'displayoptions', '0,1,5,6'),
(937, 'url', 'printintro', '1'),
(938, 'url', 'display', '0'),
(939, 'url', 'popupwidth', '620'),
(940, 'url', 'popupheight', '450'),
(941, 'workshop', 'grade', '80'),
(942, 'workshop', 'gradinggrade', '20'),
(943, 'workshop', 'gradedecimals', '0'),
(944, 'workshop', 'maxbytes', '0'),
(945, 'workshop', 'strategy', 'accumulative'),
(946, 'workshop', 'examplesmode', '0'),
(947, 'workshopallocation_random', 'numofreviews', '5'),
(948, 'workshopform_numerrors', 'grade0', 'No'),
(949, 'workshopform_numerrors', 'grade1', 'Yes'),
(950, 'workshopeval_best', 'comparison', '5'),
(951, 'block_course_overview', 'defaultmaxcourses', '10'),
(952, 'block_course_overview', 'forcedefaultmaxcourses', '0'),
(953, 'block_course_overview', 'showchildren', '0'),
(954, 'block_course_overview', 'showwelcomearea', '0'),
(955, 'block_course_overview', 'showcategories', '0'),
(956, 'block_section_links', 'numsections1', '22'),
(957, 'block_section_links', 'incby1', '2'),
(958, 'block_section_links', 'numsections2', '40'),
(959, 'block_section_links', 'incby2', '5'),
(960, 'block_tag_youtube', 'apikey', ''),
(961, 'format_singleactivity', 'activitytype', 'forum'),
(962, 'enrol_cohort', 'roleid', '5'),
(963, 'enrol_cohort', 'unenrolaction', '0'),
(964, 'enrol_database', 'dbtype', ''),
(965, 'enrol_database', 'dbhost', 'localhost'),
(966, 'enrol_database', 'dbuser', ''),
(967, 'enrol_database', 'dbpass', ''),
(968, 'enrol_database', 'dbname', ''),
(969, 'enrol_database', 'dbencoding', 'utf-8'),
(970, 'enrol_database', 'dbsetupsql', ''),
(971, 'enrol_database', 'dbsybasequoting', '0'),
(972, 'enrol_database', 'debugdb', '0'),
(973, 'enrol_database', 'localcoursefield', 'idnumber'),
(974, 'enrol_database', 'localuserfield', 'idnumber'),
(975, 'enrol_database', 'localrolefield', 'shortname'),
(976, 'enrol_database', 'localcategoryfield', 'id'),
(977, 'enrol_database', 'remoteenroltable', ''),
(978, 'enrol_database', 'remotecoursefield', ''),
(979, 'enrol_database', 'remoteuserfield', ''),
(980, 'enrol_database', 'remoterolefield', ''),
(981, 'enrol_database', 'remoteotheruserfield', ''),
(982, 'enrol_database', 'defaultrole', '5'),
(983, 'enrol_database', 'ignorehiddencourses', '0'),
(984, 'enrol_database', 'unenrolaction', '0'),
(985, 'enrol_database', 'newcoursetable', ''),
(986, 'enrol_database', 'newcoursefullname', 'fullname'),
(987, 'enrol_database', 'newcourseshortname', 'shortname'),
(988, 'enrol_database', 'newcourseidnumber', 'idnumber'),
(989, 'enrol_database', 'newcoursecategory', ''),
(990, 'enrol_database', 'defaultcategory', '1'),
(991, 'enrol_database', 'templatecourse', ''),
(992, 'enrol_flatfile', 'location', ''),
(993, 'enrol_flatfile', 'encoding', 'UTF-8'),
(994, 'enrol_flatfile', 'mailstudents', '0'),
(995, 'enrol_flatfile', 'mailteachers', '0'),
(996, 'enrol_flatfile', 'mailadmins', '0'),
(997, 'enrol_flatfile', 'unenrolaction', '3'),
(998, 'enrol_flatfile', 'expiredaction', '3'),
(999, 'enrol_guest', 'requirepassword', '0'),
(1000, 'enrol_guest', 'usepasswordpolicy', '0'),
(1001, 'enrol_guest', 'showhint', '0'),
(1002, 'enrol_guest', 'defaultenrol', '1'),
(1003, 'enrol_guest', 'status', '1'),
(1004, 'enrol_guest', 'status_adv', ''),
(1005, 'enrol_imsenterprise', 'imsfilelocation', ''),
(1006, 'enrol_imsenterprise', 'logtolocation', ''),
(1007, 'enrol_imsenterprise', 'mailadmins', '0'),
(1008, 'enrol_imsenterprise', 'createnewusers', '0'),
(1009, 'enrol_imsenterprise', 'imsdeleteusers', '0'),
(1010, 'enrol_imsenterprise', 'fixcaseusernames', '0'),
(1011, 'enrol_imsenterprise', 'fixcasepersonalnames', '0'),
(1012, 'enrol_imsenterprise', 'imssourcedidfallback', '0'),
(1013, 'enrol_imsenterprise', 'imsrolemap01', '5'),
(1014, 'enrol_imsenterprise', 'imsrolemap02', '3'),
(1015, 'enrol_imsenterprise', 'imsrolemap03', '3');
INSERT INTO `mdl_config_plugins` (`id`, `plugin`, `name`, `value`) VALUES
(1016, 'enrol_imsenterprise', 'imsrolemap04', '5'),
(1017, 'enrol_imsenterprise', 'imsrolemap05', '0'),
(1018, 'enrol_imsenterprise', 'imsrolemap06', '4'),
(1019, 'enrol_imsenterprise', 'imsrolemap07', '0'),
(1020, 'enrol_imsenterprise', 'imsrolemap08', '4'),
(1021, 'enrol_imsenterprise', 'truncatecoursecodes', '0'),
(1022, 'enrol_imsenterprise', 'createnewcourses', '0'),
(1023, 'enrol_imsenterprise', 'createnewcategories', '0'),
(1024, 'enrol_imsenterprise', 'imsunenrol', '0'),
(1025, 'enrol_imsenterprise', 'imscoursemapshortname', 'coursecode'),
(1026, 'enrol_imsenterprise', 'imscoursemapfullname', 'short'),
(1027, 'enrol_imsenterprise', 'imscoursemapsummary', 'ignore'),
(1028, 'enrol_imsenterprise', 'imsrestricttarget', ''),
(1029, 'enrol_imsenterprise', 'imscapitafix', '0'),
(1030, 'enrol_manual', 'expiredaction', '1'),
(1031, 'enrol_manual', 'expirynotifyhour', '6'),
(1032, 'enrol_manual', 'defaultenrol', '1'),
(1033, 'enrol_manual', 'status', '0'),
(1034, 'enrol_manual', 'roleid', '5'),
(1035, 'enrol_manual', 'enrolstart', '4'),
(1036, 'enrol_manual', 'enrolperiod', '0'),
(1037, 'enrol_manual', 'expirynotify', '0'),
(1038, 'enrol_manual', 'expirythreshold', '86400'),
(1039, 'enrol_meta', 'nosyncroleids', ''),
(1040, 'enrol_meta', 'syncall', '1'),
(1041, 'enrol_meta', 'unenrolaction', '3'),
(1042, 'enrol_meta', 'coursesort', 'sortorder'),
(1043, 'enrol_mnet', 'roleid', '5'),
(1044, 'enrol_mnet', 'roleid_adv', '1'),
(1045, 'enrol_paypal', 'paypalbusiness', ''),
(1046, 'enrol_paypal', 'mailstudents', '0'),
(1047, 'enrol_paypal', 'mailteachers', '0'),
(1048, 'enrol_paypal', 'mailadmins', '0'),
(1049, 'enrol_paypal', 'expiredaction', '3'),
(1050, 'enrol_paypal', 'status', '1'),
(1051, 'enrol_paypal', 'cost', '0'),
(1052, 'enrol_paypal', 'currency', 'USD'),
(1053, 'enrol_paypal', 'roleid', '5'),
(1054, 'enrol_paypal', 'enrolperiod', '0'),
(1055, 'enrol_self', 'requirepassword', '0'),
(1056, 'enrol_self', 'usepasswordpolicy', '0'),
(1057, 'enrol_self', 'showhint', '0'),
(1058, 'enrol_self', 'expiredaction', '1'),
(1059, 'enrol_self', 'expirynotifyhour', '6'),
(1060, 'enrol_self', 'defaultenrol', '1'),
(1061, 'enrol_self', 'status', '1'),
(1062, 'enrol_self', 'newenrols', '1'),
(1063, 'enrol_self', 'groupkey', '0'),
(1064, 'enrol_self', 'roleid', '5'),
(1065, 'enrol_self', 'enrolperiod', '0'),
(1066, 'enrol_self', 'expirynotify', '0'),
(1067, 'enrol_self', 'expirythreshold', '86400'),
(1068, 'enrol_self', 'longtimenosee', '0'),
(1069, 'enrol_self', 'maxenrolled', '0'),
(1070, 'enrol_self', 'sendcoursewelcomemessage', '1'),
(1071, 'filter_emoticon', 'formats', '1,4,0'),
(1072, 'filter_mathjaxloader', 'httpurl', 'http://cdn.mathjax.org/mathjax/2.5-latest/MathJax.js'),
(1073, 'filter_mathjaxloader', 'httpsurl', 'https://cdn.mathjax.org/mathjax/2.5-latest/MathJax.js'),
(1074, 'filter_mathjaxloader', 'texfiltercompatibility', '0'),
(1075, 'filter_mathjaxloader', 'mathjaxconfig', '\nMathJax.Hub.Config({\n    config: ["Accessible.js", "Safe.js"],\n    errorSettings: { message: ["!"] },\n    skipStartupTypeset: true,\n    messageStyle: "none"\n});\n'),
(1076, 'filter_mathjaxloader', 'additionaldelimiters', ''),
(1077, 'filter_tex', 'latexpreamble', '\\usepackage[latin1]{inputenc}\n\\usepackage{amsmath}\n\\usepackage{amsfonts}\n\\RequirePackage{amsmath,amssymb,latexsym}\n'),
(1078, 'filter_tex', 'latexbackground', '#FFFFFF'),
(1079, 'filter_tex', 'density', '120'),
(1080, 'filter_tex', 'pathlatex', '/usr/bin/latex'),
(1081, 'filter_tex', 'pathdvips', '/usr/bin/dvips'),
(1082, 'filter_tex', 'pathconvert', '/usr/bin/convert'),
(1083, 'filter_tex', 'pathdvisvgm', '/usr/bin/dvisvgm'),
(1084, 'filter_tex', 'pathmimetex', ''),
(1085, 'filter_tex', 'convertformat', 'gif'),
(1086, 'filter_urltolink', 'formats', '0'),
(1087, 'filter_urltolink', 'embedimages', '1'),
(1088, 'logstore_database', 'dbdriver', ''),
(1089, 'logstore_database', 'dbhost', ''),
(1090, 'logstore_database', 'dbuser', ''),
(1091, 'logstore_database', 'dbpass', ''),
(1092, 'logstore_database', 'dbname', ''),
(1093, 'logstore_database', 'dbtable', ''),
(1094, 'logstore_database', 'dbpersist', '0'),
(1095, 'logstore_database', 'dbsocket', ''),
(1096, 'logstore_database', 'dbport', ''),
(1097, 'logstore_database', 'dbschema', ''),
(1098, 'logstore_database', 'dbcollation', ''),
(1099, 'logstore_database', 'buffersize', '50'),
(1100, 'logstore_database', 'logguests', '0'),
(1101, 'logstore_database', 'includelevels', '1,2,0'),
(1102, 'logstore_database', 'includeactions', 'c,r,u,d'),
(1103, 'logstore_legacy', 'loglegacy', '0'),
(1104, 'logstore_standard', 'logguests', '1'),
(1105, 'logstore_standard', 'loglifetime', '0'),
(1106, 'logstore_standard', 'buffersize', '50'),
(1107, 'editor_atto', 'toolbar', 'collapse = collapse\nstyle1 = title, bold, italic\nlist = unorderedlist, orderedlist\nlinks = link\nfiles = image, media, managefiles\nstyle2 = underline, strike, subscript, superscript\nalign = align\nindent = indent\ninsert = equation, charmap, table, clear\nundo = undo\naccessibility = accessibilitychecker, accessibilityhelper\nother = html'),
(1108, 'editor_atto', 'autosavefrequency', '60'),
(1109, 'atto_collapse', 'showgroups', '5'),
(1110, 'atto_equation', 'librarygroup1', '\n\\cdot\n\\times\n\\ast\n\\div\n\\diamond\n\\pm\n\\mp\n\\oplus\n\\ominus\n\\otimes\n\\oslash\n\\odot\n\\circ\n\\bullet\n\\asymp\n\\equiv\n\\subseteq\n\\supseteq\n\\leq\n\\geq\n\\preceq\n\\succeq\n\\sim\n\\simeq\n\\approx\n\\subset\n\\supset\n\\ll\n\\gg\n\\prec\n\\succ\n\\infty\n\\in\n\\ni\n\\forall\n\\exists\n\\neq\n'),
(1111, 'atto_equation', 'librarygroup2', '\n\\leftarrow\n\\rightarrow\n\\uparrow\n\\downarrow\n\\leftrightarrow\n\\nearrow\n\\searrow\n\\swarrow\n\\nwarrow\n\\Leftarrow\n\\Rightarrow\n\\Uparrow\n\\Downarrow\n\\Leftrightarrow\n'),
(1112, 'atto_equation', 'librarygroup3', '\n\\alpha\n\\beta\n\\gamma\n\\delta\n\\epsilon\n\\zeta\n\\eta\n\\theta\n\\iota\n\\kappa\n\\lambda\n\\mu\n\\nu\n\\xi\n\\pi\n\\rho\n\\sigma\n\\tau\n\\upsilon\n\\phi\n\\chi\n\\psi\n\\omega\n\\Gamma\n\\Delta\n\\Theta\n\\Lambda\n\\Xi\n\\Pi\n\\Sigma\n\\Upsilon\n\\Phi\n\\Psi\n\\Omega\n'),
(1113, 'atto_equation', 'librarygroup4', '\n\\sum{a,b}\n\\sqrt[a]{b+c}\n\\int_{a}^{b}{c}\n\\iint_{a}^{b}{c}\n\\iiint_{a}^{b}{c}\n\\oint{a}\n(a)\n[a]\n\\lbrace{a}\\rbrace\n\\left| \\begin{matrix} a_1 & a_2 \\ a_3 & a_4 \\end{matrix} \\right|\n\\frac{a}{b+c}\n\\vec{a}\n\\binom {a} {b}\n{a \\brack b}\n{a \\brace b}\n'),
(1114, 'atto_table', 'allowborders', '0'),
(1115, 'atto_table', 'allowbackgroundcolour', '0'),
(1116, 'atto_table', 'allowwidth', '0'),
(1117, 'editor_tinymce', 'customtoolbar', 'wrap,formatselect,wrap,bold,italic,wrap,bullist,numlist,wrap,link,unlink,wrap,image\n\nundo,redo,wrap,underline,strikethrough,sub,sup,wrap,justifyleft,justifycenter,justifyright,wrap,outdent,indent,wrap,forecolor,backcolor,wrap,ltr,rtl\n\nfontselect,fontsizeselect,wrap,code,search,replace,wrap,nonbreaking,charmap,table,wrap,cleanup,removeformat,pastetext,pasteword,wrap,fullscreen'),
(1118, 'editor_tinymce', 'fontselectlist', 'Trebuchet=Trebuchet MS,Verdana,Arial,Helvetica,sans-serif;Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;Wingdings=wingdings'),
(1119, 'editor_tinymce', 'customconfig', ''),
(1120, 'tinymce_moodleemoticon', 'requireemoticon', '1'),
(1121, 'tinymce_spellchecker', 'spellengine', ''),
(1122, 'tinymce_spellchecker', 'spelllanguagelist', '+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv'),
(1123, 'theme_lambda', 'version', '2015110809'),
(1124, 'theme_lambda', 'logo', ''),
(1125, 'theme_lambda', 'logo_res', '0'),
(1126, 'theme_lambda', 'pagewidth', '1600'),
(1127, 'theme_lambda', 'layout', ''),
(1128, 'theme_lambda', 'mycourses_dropdown', ''),
(1129, 'theme_lambda', 'footnote', ''),
(1130, 'theme_lambda', 'customcss', ''),
(1131, 'theme_lambda', 'list_bg', '0'),
(1132, 'theme_lambda', 'pagebackground', ''),
(1133, 'theme_lambda', 'page_bg_repeat', '0'),
(1134, 'theme_lambda', 'maincolor', '#e2a500'),
(1135, 'theme_lambda', 'mainhovercolor', '#c48f00'),
(1136, 'theme_lambda', 'linkcolor', '#966b00'),
(1137, 'theme_lambda', 'def_buttoncolor', '#8ec63f'),
(1138, 'theme_lambda', 'def_buttonhovercolor', '#77ae29'),
(1139, 'theme_lambda', 'menufirstlevelcolor', '#323A45'),
(1140, 'theme_lambda', 'menufirstlevel_linkcolor', '#ffffff'),
(1141, 'theme_lambda', 'menusecondlevelcolor', '#f4f4f4'),
(1142, 'theme_lambda', 'menusecondlevel_linkcolor', '#444444'),
(1143, 'theme_lambda', 'footercolor', '#323A45'),
(1144, 'theme_lambda', 'footerheadingcolor', '#f9f9f9'),
(1145, 'theme_lambda', 'footertextcolor', '#bdc3c7'),
(1146, 'theme_lambda', 'copyrightcolor', '#292F38'),
(1147, 'theme_lambda', 'copyright_textcolor', '#bdc3c7'),
(1148, 'theme_lambda', 'website', ''),
(1149, 'theme_lambda', 'socials_mail', ''),
(1150, 'theme_lambda', 'facebook', ''),
(1151, 'theme_lambda', 'flickr', ''),
(1152, 'theme_lambda', 'twitter', ''),
(1153, 'theme_lambda', 'googleplus', ''),
(1154, 'theme_lambda', 'pinterest', ''),
(1155, 'theme_lambda', 'instagram', ''),
(1156, 'theme_lambda', 'youtube', ''),
(1157, 'theme_lambda', 'socials_color', '#a9a9a9'),
(1158, 'theme_lambda', 'socials_position', '0'),
(1159, 'theme_lambda', 'font_body', '1'),
(1160, 'theme_lambda', 'font_heading', '1'),
(1161, 'theme_lambda', 'slide1image', ''),
(1162, 'theme_lambda', 'slide1', ''),
(1163, 'theme_lambda', 'slide1caption', ''),
(1164, 'theme_lambda', 'slide1_url', ''),
(1165, 'theme_lambda', 'slide2image', ''),
(1166, 'theme_lambda', 'slide2', ''),
(1167, 'theme_lambda', 'slide2caption', ''),
(1168, 'theme_lambda', 'slide2_url', ''),
(1169, 'theme_lambda', 'slide3image', ''),
(1170, 'theme_lambda', 'slide3', ''),
(1171, 'theme_lambda', 'slide3caption', ''),
(1172, 'theme_lambda', 'slide3_url', ''),
(1173, 'theme_lambda', 'slide4image', ''),
(1174, 'theme_lambda', 'slide4', ''),
(1175, 'theme_lambda', 'slide4caption', ''),
(1176, 'theme_lambda', 'slide4_url', ''),
(1177, 'theme_lambda', 'slide5image', ''),
(1178, 'theme_lambda', 'slide5', ''),
(1179, 'theme_lambda', 'slide5caption', ''),
(1180, 'theme_lambda', 'slide5_url', ''),
(1181, 'theme_lambda', 'slideshowpattern', '0'),
(1182, 'theme_lambda', 'slideshow_advance', '1'),
(1183, 'theme_lambda', 'slideshow_nav', '1'),
(1184, 'theme_lambda', 'slideshow_loader', '0'),
(1185, 'theme_lambda', 'slideshow_imgfx', 'random'),
(1186, 'theme_lambda', 'slideshow_txtfx', 'moveFromLeft'),
(1187, 'theme_lambda', 'carousel_position', '1'),
(1188, 'theme_lambda', 'carousel_h', ''),
(1189, 'theme_lambda', 'carousel_hi', '3'),
(1190, 'theme_lambda', 'carousel_add_html', ''),
(1191, 'theme_lambda', 'carousel_slides', '4'),
(1192, 'theme_lambda', 'carousel_image_1', ''),
(1193, 'theme_lambda', 'carousel_heading_1', ''),
(1194, 'theme_lambda', 'carousel_caption_1', ''),
(1195, 'theme_lambda', 'carousel_url_1', ''),
(1196, 'theme_lambda', 'carousel_btntext_1', ''),
(1197, 'theme_lambda', 'carousel_color_1', '0'),
(1198, 'theme_lambda', 'carousel_image_2', ''),
(1199, 'theme_lambda', 'carousel_heading_2', ''),
(1200, 'theme_lambda', 'carousel_caption_2', ''),
(1201, 'theme_lambda', 'carousel_url_2', ''),
(1202, 'theme_lambda', 'carousel_btntext_2', ''),
(1203, 'theme_lambda', 'carousel_color_2', '0'),
(1204, 'theme_lambda', 'carousel_image_3', ''),
(1205, 'theme_lambda', 'carousel_heading_3', ''),
(1206, 'theme_lambda', 'carousel_caption_3', ''),
(1207, 'theme_lambda', 'carousel_url_3', ''),
(1208, 'theme_lambda', 'carousel_btntext_3', ''),
(1209, 'theme_lambda', 'carousel_color_3', '0'),
(1210, 'theme_lambda', 'carousel_image_4', ''),
(1211, 'theme_lambda', 'carousel_heading_4', ''),
(1212, 'theme_lambda', 'carousel_caption_4', ''),
(1213, 'theme_lambda', 'carousel_url_4', ''),
(1214, 'theme_lambda', 'carousel_btntext_4', ''),
(1215, 'theme_lambda', 'carousel_color_4', '0'),
(1216, 'enrol_ldap', 'objectclass', '(objectClass=*)');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_context`
--

CREATE TABLE IF NOT EXISTS `mdl_context` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextlevel` bigint(10) NOT NULL DEFAULT '0',
  `instanceid` bigint(10) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  `depth` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_cont_conins_uix` (`contextlevel`,`instanceid`),
  KEY `mdl_cont_ins_ix` (`instanceid`),
  KEY `mdl_cont_pat_ix` (`path`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='one of these must be set' AUTO_INCREMENT=98 ;

--
-- Dumping data for table `mdl_context`
--

INSERT INTO `mdl_context` (`id`, `contextlevel`, `instanceid`, `path`, `depth`) VALUES
(1, 10, 0, '/1', 1),
(2, 50, 1, '/1/2', 2),
(4, 30, 1, '/1/4', 2),
(5, 30, 2, '/1/5', 2),
(6, 80, 1, '/1/2/6', 3),
(7, 80, 2, '/1/2/7', 3),
(8, 80, 3, '/1/2/8', 3),
(9, 80, 4, '/1/9', 2),
(10, 80, 5, '/1/10', 2),
(11, 80, 6, '/1/11', 2),
(12, 80, 7, '/1/12', 2),
(13, 80, 8, '/1/13', 2),
(14, 80, 9, '/1/14', 2),
(15, 80, 10, '/1/15', 2),
(16, 80, 11, '/1/16', 2),
(17, 80, 12, '/1/17', 2),
(23, 30, 3, '/1/23', 2),
(30, 40, 2, '/1/30', 2),
(31, 40, 3, '/1/31', 2),
(32, 40, 4, '/1/32', 2),
(33, 40, 5, '/1/33', 2),
(34, 50, 2, '/1/30/34', 3),
(35, 80, 24, '/1/30/34/35', 4),
(36, 80, 25, '/1/30/34/36', 4),
(37, 80, 26, '/1/30/34/37', 4),
(38, 80, 27, '/1/30/34/38', 4),
(39, 50, 3, '/1/30/39', 3),
(40, 80, 28, '/1/30/39/40', 4),
(41, 80, 29, '/1/30/39/41', 4),
(42, 80, 30, '/1/30/39/42', 4),
(43, 80, 31, '/1/30/39/43', 4),
(44, 50, 4, '/1/30/44', 3),
(49, 50, 5, '/1/30/49', 3),
(50, 80, 36, '/1/30/49/50', 4),
(51, 80, 37, '/1/30/49/51', 4),
(52, 80, 38, '/1/30/49/52', 4),
(53, 80, 39, '/1/30/49/53', 4),
(54, 50, 6, '/1/31/54', 3),
(59, 70, 1, '/1/30/49/59', 4),
(60, 70, 2, '/1/30/44/60', 4),
(61, 70, 3, '/1/31/54/61', 4),
(62, 70, 4, '/1/30/39/62', 4),
(63, 50, 7, '/1/33/63', 3),
(64, 80, 44, '/1/33/63/64', 4),
(65, 80, 45, '/1/33/63/65', 4),
(66, 80, 46, '/1/33/63/66', 4),
(67, 80, 47, '/1/33/63/67', 4),
(68, 50, 8, '/1/33/68', 3),
(69, 80, 48, '/1/33/68/69', 4),
(70, 80, 49, '/1/33/68/70', 4),
(71, 80, 50, '/1/33/68/71', 4),
(72, 80, 51, '/1/33/68/72', 4),
(73, 50, 9, '/1/33/73', 3),
(74, 80, 52, '/1/33/73/74', 4),
(75, 80, 53, '/1/33/73/75', 4),
(76, 80, 54, '/1/33/73/76', 4),
(77, 80, 55, '/1/33/73/77', 4),
(78, 50, 10, '/1/33/78', 3),
(79, 80, 56, '/1/33/78/79', 4),
(80, 80, 57, '/1/33/78/80', 4),
(81, 80, 58, '/1/33/78/81', 4),
(82, 80, 59, '/1/33/78/82', 4),
(83, 50, 11, '/1/33/83', 3),
(84, 80, 60, '/1/33/83/84', 4),
(85, 80, 61, '/1/33/83/85', 4),
(86, 80, 62, '/1/33/83/86', 4),
(87, 80, 63, '/1/33/83/87', 4),
(88, 50, 12, '/1/32/88', 3),
(89, 80, 64, '/1/32/88/89', 4),
(90, 80, 65, '/1/32/88/90', 4),
(91, 80, 66, '/1/32/88/91', 4),
(92, 80, 67, '/1/32/88/92', 4),
(93, 50, 13, '/1/32/93', 3),
(94, 80, 68, '/1/32/93/94', 4),
(95, 80, 69, '/1/32/93/95', 4),
(96, 80, 70, '/1/32/93/96', 4),
(97, 80, 71, '/1/32/93/97', 4);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_context_temp`
--

CREATE TABLE IF NOT EXISTS `mdl_context_temp` (
  `id` bigint(10) NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `depth` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used by build_context_path() in upgrade and cron to keep con';

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course`
--

CREATE TABLE IF NOT EXISTS `mdl_course` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `category` bigint(10) NOT NULL DEFAULT '0',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `fullname` varchar(254) NOT NULL DEFAULT '',
  `shortname` varchar(255) NOT NULL DEFAULT '',
  `idnumber` varchar(100) NOT NULL DEFAULT '',
  `summary` longtext,
  `summaryformat` tinyint(2) NOT NULL DEFAULT '0',
  `format` varchar(21) NOT NULL DEFAULT 'topics',
  `showgrades` tinyint(2) NOT NULL DEFAULT '1',
  `newsitems` mediumint(5) NOT NULL DEFAULT '1',
  `startdate` bigint(10) NOT NULL DEFAULT '0',
  `marker` bigint(10) NOT NULL DEFAULT '0',
  `maxbytes` bigint(10) NOT NULL DEFAULT '0',
  `legacyfiles` smallint(4) NOT NULL DEFAULT '0',
  `showreports` smallint(4) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `visibleold` tinyint(1) NOT NULL DEFAULT '1',
  `groupmode` smallint(4) NOT NULL DEFAULT '0',
  `groupmodeforce` smallint(4) NOT NULL DEFAULT '0',
  `defaultgroupingid` bigint(10) NOT NULL DEFAULT '0',
  `lang` varchar(30) NOT NULL DEFAULT '',
  `calendartype` varchar(30) NOT NULL DEFAULT '',
  `theme` varchar(50) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `requested` tinyint(1) NOT NULL DEFAULT '0',
  `enablecompletion` tinyint(1) NOT NULL DEFAULT '0',
  `completionnotify` tinyint(1) NOT NULL DEFAULT '0',
  `cacherev` bigint(10) NOT NULL DEFAULT '0',
  `discount_status` int(11) NOT NULL DEFAULT '0',
  `discount_size` int(11) NOT NULL DEFAULT '0',
  `cost` float DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_cour_cat_ix` (`category`),
  KEY `mdl_cour_idn_ix` (`idnumber`),
  KEY `mdl_cour_sho_ix` (`shortname`),
  KEY `mdl_cour_sor_ix` (`sortorder`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Central course table' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `mdl_course`
--

INSERT INTO `mdl_course` (`id`, `category`, `sortorder`, `fullname`, `shortname`, `idnumber`, `summary`, `summaryformat`, `format`, `showgrades`, `newsitems`, `startdate`, `marker`, `maxbytes`, `legacyfiles`, `showreports`, `visible`, `visibleold`, `groupmode`, `groupmodeforce`, `defaultgroupingid`, `lang`, `calendartype`, `theme`, `timecreated`, `timemodified`, `requested`, `enablecompletion`, `completionnotify`, `cacherev`, `discount_status`, `discount_size`, `cost`) VALUES
(1, 0, 1, 'Medical2 Institute', 'MDL2', '', 'Some cool description', 0, 'site', 1, 3, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '', '', '', 1451552893, 1451553154, 0, 0, 0, 1453287272, 0, 0, 0),
(2, 2, 10004, 'Workshop #1', 'WS1', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453377600, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453375088, 1454401154, 0, 0, 0, 1454401154, 0, 0, 175),
(3, 2, 10003, 'Workshop #2', 'WS2', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453377600, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453375118, 1454401141, 0, 0, 0, 1454401141, 0, 5, 235),
(4, 2, 10002, 'Workshop #3', 'WS3', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453377600, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453375151, 1454401115, 0, 0, 0, 1454401115, 0, 2, 675),
(5, 2, 10001, 'Workshop #4', 'WS4', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul>', 1, 'weeks', 1, 5, 1453377600, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453375182, 1454401101, 0, 0, 0, 1454401101, 0, 0, 0),
(6, 3, 20001, 'Course#1', 'CS1', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453377600, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453375220, 1454401174, 0, 0, 0, 1454401174, 0, 2, 355),
(7, 5, 40005, 'Nursing school at some location #1', 'NRS1', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652020, 1454401290, 0, 0, 0, 1454401290, 0, 2, 800),
(8, 5, 40004, 'Nursing school at some location #2', 'NRS2', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652062, 1454401276, 0, 0, 0, 1454401276, 0, 3, 800),
(9, 5, 40003, 'Nursing school at some location #3', 'NRS3', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652130, 1454401260, 0, 0, 0, 1454401260, 0, 0, 754),
(10, 5, 40002, 'Nursing school at some location #4', 'NRS4', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652167, 1454401243, 0, 0, 0, 1454401243, 0, 0, 835),
(11, 5, 40001, 'Nursing school at some location #5', 'NRS5', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652203, 1454401228, 0, 0, 0, 1454401228, 0, 0, 675),
(12, 4, 30002, 'Some exam #1', 'EX1', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652250, 1454401210, 0, 0, 0, 1454401210, 0, 0, 125),
(13, 4, 30001, 'Some exam #2', 'EX2', '', 'Some cool description<br><ul><li>item1</li><li>item2</li><li>item3</li><li>item4</li></ul><br>', 1, 'weeks', 1, 5, 1453723200, 0, 0, 0, 0, 1, 1, 1, 0, 0, '', '', '', 1453652284, 1454401194, 0, 0, 0, 1454401194, 0, 0, 145);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_categories`
--

CREATE TABLE IF NOT EXISTS `mdl_course_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `idnumber` varchar(100) DEFAULT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `parent` bigint(10) NOT NULL DEFAULT '0',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `coursecount` bigint(10) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `visibleold` tinyint(1) NOT NULL DEFAULT '1',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `depth` bigint(10) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `theme` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_courcate_par_ix` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Course categories' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mdl_course_categories`
--

INSERT INTO `mdl_course_categories` (`id`, `name`, `idnumber`, `description`, `descriptionformat`, `parent`, `sortorder`, `coursecount`, `visible`, `visibleold`, `timemodified`, `depth`, `path`, `theme`) VALUES
(2, 'Workshops', '', '<p>This is Workshops category.<br></p>', 1, 0, 10000, 4, 1, 1, 1453371729, 1, '/2', NULL),
(3, 'CEU Courses', '', '<p>This is CEUs Courses category.<br></p>', 1, 0, 20000, 1, 1, 1, 1453371775, 1, '/3', NULL),
(4, 'Exams', '', '<p>This is Exams category.<br></p>', 1, 0, 30000, 2, 1, 1, 1453371811, 1, '/4', NULL),
(5, 'Nursing School', '', '<p>This is Nursing School category.<br></p>', 1, 0, 40000, 5, 1, 1, 1453371854, 1, '/5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_completions`
--

CREATE TABLE IF NOT EXISTS `mdl_course_completions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `course` bigint(10) NOT NULL DEFAULT '0',
  `timeenrolled` bigint(10) NOT NULL DEFAULT '0',
  `timestarted` bigint(10) NOT NULL DEFAULT '0',
  `timecompleted` bigint(10) DEFAULT NULL,
  `reaggregate` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_courcomp_usecou_uix` (`userid`,`course`),
  KEY `mdl_courcomp_use_ix` (`userid`),
  KEY `mdl_courcomp_cou_ix` (`course`),
  KEY `mdl_courcomp_tim_ix` (`timecompleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Course completion records' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_completion_aggr_methd`
--

CREATE TABLE IF NOT EXISTS `mdl_course_completion_aggr_methd` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `criteriatype` bigint(10) DEFAULT NULL,
  `method` tinyint(1) NOT NULL DEFAULT '0',
  `value` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_courcompaggrmeth_coucr_uix` (`course`,`criteriatype`),
  KEY `mdl_courcompaggrmeth_cou_ix` (`course`),
  KEY `mdl_courcompaggrmeth_cri_ix` (`criteriatype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Course completion aggregation methods for criteria' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_completion_criteria`
--

CREATE TABLE IF NOT EXISTS `mdl_course_completion_criteria` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `criteriatype` bigint(10) NOT NULL DEFAULT '0',
  `module` varchar(100) DEFAULT NULL,
  `moduleinstance` bigint(10) DEFAULT NULL,
  `courseinstance` bigint(10) DEFAULT NULL,
  `enrolperiod` bigint(10) DEFAULT NULL,
  `timeend` bigint(10) DEFAULT NULL,
  `gradepass` decimal(10,5) DEFAULT NULL,
  `role` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_courcompcrit_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Course completion criteria' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_completion_crit_compl`
--

CREATE TABLE IF NOT EXISTS `mdl_course_completion_crit_compl` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `course` bigint(10) NOT NULL DEFAULT '0',
  `criteriaid` bigint(10) NOT NULL DEFAULT '0',
  `gradefinal` decimal(10,5) DEFAULT NULL,
  `unenroled` bigint(10) DEFAULT NULL,
  `timecompleted` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_courcompcritcomp_useco_uix` (`userid`,`course`,`criteriaid`),
  KEY `mdl_courcompcritcomp_use_ix` (`userid`),
  KEY `mdl_courcompcritcomp_cou_ix` (`course`),
  KEY `mdl_courcompcritcomp_cri_ix` (`criteriaid`),
  KEY `mdl_courcompcritcomp_tim_ix` (`timecompleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Course completion user records' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_format_options`
--

CREATE TABLE IF NOT EXISTS `mdl_course_format_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `format` varchar(21) NOT NULL DEFAULT '',
  `sectionid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_courformopti_couforsec_uix` (`courseid`,`format`,`sectionid`,`name`),
  KEY `mdl_courformopti_cou_ix` (`courseid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Stores format-specific options for the course or course sect' AUTO_INCREMENT=38 ;

--
-- Dumping data for table `mdl_course_format_options`
--

INSERT INTO `mdl_course_format_options` (`id`, `courseid`, `format`, `sectionid`, `name`, `value`) VALUES
(1, 1, 'site', 0, 'numsections', '1'),
(2, 2, 'weeks', 0, 'numsections', '10'),
(3, 2, 'weeks', 0, 'hiddensections', '0'),
(4, 2, 'weeks', 0, 'coursedisplay', '0'),
(5, 3, 'weeks', 0, 'numsections', '10'),
(6, 3, 'weeks', 0, 'hiddensections', '0'),
(7, 3, 'weeks', 0, 'coursedisplay', '0'),
(8, 4, 'weeks', 0, 'numsections', '10'),
(9, 4, 'weeks', 0, 'hiddensections', '0'),
(10, 4, 'weeks', 0, 'coursedisplay', '0'),
(11, 5, 'weeks', 0, 'numsections', '10'),
(12, 5, 'weeks', 0, 'hiddensections', '0'),
(13, 5, 'weeks', 0, 'coursedisplay', '0'),
(14, 6, 'weeks', 0, 'numsections', '10'),
(15, 6, 'weeks', 0, 'hiddensections', '0'),
(16, 6, 'weeks', 0, 'coursedisplay', '0'),
(17, 7, 'weeks', 0, 'numsections', '10'),
(18, 7, 'weeks', 0, 'hiddensections', '0'),
(19, 7, 'weeks', 0, 'coursedisplay', '0'),
(20, 8, 'weeks', 0, 'numsections', '10'),
(21, 8, 'weeks', 0, 'hiddensections', '0'),
(22, 8, 'weeks', 0, 'coursedisplay', '0'),
(23, 9, 'weeks', 0, 'numsections', '10'),
(24, 9, 'weeks', 0, 'hiddensections', '0'),
(25, 9, 'weeks', 0, 'coursedisplay', '0'),
(26, 10, 'weeks', 0, 'numsections', '10'),
(27, 10, 'weeks', 0, 'hiddensections', '0'),
(28, 10, 'weeks', 0, 'coursedisplay', '0'),
(29, 11, 'weeks', 0, 'numsections', '10'),
(30, 11, 'weeks', 0, 'hiddensections', '0'),
(31, 11, 'weeks', 0, 'coursedisplay', '0'),
(32, 12, 'weeks', 0, 'numsections', '10'),
(33, 12, 'weeks', 0, 'hiddensections', '0'),
(34, 12, 'weeks', 0, 'coursedisplay', '0'),
(35, 13, 'weeks', 0, 'numsections', '10'),
(36, 13, 'weeks', 0, 'hiddensections', '0'),
(37, 13, 'weeks', 0, 'coursedisplay', '0');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_modules`
--

CREATE TABLE IF NOT EXISTS `mdl_course_modules` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `module` bigint(10) NOT NULL DEFAULT '0',
  `instance` bigint(10) NOT NULL DEFAULT '0',
  `section` bigint(10) NOT NULL DEFAULT '0',
  `idnumber` varchar(100) DEFAULT NULL,
  `added` bigint(10) NOT NULL DEFAULT '0',
  `score` smallint(4) NOT NULL DEFAULT '0',
  `indent` mediumint(5) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `visibleold` tinyint(1) NOT NULL DEFAULT '1',
  `groupmode` smallint(4) NOT NULL DEFAULT '0',
  `groupingid` bigint(10) NOT NULL DEFAULT '0',
  `completion` tinyint(1) NOT NULL DEFAULT '0',
  `completiongradeitemnumber` bigint(10) DEFAULT NULL,
  `completionview` tinyint(1) NOT NULL DEFAULT '0',
  `completionexpected` bigint(10) NOT NULL DEFAULT '0',
  `showdescription` tinyint(1) NOT NULL DEFAULT '0',
  `availability` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_courmodu_vis_ix` (`visible`),
  KEY `mdl_courmodu_cou_ix` (`course`),
  KEY `mdl_courmodu_mod_ix` (`module`),
  KEY `mdl_courmodu_ins_ix` (`instance`),
  KEY `mdl_courmodu_idncou_ix` (`idnumber`,`course`),
  KEY `mdl_courmodu_gro_ix` (`groupingid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='course_modules table retrofitted from MySQL' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mdl_course_modules`
--

INSERT INTO `mdl_course_modules` (`id`, `course`, `module`, `instance`, `section`, `idnumber`, `added`, `score`, `indent`, `visible`, `visibleold`, `groupmode`, `groupingid`, `completion`, `completiongradeitemnumber`, `completionview`, `completionexpected`, `showdescription`, `availability`) VALUES
(1, 5, 9, 1, 4, NULL, 1453387795, 0, 0, 1, 1, 0, 0, 0, NULL, 0, 0, 0, NULL),
(2, 4, 9, 2, 3, NULL, 1453448062, 0, 0, 1, 1, 0, 0, 0, NULL, 0, 0, 0, NULL),
(3, 6, 9, 3, 5, NULL, 1453448250, 0, 0, 1, 1, 0, 0, 0, NULL, 0, 0, 0, NULL),
(4, 3, 9, 4, 2, NULL, 1453448359, 0, 0, 1, 1, 0, 0, 0, NULL, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_modules_completion`
--

CREATE TABLE IF NOT EXISTS `mdl_course_modules_completion` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `coursemoduleid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `completionstate` tinyint(1) NOT NULL,
  `viewed` tinyint(1) DEFAULT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_courmoducomp_usecou_uix` (`userid`,`coursemoduleid`),
  KEY `mdl_courmoducomp_cou_ix` (`coursemoduleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the completion state (completed or not completed, etc' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_published`
--

CREATE TABLE IF NOT EXISTS `mdl_course_published` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `huburl` varchar(255) DEFAULT NULL,
  `courseid` bigint(10) NOT NULL,
  `timepublished` bigint(10) NOT NULL,
  `enrollable` tinyint(1) NOT NULL DEFAULT '1',
  `hubcourseid` bigint(10) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `timechecked` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Information about how and when an local courses were publish' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_request`
--

CREATE TABLE IF NOT EXISTS `mdl_course_request` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(254) NOT NULL DEFAULT '',
  `shortname` varchar(100) NOT NULL DEFAULT '',
  `summary` longtext NOT NULL,
  `summaryformat` tinyint(2) NOT NULL DEFAULT '0',
  `category` bigint(10) NOT NULL DEFAULT '0',
  `reason` longtext NOT NULL,
  `requester` bigint(10) NOT NULL DEFAULT '0',
  `password` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_courrequ_sho_ix` (`shortname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='course requests' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_course_sections`
--

CREATE TABLE IF NOT EXISTS `mdl_course_sections` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `section` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `summary` longtext,
  `summaryformat` tinyint(2) NOT NULL DEFAULT '0',
  `sequence` longtext,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `availability` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_coursect_cousec_uix` (`course`,`section`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='to define the sections for each course' AUTO_INCREMENT=53 ;

--
-- Dumping data for table `mdl_course_sections`
--

INSERT INTO `mdl_course_sections` (`id`, `course`, `section`, `name`, `summary`, `summaryformat`, `sequence`, `visible`, `availability`) VALUES
(1, 2, 0, NULL, '', 1, '', 1, NULL),
(2, 3, 0, NULL, '', 1, '4', 1, NULL),
(3, 4, 0, NULL, '', 1, '2', 1, NULL),
(4, 5, 0, NULL, '', 1, '1', 1, NULL),
(5, 6, 0, NULL, '', 1, '3', 1, NULL),
(6, 5, 1, NULL, '', 1, '', 1, NULL),
(7, 5, 2, NULL, '', 1, '', 1, NULL),
(8, 5, 3, NULL, '', 1, '', 1, NULL),
(9, 5, 4, NULL, '', 1, '', 1, NULL),
(10, 5, 5, NULL, '', 1, '', 1, NULL),
(11, 5, 6, NULL, '', 1, '', 1, NULL),
(12, 5, 7, NULL, '', 1, '', 1, NULL),
(13, 5, 8, NULL, '', 1, '', 1, NULL),
(14, 5, 9, NULL, '', 1, '', 1, NULL),
(15, 5, 10, NULL, '', 1, '', 1, NULL),
(16, 4, 1, NULL, '', 1, '', 1, NULL),
(17, 4, 2, NULL, '', 1, '', 1, NULL),
(18, 4, 3, NULL, '', 1, '', 1, NULL),
(19, 4, 4, NULL, '', 1, '', 1, NULL),
(20, 4, 5, NULL, '', 1, '', 1, NULL),
(21, 4, 6, NULL, '', 1, '', 1, NULL),
(22, 4, 7, NULL, '', 1, '', 1, NULL),
(23, 4, 8, NULL, '', 1, '', 1, NULL),
(24, 4, 9, NULL, '', 1, '', 1, NULL),
(25, 4, 10, NULL, '', 1, '', 1, NULL),
(26, 6, 1, NULL, '', 1, '', 1, NULL),
(27, 6, 2, NULL, '', 1, '', 1, NULL),
(28, 6, 3, NULL, '', 1, '', 1, NULL),
(29, 6, 4, NULL, '', 1, '', 1, NULL),
(30, 6, 5, NULL, '', 1, '', 1, NULL),
(31, 6, 6, NULL, '', 1, '', 1, NULL),
(32, 6, 7, NULL, '', 1, '', 1, NULL),
(33, 6, 8, NULL, '', 1, '', 1, NULL),
(34, 6, 9, NULL, '', 1, '', 1, NULL),
(35, 6, 10, NULL, '', 1, '', 1, NULL),
(36, 3, 1, NULL, '', 1, '', 1, NULL),
(37, 3, 2, NULL, '', 1, '', 1, NULL),
(38, 3, 3, NULL, '', 1, '', 1, NULL),
(39, 3, 4, NULL, '', 1, '', 1, NULL),
(40, 3, 5, NULL, '', 1, '', 1, NULL),
(41, 3, 6, NULL, '', 1, '', 1, NULL),
(42, 3, 7, NULL, '', 1, '', 1, NULL),
(43, 3, 8, NULL, '', 1, '', 1, NULL),
(44, 3, 9, NULL, '', 1, '', 1, NULL),
(45, 3, 10, NULL, '', 1, '', 1, NULL),
(46, 7, 0, NULL, '', 1, '', 1, NULL),
(47, 8, 0, NULL, '', 1, '', 1, NULL),
(48, 9, 0, NULL, '', 1, '', 1, NULL),
(49, 10, 0, NULL, '', 1, '', 1, NULL),
(50, 11, 0, NULL, '', 1, '', 1, NULL),
(51, 12, 0, NULL, '', 1, '', 1, NULL),
(52, 13, 0, NULL, '', 1, '', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_data`
--

CREATE TABLE IF NOT EXISTS `mdl_data` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `comments` smallint(4) NOT NULL DEFAULT '0',
  `timeavailablefrom` bigint(10) NOT NULL DEFAULT '0',
  `timeavailableto` bigint(10) NOT NULL DEFAULT '0',
  `timeviewfrom` bigint(10) NOT NULL DEFAULT '0',
  `timeviewto` bigint(10) NOT NULL DEFAULT '0',
  `requiredentries` int(8) NOT NULL DEFAULT '0',
  `requiredentriestoview` int(8) NOT NULL DEFAULT '0',
  `maxentries` int(8) NOT NULL DEFAULT '0',
  `rssarticles` smallint(4) NOT NULL DEFAULT '0',
  `singletemplate` longtext,
  `listtemplate` longtext,
  `listtemplateheader` longtext,
  `listtemplatefooter` longtext,
  `addtemplate` longtext,
  `rsstemplate` longtext,
  `rsstitletemplate` longtext,
  `csstemplate` longtext,
  `jstemplate` longtext,
  `asearchtemplate` longtext,
  `approval` smallint(4) NOT NULL DEFAULT '0',
  `manageapproved` smallint(4) NOT NULL DEFAULT '1',
  `scale` bigint(10) NOT NULL DEFAULT '0',
  `assessed` bigint(10) NOT NULL DEFAULT '0',
  `assesstimestart` bigint(10) NOT NULL DEFAULT '0',
  `assesstimefinish` bigint(10) NOT NULL DEFAULT '0',
  `defaultsort` bigint(10) NOT NULL DEFAULT '0',
  `defaultsortdir` smallint(4) NOT NULL DEFAULT '0',
  `editany` smallint(4) NOT NULL DEFAULT '0',
  `notification` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_data_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='all database activities' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_data_content`
--

CREATE TABLE IF NOT EXISTS `mdl_data_content` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `fieldid` bigint(10) NOT NULL DEFAULT '0',
  `recordid` bigint(10) NOT NULL DEFAULT '0',
  `content` longtext,
  `content1` longtext,
  `content2` longtext,
  `content3` longtext,
  `content4` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_datacont_rec_ix` (`recordid`),
  KEY `mdl_datacont_fie_ix` (`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='the content introduced in each record/fields' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_data_fields`
--

CREATE TABLE IF NOT EXISTS `mdl_data_fields` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `dataid` bigint(10) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `param1` longtext,
  `param2` longtext,
  `param3` longtext,
  `param4` longtext,
  `param5` longtext,
  `param6` longtext,
  `param7` longtext,
  `param8` longtext,
  `param9` longtext,
  `param10` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_datafiel_typdat_ix` (`type`,`dataid`),
  KEY `mdl_datafiel_dat_ix` (`dataid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='every field available' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_data_records`
--

CREATE TABLE IF NOT EXISTS `mdl_data_records` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `dataid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `approved` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_datareco_dat_ix` (`dataid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='every record introduced' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_editor_atto_autosave`
--

CREATE TABLE IF NOT EXISTS `mdl_editor_atto_autosave` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `elementid` varchar(255) NOT NULL DEFAULT '',
  `contextid` bigint(10) NOT NULL,
  `pagehash` varchar(64) NOT NULL DEFAULT '',
  `userid` bigint(10) NOT NULL,
  `drafttext` longtext NOT NULL,
  `draftid` bigint(10) DEFAULT NULL,
  `pageinstance` varchar(64) NOT NULL DEFAULT '',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_editattoauto_eleconuse_uix` (`elementid`,`contextid`,`userid`,`pagehash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Draft text that is auto-saved every 5 seconds while an edito' AUTO_INCREMENT=54 ;

--
-- Dumping data for table `mdl_editor_atto_autosave`
--

INSERT INTO `mdl_editor_atto_autosave` (`id`, `elementid`, `contextid`, `pagehash`, `userid`, `drafttext`, `draftid`, `pageinstance`, `timemodified`) VALUES
(1, 'id_s__auth_instructions', 1, 'd2c447eaca89cf394e53df22075504d111164155', 2, '', -1, 'yui_3_17_2_1_1452670081277_151', 1452670097),
(3, 'id_description_editor', 23, '6bd7f87d9c74661c151820b31928503e1f4a6b2d', 3, '', 229792672, 'yui_3_17_2_1_1453362540172_164', 1453362553),
(4, 'id_description_editor', 3, '77724e792043bb71d39b0337cd5888344e6ba464', 2, '', 22990388, 'yui_3_17_2_1_1453362934388_214', 1453362945),
(15, 'summary', 1, 'e85752665d73cac2007433ed48caf385f11759ef', 2, '', -1, 'yui_3_17_2_1_1453479324412_150', 1453479325),
(31, 'id_summary_editor', 93, '4551f2fff2a8f3667f9334113f9840a3420b92c2', 2, '', 657657062, 'yui_3_17_2_1_1454350921686_390', 1454350925),
(52, 'summary', 1, '3e34249fdc3ecea634149201098fa01b5fc9ea75', 2, 'Some cool description', -1, 'yui_3_17_2_1_1455143641099_150', 1455143712),
(53, 'id_s__auth_instructions', 1, 'e3cc9fa0eb6a67b5342e7ca5fd5d77d9c7f400f9', 2, '', -1, 'yui_3_17_2_1_1455291992713_150', 1455291999);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_enrol`
--

CREATE TABLE IF NOT EXISTS `mdl_enrol` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `enrol` varchar(20) NOT NULL DEFAULT '',
  `status` bigint(10) NOT NULL DEFAULT '0',
  `courseid` bigint(10) NOT NULL,
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `enrolperiod` bigint(10) DEFAULT '0',
  `enrolstartdate` bigint(10) DEFAULT '0',
  `enrolenddate` bigint(10) DEFAULT '0',
  `expirynotify` tinyint(1) DEFAULT '0',
  `expirythreshold` bigint(10) DEFAULT '0',
  `notifyall` tinyint(1) DEFAULT '0',
  `password` varchar(50) DEFAULT NULL,
  `cost` varchar(20) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `roleid` bigint(10) DEFAULT '0',
  `customint1` bigint(10) DEFAULT NULL,
  `customint2` bigint(10) DEFAULT NULL,
  `customint3` bigint(10) DEFAULT NULL,
  `customint4` bigint(10) DEFAULT NULL,
  `customint5` bigint(10) DEFAULT NULL,
  `customint6` bigint(10) DEFAULT NULL,
  `customint7` bigint(10) DEFAULT NULL,
  `customint8` bigint(10) DEFAULT NULL,
  `customchar1` varchar(255) DEFAULT NULL,
  `customchar2` varchar(255) DEFAULT NULL,
  `customchar3` varchar(1333) DEFAULT NULL,
  `customdec1` decimal(12,7) DEFAULT NULL,
  `customdec2` decimal(12,7) DEFAULT NULL,
  `customtext1` longtext,
  `customtext2` longtext,
  `customtext3` longtext,
  `customtext4` longtext,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_enro_enr_ix` (`enrol`),
  KEY `mdl_enro_cou_ix` (`courseid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Instances of enrolment plugins used in courses, fields marke' AUTO_INCREMENT=37 ;

--
-- Dumping data for table `mdl_enrol`
--

INSERT INTO `mdl_enrol` (`id`, `enrol`, `status`, `courseid`, `sortorder`, `name`, `enrolperiod`, `enrolstartdate`, `enrolenddate`, `expirynotify`, `expirythreshold`, `notifyall`, `password`, `cost`, `currency`, `roleid`, `customint1`, `customint2`, `customint3`, `customint4`, `customint5`, `customint6`, `customint7`, `customint8`, `customchar1`, `customchar2`, `customchar3`, `customdec1`, `customdec2`, `customtext1`, `customtext2`, `customtext3`, `customtext4`, `timecreated`, `timemodified`) VALUES
(1, 'manual', 0, 2, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375089, 1453375089),
(2, 'guest', 1, 2, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375089, 1453375089),
(3, 'self', 1, 2, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375089, 1453375089),
(4, 'manual', 0, 3, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375119, 1453375119),
(5, 'guest', 1, 3, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375119, 1453375119),
(6, 'self', 1, 3, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375119, 1453375119),
(7, 'manual', 0, 4, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375152, 1453375152),
(8, 'guest', 1, 4, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375152, 1453375152),
(9, 'self', 1, 4, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375152, 1453375152),
(10, 'manual', 0, 5, 0, NULL, 1036800, 0, 0, 1, 172800, 1, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375183, 1454350596),
(11, 'guest', 1, 5, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375183, 1453375183),
(12, 'self', 1, 5, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375183, 1453375183),
(13, 'manual', 0, 6, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375221, 1453375221),
(14, 'guest', 1, 6, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375221, 1453375221),
(15, 'self', 1, 6, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453375221, 1453375221),
(16, 'manual', 0, 7, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652021, 1453652021),
(17, 'guest', 1, 7, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652021, 1453652021),
(18, 'self', 1, 7, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652022, 1453652022),
(19, 'manual', 0, 8, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652063, 1453652063),
(20, 'guest', 1, 8, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652063, 1453652063),
(21, 'self', 1, 8, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652063, 1453652063),
(22, 'manual', 0, 9, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652131, 1453652131),
(23, 'guest', 1, 9, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652131, 1453652131),
(24, 'self', 1, 9, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652131, 1453652131),
(25, 'manual', 0, 10, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652168, 1453652168),
(26, 'guest', 1, 10, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652168, 1453652168),
(27, 'self', 1, 10, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652168, 1453652168),
(28, 'manual', 0, 11, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652204, 1453652204),
(29, 'guest', 1, 11, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652204, 1453652204),
(30, 'self', 1, 11, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652204, 1453652204),
(31, 'manual', 0, 12, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652251, 1453652251),
(32, 'guest', 1, 12, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652251, 1453652251),
(33, 'self', 1, 12, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652251, 1453652251),
(34, 'manual', 0, 13, 0, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652285, 1453652285),
(35, 'guest', 1, 13, 1, NULL, 0, 0, 0, 0, 0, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652285, 1453652285),
(36, 'self', 1, 13, 2, NULL, 0, 0, 0, 0, 86400, 0, NULL, NULL, NULL, 5, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1453652285, 1453652285);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_enrol_flatfile`
--

CREATE TABLE IF NOT EXISTS `mdl_enrol_flatfile` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `action` varchar(30) NOT NULL DEFAULT '',
  `roleid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `courseid` bigint(10) NOT NULL,
  `timestart` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_enroflat_cou_ix` (`courseid`),
  KEY `mdl_enroflat_use_ix` (`userid`),
  KEY `mdl_enroflat_rol_ix` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='enrol_flatfile table retrofitted from MySQL' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_enrol_paypal`
--

CREATE TABLE IF NOT EXISTS `mdl_enrol_paypal` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `business` varchar(255) NOT NULL DEFAULT '',
  `receiver_email` varchar(255) NOT NULL DEFAULT '',
  `receiver_id` varchar(255) NOT NULL DEFAULT '',
  `item_name` varchar(255) NOT NULL DEFAULT '',
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `instanceid` bigint(10) NOT NULL DEFAULT '0',
  `memo` varchar(255) NOT NULL DEFAULT '',
  `tax` varchar(255) NOT NULL DEFAULT '',
  `option_name1` varchar(255) NOT NULL DEFAULT '',
  `option_selection1_x` varchar(255) NOT NULL DEFAULT '',
  `option_name2` varchar(255) NOT NULL DEFAULT '',
  `option_selection2_x` varchar(255) NOT NULL DEFAULT '',
  `payment_status` varchar(255) NOT NULL DEFAULT '',
  `pending_reason` varchar(255) NOT NULL DEFAULT '',
  `reason_code` varchar(30) NOT NULL DEFAULT '',
  `txn_id` varchar(255) NOT NULL DEFAULT '',
  `parent_txn_id` varchar(255) NOT NULL DEFAULT '',
  `payment_type` varchar(30) NOT NULL DEFAULT '',
  `timeupdated` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds all known information about PayPal transactions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_event`
--

CREATE TABLE IF NOT EXISTS `mdl_event` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `description` longtext NOT NULL,
  `format` smallint(4) NOT NULL DEFAULT '0',
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `repeatid` bigint(10) NOT NULL DEFAULT '0',
  `modulename` varchar(20) NOT NULL DEFAULT '',
  `instance` bigint(10) NOT NULL DEFAULT '0',
  `eventtype` varchar(20) NOT NULL DEFAULT '',
  `timestart` bigint(10) NOT NULL DEFAULT '0',
  `timeduration` bigint(10) NOT NULL DEFAULT '0',
  `visible` smallint(4) NOT NULL DEFAULT '1',
  `uuid` varchar(255) NOT NULL DEFAULT '',
  `sequence` bigint(10) NOT NULL DEFAULT '1',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `subscriptionid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_even_cou_ix` (`courseid`),
  KEY `mdl_even_use_ix` (`userid`),
  KEY `mdl_even_tim_ix` (`timestart`),
  KEY `mdl_even_tim2_ix` (`timeduration`),
  KEY `mdl_even_grocouvisuse_ix` (`groupid`,`courseid`,`visible`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='For everything with a time associated to it' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_events_handlers`
--

CREATE TABLE IF NOT EXISTS `mdl_events_handlers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `eventname` varchar(166) NOT NULL DEFAULT '',
  `component` varchar(166) NOT NULL DEFAULT '',
  `handlerfile` varchar(255) NOT NULL DEFAULT '',
  `handlerfunction` longtext,
  `schedule` varchar(255) DEFAULT NULL,
  `status` bigint(10) NOT NULL DEFAULT '0',
  `internal` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_evenhand_evecom_uix` (`eventname`,`component`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table is for storing which components requests what typ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_events_queue`
--

CREATE TABLE IF NOT EXISTS `mdl_events_queue` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `eventdata` longtext NOT NULL,
  `stackdump` longtext,
  `userid` bigint(10) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_evenqueu_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table is for storing queued events. It stores only one ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_events_queue_handlers`
--

CREATE TABLE IF NOT EXISTS `mdl_events_queue_handlers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `queuedeventid` bigint(10) NOT NULL,
  `handlerid` bigint(10) NOT NULL,
  `status` bigint(10) DEFAULT NULL,
  `errormessage` longtext,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_evenqueuhand_que_ix` (`queuedeventid`),
  KEY `mdl_evenqueuhand_han_ix` (`handlerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This is the list of queued handlers for processing. The even' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_event_subscriptions`
--

CREATE TABLE IF NOT EXISTS `mdl_event_subscriptions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `eventtype` varchar(20) NOT NULL DEFAULT '',
  `pollinterval` bigint(10) NOT NULL DEFAULT '0',
  `lastupdated` bigint(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tracks subscriptions to remote calendars.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_external_functions`
--

CREATE TABLE IF NOT EXISTS `mdl_external_functions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `classname` varchar(100) NOT NULL DEFAULT '',
  `methodname` varchar(100) NOT NULL DEFAULT '',
  `classpath` varchar(255) DEFAULT NULL,
  `component` varchar(100) NOT NULL DEFAULT '',
  `capabilities` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_extefunc_nam_uix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='list of all external functions' AUTO_INCREMENT=193 ;

--
-- Dumping data for table `mdl_external_functions`
--

INSERT INTO `mdl_external_functions` (`id`, `name`, `classname`, `methodname`, `classpath`, `component`, `capabilities`) VALUES
(1, 'core_cohort_create_cohorts', 'core_cohort_external', 'create_cohorts', 'cohort/externallib.php', 'moodle', 'moodle/cohort:manage'),
(2, 'core_cohort_delete_cohorts', 'core_cohort_external', 'delete_cohorts', 'cohort/externallib.php', 'moodle', 'moodle/cohort:manage'),
(3, 'core_cohort_get_cohorts', 'core_cohort_external', 'get_cohorts', 'cohort/externallib.php', 'moodle', 'moodle/cohort:view'),
(4, 'core_cohort_update_cohorts', 'core_cohort_external', 'update_cohorts', 'cohort/externallib.php', 'moodle', 'moodle/cohort:manage'),
(5, 'core_cohort_add_cohort_members', 'core_cohort_external', 'add_cohort_members', 'cohort/externallib.php', 'moodle', 'moodle/cohort:assign'),
(6, 'core_cohort_delete_cohort_members', 'core_cohort_external', 'delete_cohort_members', 'cohort/externallib.php', 'moodle', 'moodle/cohort:assign'),
(7, 'core_cohort_get_cohort_members', 'core_cohort_external', 'get_cohort_members', 'cohort/externallib.php', 'moodle', 'moodle/cohort:view'),
(8, 'core_comment_get_comments', 'core_comment_external', 'get_comments', NULL, 'moodle', 'moodle/comment:view'),
(9, 'core_grades_get_grades', 'core_grades_external', 'get_grades', NULL, 'moodle', 'moodle/grade:view, moodle/grade:viewall, moodle/grade:viewhidden'),
(10, 'core_grades_update_grades', 'core_grades_external', 'update_grades', NULL, 'moodle', ''),
(11, 'moodle_group_create_groups', 'moodle_group_external', 'create_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(12, 'core_group_create_groups', 'core_group_external', 'create_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(13, 'moodle_group_get_groups', 'moodle_group_external', 'get_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(14, 'core_group_get_groups', 'core_group_external', 'get_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(15, 'moodle_group_get_course_groups', 'moodle_group_external', 'get_course_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(16, 'core_group_get_course_groups', 'core_group_external', 'get_course_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(17, 'moodle_group_delete_groups', 'moodle_group_external', 'delete_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(18, 'core_group_delete_groups', 'core_group_external', 'delete_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(19, 'moodle_group_get_groupmembers', 'moodle_group_external', 'get_groupmembers', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(20, 'core_group_get_group_members', 'core_group_external', 'get_group_members', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(21, 'moodle_group_add_groupmembers', 'moodle_group_external', 'add_groupmembers', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(22, 'core_group_add_group_members', 'core_group_external', 'add_group_members', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(23, 'moodle_group_delete_groupmembers', 'moodle_group_external', 'delete_groupmembers', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(24, 'core_group_delete_group_members', 'core_group_external', 'delete_group_members', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(25, 'core_group_create_groupings', 'core_group_external', 'create_groupings', 'group/externallib.php', 'moodle', ''),
(26, 'core_group_update_groupings', 'core_group_external', 'update_groupings', 'group/externallib.php', 'moodle', ''),
(27, 'core_group_get_groupings', 'core_group_external', 'get_groupings', 'group/externallib.php', 'moodle', ''),
(28, 'core_group_get_course_groupings', 'core_group_external', 'get_course_groupings', 'group/externallib.php', 'moodle', ''),
(29, 'core_group_delete_groupings', 'core_group_external', 'delete_groupings', 'group/externallib.php', 'moodle', ''),
(30, 'core_group_assign_grouping', 'core_group_external', 'assign_grouping', 'group/externallib.php', 'moodle', ''),
(31, 'core_group_unassign_grouping', 'core_group_external', 'unassign_grouping', 'group/externallib.php', 'moodle', ''),
(32, 'core_group_get_course_user_groups', 'core_group_external', 'get_course_user_groups', 'group/externallib.php', 'moodle', 'moodle/course:managegroups'),
(33, 'core_group_get_activity_allowed_groups', 'core_group_external', 'get_activity_allowed_groups', 'group/externallib.php', 'moodle', ''),
(34, 'core_group_get_activity_groupmode', 'core_group_external', 'get_activity_groupmode', 'group/externallib.php', 'moodle', ''),
(35, 'core_notes_get_course_notes', 'core_notes_external', 'get_course_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:view'),
(36, 'moodle_file_get_files', 'moodle_file_external', 'get_files', 'files/externallib.php', 'moodle', ''),
(37, 'core_files_get_files', 'core_files_external', 'get_files', 'files/externallib.php', 'moodle', ''),
(38, 'moodle_file_upload', 'moodle_file_external', 'upload', 'files/externallib.php', 'moodle', ''),
(39, 'core_files_upload', 'core_files_external', 'upload', 'files/externallib.php', 'moodle', ''),
(40, 'moodle_user_create_users', 'moodle_user_external', 'create_users', 'user/externallib.php', 'moodle', 'moodle/user:create'),
(41, 'core_user_create_users', 'core_user_external', 'create_users', 'user/externallib.php', 'moodle', 'moodle/user:create'),
(42, 'core_user_get_users', 'core_user_external', 'get_users', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update'),
(43, 'moodle_user_get_users_by_id', 'moodle_user_external', 'get_users_by_id', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update'),
(44, 'core_user_get_users_by_field', 'core_user_external', 'get_users_by_field', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update'),
(45, 'core_user_get_users_by_id', 'core_user_external', 'get_users_by_id', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update'),
(46, 'moodle_user_get_users_by_courseid', 'moodle_user_external', 'get_users_by_courseid', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update, moodle/site:accessallgroups'),
(47, 'moodle_user_get_course_participants_by_id', 'moodle_user_external', 'get_course_participants_by_id', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update, moodle/site:accessallgroups'),
(48, 'core_user_get_course_user_profiles', 'core_user_external', 'get_course_user_profiles', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update, moodle/site:accessallgroups'),
(49, 'moodle_user_delete_users', 'moodle_user_external', 'delete_users', 'user/externallib.php', 'moodle', 'moodle/user:delete'),
(50, 'core_user_delete_users', 'core_user_external', 'delete_users', 'user/externallib.php', 'moodle', 'moodle/user:delete'),
(51, 'moodle_user_update_users', 'moodle_user_external', 'update_users', 'user/externallib.php', 'moodle', 'moodle/user:update'),
(52, 'core_user_update_users', 'core_user_external', 'update_users', 'user/externallib.php', 'moodle', 'moodle/user:update'),
(53, 'core_user_add_user_device', 'core_user_external', 'add_user_device', 'user/externallib.php', 'moodle', ''),
(54, 'core_user_remove_user_device', 'core_user_external', 'remove_user_device', 'user/externallib.php', 'moodle', ''),
(55, 'core_user_view_user_list', 'core_user_external', 'view_user_list', 'user/externallib.php', 'moodle', 'moodle/course:viewparticipants'),
(56, 'core_user_view_user_profile', 'core_user_external', 'view_user_profile', 'user/externallib.php', 'moodle', 'moodle/user:viewdetails'),
(57, 'core_user_add_user_private_files', 'core_user_external', 'add_user_private_files', 'user/externallib.php', 'moodle', 'moodle/user:manageownfiles'),
(58, 'core_enrol_get_enrolled_users_with_capability', 'core_enrol_external', 'get_enrolled_users_with_capability', 'enrol/externallib.php', 'moodle', ''),
(59, 'moodle_enrol_get_enrolled_users', 'moodle_enrol_external', 'get_enrolled_users', 'enrol/externallib.php', 'moodle', 'moodle/site:viewparticipants, moodle/course:viewparticipants,\n            moodle/role:review, moodle/site:accessallgroups, moodle/course:enrolreview'),
(60, 'core_enrol_get_enrolled_users', 'core_enrol_external', 'get_enrolled_users', 'enrol/externallib.php', 'moodle', 'moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update, moodle/site:accessallgroups'),
(61, 'moodle_enrol_get_users_courses', 'moodle_enrol_external', 'get_users_courses', 'enrol/externallib.php', 'moodle', 'moodle/course:viewparticipants'),
(62, 'core_enrol_get_users_courses', 'core_enrol_external', 'get_users_courses', 'enrol/externallib.php', 'moodle', 'moodle/course:viewparticipants'),
(63, 'core_enrol_get_course_enrolment_methods', 'core_enrol_external', 'get_course_enrolment_methods', 'enrol/externallib.php', 'moodle', ''),
(64, 'moodle_role_assign', 'moodle_enrol_external', 'role_assign', 'enrol/externallib.php', 'moodle', 'moodle/role:assign'),
(65, 'core_role_assign_roles', 'core_role_external', 'assign_roles', 'enrol/externallib.php', 'moodle', 'moodle/role:assign'),
(66, 'moodle_role_unassign', 'moodle_enrol_external', 'role_unassign', 'enrol/externallib.php', 'moodle', 'moodle/role:assign'),
(67, 'core_role_unassign_roles', 'core_role_external', 'unassign_roles', 'enrol/externallib.php', 'moodle', 'moodle/role:assign'),
(68, 'core_course_get_contents', 'core_course_external', 'get_course_contents', 'course/externallib.php', 'moodle', 'moodle/course:update,moodle/course:viewhiddencourses'),
(69, 'moodle_course_get_courses', 'moodle_course_external', 'get_courses', 'course/externallib.php', 'moodle', 'moodle/course:view,moodle/course:update,moodle/course:viewhiddencourses'),
(70, 'core_course_get_courses', 'core_course_external', 'get_courses', 'course/externallib.php', 'moodle', 'moodle/course:view,moodle/course:update,moodle/course:viewhiddencourses'),
(71, 'core_course_search_courses', 'core_course_external', 'search_courses', 'course/externallib.php', 'moodle', ''),
(72, 'moodle_course_create_courses', 'moodle_course_external', 'create_courses', 'course/externallib.php', 'moodle', 'moodle/course:create,moodle/course:visibility'),
(73, 'core_course_create_courses', 'core_course_external', 'create_courses', 'course/externallib.php', 'moodle', 'moodle/course:create,moodle/course:visibility'),
(74, 'core_course_delete_courses', 'core_course_external', 'delete_courses', 'course/externallib.php', 'moodle', 'moodle/course:delete'),
(75, 'core_course_delete_modules', 'core_course_external', 'delete_modules', 'course/externallib.php', 'moodle', 'moodle/course:manageactivities'),
(76, 'core_course_duplicate_course', 'core_course_external', 'duplicate_course', 'course/externallib.php', 'moodle', 'moodle/backup:backupcourse,moodle/restore:restorecourse,moodle/course:create'),
(77, 'core_course_update_courses', 'core_course_external', 'update_courses', 'course/externallib.php', 'moodle', 'moodle/course:update,moodle/course:changecategory,moodle/course:changefullname,moodle/course:changeshortname,moodle/course:changeidnumber,moodle/course:changesummary,moodle/course:visibility'),
(78, 'core_course_view_course', 'core_course_external', 'view_course', 'course/externallib.php', 'moodle', ''),
(79, 'core_course_get_course_module', 'core_course_external', 'get_course_module', 'course/externallib.php', 'moodle', ''),
(80, 'core_course_get_course_module_by_instance', 'core_course_external', 'get_course_module_by_instance', 'course/externallib.php', 'moodle', ''),
(81, 'core_course_get_categories', 'core_course_external', 'get_categories', 'course/externallib.php', 'moodle', 'moodle/category:viewhiddencategories'),
(82, 'core_course_create_categories', 'core_course_external', 'create_categories', 'course/externallib.php', 'moodle', 'moodle/category:manage'),
(83, 'core_course_update_categories', 'core_course_external', 'update_categories', 'course/externallib.php', 'moodle', 'moodle/category:manage'),
(84, 'core_course_delete_categories', 'core_course_external', 'delete_categories', 'course/externallib.php', 'moodle', 'moodle/category:manage'),
(85, 'core_course_import_course', 'core_course_external', 'import_course', 'course/externallib.php', 'moodle', 'moodle/backup:backuptargetimport, moodle/restore:restoretargetimport'),
(86, 'moodle_message_send_instantmessages', 'moodle_message_external', 'send_instantmessages', 'message/externallib.php', 'moodle', 'moodle/site:sendmessage'),
(87, 'core_message_send_instant_messages', 'core_message_external', 'send_instant_messages', 'message/externallib.php', 'moodle', 'moodle/site:sendmessage'),
(88, 'core_message_create_contacts', 'core_message_external', 'create_contacts', 'message/externallib.php', 'moodle', ''),
(89, 'core_message_delete_contacts', 'core_message_external', 'delete_contacts', 'message/externallib.php', 'moodle', ''),
(90, 'core_message_block_contacts', 'core_message_external', 'block_contacts', 'message/externallib.php', 'moodle', ''),
(91, 'core_message_unblock_contacts', 'core_message_external', 'unblock_contacts', 'message/externallib.php', 'moodle', ''),
(92, 'core_message_get_contacts', 'core_message_external', 'get_contacts', 'message/externallib.php', 'moodle', ''),
(93, 'core_message_search_contacts', 'core_message_external', 'search_contacts', 'message/externallib.php', 'moodle', ''),
(94, 'core_message_get_messages', 'core_message_external', 'get_messages', 'message/externallib.php', 'moodle', ''),
(95, 'core_message_get_blocked_users', 'core_message_external', 'get_blocked_users', 'message/externallib.php', 'moodle', ''),
(96, 'core_message_mark_message_read', 'core_message_external', 'mark_message_read', 'message/externallib.php', 'moodle', ''),
(97, 'moodle_notes_create_notes', 'moodle_notes_external', 'create_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:manage'),
(98, 'core_notes_create_notes', 'core_notes_external', 'create_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:manage'),
(99, 'core_notes_delete_notes', 'core_notes_external', 'delete_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:manage'),
(100, 'core_notes_get_notes', 'core_notes_external', 'get_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:view'),
(101, 'core_notes_update_notes', 'core_notes_external', 'update_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:manage'),
(102, 'core_notes_view_notes', 'core_notes_external', 'view_notes', 'notes/externallib.php', 'moodle', 'moodle/notes:view'),
(103, 'core_grading_get_definitions', 'core_grading_external', 'get_definitions', NULL, 'moodle', ''),
(104, 'core_grade_get_definitions', 'core_grade_external', 'get_definitions', 'grade/externallib.php', 'moodle', ''),
(105, 'core_grading_save_definitions', 'core_grading_external', 'save_definitions', NULL, 'moodle', ''),
(106, 'core_grading_get_gradingform_instances', 'core_grading_external', 'get_gradingform_instances', NULL, 'moodle', ''),
(107, 'moodle_webservice_get_siteinfo', 'moodle_webservice_external', 'get_siteinfo', 'webservice/externallib.php', 'moodle', ''),
(108, 'core_webservice_get_site_info', 'core_webservice_external', 'get_site_info', 'webservice/externallib.php', 'moodle', ''),
(109, 'core_get_string', 'core_external', 'get_string', 'lib/external/externallib.php', 'moodle', ''),
(110, 'core_get_strings', 'core_external', 'get_strings', 'lib/external/externallib.php', 'moodle', ''),
(111, 'core_get_component_strings', 'core_external', 'get_component_strings', 'lib/external/externallib.php', 'moodle', ''),
(112, 'core_calendar_delete_calendar_events', 'core_calendar_external', 'delete_calendar_events', 'calendar/externallib.php', 'moodle', 'moodle/calendar:manageentries'),
(113, 'core_calendar_get_calendar_events', 'core_calendar_external', 'get_calendar_events', 'calendar/externallib.php', 'moodle', 'moodle/calendar:manageentries'),
(114, 'core_calendar_create_calendar_events', 'core_calendar_external', 'create_calendar_events', 'calendar/externallib.php', 'moodle', 'moodle/calendar:manageentries'),
(115, 'core_output_load_template', 'core\\output\\external', 'load_template', NULL, 'moodle', ''),
(116, 'core_completion_update_activity_completion_status_manually', 'core_completion_external', 'update_activity_completion_status_manually', NULL, 'moodle', ''),
(117, 'core_completion_mark_course_self_completed', 'core_completion_external', 'mark_course_self_completed', NULL, 'moodle', ''),
(118, 'core_completion_get_activities_completion_status', 'core_completion_external', 'get_activities_completion_status', NULL, 'moodle', ''),
(119, 'core_completion_get_course_completion_status', 'core_completion_external', 'get_course_completion_status', NULL, 'moodle', 'report/completion:view'),
(120, 'core_rating_get_item_ratings', 'core_rating_external', 'get_item_ratings', NULL, 'moodle', 'moodle/rating:view'),
(121, 'core_tag_update_tags', 'core_tag_external', 'update_tags', NULL, 'moodle', ''),
(122, 'core_tag_get_tags', 'core_tag_external', 'get_tags', NULL, 'moodle', ''),
(123, 'mod_assign_get_grades', 'mod_assign_external', 'get_grades', 'mod/assign/externallib.php', 'mod_assign', ''),
(124, 'mod_assign_get_assignments', 'mod_assign_external', 'get_assignments', 'mod/assign/externallib.php', 'mod_assign', ''),
(125, 'mod_assign_get_submissions', 'mod_assign_external', 'get_submissions', 'mod/assign/externallib.php', 'mod_assign', ''),
(126, 'mod_assign_get_user_flags', 'mod_assign_external', 'get_user_flags', 'mod/assign/externallib.php', 'mod_assign', ''),
(127, 'mod_assign_set_user_flags', 'mod_assign_external', 'set_user_flags', 'mod/assign/externallib.php', 'mod_assign', 'mod/assign:grade'),
(128, 'mod_assign_get_user_mappings', 'mod_assign_external', 'get_user_mappings', 'mod/assign/externallib.php', 'mod_assign', ''),
(129, 'mod_assign_revert_submissions_to_draft', 'mod_assign_external', 'revert_submissions_to_draft', 'mod/assign/externallib.php', 'mod_assign', ''),
(130, 'mod_assign_lock_submissions', 'mod_assign_external', 'lock_submissions', 'mod/assign/externallib.php', 'mod_assign', ''),
(131, 'mod_assign_unlock_submissions', 'mod_assign_external', 'unlock_submissions', 'mod/assign/externallib.php', 'mod_assign', ''),
(132, 'mod_assign_save_submission', 'mod_assign_external', 'save_submission', 'mod/assign/externallib.php', 'mod_assign', ''),
(133, 'mod_assign_submit_for_grading', 'mod_assign_external', 'submit_for_grading', 'mod/assign/externallib.php', 'mod_assign', ''),
(134, 'mod_assign_save_grade', 'mod_assign_external', 'save_grade', 'mod/assign/externallib.php', 'mod_assign', ''),
(135, 'mod_assign_save_grades', 'mod_assign_external', 'save_grades', 'mod/assign/externallib.php', 'mod_assign', ''),
(136, 'mod_assign_save_user_extensions', 'mod_assign_external', 'save_user_extensions', 'mod/assign/externallib.php', 'mod_assign', ''),
(137, 'mod_assign_reveal_identities', 'mod_assign_external', 'reveal_identities', 'mod/assign/externallib.php', 'mod_assign', ''),
(138, 'mod_assign_view_grading_table', 'mod_assign_external', 'view_grading_table', 'mod/assign/externallib.php', 'mod_assign', 'mod/assign:view, mod/assign:viewgrades'),
(139, 'mod_book_view_book', 'mod_book_external', 'view_book', NULL, 'mod_book', 'mod/book:read'),
(140, 'mod_book_get_books_by_courses', 'mod_book_external', 'get_books_by_courses', NULL, 'mod_book', ''),
(141, 'mod_chat_login_user', 'mod_chat_external', 'login_user', NULL, 'mod_chat', 'mod/chat:chat'),
(142, 'mod_chat_get_chat_users', 'mod_chat_external', 'get_chat_users', NULL, 'mod_chat', 'mod/chat:chat'),
(143, 'mod_chat_send_chat_message', 'mod_chat_external', 'send_chat_message', NULL, 'mod_chat', 'mod/chat:chat'),
(144, 'mod_chat_get_chat_latest_messages', 'mod_chat_external', 'get_chat_latest_messages', NULL, 'mod_chat', 'mod/chat:chat'),
(145, 'mod_chat_view_chat', 'mod_chat_external', 'view_chat', NULL, 'mod_chat', 'mod/chat:chat'),
(146, 'mod_chat_get_chats_by_courses', 'mod_chat_external', 'get_chats_by_courses', NULL, 'mod_chat', ''),
(147, 'mod_choice_get_choice_results', 'mod_choice_external', 'get_choice_results', NULL, 'mod_choice', ''),
(148, 'mod_choice_get_choice_options', 'mod_choice_external', 'get_choice_options', NULL, 'mod_choice', 'mod/choice:choose'),
(149, 'mod_choice_submit_choice_response', 'mod_choice_external', 'submit_choice_response', NULL, 'mod_choice', 'mod/choice:choose'),
(150, 'mod_choice_view_choice', 'mod_choice_external', 'view_choice', NULL, 'mod_choice', ''),
(151, 'mod_choice_get_choices_by_courses', 'mod_choice_external', 'get_choices_by_courses', NULL, 'mod_choice', ''),
(152, 'mod_choice_delete_choice_responses', 'mod_choice_external', 'delete_choice_responses', NULL, 'mod_choice', 'mod/choice:choose'),
(153, 'mod_data_get_databases_by_courses', 'mod_data_external', 'get_databases_by_courses', NULL, 'mod_data', 'mod/data:viewentry'),
(154, 'mod_folder_view_folder', 'mod_folder_external', 'view_folder', NULL, 'mod_folder', 'mod/folder:view'),
(155, 'mod_forum_get_forums_by_courses', 'mod_forum_external', 'get_forums_by_courses', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:viewdiscussion'),
(156, 'mod_forum_get_forum_discussions', 'mod_forum_external', 'get_forum_discussions', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:viewdiscussion, mod/forum:viewqandawithoutposting'),
(157, 'mod_forum_get_forum_discussion_posts', 'mod_forum_external', 'get_forum_discussion_posts', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:viewdiscussion, mod/forum:viewqandawithoutposting'),
(158, 'mod_forum_get_forum_discussions_paginated', 'mod_forum_external', 'get_forum_discussions_paginated', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:viewdiscussion, mod/forum:viewqandawithoutposting'),
(159, 'mod_forum_view_forum', 'mod_forum_external', 'view_forum', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:viewdiscussion'),
(160, 'mod_forum_view_forum_discussion', 'mod_forum_external', 'view_forum_discussion', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:viewdiscussion'),
(161, 'mod_forum_add_discussion_post', 'mod_forum_external', 'add_discussion_post', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:replypost'),
(162, 'mod_forum_add_discussion', 'mod_forum_external', 'add_discussion', 'mod/forum/externallib.php', 'mod_forum', 'mod/forum:startdiscussion'),
(163, 'mod_imscp_view_imscp', 'mod_imscp_external', 'view_imscp', NULL, 'mod_imscp', 'mod/imscp:view'),
(164, 'mod_imscp_get_imscps_by_courses', 'mod_imscp_external', 'get_imscps_by_courses', NULL, 'mod_imscp', 'moodle/imscp:view'),
(165, 'mod_lti_get_tool_launch_data', 'mod_lti_external', 'get_tool_launch_data', NULL, 'mod_lti', 'mod/lti:view'),
(166, 'mod_lti_get_ltis_by_courses', 'mod_lti_external', 'get_ltis_by_courses', NULL, 'mod_lti', 'mod/lti:view'),
(167, 'mod_lti_view_lti', 'mod_lti_external', 'view_lti', NULL, 'mod_lti', 'mod/lti:view'),
(168, 'mod_page_view_page', 'mod_page_external', 'view_page', NULL, 'mod_page', 'mod/page:view'),
(169, 'mod_resource_view_resource', 'mod_resource_external', 'view_resource', NULL, 'mod_resource', 'mod/resource:view'),
(170, 'mod_scorm_view_scorm', 'mod_scorm_external', 'view_scorm', NULL, 'mod_scorm', ''),
(171, 'mod_scorm_get_scorm_attempt_count', 'mod_scorm_external', 'get_scorm_attempt_count', NULL, 'mod_scorm', ''),
(172, 'mod_scorm_get_scorm_scoes', 'mod_scorm_external', 'get_scorm_scoes', NULL, 'mod_scorm', ''),
(173, 'mod_scorm_get_scorm_user_data', 'mod_scorm_external', 'get_scorm_user_data', NULL, 'mod_scorm', ''),
(174, 'mod_scorm_insert_scorm_tracks', 'mod_scorm_external', 'insert_scorm_tracks', NULL, 'mod_scorm', 'mod/scorm:savetrack'),
(175, 'mod_scorm_get_scorm_sco_tracks', 'mod_scorm_external', 'get_scorm_sco_tracks', NULL, 'mod_scorm', ''),
(176, 'mod_scorm_get_scorms_by_courses', 'mod_scorm_external', 'get_scorms_by_courses', NULL, 'mod_scorm', ''),
(177, 'mod_survey_get_surveys_by_courses', 'mod_survey_external', 'get_surveys_by_courses', NULL, 'mod_survey', ''),
(178, 'mod_survey_view_survey', 'mod_survey_external', 'view_survey', NULL, 'mod_survey', 'mod/survey:participate'),
(179, 'mod_survey_get_questions', 'mod_survey_external', 'get_questions', NULL, 'mod_survey', 'mod/survey:participate'),
(180, 'mod_survey_submit_answers', 'mod_survey_external', 'submit_answers', NULL, 'mod_survey', 'mod/survey:participate'),
(181, 'mod_url_view_url', 'mod_url_external', 'view_url', NULL, 'mod_url', 'mod/url:view'),
(182, 'moodle_enrol_manual_enrol_users', 'moodle_enrol_manual_external', 'manual_enrol_users', 'enrol/manual/externallib.php', 'enrol_manual', 'enrol/manual:enrol'),
(183, 'enrol_manual_enrol_users', 'enrol_manual_external', 'enrol_users', 'enrol/manual/externallib.php', 'enrol_manual', 'enrol/manual:enrol'),
(184, 'enrol_manual_unenrol_users', 'enrol_manual_external', 'unenrol_users', 'enrol/manual/externallib.php', 'enrol_manual', 'enrol/manual:unenrol'),
(185, 'enrol_self_get_instance_info', 'enrol_self_external', 'get_instance_info', 'enrol/self/externallib.php', 'enrol_self', ''),
(186, 'enrol_self_enrol_user', 'enrol_self_external', 'enrol_user', 'enrol/self/externallib.php', 'enrol_self', ''),
(187, 'message_airnotifier_is_system_configured', 'message_airnotifier_external', 'is_system_configured', 'message/output/airnotifier/externallib.php', 'message_airnotifier', ''),
(188, 'message_airnotifier_are_notification_preferences_configured', 'message_airnotifier_external', 'are_notification_preferences_configured', 'message/output/airnotifier/externallib.php', 'message_airnotifier', ''),
(189, 'gradereport_user_get_grades_table', 'gradereport_user_external', 'get_grades_table', 'grade/report/user/externallib.php', 'gradereport_user', 'gradereport/user:view'),
(190, 'gradereport_user_view_grade_report', 'gradereport_user_external', 'view_grade_report', 'grade/report/user/externallib.php', 'gradereport_user', 'gradereport/user:view'),
(191, 'tool_templatelibrary_list_templates', 'tool_templatelibrary\\external', 'list_templates', NULL, 'tool_templatelibrary', ''),
(192, 'tool_templatelibrary_load_canonical_template', 'tool_templatelibrary\\external', 'load_canonical_template', NULL, 'tool_templatelibrary', '');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_external_services`
--

CREATE TABLE IF NOT EXISTS `mdl_external_services` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL,
  `requiredcapability` varchar(150) DEFAULT NULL,
  `restrictedusers` tinyint(1) NOT NULL,
  `component` varchar(100) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `shortname` varchar(255) DEFAULT NULL,
  `downloadfiles` tinyint(1) NOT NULL DEFAULT '0',
  `uploadfiles` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_exteserv_nam_uix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='built in and custom external services' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mdl_external_services`
--

INSERT INTO `mdl_external_services` (`id`, `name`, `enabled`, `requiredcapability`, `restrictedusers`, `component`, `timecreated`, `timemodified`, `shortname`, `downloadfiles`, `uploadfiles`) VALUES
(1, 'Moodle mobile web service', 0, NULL, 0, 'moodle', 1451552897, 1451553064, 'moodle_mobile_app', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_external_services_functions`
--

CREATE TABLE IF NOT EXISTS `mdl_external_services_functions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `externalserviceid` bigint(10) NOT NULL,
  `functionname` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_exteservfunc_ext_ix` (`externalserviceid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='lists functions available in each service group' AUTO_INCREMENT=115 ;

--
-- Dumping data for table `mdl_external_services_functions`
--

INSERT INTO `mdl_external_services_functions` (`id`, `externalserviceid`, `functionname`) VALUES
(1, 1, 'moodle_enrol_get_users_courses'),
(2, 1, 'moodle_enrol_get_enrolled_users'),
(3, 1, 'moodle_user_get_users_by_id'),
(4, 1, 'moodle_webservice_get_siteinfo'),
(5, 1, 'moodle_notes_create_notes'),
(6, 1, 'moodle_user_get_course_participants_by_id'),
(7, 1, 'moodle_user_get_users_by_courseid'),
(8, 1, 'moodle_message_send_instantmessages'),
(9, 1, 'core_course_get_contents'),
(10, 1, 'core_get_component_strings'),
(11, 1, 'core_user_add_user_device'),
(12, 1, 'core_calendar_get_calendar_events'),
(13, 1, 'core_enrol_get_users_courses'),
(14, 1, 'core_enrol_get_enrolled_users'),
(15, 1, 'core_enrol_get_course_enrolment_methods'),
(16, 1, 'enrol_self_enrol_user'),
(17, 1, 'core_user_get_users_by_id'),
(18, 1, 'core_webservice_get_site_info'),
(19, 1, 'core_notes_create_notes'),
(20, 1, 'core_user_get_course_user_profiles'),
(21, 1, 'core_message_send_instant_messages'),
(22, 1, 'mod_assign_get_grades'),
(23, 1, 'mod_assign_get_assignments'),
(24, 1, 'mod_assign_get_submissions'),
(25, 1, 'mod_assign_get_user_flags'),
(26, 1, 'mod_assign_set_user_flags'),
(27, 1, 'mod_assign_get_user_mappings'),
(28, 1, 'mod_assign_revert_submissions_to_draft'),
(29, 1, 'mod_assign_lock_submissions'),
(30, 1, 'mod_assign_unlock_submissions'),
(31, 1, 'mod_assign_save_submission'),
(32, 1, 'mod_assign_submit_for_grading'),
(33, 1, 'mod_assign_save_grade'),
(34, 1, 'mod_assign_save_user_extensions'),
(35, 1, 'mod_assign_reveal_identities'),
(36, 1, 'message_airnotifier_is_system_configured'),
(37, 1, 'message_airnotifier_are_notification_preferences_configured'),
(38, 1, 'core_grades_update_grades'),
(39, 1, 'mod_forum_get_forums_by_courses'),
(40, 1, 'mod_forum_get_forum_discussions_paginated'),
(41, 1, 'mod_forum_get_forum_discussion_posts'),
(42, 1, 'mod_forum_add_discussion_post'),
(43, 1, 'mod_forum_add_discussion'),
(44, 1, 'core_files_get_files'),
(45, 1, 'core_message_get_messages'),
(46, 1, 'core_message_create_contacts'),
(47, 1, 'core_message_delete_contacts'),
(48, 1, 'core_message_block_contacts'),
(49, 1, 'core_message_unblock_contacts'),
(50, 1, 'core_message_get_contacts'),
(51, 1, 'core_message_search_contacts'),
(52, 1, 'core_message_get_blocked_users'),
(53, 1, 'gradereport_user_get_grades_table'),
(54, 1, 'core_group_get_course_user_groups'),
(55, 1, 'core_group_get_activity_allowed_groups'),
(56, 1, 'core_group_get_activity_groupmode'),
(57, 1, 'core_user_remove_user_device'),
(58, 1, 'core_course_get_courses'),
(59, 1, 'core_completion_update_activity_completion_status_manually'),
(60, 1, 'core_completion_mark_course_self_completed'),
(61, 1, 'mod_data_get_databases_by_courses'),
(62, 1, 'core_comment_get_comments'),
(63, 1, 'mod_forum_view_forum'),
(64, 1, 'core_course_view_course'),
(65, 1, 'core_course_search_courses'),
(66, 1, 'core_course_get_course_module'),
(67, 1, 'core_course_get_course_module_by_instance'),
(68, 1, 'core_completion_get_activities_completion_status'),
(69, 1, 'core_notes_get_course_notes'),
(70, 1, 'core_completion_get_course_completion_status'),
(71, 1, 'core_user_view_user_list'),
(72, 1, 'core_message_mark_message_read'),
(73, 1, 'core_notes_view_notes'),
(74, 1, 'mod_forum_view_forum_discussion'),
(75, 1, 'core_user_view_user_profile'),
(76, 1, 'gradereport_user_view_grade_report'),
(77, 1, 'core_rating_get_item_ratings'),
(78, 1, 'mod_url_view_url'),
(79, 1, 'core_user_get_users_by_field'),
(80, 1, 'core_user_add_user_private_files'),
(81, 1, 'mod_assign_view_grading_table'),
(82, 1, 'mod_scorm_view_scorm'),
(83, 1, 'mod_scorm_get_scorm_scoes'),
(84, 1, 'mod_scorm_get_scorm_user_data'),
(85, 1, 'mod_scorm_insert_scorm_tracks'),
(86, 1, 'mod_scorm_get_scorm_sco_tracks'),
(87, 1, 'mod_scorm_get_scorm_attempt_count'),
(88, 1, 'mod_scorm_get_scorms_by_courses'),
(89, 1, 'mod_survey_get_surveys_by_courses'),
(90, 1, 'mod_survey_view_survey'),
(91, 1, 'mod_survey_get_questions'),
(92, 1, 'mod_survey_submit_answers'),
(93, 1, 'mod_page_view_page'),
(94, 1, 'mod_resource_view_resource'),
(95, 1, 'mod_folder_view_folder'),
(96, 1, 'mod_chat_login_user'),
(97, 1, 'mod_chat_get_chat_users'),
(98, 1, 'mod_chat_send_chat_message'),
(99, 1, 'mod_chat_get_chat_latest_messages'),
(100, 1, 'mod_chat_view_chat'),
(101, 1, 'mod_chat_get_chats_by_courses'),
(102, 1, 'mod_book_view_book'),
(103, 1, 'mod_book_get_books_by_courses'),
(104, 1, 'mod_choice_get_choice_results'),
(105, 1, 'mod_choice_get_choice_options'),
(106, 1, 'mod_choice_submit_choice_response'),
(107, 1, 'mod_choice_view_choice'),
(108, 1, 'mod_choice_get_choices_by_courses'),
(109, 1, 'mod_choice_delete_choice_responses'),
(110, 1, 'mod_lti_get_tool_launch_data'),
(111, 1, 'mod_lti_get_ltis_by_courses'),
(112, 1, 'mod_lti_view_lti'),
(113, 1, 'mod_imscp_view_imscp'),
(114, 1, 'mod_imscp_get_imscps_by_courses');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_external_services_users`
--

CREATE TABLE IF NOT EXISTS `mdl_external_services_users` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `externalserviceid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `iprestriction` varchar(255) DEFAULT NULL,
  `validuntil` bigint(10) DEFAULT NULL,
  `timecreated` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_exteservuser_ext_ix` (`externalserviceid`),
  KEY `mdl_exteservuser_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='users allowed to use services with restricted users flag' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_external_tokens`
--

CREATE TABLE IF NOT EXISTS `mdl_external_tokens` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(128) NOT NULL DEFAULT '',
  `tokentype` smallint(4) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `externalserviceid` bigint(10) NOT NULL,
  `sid` varchar(128) DEFAULT NULL,
  `contextid` bigint(10) NOT NULL,
  `creatorid` bigint(10) NOT NULL DEFAULT '1',
  `iprestriction` varchar(255) DEFAULT NULL,
  `validuntil` bigint(10) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  `lastaccess` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_extetoke_use_ix` (`userid`),
  KEY `mdl_extetoke_ext_ix` (`externalserviceid`),
  KEY `mdl_extetoke_con_ix` (`contextid`),
  KEY `mdl_extetoke_cre_ix` (`creatorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Security tokens for accessing of external services' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_faq`
--

CREATE TABLE IF NOT EXISTS `mdl_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mdl_faq`
--

INSERT INTO `mdl_faq` (`id`, `content`) VALUES
(1, '<p>Q: What is Office 365 University?<br />\nA: Office 365 University is a four-year subscription for full- and part-time enrolled university and college students, faculty, and staff in accredited institutions. Office 365 University includes the latest Office applications, which you can install on 2 PCs, Macs, or tablets, plus 2 phones. You&rsquo;ll also get 1TB of OneDrive cloud storage and 60 Skype minutes.</p>\n\n<p>Q: Who is eligible for Office 365 University?<br />\nA: Office 365 University is available to full- and part-time enrolled students, faculty, and staff of qualified, accredited higher education institutions. Alumni of these institutions are not eligible. Qualified, accredited higher education institutions include universities, polytechnic schools, and institutes of technology, as well as other tertiary-level institutions, such as colleges and vocational schools that award academic degrees or professional certifications. Restrictions may vary by country. Find out if you&#39;re eligible.</p>\n\n<p>Q: How many licenses/installations does Office 365 University allow?<br />\nA: Office 365 University subscriptions do not auto-renew. When your Office 365 University subscription is in the last year, you may renew your subscription for another 4 years if you are still eligible. You can only renew your subscription one time. To renew your subscription, visit www.microsoftstore.com and purchase another Office 365 University subscription. You will be required to go through the same student status eligibility check as the first time you purchased. Important note: At checkout, use the same Microsoft account you used when setting up your original subscription. Any time you have remaining will be automatically added to your existing subscription. If you use a different Microsoft account, you will be creating a new subscription.</p>\n');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `anonymous` tinyint(1) NOT NULL DEFAULT '1',
  `email_notification` tinyint(1) NOT NULL DEFAULT '1',
  `multiple_submit` tinyint(1) NOT NULL DEFAULT '1',
  `autonumbering` tinyint(1) NOT NULL DEFAULT '1',
  `site_after_submit` varchar(255) NOT NULL DEFAULT '',
  `page_after_submit` longtext NOT NULL,
  `page_after_submitformat` tinyint(2) NOT NULL DEFAULT '0',
  `publish_stats` tinyint(1) NOT NULL DEFAULT '0',
  `timeopen` bigint(10) NOT NULL DEFAULT '0',
  `timeclose` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `completionsubmit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_feed_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='all feedbacks' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_completed`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_completed` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `feedback` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `random_response` bigint(10) NOT NULL DEFAULT '0',
  `anonymous_response` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_feedcomp_use_ix` (`userid`),
  KEY `mdl_feedcomp_fee_ix` (`feedback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='filled out feedback' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_completedtmp`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_completedtmp` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `feedback` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `guestid` varchar(255) NOT NULL DEFAULT '',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `random_response` bigint(10) NOT NULL DEFAULT '0',
  `anonymous_response` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_feedcomp_use2_ix` (`userid`),
  KEY `mdl_feedcomp_fee2_ix` (`feedback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='filled out feedback' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_item`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_item` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `feedback` bigint(10) NOT NULL DEFAULT '0',
  `template` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `presentation` longtext NOT NULL,
  `typ` varchar(255) NOT NULL DEFAULT '',
  `hasvalue` tinyint(1) NOT NULL DEFAULT '0',
  `position` smallint(3) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `dependitem` bigint(10) NOT NULL DEFAULT '0',
  `dependvalue` varchar(255) NOT NULL DEFAULT '',
  `options` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_feeditem_fee_ix` (`feedback`),
  KEY `mdl_feeditem_tem_ix` (`template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='feedback_items' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_sitecourse_map`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_sitecourse_map` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `feedbackid` bigint(10) NOT NULL DEFAULT '0',
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_feedsitemap_cou_ix` (`courseid`),
  KEY `mdl_feedsitemap_fee_ix` (`feedbackid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='feedback sitecourse map' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_template`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_template` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `ispublic` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_feedtemp_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='templates of feedbackstructures' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_tracking`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_tracking` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `feedback` bigint(10) NOT NULL DEFAULT '0',
  `completed` bigint(10) NOT NULL DEFAULT '0',
  `tmp_completed` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_feedtrac_use_ix` (`userid`),
  KEY `mdl_feedtrac_fee_ix` (`feedback`),
  KEY `mdl_feedtrac_com_ix` (`completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='feedback trackingdata' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_value`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_value` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(10) NOT NULL DEFAULT '0',
  `item` bigint(10) NOT NULL DEFAULT '0',
  `completed` bigint(10) NOT NULL DEFAULT '0',
  `tmp_completed` bigint(10) NOT NULL DEFAULT '0',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_feedvalu_cou_ix` (`course_id`),
  KEY `mdl_feedvalu_ite_ix` (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='values of the completeds' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_feedback_valuetmp`
--

CREATE TABLE IF NOT EXISTS `mdl_feedback_valuetmp` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(10) NOT NULL DEFAULT '0',
  `item` bigint(10) NOT NULL DEFAULT '0',
  `completed` bigint(10) NOT NULL DEFAULT '0',
  `tmp_completed` bigint(10) NOT NULL DEFAULT '0',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_feedvalu_cou2_ix` (`course_id`),
  KEY `mdl_feedvalu_ite2_ix` (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='values of the completedstmp' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_files`
--

CREATE TABLE IF NOT EXISTS `mdl_files` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contenthash` varchar(40) NOT NULL DEFAULT '',
  `pathnamehash` varchar(40) NOT NULL DEFAULT '',
  `contextid` bigint(10) NOT NULL,
  `component` varchar(100) NOT NULL DEFAULT '',
  `filearea` varchar(50) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL,
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `userid` bigint(10) DEFAULT NULL,
  `filesize` bigint(10) NOT NULL,
  `mimetype` varchar(100) DEFAULT NULL,
  `status` bigint(10) NOT NULL DEFAULT '0',
  `source` longtext,
  `author` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `referencefileid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_file_pat_uix` (`pathnamehash`),
  KEY `mdl_file_comfilconite_ix` (`component`,`filearea`,`contextid`,`itemid`),
  KEY `mdl_file_con_ix` (`contenthash`),
  KEY `mdl_file_con2_ix` (`contextid`),
  KEY `mdl_file_use_ix` (`userid`),
  KEY `mdl_file_ref_ix` (`referencefileid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='description of files, content is stored in sha1 file pool' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mdl_files`
--

INSERT INTO `mdl_files` (`id`, `contenthash`, `pathnamehash`, `contextid`, `component`, `filearea`, `itemid`, `filepath`, `filename`, `userid`, `filesize`, `mimetype`, `status`, `source`, `author`, `license`, `timecreated`, `timemodified`, `sortorder`, `referencefileid`) VALUES
(1, '41cfeee5884a43a4650a851f4f85e7b28316fcc9', 'a48e186a2cc853a9e94e9305f4e9bc086391212d', 1, 'theme_more', 'backgroundimage', 0, '/', 'background.jpg', 2, 4451, 'image/jpeg', 0, NULL, NULL, NULL, 1451552937, 1451552937, 0, NULL),
(2, 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'd1da7ab1bb9c08a926037367bf8ce9a838034ead', 1, 'theme_more', 'backgroundimage', 0, '/', '.', 2, 0, NULL, 0, NULL, NULL, NULL, 1451552937, 1451552937, 0, NULL),
(3, 'fb262df98d67c4e2a5c9802403821e00cf2992af', '508e674d49c30d4fde325fe6c7f6fd3d56b247e1', 1, 'assignfeedback_editpdf', 'stamps', 0, '/', 'smile.png', 2, 1600, 'image/png', 0, NULL, NULL, NULL, 1451552938, 1451552938, 0, NULL),
(4, 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '70b7cdade7b4e27d4e83f0cdaad10d6a3c0cccb5', 1, 'assignfeedback_editpdf', 'stamps', 0, '/', '.', 2, 0, NULL, 0, NULL, NULL, NULL, 1451552938, 1451552938, 0, NULL),
(5, 'a4f146f120e7e00d21291b924e26aaabe9f4297a', '68317eab56c67d32aeaee5acf509a0c4aa828b6b', 1, 'assignfeedback_editpdf', 'stamps', 0, '/', 'sad.png', 2, 1702, 'image/png', 0, NULL, NULL, NULL, 1451552938, 1451552938, 0, NULL),
(6, '33957e31ba9c763a74638b825f0a9154acf475e1', '695a55ff780e61c9e59428aa425430b0d6bde53b', 1, 'assignfeedback_editpdf', 'stamps', 0, '/', 'tick.png', 2, 1187, 'image/png', 0, NULL, NULL, NULL, 1451552938, 1451552938, 0, NULL),
(7, 'd613d55f37bb76d38d4ffb4b7b83e6c694778c30', '373e63af262a9b8466ba8632551520be793c37ff', 1, 'assignfeedback_editpdf', 'stamps', 0, '/', 'cross.png', 2, 1230, 'image/png', 0, NULL, NULL, NULL, 1451552938, 1451552938, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_files_reference`
--

CREATE TABLE IF NOT EXISTS `mdl_files_reference` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `repositoryid` bigint(10) NOT NULL,
  `lastsync` bigint(10) DEFAULT NULL,
  `reference` longtext,
  `referencehash` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_filerefe_refrep_uix` (`referencehash`,`repositoryid`),
  KEY `mdl_filerefe_rep_ix` (`repositoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store files references' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_filter_active`
--

CREATE TABLE IF NOT EXISTS `mdl_filter_active` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `filter` varchar(32) NOT NULL DEFAULT '',
  `contextid` bigint(10) NOT NULL,
  `active` smallint(4) NOT NULL,
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_filtacti_confil_uix` (`contextid`,`filter`),
  KEY `mdl_filtacti_con_ix` (`contextid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Stores information about which filters are active in which c' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mdl_filter_active`
--

INSERT INTO `mdl_filter_active` (`id`, `filter`, `contextid`, `active`, `sortorder`) VALUES
(1, 'activitynames', 1, 1, 2),
(2, 'mathjaxloader', 1, 1, 1),
(3, 'mediaplugin', 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_filter_config`
--

CREATE TABLE IF NOT EXISTS `mdl_filter_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `filter` varchar(32) NOT NULL DEFAULT '',
  `contextid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_filtconf_confilnam_uix` (`contextid`,`filter`,`name`),
  KEY `mdl_filtconf_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores per-context configuration settings for filters which ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_folder`
--

CREATE TABLE IF NOT EXISTS `mdl_folder` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `revision` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `display` smallint(4) NOT NULL DEFAULT '0',
  `showexpanded` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_fold_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='each record is one folder resource' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum`
--

CREATE TABLE IF NOT EXISTS `mdl_forum` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT 'general',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `assessed` bigint(10) NOT NULL DEFAULT '0',
  `assesstimestart` bigint(10) NOT NULL DEFAULT '0',
  `assesstimefinish` bigint(10) NOT NULL DEFAULT '0',
  `scale` bigint(10) NOT NULL DEFAULT '0',
  `maxbytes` bigint(10) NOT NULL DEFAULT '0',
  `maxattachments` bigint(10) NOT NULL DEFAULT '1',
  `forcesubscribe` tinyint(1) NOT NULL DEFAULT '0',
  `trackingtype` tinyint(2) NOT NULL DEFAULT '1',
  `rsstype` tinyint(2) NOT NULL DEFAULT '0',
  `rssarticles` tinyint(2) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `warnafter` bigint(10) NOT NULL DEFAULT '0',
  `blockafter` bigint(10) NOT NULL DEFAULT '0',
  `blockperiod` bigint(10) NOT NULL DEFAULT '0',
  `completiondiscussions` int(9) NOT NULL DEFAULT '0',
  `completionreplies` int(9) NOT NULL DEFAULT '0',
  `completionposts` int(9) NOT NULL DEFAULT '0',
  `displaywordcount` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_foru_cou_ix` (`course`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Forums contain and structure discussion' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mdl_forum`
--

INSERT INTO `mdl_forum` (`id`, `course`, `type`, `name`, `intro`, `introformat`, `assessed`, `assesstimestart`, `assesstimefinish`, `scale`, `maxbytes`, `maxattachments`, `forcesubscribe`, `trackingtype`, `rsstype`, `rssarticles`, `timemodified`, `warnafter`, `blockafter`, `blockperiod`, `completiondiscussions`, `completionreplies`, `completionposts`, `displaywordcount`) VALUES
(1, 5, 'news', 'News forum', 'General news and announcements', 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 1453387795, 0, 0, 0, 0, 0, 0, 0),
(2, 4, 'news', 'News forum', 'General news and announcements', 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 1453448062, 0, 0, 0, 0, 0, 0, 0),
(3, 6, 'news', 'News forum', 'General news and announcements', 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 1453448250, 0, 0, 0, 0, 0, 0, 0),
(4, 3, 'news', 'News forum', 'General news and announcements', 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 1453448359, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_digests`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_digests` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `forum` bigint(10) NOT NULL,
  `maildigest` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_forudige_forusemai_uix` (`forum`,`userid`,`maildigest`),
  KEY `mdl_forudige_use_ix` (`userid`),
  KEY `mdl_forudige_for_ix` (`forum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Keeps track of user mail delivery preferences for each forum' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_discussions`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_discussions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `forum` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `firstpost` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '-1',
  `assessed` tinyint(1) NOT NULL DEFAULT '1',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `usermodified` bigint(10) NOT NULL DEFAULT '0',
  `timestart` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_forudisc_use_ix` (`userid`),
  KEY `mdl_forudisc_cou_ix` (`course`),
  KEY `mdl_forudisc_for_ix` (`forum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Forums are composed of discussions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_discussion_subs`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_discussion_subs` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `forum` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `discussion` bigint(10) NOT NULL,
  `preference` bigint(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_forudiscsubs_usedis_uix` (`userid`,`discussion`),
  KEY `mdl_forudiscsubs_for_ix` (`forum`),
  KEY `mdl_forudiscsubs_use_ix` (`userid`),
  KEY `mdl_forudiscsubs_dis_ix` (`discussion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Users may choose to subscribe and unsubscribe from specific ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_posts`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_posts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `discussion` bigint(10) NOT NULL DEFAULT '0',
  `parent` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `created` bigint(10) NOT NULL DEFAULT '0',
  `modified` bigint(10) NOT NULL DEFAULT '0',
  `mailed` tinyint(2) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` longtext NOT NULL,
  `messageformat` tinyint(2) NOT NULL DEFAULT '0',
  `messagetrust` tinyint(2) NOT NULL DEFAULT '0',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `totalscore` smallint(4) NOT NULL DEFAULT '0',
  `mailnow` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_forupost_use_ix` (`userid`),
  KEY `mdl_forupost_cre_ix` (`created`),
  KEY `mdl_forupost_mai_ix` (`mailed`),
  KEY `mdl_forupost_dis_ix` (`discussion`),
  KEY `mdl_forupost_par_ix` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='All posts are stored in this table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_queue`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_queue` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `discussionid` bigint(10) NOT NULL DEFAULT '0',
  `postid` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_foruqueu_use_ix` (`userid`),
  KEY `mdl_foruqueu_dis_ix` (`discussionid`),
  KEY `mdl_foruqueu_pos_ix` (`postid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='For keeping track of posts that will be mailed in digest for' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_read`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_read` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `forumid` bigint(10) NOT NULL DEFAULT '0',
  `discussionid` bigint(10) NOT NULL DEFAULT '0',
  `postid` bigint(10) NOT NULL DEFAULT '0',
  `firstread` bigint(10) NOT NULL DEFAULT '0',
  `lastread` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_foruread_usefor_ix` (`userid`,`forumid`),
  KEY `mdl_foruread_usedis_ix` (`userid`,`discussionid`),
  KEY `mdl_foruread_posuse_ix` (`postid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tracks each users read posts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_subscriptions`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_subscriptions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `forum` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_forusubs_use_ix` (`userid`),
  KEY `mdl_forusubs_for_ix` (`forum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Keeps track of who is subscribed to what forum' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_forum_track_prefs`
--

CREATE TABLE IF NOT EXISTS `mdl_forum_track_prefs` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `forumid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_forutracpref_usefor_ix` (`userid`,`forumid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tracks each users untracked forums' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gallery`
--

CREATE TABLE IF NOT EXISTS `mdl_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_path` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `date_added` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_glossary`
--

CREATE TABLE IF NOT EXISTS `mdl_glossary` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `allowduplicatedentries` tinyint(2) NOT NULL DEFAULT '0',
  `displayformat` varchar(50) NOT NULL DEFAULT 'dictionary',
  `mainglossary` tinyint(2) NOT NULL DEFAULT '0',
  `showspecial` tinyint(2) NOT NULL DEFAULT '1',
  `showalphabet` tinyint(2) NOT NULL DEFAULT '1',
  `showall` tinyint(2) NOT NULL DEFAULT '1',
  `allowcomments` tinyint(2) NOT NULL DEFAULT '0',
  `allowprintview` tinyint(2) NOT NULL DEFAULT '1',
  `usedynalink` tinyint(2) NOT NULL DEFAULT '1',
  `defaultapproval` tinyint(2) NOT NULL DEFAULT '1',
  `approvaldisplayformat` varchar(50) NOT NULL DEFAULT 'default',
  `globalglossary` tinyint(2) NOT NULL DEFAULT '0',
  `entbypage` smallint(3) NOT NULL DEFAULT '10',
  `editalways` tinyint(2) NOT NULL DEFAULT '0',
  `rsstype` tinyint(2) NOT NULL DEFAULT '0',
  `rssarticles` tinyint(2) NOT NULL DEFAULT '0',
  `assessed` bigint(10) NOT NULL DEFAULT '0',
  `assesstimestart` bigint(10) NOT NULL DEFAULT '0',
  `assesstimefinish` bigint(10) NOT NULL DEFAULT '0',
  `scale` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `completionentries` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_glos_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='all glossaries' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_glossary_alias`
--

CREATE TABLE IF NOT EXISTS `mdl_glossary_alias` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `entryid` bigint(10) NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_glosalia_ent_ix` (`entryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='entries alias' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_glossary_categories`
--

CREATE TABLE IF NOT EXISTS `mdl_glossary_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `glossaryid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `usedynalink` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_gloscate_glo_ix` (`glossaryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='all categories for glossary entries' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_glossary_entries`
--

CREATE TABLE IF NOT EXISTS `mdl_glossary_entries` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `glossaryid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `concept` varchar(255) NOT NULL DEFAULT '',
  `definition` longtext NOT NULL,
  `definitionformat` tinyint(2) NOT NULL DEFAULT '0',
  `definitiontrust` tinyint(2) NOT NULL DEFAULT '0',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `teacherentry` tinyint(2) NOT NULL DEFAULT '0',
  `sourceglossaryid` bigint(10) NOT NULL DEFAULT '0',
  `usedynalink` tinyint(2) NOT NULL DEFAULT '1',
  `casesensitive` tinyint(2) NOT NULL DEFAULT '0',
  `fullmatch` tinyint(2) NOT NULL DEFAULT '1',
  `approved` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_glosentr_use_ix` (`userid`),
  KEY `mdl_glosentr_con_ix` (`concept`),
  KEY `mdl_glosentr_glo_ix` (`glossaryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='all glossary entries' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_glossary_entries_categories`
--

CREATE TABLE IF NOT EXISTS `mdl_glossary_entries_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `categoryid` bigint(10) NOT NULL DEFAULT '0',
  `entryid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_glosentrcate_cat_ix` (`categoryid`),
  KEY `mdl_glosentrcate_ent_ix` (`entryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='categories of each glossary entry' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_glossary_formats`
--

CREATE TABLE IF NOT EXISTS `mdl_glossary_formats` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `popupformatname` varchar(50) NOT NULL DEFAULT '',
  `visible` tinyint(2) NOT NULL DEFAULT '1',
  `showgroup` tinyint(2) NOT NULL DEFAULT '1',
  `showtabs` varchar(100) DEFAULT NULL,
  `defaultmode` varchar(50) NOT NULL DEFAULT '',
  `defaulthook` varchar(50) NOT NULL DEFAULT '',
  `sortkey` varchar(50) NOT NULL DEFAULT '',
  `sortorder` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Setting of the display formats' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mdl_glossary_formats`
--

INSERT INTO `mdl_glossary_formats` (`id`, `name`, `popupformatname`, `visible`, `showgroup`, `showtabs`, `defaultmode`, `defaulthook`, `sortkey`, `sortorder`) VALUES
(1, 'continuous', 'continuous', 1, 1, 'standard,category,date', '', '', '', ''),
(2, 'dictionary', 'dictionary', 1, 1, 'standard', '', '', '', ''),
(3, 'encyclopedia', 'encyclopedia', 1, 1, 'standard,category,date,author', '', '', '', ''),
(4, 'entrylist', 'entrylist', 1, 1, 'standard,category,date,author', '', '', '', ''),
(5, 'faq', 'faq', 1, 1, 'standard,category,date,author', '', '', '', ''),
(6, 'fullwithauthor', 'fullwithauthor', 1, 1, 'standard,category,date,author', '', '', '', ''),
(7, 'fullwithoutauthor', 'fullwithoutauthor', 1, 1, 'standard,category,date', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_categories`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `parent` bigint(10) DEFAULT NULL,
  `depth` bigint(10) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL DEFAULT '',
  `aggregation` bigint(10) NOT NULL DEFAULT '0',
  `keephigh` bigint(10) NOT NULL DEFAULT '0',
  `droplow` bigint(10) NOT NULL DEFAULT '0',
  `aggregateonlygraded` tinyint(1) NOT NULL DEFAULT '0',
  `aggregateoutcomes` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_gradcate_cou_ix` (`courseid`),
  KEY `mdl_gradcate_par_ix` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table keeps information about categories, used for grou' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_categories_history`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_categories_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `action` bigint(10) NOT NULL DEFAULT '0',
  `oldid` bigint(10) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `loggeduser` bigint(10) DEFAULT NULL,
  `courseid` bigint(10) NOT NULL,
  `parent` bigint(10) DEFAULT NULL,
  `depth` bigint(10) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL DEFAULT '',
  `aggregation` bigint(10) NOT NULL DEFAULT '0',
  `keephigh` bigint(10) NOT NULL DEFAULT '0',
  `droplow` bigint(10) NOT NULL DEFAULT '0',
  `aggregateonlygraded` tinyint(1) NOT NULL DEFAULT '0',
  `aggregateoutcomes` tinyint(1) NOT NULL DEFAULT '0',
  `aggregatesubcats` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_gradcatehist_act_ix` (`action`),
  KEY `mdl_gradcatehist_old_ix` (`oldid`),
  KEY `mdl_gradcatehist_cou_ix` (`courseid`),
  KEY `mdl_gradcatehist_par_ix` (`parent`),
  KEY `mdl_gradcatehist_log_ix` (`loggeduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='History of grade_categories' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_grades`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_grades` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `itemid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `rawgrade` decimal(10,5) DEFAULT NULL,
  `rawgrademax` decimal(10,5) NOT NULL DEFAULT '100.00000',
  `rawgrademin` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rawscaleid` bigint(10) DEFAULT NULL,
  `usermodified` bigint(10) DEFAULT NULL,
  `finalgrade` decimal(10,5) DEFAULT NULL,
  `hidden` bigint(10) NOT NULL DEFAULT '0',
  `locked` bigint(10) NOT NULL DEFAULT '0',
  `locktime` bigint(10) NOT NULL DEFAULT '0',
  `exported` bigint(10) NOT NULL DEFAULT '0',
  `overridden` bigint(10) NOT NULL DEFAULT '0',
  `excluded` bigint(10) NOT NULL DEFAULT '0',
  `feedback` longtext,
  `feedbackformat` bigint(10) NOT NULL DEFAULT '0',
  `information` longtext,
  `informationformat` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `aggregationstatus` varchar(10) NOT NULL DEFAULT 'unknown',
  `aggregationweight` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradgrad_useite_uix` (`userid`,`itemid`),
  KEY `mdl_gradgrad_locloc_ix` (`locked`,`locktime`),
  KEY `mdl_gradgrad_ite_ix` (`itemid`),
  KEY `mdl_gradgrad_use_ix` (`userid`),
  KEY `mdl_gradgrad_raw_ix` (`rawscaleid`),
  KEY `mdl_gradgrad_use2_ix` (`usermodified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='grade_grades  This table keeps individual grades for each us' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_grades_history`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_grades_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `action` bigint(10) NOT NULL DEFAULT '0',
  `oldid` bigint(10) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `loggeduser` bigint(10) DEFAULT NULL,
  `itemid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `rawgrade` decimal(10,5) DEFAULT NULL,
  `rawgrademax` decimal(10,5) NOT NULL DEFAULT '100.00000',
  `rawgrademin` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rawscaleid` bigint(10) DEFAULT NULL,
  `usermodified` bigint(10) DEFAULT NULL,
  `finalgrade` decimal(10,5) DEFAULT NULL,
  `hidden` bigint(10) NOT NULL DEFAULT '0',
  `locked` bigint(10) NOT NULL DEFAULT '0',
  `locktime` bigint(10) NOT NULL DEFAULT '0',
  `exported` bigint(10) NOT NULL DEFAULT '0',
  `overridden` bigint(10) NOT NULL DEFAULT '0',
  `excluded` bigint(10) NOT NULL DEFAULT '0',
  `feedback` longtext,
  `feedbackformat` bigint(10) NOT NULL DEFAULT '0',
  `information` longtext,
  `informationformat` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_gradgradhist_act_ix` (`action`),
  KEY `mdl_gradgradhist_tim_ix` (`timemodified`),
  KEY `mdl_gradgradhist_old_ix` (`oldid`),
  KEY `mdl_gradgradhist_ite_ix` (`itemid`),
  KEY `mdl_gradgradhist_use_ix` (`userid`),
  KEY `mdl_gradgradhist_raw_ix` (`rawscaleid`),
  KEY `mdl_gradgradhist_use2_ix` (`usermodified`),
  KEY `mdl_gradgradhist_log_ix` (`loggeduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='History table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_import_newitem`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_import_newitem` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(255) NOT NULL DEFAULT '',
  `importcode` bigint(10) NOT NULL,
  `importer` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_gradimponewi_imp_ix` (`importer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='temporary table for storing new grade_item names from grade ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_import_values`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_import_values` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `itemid` bigint(10) DEFAULT NULL,
  `newgradeitem` bigint(10) DEFAULT NULL,
  `userid` bigint(10) NOT NULL,
  `finalgrade` decimal(10,5) DEFAULT NULL,
  `feedback` longtext,
  `importcode` bigint(10) NOT NULL,
  `importer` bigint(10) DEFAULT NULL,
  `importonlyfeedback` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_gradimpovalu_ite_ix` (`itemid`),
  KEY `mdl_gradimpovalu_new_ix` (`newgradeitem`),
  KEY `mdl_gradimpovalu_imp_ix` (`importer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Temporary table for importing grades' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_items`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_items` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) DEFAULT NULL,
  `categoryid` bigint(10) DEFAULT NULL,
  `itemname` varchar(255) DEFAULT NULL,
  `itemtype` varchar(30) NOT NULL DEFAULT '',
  `itemmodule` varchar(30) DEFAULT NULL,
  `iteminstance` bigint(10) DEFAULT NULL,
  `itemnumber` bigint(10) DEFAULT NULL,
  `iteminfo` longtext,
  `idnumber` varchar(255) DEFAULT NULL,
  `calculation` longtext,
  `gradetype` smallint(4) NOT NULL DEFAULT '1',
  `grademax` decimal(10,5) NOT NULL DEFAULT '100.00000',
  `grademin` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `scaleid` bigint(10) DEFAULT NULL,
  `outcomeid` bigint(10) DEFAULT NULL,
  `gradepass` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `multfactor` decimal(10,5) NOT NULL DEFAULT '1.00000',
  `plusfactor` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `aggregationcoef` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `aggregationcoef2` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `display` bigint(10) NOT NULL DEFAULT '0',
  `decimals` tinyint(1) DEFAULT NULL,
  `hidden` bigint(10) NOT NULL DEFAULT '0',
  `locked` bigint(10) NOT NULL DEFAULT '0',
  `locktime` bigint(10) NOT NULL DEFAULT '0',
  `needsupdate` bigint(10) NOT NULL DEFAULT '0',
  `weightoverride` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_graditem_locloc_ix` (`locked`,`locktime`),
  KEY `mdl_graditem_itenee_ix` (`itemtype`,`needsupdate`),
  KEY `mdl_graditem_gra_ix` (`gradetype`),
  KEY `mdl_graditem_idncou_ix` (`idnumber`,`courseid`),
  KEY `mdl_graditem_cou_ix` (`courseid`),
  KEY `mdl_graditem_cat_ix` (`categoryid`),
  KEY `mdl_graditem_sca_ix` (`scaleid`),
  KEY `mdl_graditem_out_ix` (`outcomeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table keeps information about gradeable items (ie colum' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_items_history`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_items_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `action` bigint(10) NOT NULL DEFAULT '0',
  `oldid` bigint(10) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `loggeduser` bigint(10) DEFAULT NULL,
  `courseid` bigint(10) DEFAULT NULL,
  `categoryid` bigint(10) DEFAULT NULL,
  `itemname` varchar(255) DEFAULT NULL,
  `itemtype` varchar(30) NOT NULL DEFAULT '',
  `itemmodule` varchar(30) DEFAULT NULL,
  `iteminstance` bigint(10) DEFAULT NULL,
  `itemnumber` bigint(10) DEFAULT NULL,
  `iteminfo` longtext,
  `idnumber` varchar(255) DEFAULT NULL,
  `calculation` longtext,
  `gradetype` smallint(4) NOT NULL DEFAULT '1',
  `grademax` decimal(10,5) NOT NULL DEFAULT '100.00000',
  `grademin` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `scaleid` bigint(10) DEFAULT NULL,
  `outcomeid` bigint(10) DEFAULT NULL,
  `gradepass` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `multfactor` decimal(10,5) NOT NULL DEFAULT '1.00000',
  `plusfactor` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `aggregationcoef` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `aggregationcoef2` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `hidden` bigint(10) NOT NULL DEFAULT '0',
  `locked` bigint(10) NOT NULL DEFAULT '0',
  `locktime` bigint(10) NOT NULL DEFAULT '0',
  `needsupdate` bigint(10) NOT NULL DEFAULT '0',
  `display` bigint(10) NOT NULL DEFAULT '0',
  `decimals` tinyint(1) DEFAULT NULL,
  `weightoverride` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_graditemhist_act_ix` (`action`),
  KEY `mdl_graditemhist_old_ix` (`oldid`),
  KEY `mdl_graditemhist_cou_ix` (`courseid`),
  KEY `mdl_graditemhist_cat_ix` (`categoryid`),
  KEY `mdl_graditemhist_sca_ix` (`scaleid`),
  KEY `mdl_graditemhist_out_ix` (`outcomeid`),
  KEY `mdl_graditemhist_log_ix` (`loggeduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='History of grade_items' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_letters`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_letters` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `lowerboundary` decimal(10,5) NOT NULL,
  `letter` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradlett_conlowlet_uix` (`contextid`,`lowerboundary`,`letter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Repository for grade letters, for courses and other moodle e' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_outcomes`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_outcomes` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) DEFAULT NULL,
  `shortname` varchar(255) NOT NULL DEFAULT '',
  `fullname` longtext NOT NULL,
  `scaleid` bigint(10) DEFAULT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `usermodified` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradoutc_cousho_uix` (`courseid`,`shortname`),
  KEY `mdl_gradoutc_cou_ix` (`courseid`),
  KEY `mdl_gradoutc_sca_ix` (`scaleid`),
  KEY `mdl_gradoutc_use_ix` (`usermodified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table describes the outcomes used in the system. An out' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_outcomes_courses`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_outcomes_courses` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `outcomeid` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradoutccour_couout_uix` (`courseid`,`outcomeid`),
  KEY `mdl_gradoutccour_cou_ix` (`courseid`),
  KEY `mdl_gradoutccour_out_ix` (`outcomeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='stores what outcomes are used in what courses.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_outcomes_history`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_outcomes_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `action` bigint(10) NOT NULL DEFAULT '0',
  `oldid` bigint(10) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `loggeduser` bigint(10) DEFAULT NULL,
  `courseid` bigint(10) DEFAULT NULL,
  `shortname` varchar(255) NOT NULL DEFAULT '',
  `fullname` longtext NOT NULL,
  `scaleid` bigint(10) DEFAULT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_gradoutchist_act_ix` (`action`),
  KEY `mdl_gradoutchist_old_ix` (`oldid`),
  KEY `mdl_gradoutchist_cou_ix` (`courseid`),
  KEY `mdl_gradoutchist_sca_ix` (`scaleid`),
  KEY `mdl_gradoutchist_log_ix` (`loggeduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='History table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grade_settings`
--

CREATE TABLE IF NOT EXISTS `mdl_grade_settings` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradsett_counam_uix` (`courseid`,`name`),
  KEY `mdl_gradsett_cou_ix` (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='gradebook settings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gradingform_guide_comments`
--

CREATE TABLE IF NOT EXISTS `mdl_gradingform_guide_comments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `definitionid` bigint(10) NOT NULL,
  `sortorder` bigint(10) NOT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_gradguidcomm_def_ix` (`definitionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='frequently used comments used in marking guide' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gradingform_guide_criteria`
--

CREATE TABLE IF NOT EXISTS `mdl_gradingform_guide_criteria` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `definitionid` bigint(10) NOT NULL,
  `sortorder` bigint(10) NOT NULL,
  `shortname` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `descriptionformat` tinyint(2) DEFAULT NULL,
  `descriptionmarkers` longtext,
  `descriptionmarkersformat` tinyint(2) DEFAULT NULL,
  `maxscore` decimal(10,5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_gradguidcrit_def_ix` (`definitionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the rows of the criteria grid.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gradingform_guide_fillings`
--

CREATE TABLE IF NOT EXISTS `mdl_gradingform_guide_fillings` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `instanceid` bigint(10) NOT NULL,
  `criterionid` bigint(10) NOT NULL,
  `remark` longtext,
  `remarkformat` tinyint(2) DEFAULT NULL,
  `score` decimal(10,5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradguidfill_inscri_uix` (`instanceid`,`criterionid`),
  KEY `mdl_gradguidfill_ins_ix` (`instanceid`),
  KEY `mdl_gradguidfill_cri_ix` (`criterionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the data of how the guide is filled by a particular r' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gradingform_rubric_criteria`
--

CREATE TABLE IF NOT EXISTS `mdl_gradingform_rubric_criteria` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `definitionid` bigint(10) NOT NULL,
  `sortorder` bigint(10) NOT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_gradrubrcrit_def_ix` (`definitionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the rows of the rubric grid.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gradingform_rubric_fillings`
--

CREATE TABLE IF NOT EXISTS `mdl_gradingform_rubric_fillings` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `instanceid` bigint(10) NOT NULL,
  `criterionid` bigint(10) NOT NULL,
  `levelid` bigint(10) DEFAULT NULL,
  `remark` longtext,
  `remarkformat` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradrubrfill_inscri_uix` (`instanceid`,`criterionid`),
  KEY `mdl_gradrubrfill_lev_ix` (`levelid`),
  KEY `mdl_gradrubrfill_ins_ix` (`instanceid`),
  KEY `mdl_gradrubrfill_cri_ix` (`criterionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the data of how the rubric is filled by a particular ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_gradingform_rubric_levels`
--

CREATE TABLE IF NOT EXISTS `mdl_gradingform_rubric_levels` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `criterionid` bigint(10) NOT NULL,
  `score` decimal(10,5) NOT NULL,
  `definition` longtext,
  `definitionformat` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_gradrubrleve_cri_ix` (`criterionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the columns of the rubric grid.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grading_areas`
--

CREATE TABLE IF NOT EXISTS `mdl_grading_areas` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `component` varchar(100) NOT NULL DEFAULT '',
  `areaname` varchar(100) NOT NULL DEFAULT '',
  `activemethod` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_gradarea_concomare_uix` (`contextid`,`component`,`areaname`),
  KEY `mdl_gradarea_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Identifies gradable areas where advanced grading can happen.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grading_definitions`
--

CREATE TABLE IF NOT EXISTS `mdl_grading_definitions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `areaid` bigint(10) NOT NULL,
  `method` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `descriptionformat` tinyint(2) DEFAULT NULL,
  `status` bigint(10) NOT NULL DEFAULT '0',
  `copiedfromid` bigint(10) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  `usercreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `usermodified` bigint(10) NOT NULL,
  `timecopied` bigint(10) DEFAULT '0',
  `options` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_graddefi_aremet_uix` (`areaid`,`method`),
  KEY `mdl_graddefi_are_ix` (`areaid`),
  KEY `mdl_graddefi_use_ix` (`usermodified`),
  KEY `mdl_graddefi_use2_ix` (`usercreated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains the basic information about an advanced grading for' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_grading_instances`
--

CREATE TABLE IF NOT EXISTS `mdl_grading_instances` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `definitionid` bigint(10) NOT NULL,
  `raterid` bigint(10) NOT NULL,
  `itemid` bigint(10) DEFAULT NULL,
  `rawgrade` decimal(10,5) DEFAULT NULL,
  `status` bigint(10) NOT NULL DEFAULT '0',
  `feedback` longtext,
  `feedbackformat` tinyint(2) DEFAULT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_gradinst_def_ix` (`definitionid`),
  KEY `mdl_gradinst_rat_ix` (`raterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Grading form instance is an assessment record for one gradab' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_groupings`
--

CREATE TABLE IF NOT EXISTS `mdl_groupings` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `idnumber` varchar(100) NOT NULL DEFAULT '',
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `configdata` longtext,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_grou_idn2_ix` (`idnumber`),
  KEY `mdl_grou_cou2_ix` (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='A grouping is a collection of groups. WAS: groups_groupings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_groupings_groups`
--

CREATE TABLE IF NOT EXISTS `mdl_groupings_groups` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `groupingid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `timeadded` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_grougrou_gro_ix` (`groupingid`),
  KEY `mdl_grougrou_gro2_ix` (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Link a grouping to a group (note, groups can be in multiple ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_groups`
--

CREATE TABLE IF NOT EXISTS `mdl_groups` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `idnumber` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(254) NOT NULL DEFAULT '',
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `enrolmentkey` varchar(50) DEFAULT NULL,
  `picture` bigint(10) NOT NULL DEFAULT '0',
  `hidepicture` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_grou_idn_ix` (`idnumber`),
  KEY `mdl_grou_cou_ix` (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each record represents a group.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_groups_members`
--

CREATE TABLE IF NOT EXISTS `mdl_groups_members` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timeadded` bigint(10) NOT NULL DEFAULT '0',
  `component` varchar(100) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_groumemb_gro_ix` (`groupid`),
  KEY `mdl_groumemb_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Link a user to a group.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_group_discount`
--

CREATE TABLE IF NOT EXISTS `mdl_group_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL,
  `groupid` int(11) DEFAULT '0',
  `tot_participants` int(11) NOT NULL DEFAULT '0',
  `group_discount_status` int(11) NOT NULL DEFAULT '0',
  `group_discount_size` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `mdl_group_discount`
--

INSERT INTO `mdl_group_discount` (`id`, `courseid`, `groupid`, `tot_participants`, `group_discount_status`, `group_discount_size`) VALUES
(1, 2, 0, 0, 0, 0),
(2, 3, 0, 0, 0, 7),
(3, 4, 0, 0, 0, 9),
(4, 5, 0, 0, 0, 0),
(5, 6, 0, 0, 0, 10),
(6, 12, 0, 0, 0, 0),
(7, 13, 0, 0, 0, 0),
(8, 7, 0, 0, 0, 15),
(9, 8, 0, 0, 0, 10),
(10, 9, 0, 0, 0, 10),
(11, 10, 0, 0, 0, 12),
(12, 11, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_imscp`
--

CREATE TABLE IF NOT EXISTS `mdl_imscp` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `revision` bigint(10) NOT NULL DEFAULT '0',
  `keepold` bigint(10) NOT NULL DEFAULT '-1',
  `structure` longtext,
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_imsc_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='each record is one imscp resource' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_label`
--

CREATE TABLE IF NOT EXISTS `mdl_label` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_labe_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines labels' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `practice` smallint(3) NOT NULL DEFAULT '0',
  `modattempts` smallint(3) NOT NULL DEFAULT '0',
  `usepassword` smallint(3) NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL DEFAULT '',
  `dependency` bigint(10) NOT NULL DEFAULT '0',
  `conditions` longtext NOT NULL,
  `grade` bigint(10) NOT NULL DEFAULT '0',
  `custom` smallint(3) NOT NULL DEFAULT '0',
  `ongoing` smallint(3) NOT NULL DEFAULT '0',
  `usemaxgrade` smallint(3) NOT NULL DEFAULT '0',
  `maxanswers` smallint(3) NOT NULL DEFAULT '4',
  `maxattempts` smallint(3) NOT NULL DEFAULT '5',
  `review` smallint(3) NOT NULL DEFAULT '0',
  `nextpagedefault` smallint(3) NOT NULL DEFAULT '0',
  `feedback` smallint(3) NOT NULL DEFAULT '1',
  `minquestions` smallint(3) NOT NULL DEFAULT '0',
  `maxpages` smallint(3) NOT NULL DEFAULT '0',
  `timelimit` bigint(10) NOT NULL DEFAULT '0',
  `retake` smallint(3) NOT NULL DEFAULT '1',
  `activitylink` bigint(10) NOT NULL DEFAULT '0',
  `mediafile` varchar(255) NOT NULL DEFAULT '',
  `mediaheight` bigint(10) NOT NULL DEFAULT '100',
  `mediawidth` bigint(10) NOT NULL DEFAULT '650',
  `mediaclose` smallint(3) NOT NULL DEFAULT '0',
  `slideshow` smallint(3) NOT NULL DEFAULT '0',
  `width` bigint(10) NOT NULL DEFAULT '640',
  `height` bigint(10) NOT NULL DEFAULT '480',
  `bgcolor` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  `displayleft` smallint(3) NOT NULL DEFAULT '0',
  `displayleftif` smallint(3) NOT NULL DEFAULT '0',
  `progressbar` smallint(3) NOT NULL DEFAULT '0',
  `available` bigint(10) NOT NULL DEFAULT '0',
  `deadline` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `completionendreached` tinyint(1) DEFAULT '0',
  `completiontimespent` bigint(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_less_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines lesson' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_answers`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `pageid` bigint(10) NOT NULL DEFAULT '0',
  `jumpto` bigint(11) NOT NULL DEFAULT '0',
  `grade` smallint(4) NOT NULL DEFAULT '0',
  `score` bigint(10) NOT NULL DEFAULT '0',
  `flags` smallint(3) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `answer` longtext,
  `answerformat` tinyint(2) NOT NULL DEFAULT '0',
  `response` longtext,
  `responseformat` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_lessansw_les_ix` (`lessonid`),
  KEY `mdl_lessansw_pag_ix` (`pageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines lesson_answers' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_attempts`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_attempts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `pageid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `answerid` bigint(10) NOT NULL DEFAULT '0',
  `retry` smallint(3) NOT NULL DEFAULT '0',
  `correct` bigint(10) NOT NULL DEFAULT '0',
  `useranswer` longtext,
  `timeseen` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_lessatte_use_ix` (`userid`),
  KEY `mdl_lessatte_les_ix` (`lessonid`),
  KEY `mdl_lessatte_pag_ix` (`pageid`),
  KEY `mdl_lessatte_ans_ix` (`answerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines lesson_attempts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_branch`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_branch` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `pageid` bigint(10) NOT NULL DEFAULT '0',
  `retry` bigint(10) NOT NULL DEFAULT '0',
  `flag` smallint(3) NOT NULL DEFAULT '0',
  `timeseen` bigint(10) NOT NULL DEFAULT '0',
  `nextpageid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_lessbran_use_ix` (`userid`),
  KEY `mdl_lessbran_les_ix` (`lessonid`),
  KEY `mdl_lessbran_pag_ix` (`pageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='branches for each lesson/user' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_grades`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_grades` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `grade` double NOT NULL DEFAULT '0',
  `late` smallint(3) NOT NULL DEFAULT '0',
  `completed` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_lessgrad_use_ix` (`userid`),
  KEY `mdl_lessgrad_les_ix` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines lesson_grades' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_overrides`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_overrides` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) DEFAULT NULL,
  `userid` bigint(10) DEFAULT NULL,
  `available` bigint(10) DEFAULT NULL,
  `deadline` bigint(10) DEFAULT NULL,
  `timelimit` bigint(10) DEFAULT NULL,
  `review` smallint(3) DEFAULT NULL,
  `maxattempts` smallint(3) DEFAULT NULL,
  `retake` smallint(3) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_lessover_les_ix` (`lessonid`),
  KEY `mdl_lessover_gro_ix` (`groupid`),
  KEY `mdl_lessover_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The overrides to lesson settings.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_pages`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_pages` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `prevpageid` bigint(10) NOT NULL DEFAULT '0',
  `nextpageid` bigint(10) NOT NULL DEFAULT '0',
  `qtype` smallint(3) NOT NULL DEFAULT '0',
  `qoption` smallint(3) NOT NULL DEFAULT '0',
  `layout` smallint(3) NOT NULL DEFAULT '1',
  `display` smallint(3) NOT NULL DEFAULT '1',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `contents` longtext NOT NULL,
  `contentsformat` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_lesspage_les_ix` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines lesson_pages' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lesson_timer`
--

CREATE TABLE IF NOT EXISTS `mdl_lesson_timer` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lessonid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `starttime` bigint(10) NOT NULL DEFAULT '0',
  `lessontime` bigint(10) NOT NULL DEFAULT '0',
  `completed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_lesstime_use_ix` (`userid`),
  KEY `mdl_lesstime_les_ix` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='lesson timer for each lesson' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_license`
--

CREATE TABLE IF NOT EXISTS `mdl_license` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(255) DEFAULT NULL,
  `fullname` longtext,
  `source` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `version` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='store licenses used by moodle' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `mdl_license`
--

INSERT INTO `mdl_license` (`id`, `shortname`, `fullname`, `source`, `enabled`, `version`) VALUES
(1, 'unknown', 'Unknown license', '', 1, 2010033100),
(2, 'allrightsreserved', 'All rights reserved', 'http://en.wikipedia.org/wiki/All_rights_reserved', 1, 2010033100),
(3, 'public', 'Public Domain', 'http://creativecommons.org/licenses/publicdomain/', 1, 2010033100),
(4, 'cc', 'Creative Commons', 'http://creativecommons.org/licenses/by/3.0/', 1, 2010033100),
(5, 'cc-nd', 'Creative Commons - NoDerivs', 'http://creativecommons.org/licenses/by-nd/3.0/', 1, 2010033100),
(6, 'cc-nc-nd', 'Creative Commons - No Commercial NoDerivs', 'http://creativecommons.org/licenses/by-nc-nd/3.0/', 1, 2010033100),
(7, 'cc-nc', 'Creative Commons - No Commercial', 'http://creativecommons.org/licenses/by-nc/3.0/', 1, 2013051500),
(8, 'cc-nc-sa', 'Creative Commons - No Commercial ShareAlike', 'http://creativecommons.org/licenses/by-nc-sa/3.0/', 1, 2010033100),
(9, 'cc-sa', 'Creative Commons - ShareAlike', 'http://creativecommons.org/licenses/by-sa/3.0/', 1, 2010033100);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lock_db`
--

CREATE TABLE IF NOT EXISTS `mdl_lock_db` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `resourcekey` varchar(255) NOT NULL DEFAULT '',
  `expires` bigint(10) DEFAULT NULL,
  `owner` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_lockdb_res_uix` (`resourcekey`),
  KEY `mdl_lockdb_exp_ix` (`expires`),
  KEY `mdl_lockdb_own_ix` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores active and inactive lock types for db locking method.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_log`
--

CREATE TABLE IF NOT EXISTS `mdl_log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `time` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL DEFAULT '',
  `course` bigint(10) NOT NULL DEFAULT '0',
  `module` varchar(20) NOT NULL DEFAULT '',
  `cmid` bigint(10) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `info` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_log_coumodact_ix` (`course`,`module`,`action`),
  KEY `mdl_log_tim_ix` (`time`),
  KEY `mdl_log_act_ix` (`action`),
  KEY `mdl_log_usecou_ix` (`userid`,`course`),
  KEY `mdl_log_cmi_ix` (`cmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Every action is logged as far as possible' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_logstore_standard_log`
--

CREATE TABLE IF NOT EXISTS `mdl_logstore_standard_log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `eventname` varchar(255) NOT NULL DEFAULT '',
  `component` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(100) NOT NULL DEFAULT '',
  `target` varchar(100) NOT NULL DEFAULT '',
  `objecttable` varchar(50) DEFAULT NULL,
  `objectid` bigint(10) DEFAULT NULL,
  `crud` varchar(1) NOT NULL DEFAULT '',
  `edulevel` tinyint(1) NOT NULL,
  `contextid` bigint(10) NOT NULL,
  `contextlevel` bigint(10) NOT NULL,
  `contextinstanceid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `courseid` bigint(10) DEFAULT NULL,
  `relateduserid` bigint(10) DEFAULT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `other` longtext,
  `timecreated` bigint(10) NOT NULL,
  `origin` varchar(10) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `realuserid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_logsstanlog_tim_ix` (`timecreated`),
  KEY `mdl_logsstanlog_couanotim_ix` (`courseid`,`anonymous`,`timecreated`),
  KEY `mdl_logsstanlog_useconconcr_ix` (`userid`,`contextlevel`,`contextinstanceid`,`crud`,`edulevel`,`timecreated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Standard log table' AUTO_INCREMENT=425 ;

--
-- Dumping data for table `mdl_logstore_standard_log`
--

INSERT INTO `mdl_logstore_standard_log` (`id`, `eventname`, `component`, `action`, `target`, `objecttable`, `objectid`, `crud`, `edulevel`, `contextid`, `contextlevel`, `contextinstanceid`, `userid`, `courseid`, `relateduserid`, `anonymous`, `other`, `timecreated`, `origin`, `ip`, `realuserid`) VALUES
(1, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1451552995, 'web', '109.87.201.130', NULL),
(2, '\\core\\event\\user_password_updated', 'core', 'updated', 'user_password', NULL, NULL, 'u', 0, 5, 30, 2, 2, 0, 2, 0, 'a:1:{s:14:"forgottenreset";b:0;}', 1451553059, 'web', '109.87.201.130', NULL),
(3, '\\core\\event\\user_updated', 'core', 'updated', 'user', 'user', 2, 'u', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1451553059, 'web', '109.87.201.130', NULL),
(4, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"89f93c7162d876f33120a8c2ae94c445";}', 1451553164, 'web', '109.87.201.130', NULL),
(5, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451553165, 'web', '109.87.201.130', NULL),
(6, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451892102, 'web', '109.87.201.130', NULL),
(7, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1451892640, 'web', '109.87.201.130', NULL),
(8, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"1ee1a630058dddf70fd0a9c1ebe9b2ed";}', 1451892845, 'web', '109.87.201.130', NULL),
(9, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451892845, 'web', '109.87.201.130', NULL),
(10, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451892899, 'web', '109.87.201.130', NULL),
(11, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451893402, 'web', '109.87.201.130', NULL),
(12, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451893607, 'web', '109.87.201.130', NULL),
(13, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451893613, 'web', '109.87.201.130', NULL),
(14, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451895843, 'web', '109.87.201.130', NULL),
(15, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451897221, 'web', '109.87.201.130', NULL),
(16, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1451907315, 'web', '109.87.201.130', NULL),
(17, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452668708, 'web', '109.87.201.130', NULL),
(18, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452669910, 'web', '109.87.201.130', NULL),
(19, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452671426, 'web', '109.87.201.130', NULL),
(20, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452671437, 'web', '109.87.201.130', NULL),
(21, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8792d2c9679376e58179be6e8f1e9d42";}', 1452671706, 'web', '109.87.201.130', NULL),
(22, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452671707, 'web', '109.87.201.130', NULL),
(23, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452672534, 'web', '109.87.201.130', NULL),
(24, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452672558, 'web', '109.87.201.130', NULL),
(25, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452673460, 'web', '109.87.201.130', NULL),
(26, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"7919d3fc45661229667fe38265e2e2d0";}', 1452673495, 'web', '109.87.201.130', NULL),
(27, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452673496, 'web', '109.87.201.130', NULL),
(28, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452673682, 'web', '109.87.201.130', NULL),
(29, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"3c567443658f09f2531dee9c32f442da";}', 1452673706, 'web', '109.87.201.130', NULL),
(30, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452673851, 'web', '109.87.201.130', NULL),
(31, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1452674046, 'web', '109.87.201.130', NULL),
(32, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674066, 'web', '109.87.201.130', NULL),
(33, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674082, 'web', '109.87.201.130', NULL),
(34, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674148, 'web', '109.87.201.130', NULL),
(35, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674186, 'web', '109.87.201.130', NULL),
(36, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674273, 'web', '109.87.201.130', NULL),
(37, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"551c89b82d4f9a592b95fff0f86c4746";}', 1452674887, 'web', '109.87.201.130', NULL),
(38, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674895, 'web', '109.87.201.130', NULL),
(39, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"3094cf19b7049e0865c770c42db5605f";}', 1452674908, 'web', '109.87.201.130', NULL),
(40, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1452674914, 'web', '109.87.201.130', NULL),
(41, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452674922, 'web', '109.87.201.130', NULL),
(42, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"f5bda9a7d423619c46251db59727a418";}', 1452675845, 'web', '109.87.201.130', NULL),
(43, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452675854, 'web', '109.87.201.130', NULL),
(44, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"ed25a294deac533a14f8949e0bcc2875";}', 1452676625, 'web', '109.87.201.130', NULL),
(45, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452676645, 'web', '109.87.201.130', NULL),
(46, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"0d6527f1439cad46f5c7d19d43053d31";}', 1452680475, 'web', '109.87.201.130', NULL),
(47, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452680507, 'web', '109.87.201.130', NULL),
(48, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452691697, 'web', '109.87.201.130', NULL),
(49, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452693487, 'web', '109.87.201.130', NULL),
(50, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8c640213d1c123c4cdc06bccc810c5b4";}', 1452693495, 'web', '109.87.201.130', NULL),
(51, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1452719020, 'web', '109.87.201.130', NULL),
(52, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"47f8ef4699880fb219e3c4a092d2004a";}', 1452719041, 'web', '109.87.201.130', NULL),
(53, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453186882, 'web', '109.87.201.130', NULL),
(54, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"fcb23c7c21d0801758cdb40df3d98778";}', 1453187049, 'web', '109.87.201.130', NULL),
(55, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453187055, 'web', '109.87.201.130', NULL),
(56, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453187071, 'web', '109.87.201.130', NULL),
(57, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453199761, 'web', '109.87.201.130', NULL),
(58, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453199802, 'web', '109.87.201.130', NULL),
(59, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453201065, 'web', '109.87.201.130', NULL),
(60, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453201082, 'web', '109.87.201.130', NULL),
(61, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"19a947cce7f0fe34d9f384251ad53b52";}', 1453201712, 'web', '109.87.201.130', NULL),
(62, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453201724, 'web', '109.87.201.130', NULL),
(63, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453201730, 'web', '109.87.201.130', NULL),
(64, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453201757, 'web', '109.87.201.130', NULL),
(65, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"9d7d1358a3627772a7d4a06ba2fed512";}', 1453201772, 'web', '109.87.201.130', NULL),
(66, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453283231, 'web', '69.30.214.46', NULL),
(67, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453284854, 'web', '109.87.201.130', NULL),
(68, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453286183, 'web', '109.87.201.130', NULL),
(69, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453287406, 'web', '109.87.201.130', NULL),
(70, '\\core\\event\\user_created', 'core', 'created', 'user', 'user', 3, 'c', 0, 23, 30, 3, 2, 0, 3, 0, 'N;', 1453291188, 'web', '109.87.201.130', NULL),
(71, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"b4e0eebcaf74b668448c4760f858e523";}', 1453291340, 'web', '109.87.201.130', NULL),
(72, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:8:"username";s:7:"manager";}', 1453291361, 'web', '109.87.201.130', NULL),
(73, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 3, 1, NULL, 0, 'N;', 1453292411, 'web', '109.87.201.130', NULL),
(74, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"757be35336bb9607fcfd8a1f0e8b2881";}', 1453297806, 'web', '109.87.201.130', NULL),
(75, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:8:"username";s:7:"manager";}', 1453297817, 'web', '109.87.201.130', NULL),
(76, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453297908, 'web', '109.87.201.130', NULL),
(77, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453297938, 'web', '109.87.201.130', NULL),
(78, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453297990, 'web', '109.87.201.130', NULL),
(79, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453298023, 'web', '109.87.201.130', NULL),
(80, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453298027, 'web', '109.87.201.130', NULL),
(81, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"1413ee8fcdaf47a341ec792aee43e7a7";}', 1453298035, 'web', '109.87.201.130', NULL),
(82, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:8:"username";s:7:"manager";}', 1453298043, 'web', '109.87.201.130', NULL),
(83, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453298085, 'web', '109.87.201.130', NULL),
(84, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"a3c17c28d369958b09c89cda33d6fd98";}', 1453298108, 'web', '109.87.201.130', NULL),
(85, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453299389, 'web', '109.87.201.130', NULL),
(86, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453314663, 'web', '109.87.201.130', NULL),
(87, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453327781, 'web', '23.95.5.34', NULL),
(88, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:8:"username";s:7:"manager";}', 1453362521, 'web', '109.87.201.130', NULL),
(89, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453362527, 'web', '109.87.201.130', NULL),
(90, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453362558, 'web', '109.87.201.130', NULL),
(91, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453362590, 'web', '109.87.201.130', NULL),
(92, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453362624, 'web', '109.87.201.130', NULL),
(93, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 3, 'r', 0, 23, 30, 3, 3, 0, 3, 0, 'N;', 1453362642, 'web', '109.87.201.130', NULL),
(94, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 3, 1, NULL, 0, 'N;', 1453362662, 'web', '109.87.201.130', NULL),
(95, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:8:"username";s:7:"manager";}', 1453362673, 'web', '109.87.201.130', NULL),
(96, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 3, 'r', 0, 1, 10, 0, 3, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"e07f8f828d31cecf20064d6cc1e6b534";}', 1453362691, 'web', '109.87.201.130', NULL),
(97, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1453362702, 'web', '109.87.201.130', NULL),
(98, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453362719, 'web', '109.87.201.130', NULL),
(99, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453371258, 'web', '109.87.201.130', NULL),
(100, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453371539, 'web', '109.87.201.130', NULL),
(101, '\\core\\event\\course_category_created', 'core', 'created', 'course_category', 'course_categories', 2, 'c', 0, 30, 40, 2, 2, 0, NULL, 0, 'N;', 1453371729, 'web', '109.87.201.130', NULL),
(102, '\\core\\event\\course_category_created', 'core', 'created', 'course_category', 'course_categories', 3, 'c', 0, 31, 40, 3, 2, 0, NULL, 0, 'N;', 1453371776, 'web', '109.87.201.130', NULL),
(103, '\\core\\event\\course_category_created', 'core', 'created', 'course_category', 'course_categories', 4, 'c', 0, 32, 40, 4, 2, 0, NULL, 0, 'N;', 1453371811, 'web', '109.87.201.130', NULL),
(104, '\\core\\event\\course_category_created', 'core', 'created', 'course_category', 'course_categories', 5, 'c', 0, 33, 40, 5, 2, 0, NULL, 0, 'N;', 1453371854, 'web', '109.87.201.130', NULL),
(105, '\\core\\event\\course_category_deleted', 'core', 'deleted', 'course_category', 'course_categories', 1, 'd', 0, 3, 40, 1, 2, 0, NULL, 0, 'a:1:{s:4:"name";s:13:"Miscellaneous";}', 1453371892, 'web', '109.87.201.130', NULL),
(106, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 2, 'c', 1, 34, 50, 2, 2, 2, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS1";s:8:"fullname";s:11:"Workshop #1";}', 1453375089, 'web', '109.87.201.130', NULL),
(107, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 1, 'c', 0, 34, 50, 2, 2, 2, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453375089, 'web', '109.87.201.130', NULL),
(108, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 2, 'c', 0, 34, 50, 2, 2, 2, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453375089, 'web', '109.87.201.130', NULL),
(109, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 3, 'c', 0, 34, 50, 2, 2, 2, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453375089, 'web', '109.87.201.130', NULL),
(110, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 3, 'c', 1, 39, 50, 3, 2, 3, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS2";s:8:"fullname";s:11:"Workshop #2";}', 1453375118, 'web', '109.87.201.130', NULL),
(111, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 4, 'c', 0, 39, 50, 3, 2, 3, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453375119, 'web', '109.87.201.130', NULL),
(112, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 5, 'c', 0, 39, 50, 3, 2, 3, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453375119, 'web', '109.87.201.130', NULL),
(113, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 6, 'c', 0, 39, 50, 3, 2, 3, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453375119, 'web', '109.87.201.130', NULL),
(114, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 4, 'c', 1, 44, 50, 4, 2, 4, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS3";s:8:"fullname";s:11:"Workshop #3";}', 1453375152, 'web', '109.87.201.130', NULL),
(115, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 7, 'c', 0, 44, 50, 4, 2, 4, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453375152, 'web', '109.87.201.130', NULL),
(116, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 8, 'c', 0, 44, 50, 4, 2, 4, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453375152, 'web', '109.87.201.130', NULL),
(117, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 9, 'c', 0, 44, 50, 4, 2, 4, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453375152, 'web', '109.87.201.130', NULL),
(118, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 5, 'c', 1, 49, 50, 5, 2, 5, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS4";s:8:"fullname";s:11:"Workshop #4";}', 1453375183, 'web', '109.87.201.130', NULL),
(119, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 10, 'c', 0, 49, 50, 5, 2, 5, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453375183, 'web', '109.87.201.130', NULL),
(120, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 11, 'c', 0, 49, 50, 5, 2, 5, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453375183, 'web', '109.87.201.130', NULL),
(121, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 12, 'c', 0, 49, 50, 5, 2, 5, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453375183, 'web', '109.87.201.130', NULL),
(122, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 6, 'c', 1, 54, 50, 6, 2, 6, NULL, 0, 'a:2:{s:9:"shortname";s:3:"CS1";s:8:"fullname";s:7:"Curse#1";}', 1453375220, 'web', '109.87.201.130', NULL),
(123, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 13, 'c', 0, 54, 50, 6, 2, 6, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453375221, 'web', '109.87.201.130', NULL),
(124, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 14, 'c', 0, 54, 50, 6, 2, 6, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453375221, 'web', '109.87.201.130', NULL),
(125, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 15, 'c', 0, 54, 50, 6, 2, 6, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453375221, 'web', '109.87.201.130', NULL),
(126, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 6, 'u', 1, 54, 50, 6, 2, 6, NULL, 0, 'a:2:{s:9:"shortname";s:3:"CS1";s:8:"fullname";s:8:"Course#1";}', 1453375328, 'web', '109.87.201.130', NULL),
(127, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453376242, 'web', '109.87.201.130', NULL),
(128, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453376270, 'web', '109.87.201.130', NULL),
(129, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453376299, 'web', '109.87.201.130', NULL),
(130, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453376864, 'web', '109.87.201.130', NULL),
(131, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453376888, 'web', '109.87.201.130', NULL),
(132, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"f6b5893ec79a7d41aede7d5a3961fc6e";}', 1453376934, 'web', '109.87.201.130', NULL),
(133, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453376948, 'web', '109.87.201.130', NULL),
(134, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453376957, 'web', '109.87.201.130', NULL),
(135, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378240, 'web', '109.87.201.130', NULL),
(136, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378285, 'web', '109.87.201.130', NULL),
(137, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378295, 'web', '109.87.201.130', NULL),
(138, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378295, 'web', '109.87.201.130', NULL),
(139, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378303, 'web', '109.87.201.130', NULL),
(140, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378308, 'web', '109.87.201.130', NULL),
(141, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378315, 'web', '109.87.201.130', NULL),
(142, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378323, 'web', '109.87.201.130', NULL),
(143, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378327, 'web', '109.87.201.130', NULL),
(144, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453378335, 'web', '109.87.201.130', NULL),
(145, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453378597, 'web', '109.87.201.130', NULL),
(146, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453380317, 'web', '109.87.201.130', NULL),
(147, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 49, 50, 5, 2, 5, NULL, 0, 'N;', 1453387796, 'web', '109.87.201.130', NULL),
(148, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 49, 50, 5, 2, 5, NULL, 0, 'N;', 1453388463, 'web', '109.87.201.130', NULL),
(149, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 49, 50, 5, 2, 5, NULL, 0, 'N;', 1453388469, 'web', '109.87.201.130', NULL),
(150, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453448047, 'web', '109.87.201.130', NULL),
(151, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448063, 'web', '109.87.201.130', NULL),
(152, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448165, 'web', '109.87.201.130', NULL),
(153, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448193, 'web', '109.87.201.130', NULL),
(154, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448203, 'web', '109.87.201.130', NULL),
(155, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448212, 'web', '109.87.201.130', NULL),
(156, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448221, 'web', '109.87.201.130', NULL),
(157, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448232, 'web', '109.87.201.130', NULL),
(158, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453448237, 'web', '109.87.201.130', NULL),
(159, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448251, 'web', '109.87.201.130', NULL),
(160, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448274, 'web', '109.87.201.130', NULL),
(161, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448286, 'web', '109.87.201.130', NULL),
(162, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448295, 'web', '109.87.201.130', NULL),
(163, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448304, 'web', '109.87.201.130', NULL),
(164, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448315, 'web', '109.87.201.130', NULL),
(165, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 54, 50, 6, 2, 6, NULL, 0, 'N;', 1453448320, 'web', '109.87.201.130', NULL),
(166, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 39, 50, 3, 2, 3, NULL, 0, 'N;', 1453448359, 'web', '109.87.201.130', NULL),
(167, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"4dd3cd99c9c8e339f9ca29b14dbb2c5d";}', 1453448417, 'web', '109.87.201.130', NULL),
(168, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453448636, 'web', '109.87.201.130', NULL),
(169, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453449007, 'web', '109.87.201.130', NULL),
(170, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449047, 'web', '109.87.201.130', NULL),
(171, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449750, 'web', '109.87.201.130', NULL),
(172, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449787, 'web', '109.87.201.130', NULL),
(173, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449788, 'web', '109.87.201.130', NULL),
(174, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449838, 'web', '109.87.201.130', NULL),
(175, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449856, 'web', '109.87.201.130', NULL),
(176, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449954, 'web', '109.87.201.130', NULL),
(177, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453449965, 'web', '109.87.201.130', NULL),
(178, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453450073, 'web', '109.87.201.130', NULL),
(179, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453450991, 'web', '109.87.201.130', NULL),
(180, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453451040, 'web', '109.87.201.130', NULL),
(181, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453451617, 'web', '109.87.201.130', NULL),
(182, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453451666, 'web', '109.87.201.130', NULL),
(183, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453451698, 'web', '109.87.201.130', NULL),
(184, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453457163, 'web', '109.87.201.130', NULL),
(185, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453457256, 'web', '109.87.201.130', NULL),
(186, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453457412, 'web', '109.87.201.130', NULL),
(187, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 44, 50, 4, 2, 4, NULL, 0, 'N;', 1453457652, 'web', '109.87.201.130', NULL),
(188, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453463929, 'web', '67.14.247.46', NULL),
(189, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453464032, 'web', '67.14.247.46', NULL),
(190, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 2, 1, NULL, 0, 'N;', 1453464098, 'web', '67.14.247.46', NULL),
(191, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"ccc655f72eb17f0eacf29fc9486f6a8f";}', 1453464611, 'web', '67.14.247.46', NULL),
(192, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"d9524fa5e07e373a11e81c180f961fc5";}', 1453465221, 'web', '109.87.201.130', NULL),
(193, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453465232, 'web', '109.87.201.130', NULL),
(194, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453465242, 'web', '109.87.201.130', NULL),
(195, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"6eb459a31a699fa23d84f1ee16987544";}', 1453465283, 'web', '109.87.201.130', NULL),
(196, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453470339, 'web', '109.87.201.130', NULL),
(197, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453478998, 'web', '67.14.247.46', NULL),
(198, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"f953d07b991864d826763fc228c14a88";}', 1453480152, 'web', '67.14.247.46', NULL),
(199, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1453487572, 'web', '107.72.162.55', NULL),
(200, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1453487639, 'web', '107.72.162.55', NULL),
(201, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453539502, 'web', '109.87.201.130', NULL),
(202, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453539711, 'web', '109.87.201.130', NULL),
(203, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"ce8b34efc0c459bdb96abef3d2dca971";}', 1453539721, 'web', '109.87.201.130', NULL),
(204, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453539759, 'web', '109.87.201.130', NULL),
(205, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453539996, 'web', '109.87.201.130', NULL),
(206, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453540006, 'web', '109.87.201.130', NULL),
(207, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453540369, 'web', '109.87.201.130', NULL),
(208, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453541559, 'web', '109.87.201.130', NULL),
(209, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453546899, 'web', '109.87.201.130', NULL),
(210, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453555244, 'web', '109.87.201.130', NULL),
(211, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453556150, 'web', '109.87.201.130', NULL),
(212, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453558691, 'web', '109.87.201.130', NULL),
(213, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453559525, 'web', '109.87.201.130', NULL),
(214, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453575800, 'web', '109.87.201.130', NULL),
(215, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"45bada11e9b1ca1fbcd60835f5b33560";}', 1453575833, 'web', '109.87.201.130', NULL),
(216, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453575848, 'web', '109.87.201.130', NULL),
(217, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453578477, 'web', '109.87.201.130', NULL),
(218, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453578590, 'web', '109.87.201.130', NULL),
(219, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453621711, 'web', '109.87.201.130', NULL),
(220, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453622704, 'web', '109.87.201.130', NULL),
(221, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453622747, 'web', '109.87.201.130', NULL),
(222, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453622795, 'web', '109.87.201.130', NULL),
(223, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453622924, 'web', '109.87.201.130', NULL),
(224, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453624934, 'web', '109.87.201.130', NULL),
(225, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453635849, 'web', '109.87.201.130', NULL),
(226, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453635893, 'web', '109.87.201.130', NULL),
(227, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453637324, 'web', '109.87.201.130', NULL),
(228, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453640593, 'web', '151.80.44.115', NULL),
(229, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453640848, 'web', '109.87.201.130', NULL),
(230, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453642517, 'web', '109.87.201.130', NULL),
(231, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1453643220, 'web', '109.87.201.130', NULL),
(232, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 7, 'c', 1, 63, 50, 7, 2, 7, NULL, 0, 'a:2:{s:9:"shortname";s:3:"NRS";s:8:"fullname";s:34:"Nursing school at some location #1";}', 1453652021, 'web', '109.87.201.130', NULL),
(233, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 16, 'c', 0, 63, 50, 7, 2, 7, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652021, 'web', '109.87.201.130', NULL),
(234, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 17, 'c', 0, 63, 50, 7, 2, 7, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652022, 'web', '109.87.201.130', NULL),
(235, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 18, 'c', 0, 63, 50, 7, 2, 7, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652022, 'web', '109.87.201.130', NULL),
(236, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 8, 'c', 1, 68, 50, 8, 2, 8, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS2";s:8:"fullname";s:34:"Nursing school at some location #2";}', 1453652063, 'web', '109.87.201.130', NULL),
(237, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 19, 'c', 0, 68, 50, 8, 2, 8, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652063, 'web', '109.87.201.130', NULL),
(238, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 20, 'c', 0, 68, 50, 8, 2, 8, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652063, 'web', '109.87.201.130', NULL),
(239, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 21, 'c', 0, 68, 50, 8, 2, 8, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652063, 'web', '109.87.201.130', NULL),
(240, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 7, 'u', 1, 63, 50, 7, 2, 7, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS1";s:8:"fullname";s:34:"Nursing school at some location #1";}', 1453652093, 'web', '109.87.201.130', NULL),
(241, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 9, 'c', 1, 73, 50, 9, 2, 9, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS3";s:8:"fullname";s:34:"Nursing school at some location #3";}', 1453652130, 'web', '109.87.201.130', NULL),
(242, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 22, 'c', 0, 73, 50, 9, 2, 9, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652131, 'web', '109.87.201.130', NULL),
(243, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 23, 'c', 0, 73, 50, 9, 2, 9, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652131, 'web', '109.87.201.130', NULL),
(244, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 24, 'c', 0, 73, 50, 9, 2, 9, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652131, 'web', '109.87.201.130', NULL),
(245, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 10, 'c', 1, 78, 50, 10, 2, 10, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS4";s:8:"fullname";s:34:"Nursing school at some location #4";}', 1453652167, 'web', '109.87.201.130', NULL),
(246, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 25, 'c', 0, 78, 50, 10, 2, 10, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652168, 'web', '109.87.201.130', NULL),
(247, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 26, 'c', 0, 78, 50, 10, 2, 10, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652168, 'web', '109.87.201.130', NULL),
(248, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 27, 'c', 0, 78, 50, 10, 2, 10, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652168, 'web', '109.87.201.130', NULL),
(249, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 11, 'c', 1, 83, 50, 11, 2, 11, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS5";s:8:"fullname";s:34:"Nursing school at some location #5";}', 1453652204, 'web', '109.87.201.130', NULL),
(250, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 28, 'c', 0, 83, 50, 11, 2, 11, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652204, 'web', '109.87.201.130', NULL),
(251, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 29, 'c', 0, 83, 50, 11, 2, 11, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652204, 'web', '109.87.201.130', NULL),
(252, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 30, 'c', 0, 83, 50, 11, 2, 11, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652204, 'web', '109.87.201.130', NULL),
(253, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 12, 'c', 1, 88, 50, 12, 2, 12, NULL, 0, 'a:2:{s:9:"shortname";s:3:"EX1";s:8:"fullname";s:12:"Some exam #1";}', 1453652251, 'web', '109.87.201.130', NULL),
(254, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 31, 'c', 0, 88, 50, 12, 2, 12, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652251, 'web', '109.87.201.130', NULL),
(255, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 32, 'c', 0, 88, 50, 12, 2, 12, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652251, 'web', '109.87.201.130', NULL),
(256, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 33, 'c', 0, 88, 50, 12, 2, 12, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652251, 'web', '109.87.201.130', NULL),
(257, '\\core\\event\\course_created', 'core', 'created', 'course', 'course', 13, 'c', 1, 93, 50, 13, 2, 13, NULL, 0, 'a:2:{s:9:"shortname";s:3:"EX2";s:8:"fullname";s:12:"Some exam #2";}', 1453652285, 'web', '109.87.201.130', NULL),
(258, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 34, 'c', 0, 93, 50, 13, 2, 13, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1453652285, 'web', '109.87.201.130', NULL),
(259, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 35, 'c', 0, 93, 50, 13, 2, 13, NULL, 0, 'a:1:{s:5:"enrol";s:5:"guest";}', 1453652285, 'web', '109.87.201.130', NULL),
(260, '\\core\\event\\enrol_instance_created', 'core', 'created', 'enrol_instance', 'enrol', 36, 'c', 0, 93, 50, 13, 2, 13, NULL, 0, 'a:1:{s:5:"enrol";s:4:"self";}', 1453652285, 'web', '109.87.201.130', NULL),
(261, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453706779, 'web', '109.87.201.130', NULL),
(262, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453719283, 'web', '109.87.201.130', NULL),
(263, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453730974, 'web', '109.87.201.130', NULL),
(264, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"81263be1feddb3de34390c4f0a993129";}', 1453732801, 'web', '109.87.201.130', NULL),
(265, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453746246, 'web', '109.87.201.130', NULL),
(266, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"448e3e272ee8c1cac4533339d61e19da";}', 1453747400, 'web', '109.87.201.130', NULL);
INSERT INTO `mdl_logstore_standard_log` (`id`, `eventname`, `component`, `action`, `target`, `objecttable`, `objectid`, `crud`, `edulevel`, `contextid`, `contextlevel`, `contextinstanceid`, `userid`, `courseid`, `relateduserid`, `anonymous`, `other`, `timecreated`, `origin`, `ip`, `realuserid`) VALUES
(267, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453751886, 'web', '109.87.201.130', NULL),
(268, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"d7551f24006cc7a0f6543ac0a29c0e40";}', 1453753023, 'web', '109.87.201.130', NULL),
(269, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453801123, 'web', '109.87.201.130', NULL),
(270, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453812209, 'web', '109.87.201.130', NULL),
(271, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"d9df7f9634dac8cb24bd948c1724189c";}', 1453823305, 'web', '109.87.201.130', NULL),
(272, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453829032, 'web', '109.87.201.130', NULL),
(273, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1453868313, 'web', '136.243.5.215', NULL),
(274, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453892864, 'web', '109.87.201.130', NULL),
(275, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"4eeae89e1fc0b8d3c3a657af594c408d";}', 1453892898, 'web', '109.87.201.130', NULL),
(276, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1453896682, 'web', '109.87.201.130', NULL),
(277, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1453896945, 'web', '109.87.201.130', NULL),
(278, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1453897829, 'web', '109.87.201.130', NULL),
(279, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453897847, 'web', '109.87.201.130', NULL),
(280, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"2d10b88cb350c9ff76444ac66051bd48";}', 1453897889, 'web', '109.87.201.130', NULL),
(281, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1453897902, 'web', '109.87.201.130', NULL),
(282, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453899928, 'web', '109.87.201.130', NULL),
(283, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"3dbce4cd8ff08d2e08d5ce99309b27d1";}', 1453899959, 'web', '109.87.201.130', NULL),
(284, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1453900370, 'web', '109.87.201.130', NULL),
(285, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"e7dbd59962e6c8822448556015db5b88";}', 1453900390, 'web', '109.87.201.130', NULL),
(286, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454070113, 'web', '109.87.201.130', NULL),
(287, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"226a4a38e835f369e592ac05a0b72a7a";}', 1454070128, 'web', '109.87.201.130', NULL),
(288, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454070365, 'web', '109.87.201.130', NULL),
(289, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8c1436dcb38d0c41ae63ada837c87658";}', 1454070439, 'web', '109.87.201.130', NULL),
(290, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454070645, 'web', '109.87.201.130', NULL),
(291, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1454070676, 'web', '109.87.201.130', NULL),
(292, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1454070704, 'web', '109.87.201.130', NULL),
(293, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"0ab9c474cf42a4fbaa8b5e65089004cc";}', 1454070751, 'web', '109.87.201.130', NULL),
(294, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454070791, 'web', '109.87.201.130', NULL),
(295, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454070817, 'web', '109.87.201.130', NULL),
(296, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"6d22046f6812aa32c1845c3a9e6ca974";}', 1454070833, 'web', '109.87.201.130', NULL),
(297, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8c68956e44606bb2adf8833b1bb5e939";}', 1454070835, 'web', '109.87.201.130', NULL),
(298, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:0:"";s:6:"reason";i:1;}', 1454070839, 'web', '109.87.201.130', NULL),
(299, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1454070997, 'web', '109.87.201.130', NULL),
(300, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454071238, 'web', '109.87.201.130', NULL),
(301, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"9649d9c02ad1734dc3966f7c0bb1e38b";}', 1454071273, 'web', '109.87.201.130', NULL),
(302, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454071661, 'web', '109.87.201.130', NULL),
(303, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"fa977d3c7b95abb4085b265ef2028c38";}', 1454071832, 'web', '109.87.201.130', NULL),
(304, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454073172, 'web', '109.87.201.130', NULL),
(305, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454073342, 'web', '109.87.201.130', NULL),
(306, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"1921942e869fcbcb50c8a3df352abbbe";}', 1454073436, 'web', '109.87.201.130', NULL),
(307, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1454073479, 'web', '109.87.201.130', NULL),
(308, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1454073500, 'web', '109.87.201.130', NULL),
(309, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454073539, 'web', '109.87.201.130', NULL),
(310, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"ac7c1e8dc53d9854904620e3a7b6b0ca";}', 1454073654, 'web', '109.87.201.130', NULL),
(311, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"0cf762ad8bfcb7c635b606c656530a13";}', 1454078010, 'web', '109.87.201.130', NULL),
(312, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454078401, 'web', '109.87.201.130', NULL),
(313, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"25cc2f653fc9d2753d306ab6e54ddf2f";}', 1454078418, 'web', '109.87.201.130', NULL),
(314, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:6:"admin1";s:6:"reason";i:1;}', 1454078430, 'web', '109.87.201.130', NULL),
(315, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:6:"admin1";s:6:"reason";i:1;}', 1454078489, 'web', '109.87.201.130', NULL),
(316, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454079090, 'web', '109.87.201.130', NULL),
(317, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8abef29db841ce888e76a76922468bf8";}', 1454079119, 'web', '109.87.201.130', NULL),
(318, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454079128, 'web', '109.87.201.130', NULL),
(319, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"64df8f09415c28fe432aab2166217296";}', 1454079136, 'web', '109.87.201.130', NULL),
(320, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:6:"admin1";s:6:"reason";i:1;}', 1454079145, 'web', '109.87.201.130', NULL),
(321, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:6:"admin1";s:6:"reason";i:1;}', 1454079159, 'web', '109.87.201.130', NULL),
(322, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454079368, 'web', '109.87.201.130', NULL),
(323, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"b5c8b99a9647457a2f99be063be3d39b";}', 1454079375, 'web', '109.87.201.130', NULL),
(324, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454081454, 'web', '109.87.201.130', NULL),
(325, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"d4879f04d5380e46ac1d33d3f2847841";}', 1454081474, 'web', '109.87.201.130', NULL),
(326, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454081520, 'web', '109.87.201.130', NULL),
(327, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"08a5eaee25cecab800225fc28ca33efe";}', 1454081575, 'web', '109.87.201.130', NULL),
(328, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454081594, 'web', '109.87.201.130', NULL),
(329, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"1572820a9d4b2635cba79d86de04e41a";}', 1454081610, 'web', '109.87.201.130', NULL),
(330, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454081631, 'web', '109.87.201.130', NULL),
(331, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"ebb22234994492da81e283267b87370e";}', 1454081638, 'web', '109.87.201.130', NULL),
(332, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 0, 0, NULL, 0, 'a:2:{s:8:"username";s:6:"admin1";s:6:"reason";i:1;}', 1454081649, 'web', '109.87.201.130', NULL),
(333, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454083331, 'web', '109.87.201.130', NULL),
(334, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1454086136, 'web', '67.14.247.46', NULL),
(335, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454094895, 'web', '109.87.201.130', NULL),
(336, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"c323b076270483495681683d69529ac5";}', 1454094983, 'web', '109.87.201.130', NULL),
(337, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454126542, 'web', '144.76.30.236', NULL),
(338, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454225875, 'web', '109.87.201.130', NULL),
(339, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454254286, 'web', '24.220.5.235', NULL),
(340, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454322300, 'web', '109.87.201.130', NULL),
(341, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454322995, 'web', '109.87.201.130', NULL),
(342, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454324067, 'web', '109.87.201.130', NULL),
(343, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454325151, 'web', '109.87.201.130', NULL),
(344, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454328199, 'web', '109.87.201.130', NULL),
(345, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1454329815, 'web', '109.87.201.130', NULL),
(346, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454339185, 'web', '109.87.201.130', NULL),
(347, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454339365, 'web', '109.87.201.130', NULL),
(348, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454339540, 'web', '109.87.201.130', NULL),
(349, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454339626, 'web', '109.87.201.130', NULL),
(350, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454339688, 'web', '109.87.201.130', NULL),
(351, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454339969, 'web', '109.87.201.130', NULL),
(352, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"f4f97e2a7ca83756f892d326f4820090";}', 1454340486, 'web', '109.87.201.130', NULL),
(353, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8efeb0979144ba404b6038bccc8959bb";}', 1454340580, 'web', '109.87.201.130', NULL),
(354, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1454341610, 'web', '109.87.201.130', NULL),
(355, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454341980, 'web', '109.87.201.130', NULL),
(356, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"3fc471384ccd8e48e92522e24355f782";}', 1454344389, 'web', '109.87.201.130', NULL),
(357, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454347757, 'web', '109.87.201.130', NULL),
(358, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"79d44f1922769362a056d5a0d93ac5bb";}', 1454347826, 'web', '109.87.201.130', NULL),
(359, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454350207, 'web', '109.87.201.130', NULL),
(360, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 49, 50, 5, 2, 5, NULL, 0, 'N;', 1454350222, 'web', '109.87.201.130', NULL),
(361, '\\core\\event\\enrol_instance_updated', 'core', 'updated', 'enrol_instance', 'enrol', 10, 'u', 0, 49, 50, 5, 2, 5, NULL, 0, 'a:1:{s:5:"enrol";s:6:"manual";}', 1454350597, 'web', '109.87.201.130', NULL),
(362, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 49, 50, 5, 2, 5, NULL, 0, 'N;', 1454350614, 'web', '109.87.201.130', NULL),
(363, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 5, 'u', 1, 49, 50, 5, 2, 5, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS4";s:8:"fullname";s:11:"Workshop #4";}', 1454350662, 'web', '109.87.201.130', NULL),
(364, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 4, 'u', 1, 44, 50, 4, 2, 4, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS3";s:8:"fullname";s:11:"Workshop #3";}', 1454350676, 'web', '109.87.201.130', NULL),
(365, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 3, 'u', 1, 39, 50, 3, 2, 3, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS2";s:8:"fullname";s:11:"Workshop #2";}', 1454350690, 'web', '109.87.201.130', NULL),
(366, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 3, 'u', 1, 39, 50, 3, 2, 3, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS2";s:8:"fullname";s:11:"Workshop #2";}', 1454350709, 'web', '109.87.201.130', NULL),
(367, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 2, 'u', 1, 34, 50, 2, 2, 2, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS1";s:8:"fullname";s:11:"Workshop #1";}', 1454350721, 'web', '109.87.201.130', NULL),
(368, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 6, 'u', 1, 54, 50, 6, 2, 6, NULL, 0, 'a:2:{s:9:"shortname";s:3:"CS1";s:8:"fullname";s:8:"Course#1";}', 1454350749, 'web', '109.87.201.130', NULL),
(369, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 13, 'u', 1, 93, 50, 13, 2, 13, NULL, 0, 'a:2:{s:9:"shortname";s:3:"EX2";s:8:"fullname";s:12:"Some exam #2";}', 1454350774, 'web', '109.87.201.130', NULL),
(370, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 12, 'u', 1, 88, 50, 12, 2, 12, NULL, 0, 'a:2:{s:9:"shortname";s:3:"EX1";s:8:"fullname";s:12:"Some exam #1";}', 1454350973, 'web', '109.87.201.130', NULL),
(371, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 11, 'u', 1, 83, 50, 11, 2, 11, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS5";s:8:"fullname";s:34:"Nursing school at some location #5";}', 1454350995, 'web', '109.87.201.130', NULL),
(372, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 10, 'u', 1, 78, 50, 10, 2, 10, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS4";s:8:"fullname";s:34:"Nursing school at some location #4";}', 1454351006, 'web', '109.87.201.130', NULL),
(373, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 9, 'u', 1, 73, 50, 9, 2, 9, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS3";s:8:"fullname";s:34:"Nursing school at some location #3";}', 1454351015, 'web', '109.87.201.130', NULL),
(374, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 8, 'u', 1, 68, 50, 8, 2, 8, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS2";s:8:"fullname";s:34:"Nursing school at some location #2";}', 1454351027, 'web', '109.87.201.130', NULL),
(375, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 7, 'u', 1, 63, 50, 7, 2, 7, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS1";s:8:"fullname";s:34:"Nursing school at some location #1";}', 1454351037, 'web', '109.87.201.130', NULL),
(376, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454400395, 'web', '109.87.201.130', NULL),
(377, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 5, 'u', 1, 49, 50, 5, 2, 5, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS4";s:8:"fullname";s:11:"Workshop #4";}', 1454401080, 'web', '109.87.201.130', NULL),
(378, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 5, 'u', 1, 49, 50, 5, 2, 5, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS4";s:8:"fullname";s:11:"Workshop #4";}', 1454401101, 'web', '109.87.201.130', NULL),
(379, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 4, 'u', 1, 44, 50, 4, 2, 4, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS3";s:8:"fullname";s:11:"Workshop #3";}', 1454401115, 'web', '109.87.201.130', NULL),
(380, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 3, 'u', 1, 39, 50, 3, 2, 3, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS2";s:8:"fullname";s:11:"Workshop #2";}', 1454401127, 'web', '109.87.201.130', NULL),
(381, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 3, 'u', 1, 39, 50, 3, 2, 3, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS2";s:8:"fullname";s:11:"Workshop #2";}', 1454401141, 'web', '109.87.201.130', NULL),
(382, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 2, 'u', 1, 34, 50, 2, 2, 2, NULL, 0, 'a:2:{s:9:"shortname";s:3:"WS1";s:8:"fullname";s:11:"Workshop #1";}', 1454401154, 'web', '109.87.201.130', NULL),
(383, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 6, 'u', 1, 54, 50, 6, 2, 6, NULL, 0, 'a:2:{s:9:"shortname";s:3:"CS1";s:8:"fullname";s:8:"Course#1";}', 1454401174, 'web', '109.87.201.130', NULL),
(384, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 13, 'u', 1, 93, 50, 13, 2, 13, NULL, 0, 'a:2:{s:9:"shortname";s:3:"EX2";s:8:"fullname";s:12:"Some exam #2";}', 1454401194, 'web', '109.87.201.130', NULL),
(385, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 12, 'u', 1, 88, 50, 12, 2, 12, NULL, 0, 'a:2:{s:9:"shortname";s:3:"EX1";s:8:"fullname";s:12:"Some exam #1";}', 1454401210, 'web', '109.87.201.130', NULL),
(386, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 11, 'u', 1, 83, 50, 11, 2, 11, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS5";s:8:"fullname";s:34:"Nursing school at some location #5";}', 1454401228, 'web', '109.87.201.130', NULL),
(387, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 10, 'u', 1, 78, 50, 10, 2, 10, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS4";s:8:"fullname";s:34:"Nursing school at some location #4";}', 1454401243, 'web', '109.87.201.130', NULL),
(388, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 9, 'u', 1, 73, 50, 9, 2, 9, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS3";s:8:"fullname";s:34:"Nursing school at some location #3";}', 1454401260, 'web', '109.87.201.130', NULL),
(389, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 8, 'u', 1, 68, 50, 8, 2, 8, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS2";s:8:"fullname";s:34:"Nursing school at some location #2";}', 1454401276, 'web', '109.87.201.130', NULL),
(390, '\\core\\event\\course_updated', 'core', 'updated', 'course', 'course', 7, 'u', 1, 63, 50, 7, 2, 7, NULL, 0, 'a:2:{s:9:"shortname";s:4:"NRS1";s:8:"fullname";s:34:"Nursing school at some location #1";}', 1454401290, 'web', '109.87.201.130', NULL),
(391, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454412213, 'web', '109.87.201.130', NULL),
(392, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454418190, 'web', '109.87.201.130', NULL),
(393, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454426105, 'web', '69.30.213.18', NULL),
(394, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454429659, 'web', '109.87.201.130', NULL),
(395, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1454611921, 'web', '109.87.201.130', NULL),
(396, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454613012, 'web', '195.154.200.68', NULL),
(397, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1454826538, 'web', '176.104.37.122', NULL),
(398, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455001433, 'web', '69.30.213.202', NULL),
(399, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455028065, 'web', '109.87.201.130', NULL),
(400, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1455078175, 'web', '67.14.247.46', NULL),
(401, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455084865, 'web', '109.87.201.130', NULL),
(402, '\\core\\event\\user_profile_viewed', 'core', 'viewed', 'user_profile', 'user', 2, 'r', 0, 5, 30, 2, 2, 0, 2, 0, 'N;', 1455084911, 'web', '109.87.201.130', NULL),
(403, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"e04a52ae4337d6c85a3d73cf61b98a10";}', 1455084923, 'web', '109.87.201.130', NULL),
(404, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455089982, 'web', '109.87.201.130', NULL),
(405, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455093686, 'web', '109.87.201.130', NULL),
(406, '\\core\\event\\user_login_failed', 'core', 'failed', 'user_login', NULL, NULL, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:2:{s:8:"username";s:5:"admin";s:6:"reason";i:3;}', 1455093822, 'web', '109.87.201.130', NULL),
(407, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455093835, 'web', '109.87.201.130', NULL),
(408, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455143526, 'web', '67.14.247.46', NULL),
(409, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 2, 1, NULL, 0, 'N;', 1455143591, 'web', '67.14.247.46', NULL),
(410, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"c6fba98ba9440916e5ce86e6873d5c49";}', 1455144297, 'web', '67.14.247.46', NULL),
(411, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455144907, 'web', '69.30.213.82', NULL),
(412, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455202693, 'web', '109.87.201.130', NULL),
(413, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455202709, 'web', '109.87.201.130', NULL),
(414, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455202722, 'web', '109.87.201.130', NULL),
(415, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455209755, 'web', '109.87.201.130', NULL),
(416, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"16f44ba2076fb53beadd7929cce88104";}', 1455209927, 'web', '109.87.201.130', NULL),
(417, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455287574, 'web', '109.87.201.130', NULL),
(418, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"8219303edd69222098e11df3864991d0";}', 1455290841, 'web', '109.87.201.130', NULL),
(419, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455291715, 'web', '109.87.201.130', NULL),
(420, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455291723, 'web', '109.87.201.130', NULL),
(421, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"c97b05fc40899d395243be7787fd55a7";}', 1455292795, 'web', '109.87.201.130', NULL),
(422, '\\core\\event\\course_viewed', 'core', 'viewed', 'course', NULL, NULL, 'r', 2, 2, 50, 1, 0, 1, NULL, 0, 'N;', 1455292828, 'web', '109.87.201.130', NULL),
(423, '\\core\\event\\user_loggedin', 'core', 'loggedin', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:8:"username";s:5:"admin";}', 1455293291, 'web', '109.87.201.130', NULL),
(424, '\\core\\event\\user_loggedout', 'core', 'loggedout', 'user', 'user', 2, 'r', 0, 1, 10, 0, 2, 0, NULL, 0, 'a:1:{s:9:"sessionid";s:32:"46d0bb20b40122485809d257bf68acc5";}', 1455295116, 'web', '109.87.201.130', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_log_display`
--

CREATE TABLE IF NOT EXISTS `mdl_log_display` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `module` varchar(20) NOT NULL DEFAULT '',
  `action` varchar(40) NOT NULL DEFAULT '',
  `mtable` varchar(30) NOT NULL DEFAULT '',
  `field` varchar(200) NOT NULL DEFAULT '',
  `component` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_logdisp_modact_uix` (`module`,`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='For a particular module/action, specifies a moodle table/fie' AUTO_INCREMENT=188 ;

--
-- Dumping data for table `mdl_log_display`
--

INSERT INTO `mdl_log_display` (`id`, `module`, `action`, `mtable`, `field`, `component`) VALUES
(1, 'course', 'user report', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(2, 'course', 'view', 'course', 'fullname', 'moodle'),
(3, 'course', 'view section', 'course_sections', 'name', 'moodle'),
(4, 'course', 'update', 'course', 'fullname', 'moodle'),
(5, 'course', 'hide', 'course', 'fullname', 'moodle'),
(6, 'course', 'show', 'course', 'fullname', 'moodle'),
(7, 'course', 'move', 'course', 'fullname', 'moodle'),
(8, 'course', 'enrol', 'course', 'fullname', 'moodle'),
(9, 'course', 'unenrol', 'course', 'fullname', 'moodle'),
(10, 'course', 'report log', 'course', 'fullname', 'moodle'),
(11, 'course', 'report live', 'course', 'fullname', 'moodle'),
(12, 'course', 'report outline', 'course', 'fullname', 'moodle'),
(13, 'course', 'report participation', 'course', 'fullname', 'moodle'),
(14, 'course', 'report stats', 'course', 'fullname', 'moodle'),
(15, 'category', 'add', 'course_categories', 'name', 'moodle'),
(16, 'category', 'hide', 'course_categories', 'name', 'moodle'),
(17, 'category', 'move', 'course_categories', 'name', 'moodle'),
(18, 'category', 'show', 'course_categories', 'name', 'moodle'),
(19, 'category', 'update', 'course_categories', 'name', 'moodle'),
(20, 'message', 'write', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(21, 'message', 'read', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(22, 'message', 'add contact', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(23, 'message', 'remove contact', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(24, 'message', 'block contact', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(25, 'message', 'unblock contact', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(26, 'group', 'view', 'groups', 'name', 'moodle'),
(27, 'tag', 'update', 'tag', 'name', 'moodle'),
(28, 'tag', 'flag', 'tag', 'name', 'moodle'),
(29, 'user', 'view', 'user', 'CONCAT(firstname, '' '', lastname)', 'moodle'),
(30, 'assign', 'add', 'assign', 'name', 'mod_assign'),
(31, 'assign', 'delete mod', 'assign', 'name', 'mod_assign'),
(32, 'assign', 'download all submissions', 'assign', 'name', 'mod_assign'),
(33, 'assign', 'grade submission', 'assign', 'name', 'mod_assign'),
(34, 'assign', 'lock submission', 'assign', 'name', 'mod_assign'),
(35, 'assign', 'reveal identities', 'assign', 'name', 'mod_assign'),
(36, 'assign', 'revert submission to draft', 'assign', 'name', 'mod_assign'),
(37, 'assign', 'set marking workflow state', 'assign', 'name', 'mod_assign'),
(38, 'assign', 'submission statement accepted', 'assign', 'name', 'mod_assign'),
(39, 'assign', 'submit', 'assign', 'name', 'mod_assign'),
(40, 'assign', 'submit for grading', 'assign', 'name', 'mod_assign'),
(41, 'assign', 'unlock submission', 'assign', 'name', 'mod_assign'),
(42, 'assign', 'update', 'assign', 'name', 'mod_assign'),
(43, 'assign', 'upload', 'assign', 'name', 'mod_assign'),
(44, 'assign', 'view', 'assign', 'name', 'mod_assign'),
(45, 'assign', 'view all', 'course', 'fullname', 'mod_assign'),
(46, 'assign', 'view confirm submit assignment form', 'assign', 'name', 'mod_assign'),
(47, 'assign', 'view grading form', 'assign', 'name', 'mod_assign'),
(48, 'assign', 'view submission', 'assign', 'name', 'mod_assign'),
(49, 'assign', 'view submission grading table', 'assign', 'name', 'mod_assign'),
(50, 'assign', 'view submit assignment form', 'assign', 'name', 'mod_assign'),
(51, 'assign', 'view feedback', 'assign', 'name', 'mod_assign'),
(52, 'assign', 'view batch set marking workflow state', 'assign', 'name', 'mod_assign'),
(53, 'assignment', 'view', 'assignment', 'name', 'mod_assignment'),
(54, 'assignment', 'add', 'assignment', 'name', 'mod_assignment'),
(55, 'assignment', 'update', 'assignment', 'name', 'mod_assignment'),
(56, 'assignment', 'view submission', 'assignment', 'name', 'mod_assignment'),
(57, 'assignment', 'upload', 'assignment', 'name', 'mod_assignment'),
(58, 'book', 'add', 'book', 'name', 'mod_book'),
(59, 'book', 'update', 'book', 'name', 'mod_book'),
(60, 'book', 'view', 'book', 'name', 'mod_book'),
(61, 'book', 'add chapter', 'book_chapters', 'title', 'mod_book'),
(62, 'book', 'update chapter', 'book_chapters', 'title', 'mod_book'),
(63, 'book', 'view chapter', 'book_chapters', 'title', 'mod_book'),
(64, 'chat', 'view', 'chat', 'name', 'mod_chat'),
(65, 'chat', 'add', 'chat', 'name', 'mod_chat'),
(66, 'chat', 'update', 'chat', 'name', 'mod_chat'),
(67, 'chat', 'report', 'chat', 'name', 'mod_chat'),
(68, 'chat', 'talk', 'chat', 'name', 'mod_chat'),
(69, 'choice', 'view', 'choice', 'name', 'mod_choice'),
(70, 'choice', 'update', 'choice', 'name', 'mod_choice'),
(71, 'choice', 'add', 'choice', 'name', 'mod_choice'),
(72, 'choice', 'report', 'choice', 'name', 'mod_choice'),
(73, 'choice', 'choose', 'choice', 'name', 'mod_choice'),
(74, 'choice', 'choose again', 'choice', 'name', 'mod_choice'),
(75, 'data', 'view', 'data', 'name', 'mod_data'),
(76, 'data', 'add', 'data', 'name', 'mod_data'),
(77, 'data', 'update', 'data', 'name', 'mod_data'),
(78, 'data', 'record delete', 'data', 'name', 'mod_data'),
(79, 'data', 'fields add', 'data_fields', 'name', 'mod_data'),
(80, 'data', 'fields update', 'data_fields', 'name', 'mod_data'),
(81, 'data', 'templates saved', 'data', 'name', 'mod_data'),
(82, 'data', 'templates def', 'data', 'name', 'mod_data'),
(83, 'feedback', 'startcomplete', 'feedback', 'name', 'mod_feedback'),
(84, 'feedback', 'submit', 'feedback', 'name', 'mod_feedback'),
(85, 'feedback', 'delete', 'feedback', 'name', 'mod_feedback'),
(86, 'feedback', 'view', 'feedback', 'name', 'mod_feedback'),
(87, 'feedback', 'view all', 'course', 'shortname', 'mod_feedback'),
(88, 'folder', 'view', 'folder', 'name', 'mod_folder'),
(89, 'folder', 'view all', 'folder', 'name', 'mod_folder'),
(90, 'folder', 'update', 'folder', 'name', 'mod_folder'),
(91, 'folder', 'add', 'folder', 'name', 'mod_folder'),
(92, 'forum', 'add', 'forum', 'name', 'mod_forum'),
(93, 'forum', 'update', 'forum', 'name', 'mod_forum'),
(94, 'forum', 'add discussion', 'forum_discussions', 'name', 'mod_forum'),
(95, 'forum', 'add post', 'forum_posts', 'subject', 'mod_forum'),
(96, 'forum', 'update post', 'forum_posts', 'subject', 'mod_forum'),
(97, 'forum', 'user report', 'user', 'CONCAT(firstname, '' '', lastname)', 'mod_forum'),
(98, 'forum', 'move discussion', 'forum_discussions', 'name', 'mod_forum'),
(99, 'forum', 'view subscribers', 'forum', 'name', 'mod_forum'),
(100, 'forum', 'view discussion', 'forum_discussions', 'name', 'mod_forum'),
(101, 'forum', 'view forum', 'forum', 'name', 'mod_forum'),
(102, 'forum', 'subscribe', 'forum', 'name', 'mod_forum'),
(103, 'forum', 'unsubscribe', 'forum', 'name', 'mod_forum'),
(104, 'glossary', 'add', 'glossary', 'name', 'mod_glossary'),
(105, 'glossary', 'update', 'glossary', 'name', 'mod_glossary'),
(106, 'glossary', 'view', 'glossary', 'name', 'mod_glossary'),
(107, 'glossary', 'view all', 'glossary', 'name', 'mod_glossary'),
(108, 'glossary', 'add entry', 'glossary', 'name', 'mod_glossary'),
(109, 'glossary', 'update entry', 'glossary', 'name', 'mod_glossary'),
(110, 'glossary', 'add category', 'glossary', 'name', 'mod_glossary'),
(111, 'glossary', 'update category', 'glossary', 'name', 'mod_glossary'),
(112, 'glossary', 'delete category', 'glossary', 'name', 'mod_glossary'),
(113, 'glossary', 'approve entry', 'glossary', 'name', 'mod_glossary'),
(114, 'glossary', 'disapprove entry', 'glossary', 'name', 'mod_glossary'),
(115, 'glossary', 'view entry', 'glossary_entries', 'concept', 'mod_glossary'),
(116, 'imscp', 'view', 'imscp', 'name', 'mod_imscp'),
(117, 'imscp', 'view all', 'imscp', 'name', 'mod_imscp'),
(118, 'imscp', 'update', 'imscp', 'name', 'mod_imscp'),
(119, 'imscp', 'add', 'imscp', 'name', 'mod_imscp'),
(120, 'label', 'add', 'label', 'name', 'mod_label'),
(121, 'label', 'update', 'label', 'name', 'mod_label'),
(122, 'lesson', 'start', 'lesson', 'name', 'mod_lesson'),
(123, 'lesson', 'end', 'lesson', 'name', 'mod_lesson'),
(124, 'lesson', 'view', 'lesson_pages', 'title', 'mod_lesson'),
(125, 'lti', 'view', 'lti', 'name', 'mod_lti'),
(126, 'lti', 'launch', 'lti', 'name', 'mod_lti'),
(127, 'lti', 'view all', 'lti', 'name', 'mod_lti'),
(128, 'page', 'view', 'page', 'name', 'mod_page'),
(129, 'page', 'view all', 'page', 'name', 'mod_page'),
(130, 'page', 'update', 'page', 'name', 'mod_page'),
(131, 'page', 'add', 'page', 'name', 'mod_page'),
(132, 'quiz', 'add', 'quiz', 'name', 'mod_quiz'),
(133, 'quiz', 'update', 'quiz', 'name', 'mod_quiz'),
(134, 'quiz', 'view', 'quiz', 'name', 'mod_quiz'),
(135, 'quiz', 'report', 'quiz', 'name', 'mod_quiz'),
(136, 'quiz', 'attempt', 'quiz', 'name', 'mod_quiz'),
(137, 'quiz', 'submit', 'quiz', 'name', 'mod_quiz'),
(138, 'quiz', 'review', 'quiz', 'name', 'mod_quiz'),
(139, 'quiz', 'editquestions', 'quiz', 'name', 'mod_quiz'),
(140, 'quiz', 'preview', 'quiz', 'name', 'mod_quiz'),
(141, 'quiz', 'start attempt', 'quiz', 'name', 'mod_quiz'),
(142, 'quiz', 'close attempt', 'quiz', 'name', 'mod_quiz'),
(143, 'quiz', 'continue attempt', 'quiz', 'name', 'mod_quiz'),
(144, 'quiz', 'edit override', 'quiz', 'name', 'mod_quiz'),
(145, 'quiz', 'delete override', 'quiz', 'name', 'mod_quiz'),
(146, 'quiz', 'view summary', 'quiz', 'name', 'mod_quiz'),
(147, 'resource', 'view', 'resource', 'name', 'mod_resource'),
(148, 'resource', 'view all', 'resource', 'name', 'mod_resource'),
(149, 'resource', 'update', 'resource', 'name', 'mod_resource'),
(150, 'resource', 'add', 'resource', 'name', 'mod_resource'),
(151, 'scorm', 'view', 'scorm', 'name', 'mod_scorm'),
(152, 'scorm', 'review', 'scorm', 'name', 'mod_scorm'),
(153, 'scorm', 'update', 'scorm', 'name', 'mod_scorm'),
(154, 'scorm', 'add', 'scorm', 'name', 'mod_scorm'),
(155, 'survey', 'add', 'survey', 'name', 'mod_survey'),
(156, 'survey', 'update', 'survey', 'name', 'mod_survey'),
(157, 'survey', 'download', 'survey', 'name', 'mod_survey'),
(158, 'survey', 'view form', 'survey', 'name', 'mod_survey'),
(159, 'survey', 'view graph', 'survey', 'name', 'mod_survey'),
(160, 'survey', 'view report', 'survey', 'name', 'mod_survey'),
(161, 'survey', 'submit', 'survey', 'name', 'mod_survey'),
(162, 'url', 'view', 'url', 'name', 'mod_url'),
(163, 'url', 'view all', 'url', 'name', 'mod_url'),
(164, 'url', 'update', 'url', 'name', 'mod_url'),
(165, 'url', 'add', 'url', 'name', 'mod_url'),
(166, 'workshop', 'add', 'workshop', 'name', 'mod_workshop'),
(167, 'workshop', 'update', 'workshop', 'name', 'mod_workshop'),
(168, 'workshop', 'view', 'workshop', 'name', 'mod_workshop'),
(169, 'workshop', 'view all', 'workshop', 'name', 'mod_workshop'),
(170, 'workshop', 'add submission', 'workshop_submissions', 'title', 'mod_workshop'),
(171, 'workshop', 'update submission', 'workshop_submissions', 'title', 'mod_workshop'),
(172, 'workshop', 'view submission', 'workshop_submissions', 'title', 'mod_workshop'),
(173, 'workshop', 'add assessment', 'workshop_submissions', 'title', 'mod_workshop'),
(174, 'workshop', 'update assessment', 'workshop_submissions', 'title', 'mod_workshop'),
(175, 'workshop', 'add example', 'workshop_submissions', 'title', 'mod_workshop'),
(176, 'workshop', 'update example', 'workshop_submissions', 'title', 'mod_workshop'),
(177, 'workshop', 'view example', 'workshop_submissions', 'title', 'mod_workshop'),
(178, 'workshop', 'add reference assessment', 'workshop_submissions', 'title', 'mod_workshop'),
(179, 'workshop', 'update reference assessment', 'workshop_submissions', 'title', 'mod_workshop'),
(180, 'workshop', 'add example assessment', 'workshop_submissions', 'title', 'mod_workshop'),
(181, 'workshop', 'update example assessment', 'workshop_submissions', 'title', 'mod_workshop'),
(182, 'workshop', 'update aggregate grades', 'workshop', 'name', 'mod_workshop'),
(183, 'workshop', 'update clear aggregated grades', 'workshop', 'name', 'mod_workshop'),
(184, 'workshop', 'update clear assessments', 'workshop', 'name', 'mod_workshop'),
(185, 'book', 'exportimscp', 'book', 'name', 'booktool_exportimscp'),
(186, 'book', 'print', 'book', 'name', 'booktool_print'),
(187, 'book', 'print chapter', 'book_chapters', 'title', 'booktool_print');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_log_queries`
--

CREATE TABLE IF NOT EXISTS `mdl_log_queries` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `qtype` mediumint(5) NOT NULL,
  `sqltext` longtext NOT NULL,
  `sqlparams` longtext,
  `error` mediumint(5) NOT NULL DEFAULT '0',
  `info` longtext,
  `backtrace` longtext,
  `exectime` decimal(10,5) NOT NULL,
  `timelogged` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Logged database queries.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lti`
--

CREATE TABLE IF NOT EXISTS `mdl_lti` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `typeid` bigint(10) DEFAULT NULL,
  `toolurl` longtext NOT NULL,
  `securetoolurl` longtext,
  `instructorchoicesendname` tinyint(1) DEFAULT NULL,
  `instructorchoicesendemailaddr` tinyint(1) DEFAULT NULL,
  `instructorchoiceallowroster` tinyint(1) DEFAULT NULL,
  `instructorchoiceallowsetting` tinyint(1) DEFAULT NULL,
  `instructorcustomparameters` varchar(255) DEFAULT NULL,
  `instructorchoiceacceptgrades` tinyint(1) DEFAULT NULL,
  `grade` bigint(10) NOT NULL DEFAULT '100',
  `launchcontainer` tinyint(2) NOT NULL DEFAULT '1',
  `resourcekey` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `debuglaunch` tinyint(1) NOT NULL DEFAULT '0',
  `showtitlelaunch` tinyint(1) NOT NULL DEFAULT '0',
  `showdescriptionlaunch` tinyint(1) NOT NULL DEFAULT '0',
  `servicesalt` varchar(40) DEFAULT NULL,
  `icon` longtext,
  `secureicon` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_lti_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table contains Basic LTI activities instances' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lti_submission`
--

CREATE TABLE IF NOT EXISTS `mdl_lti_submission` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `ltiid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `datesubmitted` bigint(10) NOT NULL,
  `dateupdated` bigint(10) NOT NULL,
  `gradepercent` decimal(10,5) NOT NULL,
  `originalgrade` decimal(10,5) NOT NULL,
  `launchid` bigint(10) NOT NULL,
  `state` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_ltisubm_lti_ix` (`ltiid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Keeps track of individual submissions for LTI activities.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lti_tool_proxies`
--

CREATE TABLE IF NOT EXISTS `mdl_lti_tool_proxies` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'Tool Provider',
  `regurl` longtext,
  `state` tinyint(2) NOT NULL DEFAULT '1',
  `guid` varchar(255) DEFAULT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `vendorcode` varchar(255) DEFAULT NULL,
  `capabilityoffered` longtext NOT NULL,
  `serviceoffered` longtext NOT NULL,
  `toolproxy` longtext,
  `createdby` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_ltitoolprox_gui_uix` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='LTI tool proxy registrations' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lti_tool_settings`
--

CREATE TABLE IF NOT EXISTS `mdl_lti_tool_settings` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `toolproxyid` bigint(10) NOT NULL,
  `course` bigint(10) DEFAULT NULL,
  `coursemoduleid` bigint(10) DEFAULT NULL,
  `settings` longtext NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_ltitoolsett_too_ix` (`toolproxyid`),
  KEY `mdl_ltitoolsett_cou_ix` (`course`),
  KEY `mdl_ltitoolsett_cou2_ix` (`coursemoduleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='LTI tool setting values' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lti_types`
--

CREATE TABLE IF NOT EXISTS `mdl_lti_types` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'basiclti Activity',
  `baseurl` longtext NOT NULL,
  `tooldomain` varchar(255) NOT NULL DEFAULT '',
  `state` tinyint(2) NOT NULL DEFAULT '2',
  `course` bigint(10) NOT NULL,
  `coursevisible` tinyint(1) NOT NULL DEFAULT '0',
  `toolproxyid` bigint(10) DEFAULT NULL,
  `enabledcapability` longtext,
  `parameter` longtext,
  `icon` longtext,
  `secureicon` longtext,
  `createdby` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_ltitype_cou_ix` (`course`),
  KEY `mdl_ltitype_too_ix` (`tooldomain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Basic LTI pre-configured activities' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lti_types_config`
--

CREATE TABLE IF NOT EXISTS `mdl_lti_types_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `typeid` bigint(10) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_ltitypeconf_typ_ix` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Basic LTI types configuration' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message`
--

CREATE TABLE IF NOT EXISTS `mdl_message` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `useridfrom` bigint(10) NOT NULL DEFAULT '0',
  `useridto` bigint(10) NOT NULL DEFAULT '0',
  `subject` longtext,
  `fullmessage` longtext,
  `fullmessageformat` smallint(4) DEFAULT '0',
  `fullmessagehtml` longtext,
  `smallmessage` longtext,
  `notification` tinyint(1) DEFAULT '0',
  `contexturl` longtext,
  `contexturlname` longtext,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timeuserfromdeleted` bigint(10) NOT NULL DEFAULT '0',
  `timeusertodeleted` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_mess_use_ix` (`useridto`),
  KEY `mdl_mess_useusetimtim_ix` (`useridfrom`,`useridto`,`timeuserfromdeleted`,`timeusertodeleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores all unread messages' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_messageinbound_datakeys`
--

CREATE TABLE IF NOT EXISTS `mdl_messageinbound_datakeys` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `handler` bigint(10) NOT NULL,
  `datavalue` bigint(10) NOT NULL,
  `datakey` varchar(64) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  `expires` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_messdata_handat_uix` (`handler`,`datavalue`),
  KEY `mdl_messdata_han_ix` (`handler`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Inbound Message data item secret keys.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_messageinbound_handlers`
--

CREATE TABLE IF NOT EXISTS `mdl_messageinbound_handlers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `component` varchar(100) NOT NULL DEFAULT '',
  `classname` varchar(255) NOT NULL DEFAULT '',
  `defaultexpiration` bigint(10) NOT NULL DEFAULT '86400',
  `validateaddress` tinyint(1) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_messhand_cla_uix` (`classname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Inbound Message Handler definitions.' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mdl_messageinbound_handlers`
--

INSERT INTO `mdl_messageinbound_handlers` (`id`, `component`, `classname`, `defaultexpiration`, `validateaddress`, `enabled`) VALUES
(1, 'core', '\\core\\message\\inbound\\private_files_handler', 0, 1, 0),
(2, 'mod_forum', '\\mod_forum\\message\\inbound\\reply_handler', 604800, 1, 0),
(3, 'tool_messageinbound', '\\tool_messageinbound\\message\\inbound\\invalid_recipient_handler', 604800, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_messageinbound_messagelist`
--

CREATE TABLE IF NOT EXISTS `mdl_messageinbound_messagelist` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `messageid` longtext NOT NULL,
  `userid` bigint(10) NOT NULL,
  `address` longtext NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_messmess_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='A list of message IDs for existing replies' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message_airnotifier_devices`
--

CREATE TABLE IF NOT EXISTS `mdl_message_airnotifier_devices` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userdeviceid` bigint(10) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_messairndevi_use_uix` (`userdeviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store information about the devices registered in Airnotifie' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message_contacts`
--

CREATE TABLE IF NOT EXISTS `mdl_message_contacts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `contactid` bigint(10) NOT NULL DEFAULT '0',
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_messcont_usecon_uix` (`userid`,`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maintains lists of relationships between users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message_processors`
--

CREATE TABLE IF NOT EXISTS `mdl_message_processors` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(166) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='List of message output plugins' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mdl_message_processors`
--

INSERT INTO `mdl_message_processors` (`id`, `name`, `enabled`) VALUES
(1, 'airnotifier', 0),
(2, 'email', 1),
(3, 'jabber', 1),
(4, 'popup', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message_providers`
--

CREATE TABLE IF NOT EXISTS `mdl_message_providers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `component` varchar(200) NOT NULL DEFAULT '',
  `capability` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_messprov_comnam_uix` (`component`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table stores the message providers (modules and core sy' AUTO_INCREMENT=29 ;

--
-- Dumping data for table `mdl_message_providers`
--

INSERT INTO `mdl_message_providers` (`id`, `name`, `component`, `capability`) VALUES
(1, 'notices', 'moodle', 'moodle/site:config'),
(2, 'errors', 'moodle', 'moodle/site:config'),
(3, 'availableupdate', 'moodle', 'moodle/site:config'),
(4, 'instantmessage', 'moodle', NULL),
(5, 'backup', 'moodle', 'moodle/site:config'),
(6, 'courserequested', 'moodle', 'moodle/site:approvecourse'),
(7, 'courserequestapproved', 'moodle', 'moodle/course:request'),
(8, 'courserequestrejected', 'moodle', 'moodle/course:request'),
(9, 'badgerecipientnotice', 'moodle', 'moodle/badges:earnbadge'),
(10, 'badgecreatornotice', 'moodle', NULL),
(11, 'assign_notification', 'mod_assign', NULL),
(12, 'assignment_updates', 'mod_assignment', NULL),
(13, 'submission', 'mod_feedback', NULL),
(14, 'message', 'mod_feedback', NULL),
(15, 'posts', 'mod_forum', NULL),
(16, 'graded_essay', 'mod_lesson', NULL),
(17, 'submission', 'mod_quiz', 'mod/quiz:emailnotifysubmission'),
(18, 'confirmation', 'mod_quiz', 'mod/quiz:emailconfirmsubmission'),
(19, 'attempt_overdue', 'mod_quiz', 'mod/quiz:emailwarnoverdue'),
(20, 'flatfile_enrolment', 'enrol_flatfile', NULL),
(21, 'imsenterprise_enrolment', 'enrol_imsenterprise', NULL),
(22, 'expiry_notification', 'enrol_manual', NULL),
(23, 'paypal_enrolment', 'enrol_paypal', NULL),
(24, 'expiry_notification', 'enrol_self', NULL),
(25, 'invalidrecipienthandler', 'tool_messageinbound', NULL),
(26, 'messageprocessingerror', 'tool_messageinbound', NULL),
(27, 'messageprocessingsuccess', 'tool_messageinbound', NULL),
(28, 'notification', 'tool_monitor', 'tool/monitor:subscribe');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message_read`
--

CREATE TABLE IF NOT EXISTS `mdl_message_read` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `useridfrom` bigint(10) NOT NULL DEFAULT '0',
  `useridto` bigint(10) NOT NULL DEFAULT '0',
  `subject` longtext,
  `fullmessage` longtext,
  `fullmessageformat` smallint(4) DEFAULT '0',
  `fullmessagehtml` longtext,
  `smallmessage` longtext,
  `notification` tinyint(1) DEFAULT '0',
  `contexturl` longtext,
  `contexturlname` longtext,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timeread` bigint(10) NOT NULL DEFAULT '0',
  `timeuserfromdeleted` bigint(10) NOT NULL DEFAULT '0',
  `timeusertodeleted` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_messread_use_ix` (`useridto`),
  KEY `mdl_messread_useusetimtim_ix` (`useridfrom`,`useridto`,`timeuserfromdeleted`,`timeusertodeleted`),
  KEY `mdl_messread_nottim_ix` (`notification`,`timeread`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores all messages that have been read' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_message_working`
--

CREATE TABLE IF NOT EXISTS `mdl_message_working` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `unreadmessageid` bigint(10) NOT NULL,
  `processorid` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_messwork_unr_ix` (`unreadmessageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lists all the messages and processors that need to be proces' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnetservice_enrol_courses`
--

CREATE TABLE IF NOT EXISTS `mdl_mnetservice_enrol_courses` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hostid` bigint(10) NOT NULL,
  `remoteid` bigint(10) NOT NULL,
  `categoryid` bigint(10) NOT NULL,
  `categoryname` varchar(255) NOT NULL DEFAULT '',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `fullname` varchar(254) NOT NULL DEFAULT '',
  `shortname` varchar(100) NOT NULL DEFAULT '',
  `idnumber` varchar(100) NOT NULL DEFAULT '',
  `summary` longtext NOT NULL,
  `summaryformat` smallint(3) DEFAULT '0',
  `startdate` bigint(10) NOT NULL,
  `roleid` bigint(10) NOT NULL,
  `rolename` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_mnetenrocour_hosrem_uix` (`hostid`,`remoteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Caches the information fetched via XML-RPC about courses on ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnetservice_enrol_enrolments`
--

CREATE TABLE IF NOT EXISTS `mdl_mnetservice_enrol_enrolments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hostid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `remotecourseid` bigint(10) NOT NULL,
  `rolename` varchar(255) NOT NULL DEFAULT '',
  `enroltime` bigint(10) NOT NULL DEFAULT '0',
  `enroltype` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_mnetenroenro_use_ix` (`userid`),
  KEY `mdl_mnetenroenro_hos_ix` (`hostid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Caches the information about enrolments of our local users i' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_application`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_application` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `display_name` varchar(50) NOT NULL DEFAULT '',
  `xmlrpc_server_url` varchar(255) NOT NULL DEFAULT '',
  `sso_land_url` varchar(255) NOT NULL DEFAULT '',
  `sso_jump_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Information about applications on remote hosts' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mdl_mnet_application`
--

INSERT INTO `mdl_mnet_application` (`id`, `name`, `display_name`, `xmlrpc_server_url`, `sso_land_url`, `sso_jump_url`) VALUES
(1, 'moodle', 'Moodle', '/mnet/xmlrpc/server.php', '/auth/mnet/land.php', '/auth/mnet/jump.php'),
(2, 'mahara', 'Mahara', '/api/xmlrpc/server.php', '/auth/xmlrpc/land.php', '/auth/xmlrpc/jump.php');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_host`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_host` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `wwwroot` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `public_key` longtext NOT NULL,
  `public_key_expires` bigint(10) NOT NULL DEFAULT '0',
  `transport` tinyint(2) NOT NULL DEFAULT '0',
  `portno` mediumint(5) NOT NULL DEFAULT '0',
  `last_connect_time` bigint(10) NOT NULL DEFAULT '0',
  `last_log_id` bigint(10) NOT NULL DEFAULT '0',
  `force_theme` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(100) DEFAULT NULL,
  `applicationid` bigint(10) NOT NULL DEFAULT '1',
  `sslverification` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_mnethost_app_ix` (`applicationid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Information about the local and remote hosts for RPC' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mdl_mnet_host`
--

INSERT INTO `mdl_mnet_host` (`id`, `deleted`, `wwwroot`, `ip_address`, `name`, `public_key`, `public_key_expires`, `transport`, `portno`, `last_connect_time`, `last_log_id`, `force_theme`, `theme`, `applicationid`, `sslverification`) VALUES
(1, 0, 'http://mycodebusters.com/medical/lms', '173.254.28.144', '', '', 0, 0, 0, 0, 0, 0, NULL, 1, 0),
(2, 0, '', '', 'All Hosts', '', 0, 0, 0, 0, 0, 0, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_host2service`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_host2service` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hostid` bigint(10) NOT NULL DEFAULT '0',
  `serviceid` bigint(10) NOT NULL DEFAULT '0',
  `publish` tinyint(1) NOT NULL DEFAULT '0',
  `subscribe` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_mnethost_hosser_uix` (`hostid`,`serviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Information about the services for a given host' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_log`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hostid` bigint(10) NOT NULL DEFAULT '0',
  `remoteid` bigint(10) NOT NULL DEFAULT '0',
  `time` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL DEFAULT '',
  `course` bigint(10) NOT NULL DEFAULT '0',
  `coursename` varchar(40) NOT NULL DEFAULT '',
  `module` varchar(20) NOT NULL DEFAULT '',
  `cmid` bigint(10) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `info` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_mnetlog_hosusecou_ix` (`hostid`,`userid`,`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store session data from users migrating to other sites' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_remote_rpc`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_remote_rpc` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `functionname` varchar(40) NOT NULL DEFAULT '',
  `xmlrpcpath` varchar(80) NOT NULL DEFAULT '',
  `plugintype` varchar(20) NOT NULL DEFAULT '',
  `pluginname` varchar(20) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table describes functions that might be called remotely' AUTO_INCREMENT=17 ;

--
-- Dumping data for table `mdl_mnet_remote_rpc`
--

INSERT INTO `mdl_mnet_remote_rpc` (`id`, `functionname`, `xmlrpcpath`, `plugintype`, `pluginname`, `enabled`) VALUES
(1, 'user_authorise', 'auth/mnet/auth.php/user_authorise', 'auth', 'mnet', 1),
(2, 'keepalive_server', 'auth/mnet/auth.php/keepalive_server', 'auth', 'mnet', 1),
(3, 'kill_children', 'auth/mnet/auth.php/kill_children', 'auth', 'mnet', 1),
(4, 'refresh_log', 'auth/mnet/auth.php/refresh_log', 'auth', 'mnet', 1),
(5, 'fetch_user_image', 'auth/mnet/auth.php/fetch_user_image', 'auth', 'mnet', 1),
(6, 'fetch_theme_info', 'auth/mnet/auth.php/fetch_theme_info', 'auth', 'mnet', 1),
(7, 'update_enrolments', 'auth/mnet/auth.php/update_enrolments', 'auth', 'mnet', 1),
(8, 'keepalive_client', 'auth/mnet/auth.php/keepalive_client', 'auth', 'mnet', 1),
(9, 'kill_child', 'auth/mnet/auth.php/kill_child', 'auth', 'mnet', 1),
(10, 'available_courses', 'enrol/mnet/enrol.php/available_courses', 'enrol', 'mnet', 1),
(11, 'user_enrolments', 'enrol/mnet/enrol.php/user_enrolments', 'enrol', 'mnet', 1),
(12, 'enrol_user', 'enrol/mnet/enrol.php/enrol_user', 'enrol', 'mnet', 1),
(13, 'unenrol_user', 'enrol/mnet/enrol.php/unenrol_user', 'enrol', 'mnet', 1),
(14, 'course_enrolments', 'enrol/mnet/enrol.php/course_enrolments', 'enrol', 'mnet', 1),
(15, 'send_content_intent', 'portfolio/mahara/lib.php/send_content_intent', 'portfolio', 'mahara', 1),
(16, 'send_content_ready', 'portfolio/mahara/lib.php/send_content_ready', 'portfolio', 'mahara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_remote_service2rpc`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_remote_service2rpc` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `serviceid` bigint(10) NOT NULL DEFAULT '0',
  `rpcid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_mnetremoserv_rpcser_uix` (`rpcid`,`serviceid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Group functions or methods under a service' AUTO_INCREMENT=17 ;

--
-- Dumping data for table `mdl_mnet_remote_service2rpc`
--

INSERT INTO `mdl_mnet_remote_service2rpc` (`id`, `serviceid`, `rpcid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 2, 8),
(9, 2, 9),
(10, 3, 10),
(11, 3, 11),
(12, 3, 12),
(13, 3, 13),
(14, 3, 14),
(15, 4, 15),
(16, 4, 16);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_rpc`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_rpc` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `functionname` varchar(40) NOT NULL DEFAULT '',
  `xmlrpcpath` varchar(80) NOT NULL DEFAULT '',
  `plugintype` varchar(20) NOT NULL DEFAULT '',
  `pluginname` varchar(20) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `help` longtext NOT NULL,
  `profile` longtext NOT NULL,
  `filename` varchar(100) NOT NULL DEFAULT '',
  `classname` varchar(150) DEFAULT NULL,
  `static` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_mnetrpc_enaxml_ix` (`enabled`,`xmlrpcpath`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Functions or methods that we may publish or subscribe to' AUTO_INCREMENT=16 ;

--
-- Dumping data for table `mdl_mnet_rpc`
--

INSERT INTO `mdl_mnet_rpc` (`id`, `functionname`, `xmlrpcpath`, `plugintype`, `pluginname`, `enabled`, `help`, `profile`, `filename`, `classname`, `static`) VALUES
(1, 'user_authorise', 'auth/mnet/auth.php/user_authorise', 'auth', 'mnet', 1, 'Return user data for the provided token, compare with user_agent string.', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:5:"token";s:4:"type";s:6:"string";s:11:"description";s:37:"The unique ID provided by remotehost.";}i:1;a:3:{s:4:"name";s:9:"useragent";s:4:"type";s:6:"string";s:11:"description";s:18:"User Agent string.";}}s:6:"return";a:2:{s:4:"type";s:5:"array";s:11:"description";s:44:"$userdata Array of user info for remote host";}}', 'auth.php', 'auth_plugin_mnet', 0),
(2, 'keepalive_server', 'auth/mnet/auth.php/keepalive_server', 'auth', 'mnet', 1, 'Receives an array of usernames from a remote machine and prods their\nsessions to keep them alive', 'a:2:{s:10:"parameters";a:1:{i:0;a:3:{s:4:"name";s:5:"array";s:4:"type";s:5:"array";s:11:"description";s:21:"An array of usernames";}}s:6:"return";a:2:{s:4:"type";s:6:"string";s:11:"description";s:28:""All ok" or an error message";}}', 'auth.php', 'auth_plugin_mnet', 0),
(3, 'kill_children', 'auth/mnet/auth.php/kill_children', 'auth', 'mnet', 1, 'The IdP uses this function to kill child sessions on other hosts', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:8:"username";s:4:"type";s:6:"string";s:11:"description";s:28:"Username for session to kill";}i:1;a:3:{s:4:"name";s:9:"useragent";s:4:"type";s:6:"string";s:11:"description";s:35:"SHA1 hash of user agent to look for";}}s:6:"return";a:2:{s:4:"type";s:6:"string";s:11:"description";s:39:"A plaintext report of what has happened";}}', 'auth.php', 'auth_plugin_mnet', 0),
(4, 'refresh_log', 'auth/mnet/auth.php/refresh_log', 'auth', 'mnet', 1, 'Receives an array of log entries from an SP and adds them to the mnet_log\ntable', 'a:2:{s:10:"parameters";a:1:{i:0;a:3:{s:4:"name";s:5:"array";s:4:"type";s:5:"array";s:11:"description";s:21:"An array of usernames";}}s:6:"return";a:2:{s:4:"type";s:6:"string";s:11:"description";s:28:""All ok" or an error message";}}', 'auth.php', 'auth_plugin_mnet', 0),
(5, 'fetch_user_image', 'auth/mnet/auth.php/fetch_user_image', 'auth', 'mnet', 1, 'Returns the user''s profile image info\nIf the user exists and has a profile picture, the returned array will contain keys:\n f1          - the content of the default 100x100px image\n f1_mimetype - the mimetype of the f1 file\n f2          - the content of the 35x35px variant of the image\n f2_mimetype - the mimetype of the f2 file\nThe mimetype information was added in Moodle 2.0. In Moodle 1.x, images are always jpegs.', 'a:2:{s:10:"parameters";a:1:{i:0;a:3:{s:4:"name";s:8:"username";s:4:"type";s:3:"int";s:11:"description";s:18:"The id of the user";}}s:6:"return";a:2:{s:4:"type";s:5:"array";s:11:"description";s:84:"false if user not found, empty array if no picture exists, array with data otherwise";}}', 'auth.php', 'auth_plugin_mnet', 0),
(6, 'fetch_theme_info', 'auth/mnet/auth.php/fetch_theme_info', 'auth', 'mnet', 1, 'Returns the theme information and logo url as strings.', 'a:2:{s:10:"parameters";a:0:{}s:6:"return";a:2:{s:4:"type";s:6:"string";s:11:"description";s:14:"The theme info";}}', 'auth.php', 'auth_plugin_mnet', 0),
(7, 'update_enrolments', 'auth/mnet/auth.php/update_enrolments', 'auth', 'mnet', 1, 'Invoke this function _on_ the IDP to update it with enrolment info local to\nthe SP right after calling user_authorise()\nNormally called by the SP after calling user_authorise()', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:8:"username";s:4:"type";s:6:"string";s:11:"description";s:12:"The username";}i:1;a:3:{s:4:"name";s:7:"courses";s:4:"type";s:5:"array";s:11:"description";s:75:"Assoc array of courses following the structure of mnetservice_enrol_courses";}}s:6:"return";a:2:{s:4:"type";s:4:"bool";s:11:"description";s:0:"";}}', 'auth.php', 'auth_plugin_mnet', 0),
(8, 'keepalive_client', 'auth/mnet/auth.php/keepalive_client', 'auth', 'mnet', 1, 'Poll the IdP server to let it know that a user it has authenticated is still\nonline', 'a:2:{s:10:"parameters";a:0:{}s:6:"return";a:2:{s:4:"type";s:4:"void";s:11:"description";s:0:"";}}', 'auth.php', 'auth_plugin_mnet', 0),
(9, 'kill_child', 'auth/mnet/auth.php/kill_child', 'auth', 'mnet', 1, 'When the IdP requests that child sessions are terminated,\nthis function will be called on each of the child hosts. The machine that\ncalls the function (over xmlrpc) provides us with the mnethostid we need.', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:8:"username";s:4:"type";s:6:"string";s:11:"description";s:28:"Username for session to kill";}i:1;a:3:{s:4:"name";s:9:"useragent";s:4:"type";s:6:"string";s:11:"description";s:35:"SHA1 hash of user agent to look for";}}s:6:"return";a:2:{s:4:"type";s:4:"bool";s:11:"description";s:15:"True on success";}}', 'auth.php', 'auth_plugin_mnet', 0),
(10, 'available_courses', 'enrol/mnet/enrol.php/available_courses', 'enrol', 'mnet', 1, 'Returns list of courses that we offer to the caller for remote enrolment of their users\nSince Moodle 2.0, courses are made available for MNet peers by creating an instance\nof enrol_mnet plugin for the course. Hidden courses are not returned. If there are two\ninstances - one specific for the host and one for ''All hosts'', the setting of the specific\none is used. The id of the peer is kept in customint1, no other custom fields are used.', 'a:2:{s:10:"parameters";a:0:{}s:6:"return";a:2:{s:4:"type";s:5:"array";s:11:"description";s:0:"";}}', 'enrol.php', 'enrol_mnet_mnetservice_enrol', 0),
(11, 'user_enrolments', 'enrol/mnet/enrol.php/user_enrolments', 'enrol', 'mnet', 1, 'This method has never been implemented in Moodle MNet API', 'a:2:{s:10:"parameters";a:0:{}s:6:"return";a:2:{s:4:"type";s:5:"array";s:11:"description";s:11:"empty array";}}', 'enrol.php', 'enrol_mnet_mnetservice_enrol', 0),
(12, 'enrol_user', 'enrol/mnet/enrol.php/enrol_user', 'enrol', 'mnet', 1, 'Enrol remote user to our course\nIf we do not have local record for the remote user in our database,\nit gets created here.', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:8:"userdata";s:4:"type";s:5:"array";s:11:"description";s:14:"user details {";}i:1;a:3:{s:4:"name";s:8:"courseid";s:4:"type";s:3:"int";s:11:"description";s:19:"our local course id";}}s:6:"return";a:2:{s:4:"type";s:4:"bool";s:11:"description";s:69:"true if the enrolment has been successful, throws exception otherwise";}}', 'enrol.php', 'enrol_mnet_mnetservice_enrol', 0),
(13, 'unenrol_user', 'enrol/mnet/enrol.php/unenrol_user', 'enrol', 'mnet', 1, 'Unenrol remote user from our course\nOnly users enrolled via enrol_mnet plugin can be unenrolled remotely. If the\nremote user is enrolled into the local course via some other enrol plugin\n(enrol_manual for example), the remote host can''t touch such enrolment. Please\ndo not report this behaviour as bug, it is a feature ;-)', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:8:"username";s:4:"type";s:6:"string";s:11:"description";s:18:"of the remote user";}i:1;a:3:{s:4:"name";s:8:"courseid";s:4:"type";s:3:"int";s:11:"description";s:19:"of our local course";}}s:6:"return";a:2:{s:4:"type";s:4:"bool";s:11:"description";s:71:"true if the unenrolment has been successful, throws exception otherwise";}}', 'enrol.php', 'enrol_mnet_mnetservice_enrol', 0),
(14, 'course_enrolments', 'enrol/mnet/enrol.php/course_enrolments', 'enrol', 'mnet', 1, 'Returns a list of users from the client server who are enrolled in our course\nSuitable instance of enrol_mnet must be created in the course. This method will not\nreturn any information about the enrolments in courses that are not available for\nremote enrolment, even if their users are enrolled into them via other plugin\n(note the difference from {@link self::user_enrolments()}).\nThis method will return enrolment information for users from hosts regardless\nthe enrolment plugin. It does not matter if the user was enrolled remotely by\ntheir admin or locally. Once the course is available for remote enrolments, we\nwill tell them everything about their users.\nIn Moodle 1.x the returned array used to be indexed by username. The side effect\nof MDL-19219 fix is that we do not need to use such index and therefore we can\nreturn all enrolment records. MNet clients 1.x will only use the last record for\nthe student, if she is enrolled via multiple plugins.', 'a:2:{s:10:"parameters";a:2:{i:0;a:3:{s:4:"name";s:8:"courseid";s:4:"type";s:3:"int";s:11:"description";s:16:"ID of our course";}i:1;a:3:{s:4:"name";s:5:"roles";s:4:"type";s:5:"array";s:11:"description";s:58:"comma separated list of role shortnames (or array of them)";}}s:6:"return";a:2:{s:4:"type";s:5:"array";s:11:"description";s:0:"";}}', 'enrol.php', 'enrol_mnet_mnetservice_enrol', 0),
(15, 'fetch_file', 'portfolio/mahara/lib.php/fetch_file', 'portfolio', 'mahara', 1, 'xmlrpc (mnet) function to get the file.\nreads in the file and returns it base_64 encoded\nso that it can be enrypted by mnet.', 'a:2:{s:10:"parameters";a:1:{i:0;a:3:{s:4:"name";s:5:"token";s:4:"type";s:6:"string";s:11:"description";s:56:"the token recieved previously during send_content_intent";}}s:6:"return";a:2:{s:4:"type";s:4:"void";s:11:"description";s:0:"";}}', 'lib.php', 'portfolio_plugin_mahara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_service`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_service` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(40) NOT NULL DEFAULT '',
  `apiversion` varchar(10) NOT NULL DEFAULT '',
  `offer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='A service is a group of functions' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mdl_mnet_service`
--

INSERT INTO `mdl_mnet_service` (`id`, `name`, `description`, `apiversion`, `offer`) VALUES
(1, 'sso_idp', '', '1', 1),
(2, 'sso_sp', '', '1', 1),
(3, 'mnet_enrol', '', '1', 1),
(4, 'pf', '', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_service2rpc`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_service2rpc` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `serviceid` bigint(10) NOT NULL DEFAULT '0',
  `rpcid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_mnetserv_rpcser_uix` (`rpcid`,`serviceid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Group functions or methods under a service' AUTO_INCREMENT=16 ;

--
-- Dumping data for table `mdl_mnet_service2rpc`
--

INSERT INTO `mdl_mnet_service2rpc` (`id`, `serviceid`, `rpcid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 2, 8),
(9, 2, 9),
(10, 3, 10),
(11, 3, 11),
(12, 3, 12),
(13, 3, 13),
(14, 3, 14),
(15, 4, 15);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_session`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_session` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL DEFAULT '',
  `token` varchar(40) NOT NULL DEFAULT '',
  `mnethostid` bigint(10) NOT NULL DEFAULT '0',
  `useragent` varchar(40) NOT NULL DEFAULT '',
  `confirm_timeout` bigint(10) NOT NULL DEFAULT '0',
  `session_id` varchar(40) NOT NULL DEFAULT '',
  `expires` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_mnetsess_tok_uix` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store session data from users migrating to other sites' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_mnet_sso_access_control`
--

CREATE TABLE IF NOT EXISTS `mdl_mnet_sso_access_control` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `mnet_host_id` bigint(10) NOT NULL DEFAULT '0',
  `accessctrl` varchar(20) NOT NULL DEFAULT 'allow',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_mnetssoaccecont_mneuse_uix` (`mnet_host_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Users by host permitted (or not) to login from a remote prov' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_modules`
--

CREATE TABLE IF NOT EXISTS `mdl_modules` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `cron` bigint(10) NOT NULL DEFAULT '0',
  `lastcron` bigint(10) NOT NULL DEFAULT '0',
  `search` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_modu_nam_ix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='modules available in the site' AUTO_INCREMENT=23 ;

--
-- Dumping data for table `mdl_modules`
--

INSERT INTO `mdl_modules` (`id`, `name`, `cron`, `lastcron`, `search`, `visible`) VALUES
(1, 'assign', 60, 0, '', 1),
(2, 'assignment', 60, 0, '', 0),
(3, 'book', 0, 0, '', 1),
(4, 'chat', 300, 0, '', 1),
(5, 'choice', 0, 0, '', 1),
(6, 'data', 0, 0, '', 1),
(7, 'feedback', 0, 0, '', 0),
(8, 'folder', 0, 0, '', 1),
(9, 'forum', 0, 0, '', 1),
(10, 'glossary', 0, 0, '', 1),
(11, 'imscp', 0, 0, '', 1),
(12, 'label', 0, 0, '', 1),
(13, 'lesson', 0, 0, '', 1),
(14, 'lti', 0, 0, '', 1),
(15, 'page', 0, 0, '', 1),
(16, 'quiz', 60, 0, '', 1),
(17, 'resource', 0, 0, '', 1),
(18, 'scorm', 300, 0, '', 1),
(19, 'survey', 0, 0, '', 1),
(20, 'url', 0, 0, '', 1),
(21, 'wiki', 0, 0, '', 1),
(22, 'workshop', 60, 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_my_pages`
--

CREATE TABLE IF NOT EXISTS `mdl_my_pages` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `private` tinyint(1) NOT NULL DEFAULT '1',
  `sortorder` mediumint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_mypage_usepri_ix` (`userid`,`private`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Extra user pages for the My Moodle system' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `mdl_my_pages`
--

INSERT INTO `mdl_my_pages` (`id`, `userid`, `name`, `private`, `sortorder`) VALUES
(1, NULL, '__default', 0, 0),
(2, NULL, '__default', 1, 0),
(3, 2, '__default', 1, 0),
(4, 3, '__default', 1, 0),
(5, 3, '__default', 0, 0),
(6, 2, '__default', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_nursing_school_map`
--

CREATE TABLE IF NOT EXISTS `mdl_nursing_school_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL,
  `school_title` varchar(128) NOT NULL,
  `lat` float NOT NULL DEFAULT '0',
  `lng` float NOT NULL DEFAULT '0',
  `marker_text` varchar(255) NOT NULL DEFAULT '',
  `marker_link` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mdl_nursing_school_map`
--

INSERT INTO `mdl_nursing_school_map` (`id`, `courseid`, `school_title`, `lat`, `lng`, `marker_text`, `marker_link`) VALUES
(1, 7, 'Nursing school at some location #1', 39.29, -76.6, 'Baltimore, Md.', ''),
(2, 8, 'Nursing school at some location #2', 42.36, -71.03, 'Boston, Mass.', ''),
(3, 9, 'Nursing school at some location #3', 42.55, -78.05, 'Buffalo, N.Y.', ''),
(4, 10, 'Nursing school at some location #4', 34, -81.02, 'Columbia, S.C.', ''),
(5, 11, 'Nursing school at some location #5', 42.2, -83.03, 'Detroit, Mich.', '');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_page`
--

CREATE TABLE IF NOT EXISTS `mdl_page` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `content` longtext,
  `contentformat` smallint(4) NOT NULL DEFAULT '0',
  `legacyfiles` smallint(4) NOT NULL DEFAULT '0',
  `legacyfileslast` bigint(10) DEFAULT NULL,
  `display` smallint(4) NOT NULL DEFAULT '0',
  `displayoptions` longtext,
  `revision` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_page_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each record is one page and its config data' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_portfolio_instance`
--

CREATE TABLE IF NOT EXISTS `mdl_portfolio_instance` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `plugin` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='base table (not including config data) for instances of port' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_portfolio_instance_config`
--

CREATE TABLE IF NOT EXISTS `mdl_portfolio_instance_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `instance` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_portinstconf_nam_ix` (`name`),
  KEY `mdl_portinstconf_ins_ix` (`instance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='config for portfolio plugin instances' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_portfolio_instance_user`
--

CREATE TABLE IF NOT EXISTS `mdl_portfolio_instance_user` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `instance` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  KEY `mdl_portinstuser_ins_ix` (`instance`),
  KEY `mdl_portinstuser_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user data for portfolio instances.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_portfolio_log`
--

CREATE TABLE IF NOT EXISTS `mdl_portfolio_log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `time` bigint(10) NOT NULL,
  `portfolio` bigint(10) NOT NULL,
  `caller_class` varchar(150) NOT NULL DEFAULT '',
  `caller_file` varchar(255) NOT NULL DEFAULT '',
  `caller_component` varchar(255) DEFAULT NULL,
  `caller_sha1` varchar(255) NOT NULL DEFAULT '',
  `tempdataid` bigint(10) NOT NULL DEFAULT '0',
  `returnurl` varchar(255) NOT NULL DEFAULT '',
  `continueurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_portlog_use_ix` (`userid`),
  KEY `mdl_portlog_por_ix` (`portfolio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='log of portfolio transfers (used to later check for duplicat' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_portfolio_mahara_queue`
--

CREATE TABLE IF NOT EXISTS `mdl_portfolio_mahara_queue` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `transferid` bigint(10) NOT NULL,
  `token` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_portmahaqueu_tok_ix` (`token`),
  KEY `mdl_portmahaqueu_tra_ix` (`transferid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='maps mahara tokens to transfer ids' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_portfolio_tempdata`
--

CREATE TABLE IF NOT EXISTS `mdl_portfolio_tempdata` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `data` longtext,
  `expirytime` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `instance` bigint(10) DEFAULT '0',
  `queued` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_porttemp_use_ix` (`userid`),
  KEY `mdl_porttemp_ins_ix` (`instance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='stores temporary data for portfolio exports. the id of this ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_post`
--

CREATE TABLE IF NOT EXISTS `mdl_post` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `module` varchar(20) NOT NULL DEFAULT '',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `moduleid` bigint(10) NOT NULL DEFAULT '0',
  `coursemoduleid` bigint(10) NOT NULL DEFAULT '0',
  `subject` varchar(128) NOT NULL DEFAULT '',
  `summary` longtext,
  `content` longtext,
  `uniquehash` varchar(255) NOT NULL DEFAULT '',
  `rating` bigint(10) NOT NULL DEFAULT '0',
  `format` bigint(10) NOT NULL DEFAULT '0',
  `summaryformat` tinyint(2) NOT NULL DEFAULT '0',
  `attachment` varchar(100) DEFAULT NULL,
  `publishstate` varchar(20) NOT NULL DEFAULT 'draft',
  `lastmodified` bigint(10) NOT NULL DEFAULT '0',
  `created` bigint(10) NOT NULL DEFAULT '0',
  `usermodified` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_post_iduse_uix` (`id`,`userid`),
  KEY `mdl_post_las_ix` (`lastmodified`),
  KEY `mdl_post_mod_ix` (`module`),
  KEY `mdl_post_sub_ix` (`subject`),
  KEY `mdl_post_use_ix` (`usermodified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Generic post table to hold data blog entries etc in differen' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_profiling`
--

CREATE TABLE IF NOT EXISTS `mdl_profiling` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `runid` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `data` longtext NOT NULL,
  `totalexecutiontime` bigint(10) NOT NULL,
  `totalcputime` bigint(10) NOT NULL,
  `totalcalls` bigint(10) NOT NULL,
  `totalmemory` bigint(10) NOT NULL,
  `runreference` tinyint(2) NOT NULL DEFAULT '0',
  `runcomment` varchar(255) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_prof_run_uix` (`runid`),
  KEY `mdl_prof_urlrun_ix` (`url`,`runreference`),
  KEY `mdl_prof_timrun_ix` (`timecreated`,`runreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the results of all the profiling runs' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_ddimageortext`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_ddimageortext` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_qtypddim_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines drag and drop (text or images onto a background imag' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_ddimageortext_drags`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_ddimageortext_drags` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `no` bigint(10) NOT NULL DEFAULT '0',
  `draggroup` bigint(10) NOT NULL DEFAULT '0',
  `infinite` smallint(4) NOT NULL DEFAULT '0',
  `label` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_qtypddimdrag_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Images to drag. Actual file names are not stored here we use' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_ddimageortext_drops`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_ddimageortext_drops` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `no` bigint(10) NOT NULL DEFAULT '0',
  `xleft` bigint(10) NOT NULL DEFAULT '0',
  `ytop` bigint(10) NOT NULL DEFAULT '0',
  `choice` bigint(10) NOT NULL DEFAULT '0',
  `label` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_qtypddimdrop_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Drop boxes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_ddmarker`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_ddmarker` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  `showmisplaced` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_qtypddma_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines drag and drop (text or images onto a background imag' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_ddmarker_drags`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_ddmarker_drags` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `no` bigint(10) NOT NULL DEFAULT '0',
  `label` longtext NOT NULL,
  `infinite` smallint(4) NOT NULL DEFAULT '0',
  `noofdrags` bigint(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_qtypddmadrag_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Labels for markers to drag.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_ddmarker_drops`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_ddmarker_drops` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `no` bigint(10) NOT NULL DEFAULT '0',
  `shape` varchar(255) DEFAULT NULL,
  `coords` longtext NOT NULL,
  `choice` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_qtypddmadrop_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='drop regions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_essay_options`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_essay_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL,
  `responseformat` varchar(16) NOT NULL DEFAULT 'editor',
  `responserequired` tinyint(2) NOT NULL DEFAULT '1',
  `responsefieldlines` smallint(4) NOT NULL DEFAULT '15',
  `attachments` smallint(4) NOT NULL DEFAULT '0',
  `attachmentsrequired` smallint(4) NOT NULL DEFAULT '0',
  `graderinfo` longtext,
  `graderinfoformat` smallint(4) NOT NULL DEFAULT '0',
  `responsetemplate` longtext,
  `responsetemplateformat` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_qtypessaopti_que_uix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extra options for essay questions.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_match_options`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_match_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_qtypmatcopti_que_uix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines the question-type specific options for matching ques' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_match_subquestions`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_match_subquestions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `questiontext` longtext NOT NULL,
  `questiontextformat` tinyint(2) NOT NULL DEFAULT '0',
  `answertext` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_qtypmatcsubq_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The subquestions that make up a matching question' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_multichoice_options`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_multichoice_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `layout` smallint(4) NOT NULL DEFAULT '0',
  `single` smallint(4) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `answernumbering` varchar(10) NOT NULL DEFAULT 'abc',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_qtypmultopti_que_uix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for multiple choice questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_randomsamatch_options`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_randomsamatch_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `choose` bigint(10) NOT NULL DEFAULT '4',
  `subcats` tinyint(2) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_qtyprandopti_que_uix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about a random short-answer matching question' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qtype_shortanswer_options`
--

CREATE TABLE IF NOT EXISTS `mdl_qtype_shortanswer_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `usecase` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_qtypshoropti_que_uix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for short answer questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question`
--

CREATE TABLE IF NOT EXISTS `mdl_question` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `category` bigint(10) NOT NULL DEFAULT '0',
  `parent` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `questiontext` longtext NOT NULL,
  `questiontextformat` tinyint(2) NOT NULL DEFAULT '0',
  `generalfeedback` longtext NOT NULL,
  `generalfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `defaultmark` decimal(12,7) NOT NULL DEFAULT '1.0000000',
  `penalty` decimal(12,7) NOT NULL DEFAULT '0.3333333',
  `qtype` varchar(20) NOT NULL DEFAULT '',
  `length` bigint(10) NOT NULL DEFAULT '1',
  `stamp` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(255) NOT NULL DEFAULT '',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `createdby` bigint(10) DEFAULT NULL,
  `modifiedby` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_ques_qty_ix` (`qtype`),
  KEY `mdl_ques_cat_ix` (`category`),
  KEY `mdl_ques_par_ix` (`parent`),
  KEY `mdl_ques_cre_ix` (`createdby`),
  KEY `mdl_ques_mod_ix` (`modifiedby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The questions themselves' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_answers`
--

CREATE TABLE IF NOT EXISTS `mdl_question_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `answer` longtext NOT NULL,
  `answerformat` tinyint(2) NOT NULL DEFAULT '0',
  `fraction` decimal(12,7) NOT NULL DEFAULT '0.0000000',
  `feedback` longtext NOT NULL,
  `feedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quesansw_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Answers, with a fractional grade (0-1) and feedback' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_attempts`
--

CREATE TABLE IF NOT EXISTS `mdl_question_attempts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionusageid` bigint(10) NOT NULL,
  `slot` bigint(10) NOT NULL,
  `behaviour` varchar(32) NOT NULL DEFAULT '',
  `questionid` bigint(10) NOT NULL,
  `variant` bigint(10) NOT NULL DEFAULT '1',
  `maxmark` decimal(12,7) NOT NULL,
  `minfraction` decimal(12,7) NOT NULL,
  `maxfraction` decimal(12,7) NOT NULL DEFAULT '1.0000000',
  `flagged` tinyint(1) NOT NULL DEFAULT '0',
  `questionsummary` longtext,
  `rightanswer` longtext,
  `responsesummary` longtext,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quesatte_queslo_uix` (`questionusageid`,`slot`),
  KEY `mdl_quesatte_beh_ix` (`behaviour`),
  KEY `mdl_quesatte_que_ix` (`questionid`),
  KEY `mdl_quesatte_que2_ix` (`questionusageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each row here corresponds to an attempt at one question, as ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_attempt_steps`
--

CREATE TABLE IF NOT EXISTS `mdl_question_attempt_steps` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionattemptid` bigint(10) NOT NULL,
  `sequencenumber` bigint(10) NOT NULL,
  `state` varchar(13) NOT NULL DEFAULT '',
  `fraction` decimal(12,7) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL,
  `userid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quesattestep_queseq_uix` (`questionattemptid`,`sequencenumber`),
  KEY `mdl_quesattestep_que_ix` (`questionattemptid`),
  KEY `mdl_quesattestep_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores one step in in a question attempt. As well as the dat' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_attempt_step_data`
--

CREATE TABLE IF NOT EXISTS `mdl_question_attempt_step_data` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `attemptstepid` bigint(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quesattestepdata_attna_uix` (`attemptstepid`,`name`),
  KEY `mdl_quesattestepdata_att_ix` (`attemptstepid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each question_attempt_step has an associative array of the d' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_calculated`
--

CREATE TABLE IF NOT EXISTS `mdl_question_calculated` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `answer` bigint(10) NOT NULL DEFAULT '0',
  `tolerance` varchar(20) NOT NULL DEFAULT '0.0',
  `tolerancetype` bigint(10) NOT NULL DEFAULT '1',
  `correctanswerlength` bigint(10) NOT NULL DEFAULT '2',
  `correctanswerformat` bigint(10) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `mdl_quescalc_ans_ix` (`answer`),
  KEY `mdl_quescalc_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for questions of type calculated' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_calculated_options`
--

CREATE TABLE IF NOT EXISTS `mdl_question_calculated_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `synchronize` tinyint(2) NOT NULL DEFAULT '0',
  `single` smallint(4) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '0',
  `correctfeedback` longtext,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `answernumbering` varchar(10) NOT NULL DEFAULT 'abc',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quescalcopti_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for questions of type calculated' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_categories`
--

CREATE TABLE IF NOT EXISTS `mdl_question_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contextid` bigint(10) NOT NULL DEFAULT '0',
  `info` longtext NOT NULL,
  `infoformat` tinyint(2) NOT NULL DEFAULT '0',
  `stamp` varchar(255) NOT NULL DEFAULT '',
  `parent` bigint(10) NOT NULL DEFAULT '0',
  `sortorder` bigint(10) NOT NULL DEFAULT '999',
  PRIMARY KEY (`id`),
  KEY `mdl_quescate_con_ix` (`contextid`),
  KEY `mdl_quescate_par_ix` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Categories are for grouping questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_datasets`
--

CREATE TABLE IF NOT EXISTS `mdl_question_datasets` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `datasetdefinition` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quesdata_quedat_ix` (`question`,`datasetdefinition`),
  KEY `mdl_quesdata_que_ix` (`question`),
  KEY `mdl_quesdata_dat_ix` (`datasetdefinition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Many-many relation between questions and dataset definitions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_dataset_definitions`
--

CREATE TABLE IF NOT EXISTS `mdl_question_dataset_definitions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `category` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` bigint(10) NOT NULL DEFAULT '0',
  `options` varchar(255) NOT NULL DEFAULT '',
  `itemcount` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quesdatadefi_cat_ix` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Organises and stores properties for dataset items' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_dataset_items`
--

CREATE TABLE IF NOT EXISTS `mdl_question_dataset_items` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `definition` bigint(10) NOT NULL DEFAULT '0',
  `itemnumber` bigint(10) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_quesdataitem_def_ix` (`definition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Individual dataset items' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_ddwtos`
--

CREATE TABLE IF NOT EXISTS `mdl_question_ddwtos` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quesddwt_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines drag and drop (words into sentences) questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_gapselect`
--

CREATE TABLE IF NOT EXISTS `mdl_question_gapselect` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '1',
  `correctfeedback` longtext NOT NULL,
  `correctfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `partiallycorrectfeedback` longtext NOT NULL,
  `partiallycorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `incorrectfeedback` longtext NOT NULL,
  `incorrectfeedbackformat` tinyint(2) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quesgaps_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines select missing words questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_hints`
--

CREATE TABLE IF NOT EXISTS `mdl_question_hints` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionid` bigint(10) NOT NULL,
  `hint` longtext NOT NULL,
  `hintformat` smallint(4) NOT NULL DEFAULT '0',
  `shownumcorrect` tinyint(1) DEFAULT NULL,
  `clearwrong` tinyint(1) DEFAULT NULL,
  `options` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_queshint_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the the part of the question definition that gives di' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_multianswer`
--

CREATE TABLE IF NOT EXISTS `mdl_question_multianswer` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `sequence` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_quesmult_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for multianswer questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_numerical`
--

CREATE TABLE IF NOT EXISTS `mdl_question_numerical` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `answer` bigint(10) NOT NULL DEFAULT '0',
  `tolerance` varchar(255) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`id`),
  KEY `mdl_quesnume_ans_ix` (`answer`),
  KEY `mdl_quesnume_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for numerical questions.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_numerical_options`
--

CREATE TABLE IF NOT EXISTS `mdl_question_numerical_options` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `showunits` smallint(4) NOT NULL DEFAULT '0',
  `unitsleft` smallint(4) NOT NULL DEFAULT '0',
  `unitgradingtype` smallint(4) NOT NULL DEFAULT '0',
  `unitpenalty` decimal(12,7) NOT NULL DEFAULT '0.1000000',
  PRIMARY KEY (`id`),
  KEY `mdl_quesnumeopti_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for questions of type numerical This table is also u' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_numerical_units`
--

CREATE TABLE IF NOT EXISTS `mdl_question_numerical_units` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `multiplier` decimal(40,20) NOT NULL DEFAULT '1.00000000000000000000',
  `unit` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quesnumeunit_queuni_uix` (`question`,`unit`),
  KEY `mdl_quesnumeunit_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Optional unit options for numerical questions. This table is' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_response_analysis`
--

CREATE TABLE IF NOT EXISTS `mdl_question_response_analysis` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hashcode` varchar(40) NOT NULL DEFAULT '',
  `whichtries` varchar(255) NOT NULL DEFAULT '',
  `timemodified` bigint(10) NOT NULL,
  `questionid` bigint(10) NOT NULL,
  `variant` bigint(10) DEFAULT NULL,
  `subqid` varchar(100) NOT NULL DEFAULT '',
  `aid` varchar(100) DEFAULT NULL,
  `response` longtext,
  `credit` decimal(15,5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Analysis of student responses given to questions.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_response_count`
--

CREATE TABLE IF NOT EXISTS `mdl_question_response_count` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `analysisid` bigint(10) NOT NULL,
  `try` bigint(10) NOT NULL,
  `rcount` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_quesrespcoun_ana_ix` (`analysisid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Count for each responses for each try at a question.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_statistics`
--

CREATE TABLE IF NOT EXISTS `mdl_question_statistics` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hashcode` varchar(40) NOT NULL DEFAULT '',
  `timemodified` bigint(10) NOT NULL,
  `questionid` bigint(10) NOT NULL,
  `slot` bigint(10) DEFAULT NULL,
  `subquestion` smallint(4) NOT NULL,
  `variant` bigint(10) DEFAULT NULL,
  `s` bigint(10) NOT NULL DEFAULT '0',
  `effectiveweight` decimal(15,5) DEFAULT NULL,
  `negcovar` tinyint(2) NOT NULL DEFAULT '0',
  `discriminationindex` decimal(15,5) DEFAULT NULL,
  `discriminativeefficiency` decimal(15,5) DEFAULT NULL,
  `sd` decimal(15,10) DEFAULT NULL,
  `facility` decimal(15,10) DEFAULT NULL,
  `subquestions` longtext,
  `maxmark` decimal(12,7) DEFAULT NULL,
  `positions` longtext,
  `randomguessscore` decimal(12,7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Statistics for individual questions used in an activity.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_truefalse`
--

CREATE TABLE IF NOT EXISTS `mdl_question_truefalse` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `question` bigint(10) NOT NULL DEFAULT '0',
  `trueanswer` bigint(10) NOT NULL DEFAULT '0',
  `falseanswer` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_questrue_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Options for True-False questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_question_usages`
--

CREATE TABLE IF NOT EXISTS `mdl_question_usages` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `component` varchar(255) NOT NULL DEFAULT '',
  `preferredbehaviour` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_quesusag_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table''s main purpose it to assign a unique id to each a' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `timeopen` bigint(10) NOT NULL DEFAULT '0',
  `timeclose` bigint(10) NOT NULL DEFAULT '0',
  `timelimit` bigint(10) NOT NULL DEFAULT '0',
  `overduehandling` varchar(16) NOT NULL DEFAULT 'autoabandon',
  `graceperiod` bigint(10) NOT NULL DEFAULT '0',
  `preferredbehaviour` varchar(32) NOT NULL DEFAULT '',
  `canredoquestions` smallint(4) NOT NULL DEFAULT '0',
  `attempts` mediumint(6) NOT NULL DEFAULT '0',
  `attemptonlast` smallint(4) NOT NULL DEFAULT '0',
  `grademethod` smallint(4) NOT NULL DEFAULT '1',
  `decimalpoints` smallint(4) NOT NULL DEFAULT '2',
  `questiondecimalpoints` smallint(4) NOT NULL DEFAULT '-1',
  `reviewattempt` mediumint(6) NOT NULL DEFAULT '0',
  `reviewcorrectness` mediumint(6) NOT NULL DEFAULT '0',
  `reviewmarks` mediumint(6) NOT NULL DEFAULT '0',
  `reviewspecificfeedback` mediumint(6) NOT NULL DEFAULT '0',
  `reviewgeneralfeedback` mediumint(6) NOT NULL DEFAULT '0',
  `reviewrightanswer` mediumint(6) NOT NULL DEFAULT '0',
  `reviewoverallfeedback` mediumint(6) NOT NULL DEFAULT '0',
  `questionsperpage` bigint(10) NOT NULL DEFAULT '0',
  `navmethod` varchar(16) NOT NULL DEFAULT 'free',
  `shuffleanswers` smallint(4) NOT NULL DEFAULT '0',
  `sumgrades` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `grade` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '',
  `subnet` varchar(255) NOT NULL DEFAULT '',
  `browsersecurity` varchar(32) NOT NULL DEFAULT '',
  `delay1` bigint(10) NOT NULL DEFAULT '0',
  `delay2` bigint(10) NOT NULL DEFAULT '0',
  `showuserpicture` smallint(4) NOT NULL DEFAULT '0',
  `showblocks` smallint(4) NOT NULL DEFAULT '0',
  `completionattemptsexhausted` tinyint(1) DEFAULT '0',
  `completionpass` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quiz_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The settings for each quiz.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_attempts`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_attempts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quiz` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `attempt` mediumint(6) NOT NULL DEFAULT '0',
  `uniqueid` bigint(10) NOT NULL DEFAULT '0',
  `layout` longtext NOT NULL,
  `currentpage` bigint(10) NOT NULL DEFAULT '0',
  `preview` smallint(3) NOT NULL DEFAULT '0',
  `state` varchar(16) NOT NULL DEFAULT 'inprogress',
  `timestart` bigint(10) NOT NULL DEFAULT '0',
  `timefinish` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `timecheckstate` bigint(10) DEFAULT '0',
  `sumgrades` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quizatte_quiuseatt_uix` (`quiz`,`userid`,`attempt`),
  UNIQUE KEY `mdl_quizatte_uni_uix` (`uniqueid`),
  KEY `mdl_quizatte_statim_ix` (`state`,`timecheckstate`),
  KEY `mdl_quizatte_qui_ix` (`quiz`),
  KEY `mdl_quizatte_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores users attempts at quizzes.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_feedback`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_feedback` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quizid` bigint(10) NOT NULL DEFAULT '0',
  `feedbacktext` longtext NOT NULL,
  `feedbacktextformat` tinyint(2) NOT NULL DEFAULT '0',
  `mingrade` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `maxgrade` decimal(10,5) NOT NULL DEFAULT '0.00000',
  PRIMARY KEY (`id`),
  KEY `mdl_quizfeed_qui_ix` (`quizid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Feedback given to students based on which grade band their o' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_grades`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_grades` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quiz` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `grade` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_quizgrad_use_ix` (`userid`),
  KEY `mdl_quizgrad_qui_ix` (`quiz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the overall grade for each user on the quiz, based on' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_overrides`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_overrides` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quiz` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) DEFAULT NULL,
  `userid` bigint(10) DEFAULT NULL,
  `timeopen` bigint(10) DEFAULT NULL,
  `timeclose` bigint(10) DEFAULT NULL,
  `timelimit` bigint(10) DEFAULT NULL,
  `attempts` mediumint(6) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_quizover_qui_ix` (`quiz`),
  KEY `mdl_quizover_gro_ix` (`groupid`),
  KEY `mdl_quizover_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The overrides to quiz settings on a per-user and per-group b' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_overview_regrades`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_overview_regrades` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questionusageid` bigint(10) NOT NULL,
  `slot` bigint(10) NOT NULL,
  `newfraction` decimal(12,7) DEFAULT NULL,
  `oldfraction` decimal(12,7) DEFAULT NULL,
  `regraded` smallint(4) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table records which question attempts need regrading an' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_reports`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_reports` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `displayorder` bigint(10) NOT NULL,
  `capability` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quizrepo_nam_uix` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lists all the installed quiz reports and their display order' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mdl_quiz_reports`
--

INSERT INTO `mdl_quiz_reports` (`id`, `name`, `displayorder`, `capability`) VALUES
(1, 'grading', 6000, 'mod/quiz:grade'),
(2, 'overview', 10000, NULL),
(3, 'responses', 9000, NULL),
(4, 'statistics', 8000, 'quiz/statistics:view');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_sections`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_sections` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quizid` bigint(10) NOT NULL,
  `firstslot` bigint(10) NOT NULL,
  `heading` varchar(1333) DEFAULT NULL,
  `shufflequestions` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quizsect_quifir_uix` (`quizid`,`firstslot`),
  KEY `mdl_quizsect_qui_ix` (`quizid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores sections of a quiz with section name (heading), from ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_slots`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_slots` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `slot` bigint(10) NOT NULL,
  `quizid` bigint(10) NOT NULL DEFAULT '0',
  `page` bigint(10) NOT NULL,
  `requireprevious` smallint(4) NOT NULL DEFAULT '0',
  `questionid` bigint(10) NOT NULL DEFAULT '0',
  `maxmark` decimal(12,7) NOT NULL DEFAULT '0.0000000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_quizslot_quislo_uix` (`quizid`,`slot`),
  KEY `mdl_quizslot_qui_ix` (`quizid`),
  KEY `mdl_quizslot_que_ix` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the question used in a quiz, with the order, and for ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_quiz_statistics`
--

CREATE TABLE IF NOT EXISTS `mdl_quiz_statistics` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `hashcode` varchar(40) NOT NULL DEFAULT '',
  `whichattempts` smallint(4) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `firstattemptscount` bigint(10) NOT NULL,
  `highestattemptscount` bigint(10) NOT NULL,
  `lastattemptscount` bigint(10) NOT NULL,
  `allattemptscount` bigint(10) NOT NULL,
  `firstattemptsavg` decimal(15,5) DEFAULT NULL,
  `highestattemptsavg` decimal(15,5) DEFAULT NULL,
  `lastattemptsavg` decimal(15,5) DEFAULT NULL,
  `allattemptsavg` decimal(15,5) DEFAULT NULL,
  `median` decimal(15,5) DEFAULT NULL,
  `standarddeviation` decimal(15,5) DEFAULT NULL,
  `skewness` decimal(15,10) DEFAULT NULL,
  `kurtosis` decimal(15,5) DEFAULT NULL,
  `cic` decimal(15,10) DEFAULT NULL,
  `errorratio` decimal(15,10) DEFAULT NULL,
  `standarderror` decimal(15,10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table to cache results from analysis done in statistics repo' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_rating`
--

CREATE TABLE IF NOT EXISTS `mdl_rating` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL,
  `component` varchar(100) NOT NULL DEFAULT '',
  `ratingarea` varchar(50) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL,
  `scaleid` bigint(10) NOT NULL,
  `rating` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_rati_comratconite_ix` (`component`,`ratingarea`,`contextid`,`itemid`),
  KEY `mdl_rati_con_ix` (`contextid`),
  KEY `mdl_rati_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='moodle ratings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_registration_hubs`
--

CREATE TABLE IF NOT EXISTS `mdl_registration_hubs` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '',
  `hubname` varchar(255) NOT NULL DEFAULT '',
  `huburl` varchar(255) NOT NULL DEFAULT '',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `secret` varchar(255) DEFAULT NULL,
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='hub where the site is registered on with their associated to' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_repository`
--

CREATE TABLE IF NOT EXISTS `mdl_repository` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) DEFAULT '1',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table contains one entry for every configured external ' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mdl_repository`
--

INSERT INTO `mdl_repository` (`id`, `type`, `visible`, `sortorder`) VALUES
(1, 'areafiles', 1, 1),
(2, 'local', 1, 2),
(3, 'recent', 1, 3),
(4, 'upload', 1, 4),
(5, 'url', 1, 5),
(6, 'user', 1, 6),
(7, 'wikimedia', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_repository_instances`
--

CREATE TABLE IF NOT EXISTS `mdl_repository_instances` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `typeid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `contextid` bigint(10) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `timecreated` bigint(10) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table contains one entry for every configured external ' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mdl_repository_instances`
--

INSERT INTO `mdl_repository_instances` (`id`, `name`, `typeid`, `userid`, `contextid`, `username`, `password`, `timecreated`, `timemodified`, `readonly`) VALUES
(1, '', 1, 0, 1, NULL, NULL, 1451552930, 1451552930, 0),
(2, '', 2, 0, 1, NULL, NULL, 1451552930, 1451552930, 0),
(3, '', 3, 0, 1, NULL, NULL, 1451552931, 1451552931, 0),
(4, '', 4, 0, 1, NULL, NULL, 1451552931, 1451552931, 0),
(5, '', 5, 0, 1, NULL, NULL, 1451552931, 1451552931, 0),
(6, '', 6, 0, 1, NULL, NULL, 1451552931, 1451552931, 0),
(7, '', 7, 0, 1, NULL, NULL, 1451552932, 1451552932, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_repository_instance_config`
--

CREATE TABLE IF NOT EXISTS `mdl_repository_instance_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `instanceid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The config for intances' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_resource`
--

CREATE TABLE IF NOT EXISTS `mdl_resource` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `tobemigrated` smallint(4) NOT NULL DEFAULT '0',
  `legacyfiles` smallint(4) NOT NULL DEFAULT '0',
  `legacyfileslast` bigint(10) DEFAULT NULL,
  `display` smallint(4) NOT NULL DEFAULT '0',
  `displayoptions` longtext,
  `filterfiles` smallint(4) NOT NULL DEFAULT '0',
  `revision` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_reso_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Each record is one resource and its config data' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_resource_old`
--

CREATE TABLE IF NOT EXISTS `mdl_resource_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `reference` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `alltext` longtext NOT NULL,
  `popup` longtext NOT NULL,
  `options` varchar(255) NOT NULL DEFAULT '',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `oldid` bigint(10) NOT NULL,
  `cmid` bigint(10) DEFAULT NULL,
  `newmodule` varchar(50) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  `migrated` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_resoold_old_uix` (`oldid`),
  KEY `mdl_resoold_cmi_ix` (`cmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='backup of all old resource instances from 1.9' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role`
--

CREATE TABLE IF NOT EXISTS `mdl_role` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `shortname` varchar(100) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `archetype` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_role_sor_uix` (`sortorder`),
  UNIQUE KEY `mdl_role_sho_uix` (`shortname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='moodle roles' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `mdl_role`
--

INSERT INTO `mdl_role` (`id`, `name`, `shortname`, `description`, `sortorder`, `archetype`) VALUES
(1, '', 'manager', '', 1, 'manager'),
(2, '', 'coursecreator', '', 2, 'coursecreator'),
(3, '', 'editingteacher', '', 3, 'editingteacher'),
(4, '', 'teacher', '', 4, 'teacher'),
(5, '', 'student', '', 5, 'student'),
(6, '', 'guest', '', 6, 'guest'),
(7, '', 'user', '', 7, 'user'),
(8, '', 'frontpage', '', 8, 'frontpage');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_allow_assign`
--

CREATE TABLE IF NOT EXISTS `mdl_role_allow_assign` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `allowassign` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolealloassi_rolall_uix` (`roleid`,`allowassign`),
  KEY `mdl_rolealloassi_rol_ix` (`roleid`),
  KEY `mdl_rolealloassi_all_ix` (`allowassign`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='this defines what role can assign what role' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mdl_role_allow_assign`
--

INSERT INTO `mdl_role_allow_assign` (`id`, `roleid`, `allowassign`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 3, 4),
(7, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_allow_override`
--

CREATE TABLE IF NOT EXISTS `mdl_role_allow_override` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `allowoverride` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolealloover_rolall_uix` (`roleid`,`allowoverride`),
  KEY `mdl_rolealloover_rol_ix` (`roleid`),
  KEY `mdl_rolealloover_all_ix` (`allowoverride`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='this defines what role can override what role' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `mdl_role_allow_override`
--

INSERT INTO `mdl_role_allow_override` (`id`, `roleid`, `allowoverride`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 3, 4),
(10, 3, 5),
(11, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_allow_switch`
--

CREATE TABLE IF NOT EXISTS `mdl_role_allow_switch` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `roleid` bigint(10) NOT NULL,
  `allowswitch` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolealloswit_rolall_uix` (`roleid`,`allowswitch`),
  KEY `mdl_rolealloswit_rol_ix` (`roleid`),
  KEY `mdl_rolealloswit_all_ix` (`allowswitch`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table stores which which other roles a user is allowed ' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `mdl_role_allow_switch`
--

INSERT INTO `mdl_role_allow_switch` (`id`, `roleid`, `allowswitch`) VALUES
(1, 1, 3),
(2, 1, 4),
(3, 1, 5),
(4, 1, 6),
(5, 3, 4),
(6, 3, 5),
(7, 3, 6),
(8, 4, 5),
(9, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_assignments`
--

CREATE TABLE IF NOT EXISTS `mdl_role_assignments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `contextid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `modifierid` bigint(10) NOT NULL DEFAULT '0',
  `component` varchar(100) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL DEFAULT '0',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_roleassi_sor_ix` (`sortorder`),
  KEY `mdl_roleassi_rolcon_ix` (`roleid`,`contextid`),
  KEY `mdl_roleassi_useconrol_ix` (`userid`,`contextid`,`roleid`),
  KEY `mdl_roleassi_comiteuse_ix` (`component`,`itemid`,`userid`),
  KEY `mdl_roleassi_rol_ix` (`roleid`),
  KEY `mdl_roleassi_con_ix` (`contextid`),
  KEY `mdl_roleassi_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='assigning roles in different context' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_capabilities`
--

CREATE TABLE IF NOT EXISTS `mdl_role_capabilities` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `contextid` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `capability` varchar(255) NOT NULL DEFAULT '',
  `permission` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `modifierid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolecapa_rolconcap_uix` (`roleid`,`contextid`,`capability`),
  KEY `mdl_rolecapa_rol_ix` (`roleid`),
  KEY `mdl_rolecapa_con_ix` (`contextid`),
  KEY `mdl_rolecapa_mod_ix` (`modifierid`),
  KEY `mdl_rolecapa_cap_ix` (`capability`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='permission has to be signed, overriding a capability for a p' AUTO_INCREMENT=1167 ;

--
-- Dumping data for table `mdl_role_capabilities`
--

INSERT INTO `mdl_role_capabilities` (`id`, `contextid`, `roleid`, `capability`, `permission`, `timemodified`, `modifierid`) VALUES
(1, 1, 1, 'moodle/site:readallmessages', 1, 1451552894, 0),
(2, 1, 3, 'moodle/site:readallmessages', 1, 1451552894, 0),
(3, 1, 1, 'moodle/site:deleteanymessage', 1, 1451552894, 0),
(4, 1, 1, 'moodle/site:sendmessage', 1, 1451552894, 0),
(5, 1, 7, 'moodle/site:sendmessage', 1, 1451552894, 0),
(6, 1, 7, 'moodle/site:deleteownmessage', 1, 1451552894, 0),
(7, 1, 1, 'moodle/site:approvecourse', 1, 1451552894, 0),
(8, 1, 3, 'moodle/backup:backupcourse', 1, 1451552894, 0),
(9, 1, 1, 'moodle/backup:backupcourse', 1, 1451552894, 0),
(10, 1, 3, 'moodle/backup:backupsection', 1, 1451552894, 0),
(11, 1, 1, 'moodle/backup:backupsection', 1, 1451552894, 0),
(12, 1, 3, 'moodle/backup:backupactivity', 1, 1451552894, 0),
(13, 1, 1, 'moodle/backup:backupactivity', 1, 1451552894, 0),
(14, 1, 3, 'moodle/backup:backuptargethub', 1, 1451552894, 0),
(15, 1, 1, 'moodle/backup:backuptargethub', 1, 1451552894, 0),
(16, 1, 3, 'moodle/backup:backuptargetimport', 1, 1451552894, 0),
(17, 1, 1, 'moodle/backup:backuptargetimport', 1, 1451552894, 0),
(18, 1, 3, 'moodle/backup:downloadfile', 1, 1451552894, 0),
(19, 1, 1, 'moodle/backup:downloadfile', 1, 1451552894, 0),
(20, 1, 3, 'moodle/backup:configure', 1, 1451552894, 0),
(21, 1, 1, 'moodle/backup:configure', 1, 1451552894, 0),
(22, 1, 1, 'moodle/backup:userinfo', 1, 1451552894, 0),
(23, 1, 1, 'moodle/backup:anonymise', 1, 1451552894, 0),
(24, 1, 3, 'moodle/restore:restorecourse', 1, 1451552894, 0),
(25, 1, 1, 'moodle/restore:restorecourse', 1, 1451552894, 0),
(26, 1, 3, 'moodle/restore:restoresection', 1, 1451552894, 0),
(27, 1, 1, 'moodle/restore:restoresection', 1, 1451552894, 0),
(28, 1, 3, 'moodle/restore:restoreactivity', 1, 1451552894, 0),
(29, 1, 1, 'moodle/restore:restoreactivity', 1, 1451552894, 0),
(30, 1, 3, 'moodle/restore:restoretargethub', 1, 1451552894, 0),
(31, 1, 1, 'moodle/restore:restoretargethub', 1, 1451552894, 0),
(32, 1, 3, 'moodle/restore:restoretargetimport', 1, 1451552894, 0),
(33, 1, 1, 'moodle/restore:restoretargetimport', 1, 1451552894, 0),
(34, 1, 3, 'moodle/restore:uploadfile', 1, 1451552894, 0),
(35, 1, 1, 'moodle/restore:uploadfile', 1, 1451552894, 0),
(36, 1, 3, 'moodle/restore:configure', 1, 1451552894, 0),
(37, 1, 1, 'moodle/restore:configure', 1, 1451552894, 0),
(38, 1, 2, 'moodle/restore:rolldates', 1, 1451552894, 0),
(39, 1, 1, 'moodle/restore:rolldates', 1, 1451552894, 0),
(40, 1, 1, 'moodle/restore:userinfo', 1, 1451552894, 0),
(41, 1, 1, 'moodle/restore:createuser', 1, 1451552894, 0),
(42, 1, 3, 'moodle/site:manageblocks', 1, 1451552894, 0),
(43, 1, 1, 'moodle/site:manageblocks', 1, 1451552894, 0),
(44, 1, 4, 'moodle/site:accessallgroups', 1, 1451552894, 0),
(45, 1, 3, 'moodle/site:accessallgroups', 1, 1451552894, 0),
(46, 1, 1, 'moodle/site:accessallgroups', 1, 1451552894, 0),
(47, 1, 4, 'moodle/site:viewfullnames', 1, 1451552894, 0),
(48, 1, 3, 'moodle/site:viewfullnames', 1, 1451552894, 0),
(49, 1, 1, 'moodle/site:viewfullnames', 1, 1451552894, 0),
(50, 1, 4, 'moodle/site:viewuseridentity', 1, 1451552894, 0),
(51, 1, 3, 'moodle/site:viewuseridentity', 1, 1451552894, 0),
(52, 1, 1, 'moodle/site:viewuseridentity', 1, 1451552894, 0),
(53, 1, 4, 'moodle/site:viewreports', 1, 1451552894, 0),
(54, 1, 3, 'moodle/site:viewreports', 1, 1451552894, 0),
(55, 1, 1, 'moodle/site:viewreports', 1, 1451552894, 0),
(56, 1, 3, 'moodle/site:trustcontent', 1, 1451552894, 0),
(57, 1, 1, 'moodle/site:trustcontent', 1, 1451552894, 0),
(58, 1, 1, 'moodle/site:uploadusers', 1, 1451552894, 0),
(59, 1, 3, 'moodle/filter:manage', 1, 1451552894, 0),
(60, 1, 1, 'moodle/filter:manage', 1, 1451552894, 0),
(61, 1, 1, 'moodle/user:create', 1, 1451552894, 0),
(62, 1, 1, 'moodle/user:delete', 1, 1451552894, 0),
(63, 1, 1, 'moodle/user:update', 1, 1451552894, 0),
(64, 1, 6, 'moodle/user:viewdetails', 1, 1451552894, 0),
(65, 1, 5, 'moodle/user:viewdetails', 1, 1451552894, 0),
(66, 1, 4, 'moodle/user:viewdetails', 1, 1451552894, 0),
(67, 1, 3, 'moodle/user:viewdetails', 1, 1451552894, 0),
(68, 1, 1, 'moodle/user:viewdetails', 1, 1451552894, 0),
(69, 1, 1, 'moodle/user:viewalldetails', 1, 1451552894, 0),
(70, 1, 1, 'moodle/user:viewlastip', 1, 1451552894, 0),
(71, 1, 4, 'moodle/user:viewhiddendetails', 1, 1451552894, 0),
(72, 1, 3, 'moodle/user:viewhiddendetails', 1, 1451552894, 0),
(73, 1, 1, 'moodle/user:viewhiddendetails', 1, 1451552894, 0),
(74, 1, 1, 'moodle/user:loginas', 1, 1451552894, 0),
(75, 1, 1, 'moodle/user:managesyspages', 1, 1451552894, 0),
(76, 1, 7, 'moodle/user:manageownblocks', 1, 1451552894, 0),
(77, 1, 7, 'moodle/user:manageownfiles', 1, 1451552894, 0),
(78, 1, 1, 'moodle/my:configsyspages', 1, 1451552894, 0),
(79, 1, 3, 'moodle/role:assign', 1, 1451552894, 0),
(80, 1, 1, 'moodle/role:assign', 1, 1451552894, 0),
(81, 1, 4, 'moodle/role:review', 1, 1451552894, 0),
(82, 1, 3, 'moodle/role:review', 1, 1451552894, 0),
(83, 1, 1, 'moodle/role:review', 1, 1451552894, 0),
(84, 1, 1, 'moodle/role:override', 1, 1451552894, 0),
(85, 1, 3, 'moodle/role:safeoverride', 1, 1451552894, 0),
(86, 1, 1, 'moodle/role:manage', 1, 1451552894, 0),
(87, 1, 3, 'moodle/role:switchroles', 1, 1451552894, 0),
(88, 1, 1, 'moodle/role:switchroles', 1, 1451552894, 0),
(89, 1, 1, 'moodle/category:manage', 1, 1451552894, 0),
(90, 1, 2, 'moodle/category:viewhiddencategories', 1, 1451552894, 0),
(91, 1, 1, 'moodle/category:viewhiddencategories', 1, 1451552894, 0),
(92, 1, 1, 'moodle/cohort:manage', 1, 1451552894, 0),
(93, 1, 1, 'moodle/cohort:assign', 1, 1451552894, 0),
(94, 1, 3, 'moodle/cohort:view', 1, 1451552894, 0),
(95, 1, 1, 'moodle/cohort:view', 1, 1451552894, 0),
(96, 1, 2, 'moodle/course:create', 1, 1451552894, 0),
(97, 1, 1, 'moodle/course:create', 1, 1451552894, 0),
(98, 1, 7, 'moodle/course:request', 1, 1451552894, 0),
(99, 1, 1, 'moodle/course:delete', 1, 1451552894, 0),
(100, 1, 3, 'moodle/course:update', 1, 1451552894, 0),
(101, 1, 1, 'moodle/course:update', 1, 1451552894, 0),
(102, 1, 1, 'moodle/course:view', 1, 1451552894, 0),
(103, 1, 3, 'moodle/course:enrolreview', 1, 1451552894, 0),
(104, 1, 1, 'moodle/course:enrolreview', 1, 1451552894, 0),
(105, 1, 3, 'moodle/course:enrolconfig', 1, 1451552894, 0),
(106, 1, 1, 'moodle/course:enrolconfig', 1, 1451552895, 0),
(107, 1, 3, 'moodle/course:reviewotherusers', 1, 1451552895, 0),
(108, 1, 1, 'moodle/course:reviewotherusers', 1, 1451552895, 0),
(109, 1, 4, 'moodle/course:bulkmessaging', 1, 1451552895, 0),
(110, 1, 3, 'moodle/course:bulkmessaging', 1, 1451552895, 0),
(111, 1, 1, 'moodle/course:bulkmessaging', 1, 1451552895, 0),
(112, 1, 4, 'moodle/course:viewhiddenuserfields', 1, 1451552895, 0),
(113, 1, 3, 'moodle/course:viewhiddenuserfields', 1, 1451552895, 0),
(114, 1, 1, 'moodle/course:viewhiddenuserfields', 1, 1451552895, 0),
(115, 1, 2, 'moodle/course:viewhiddencourses', 1, 1451552895, 0),
(116, 1, 4, 'moodle/course:viewhiddencourses', 1, 1451552895, 0),
(117, 1, 3, 'moodle/course:viewhiddencourses', 1, 1451552895, 0),
(118, 1, 1, 'moodle/course:viewhiddencourses', 1, 1451552895, 0),
(119, 1, 3, 'moodle/course:visibility', 1, 1451552895, 0),
(120, 1, 1, 'moodle/course:visibility', 1, 1451552895, 0),
(121, 1, 3, 'moodle/course:managefiles', 1, 1451552895, 0),
(122, 1, 1, 'moodle/course:managefiles', 1, 1451552895, 0),
(123, 1, 3, 'moodle/course:manageactivities', 1, 1451552895, 0),
(124, 1, 1, 'moodle/course:manageactivities', 1, 1451552895, 0),
(125, 1, 3, 'moodle/course:activityvisibility', 1, 1451552895, 0),
(126, 1, 1, 'moodle/course:activityvisibility', 1, 1451552895, 0),
(127, 1, 4, 'moodle/course:viewhiddenactivities', 1, 1451552895, 0),
(128, 1, 3, 'moodle/course:viewhiddenactivities', 1, 1451552895, 0),
(129, 1, 1, 'moodle/course:viewhiddenactivities', 1, 1451552895, 0),
(130, 1, 5, 'moodle/course:viewparticipants', 1, 1451552895, 0),
(131, 1, 4, 'moodle/course:viewparticipants', 1, 1451552895, 0),
(132, 1, 3, 'moodle/course:viewparticipants', 1, 1451552895, 0),
(133, 1, 1, 'moodle/course:viewparticipants', 1, 1451552895, 0),
(134, 1, 3, 'moodle/course:changefullname', 1, 1451552895, 0),
(135, 1, 1, 'moodle/course:changefullname', 1, 1451552895, 0),
(136, 1, 3, 'moodle/course:changeshortname', 1, 1451552895, 0),
(137, 1, 1, 'moodle/course:changeshortname', 1, 1451552895, 0),
(138, 1, 3, 'moodle/course:changeidnumber', 1, 1451552895, 0),
(139, 1, 1, 'moodle/course:changeidnumber', 1, 1451552895, 0),
(140, 1, 3, 'moodle/course:changecategory', 1, 1451552895, 0),
(141, 1, 1, 'moodle/course:changecategory', 1, 1451552895, 0),
(142, 1, 3, 'moodle/course:changesummary', 1, 1451552895, 0),
(143, 1, 1, 'moodle/course:changesummary', 1, 1451552895, 0),
(144, 1, 1, 'moodle/site:viewparticipants', 1, 1451552895, 0),
(145, 1, 5, 'moodle/course:isincompletionreports', 1, 1451552895, 0),
(146, 1, 5, 'moodle/course:viewscales', 1, 1451552895, 0),
(147, 1, 4, 'moodle/course:viewscales', 1, 1451552895, 0),
(148, 1, 3, 'moodle/course:viewscales', 1, 1451552895, 0),
(149, 1, 1, 'moodle/course:viewscales', 1, 1451552895, 0),
(150, 1, 3, 'moodle/course:managescales', 1, 1451552895, 0),
(151, 1, 1, 'moodle/course:managescales', 1, 1451552895, 0),
(152, 1, 3, 'moodle/course:managegroups', 1, 1451552895, 0),
(153, 1, 1, 'moodle/course:managegroups', 1, 1451552895, 0),
(154, 1, 3, 'moodle/course:reset', 1, 1451552895, 0),
(155, 1, 1, 'moodle/course:reset', 1, 1451552895, 0),
(156, 1, 3, 'moodle/course:viewsuspendedusers', 1, 1451552895, 0),
(157, 1, 1, 'moodle/course:viewsuspendedusers', 1, 1451552895, 0),
(158, 1, 1, 'moodle/course:tag', 1, 1451552895, 0),
(159, 1, 3, 'moodle/course:tag', 1, 1451552895, 0),
(160, 1, 6, 'moodle/blog:view', 1, 1451552895, 0),
(161, 1, 7, 'moodle/blog:view', 1, 1451552895, 0),
(162, 1, 5, 'moodle/blog:view', 1, 1451552895, 0),
(163, 1, 4, 'moodle/blog:view', 1, 1451552895, 0),
(164, 1, 3, 'moodle/blog:view', 1, 1451552895, 0),
(165, 1, 1, 'moodle/blog:view', 1, 1451552895, 0),
(166, 1, 6, 'moodle/blog:search', 1, 1451552895, 0),
(167, 1, 7, 'moodle/blog:search', 1, 1451552895, 0),
(168, 1, 5, 'moodle/blog:search', 1, 1451552895, 0),
(169, 1, 4, 'moodle/blog:search', 1, 1451552895, 0),
(170, 1, 3, 'moodle/blog:search', 1, 1451552895, 0),
(171, 1, 1, 'moodle/blog:search', 1, 1451552895, 0),
(172, 1, 1, 'moodle/blog:viewdrafts', 1, 1451552895, 0),
(173, 1, 7, 'moodle/blog:create', 1, 1451552895, 0),
(174, 1, 1, 'moodle/blog:create', 1, 1451552895, 0),
(175, 1, 4, 'moodle/blog:manageentries', 1, 1451552895, 0),
(176, 1, 3, 'moodle/blog:manageentries', 1, 1451552895, 0),
(177, 1, 1, 'moodle/blog:manageentries', 1, 1451552895, 0),
(178, 1, 5, 'moodle/blog:manageexternal', 1, 1451552895, 0),
(179, 1, 7, 'moodle/blog:manageexternal', 1, 1451552895, 0),
(180, 1, 4, 'moodle/blog:manageexternal', 1, 1451552895, 0),
(181, 1, 3, 'moodle/blog:manageexternal', 1, 1451552895, 0),
(182, 1, 1, 'moodle/blog:manageexternal', 1, 1451552895, 0),
(183, 1, 7, 'moodle/calendar:manageownentries', 1, 1451552895, 0),
(184, 1, 1, 'moodle/calendar:manageownentries', 1, 1451552895, 0),
(185, 1, 4, 'moodle/calendar:managegroupentries', 1, 1451552895, 0),
(186, 1, 3, 'moodle/calendar:managegroupentries', 1, 1451552895, 0),
(187, 1, 1, 'moodle/calendar:managegroupentries', 1, 1451552895, 0),
(188, 1, 4, 'moodle/calendar:manageentries', 1, 1451552895, 0),
(189, 1, 3, 'moodle/calendar:manageentries', 1, 1451552895, 0),
(190, 1, 1, 'moodle/calendar:manageentries', 1, 1451552895, 0),
(191, 1, 1, 'moodle/user:editprofile', 1, 1451552895, 0),
(192, 1, 6, 'moodle/user:editownprofile', -1000, 1451552895, 0),
(193, 1, 7, 'moodle/user:editownprofile', 1, 1451552895, 0),
(194, 1, 1, 'moodle/user:editownprofile', 1, 1451552895, 0),
(195, 1, 6, 'moodle/user:changeownpassword', -1000, 1451552895, 0),
(196, 1, 7, 'moodle/user:changeownpassword', 1, 1451552895, 0),
(197, 1, 1, 'moodle/user:changeownpassword', 1, 1451552895, 0),
(198, 1, 5, 'moodle/user:readuserposts', 1, 1451552895, 0),
(199, 1, 4, 'moodle/user:readuserposts', 1, 1451552895, 0),
(200, 1, 3, 'moodle/user:readuserposts', 1, 1451552895, 0),
(201, 1, 1, 'moodle/user:readuserposts', 1, 1451552895, 0),
(202, 1, 5, 'moodle/user:readuserblogs', 1, 1451552895, 0),
(203, 1, 4, 'moodle/user:readuserblogs', 1, 1451552895, 0),
(204, 1, 3, 'moodle/user:readuserblogs', 1, 1451552895, 0),
(205, 1, 1, 'moodle/user:readuserblogs', 1, 1451552895, 0),
(206, 1, 1, 'moodle/user:editmessageprofile', 1, 1451552895, 0),
(207, 1, 6, 'moodle/user:editownmessageprofile', -1000, 1451552895, 0),
(208, 1, 7, 'moodle/user:editownmessageprofile', 1, 1451552895, 0),
(209, 1, 1, 'moodle/user:editownmessageprofile', 1, 1451552895, 0),
(210, 1, 3, 'moodle/question:managecategory', 1, 1451552895, 0),
(211, 1, 1, 'moodle/question:managecategory', 1, 1451552895, 0),
(212, 1, 3, 'moodle/question:add', 1, 1451552895, 0),
(213, 1, 1, 'moodle/question:add', 1, 1451552895, 0),
(214, 1, 3, 'moodle/question:editmine', 1, 1451552895, 0),
(215, 1, 1, 'moodle/question:editmine', 1, 1451552895, 0),
(216, 1, 3, 'moodle/question:editall', 1, 1451552895, 0),
(217, 1, 1, 'moodle/question:editall', 1, 1451552895, 0),
(218, 1, 3, 'moodle/question:viewmine', 1, 1451552895, 0),
(219, 1, 1, 'moodle/question:viewmine', 1, 1451552895, 0),
(220, 1, 3, 'moodle/question:viewall', 1, 1451552895, 0),
(221, 1, 1, 'moodle/question:viewall', 1, 1451552895, 0),
(222, 1, 3, 'moodle/question:usemine', 1, 1451552895, 0),
(223, 1, 1, 'moodle/question:usemine', 1, 1451552895, 0),
(224, 1, 3, 'moodle/question:useall', 1, 1451552895, 0),
(225, 1, 1, 'moodle/question:useall', 1, 1451552895, 0),
(226, 1, 3, 'moodle/question:movemine', 1, 1451552895, 0),
(227, 1, 1, 'moodle/question:movemine', 1, 1451552895, 0),
(228, 1, 3, 'moodle/question:moveall', 1, 1451552895, 0),
(229, 1, 1, 'moodle/question:moveall', 1, 1451552895, 0),
(230, 1, 1, 'moodle/question:config', 1, 1451552895, 0),
(231, 1, 5, 'moodle/question:flag', 1, 1451552895, 0),
(232, 1, 4, 'moodle/question:flag', 1, 1451552895, 0),
(233, 1, 3, 'moodle/question:flag', 1, 1451552895, 0),
(234, 1, 1, 'moodle/question:flag', 1, 1451552895, 0),
(235, 1, 4, 'moodle/site:doclinks', 1, 1451552895, 0),
(236, 1, 3, 'moodle/site:doclinks', 1, 1451552895, 0),
(237, 1, 1, 'moodle/site:doclinks', 1, 1451552895, 0),
(238, 1, 3, 'moodle/course:sectionvisibility', 1, 1451552895, 0),
(239, 1, 1, 'moodle/course:sectionvisibility', 1, 1451552895, 0),
(240, 1, 3, 'moodle/course:useremail', 1, 1451552895, 0),
(241, 1, 1, 'moodle/course:useremail', 1, 1451552895, 0),
(242, 1, 3, 'moodle/course:viewhiddensections', 1, 1451552895, 0),
(243, 1, 1, 'moodle/course:viewhiddensections', 1, 1451552895, 0),
(244, 1, 3, 'moodle/course:setcurrentsection', 1, 1451552895, 0),
(245, 1, 1, 'moodle/course:setcurrentsection', 1, 1451552895, 0),
(246, 1, 3, 'moodle/course:movesections', 1, 1451552895, 0),
(247, 1, 1, 'moodle/course:movesections', 1, 1451552895, 0),
(248, 1, 4, 'moodle/grade:viewall', 1, 1451552895, 0),
(249, 1, 3, 'moodle/grade:viewall', 1, 1451552895, 0),
(250, 1, 1, 'moodle/grade:viewall', 1, 1451552895, 0),
(251, 1, 5, 'moodle/grade:view', 1, 1451552895, 0),
(252, 1, 4, 'moodle/grade:viewhidden', 1, 1451552895, 0),
(253, 1, 3, 'moodle/grade:viewhidden', 1, 1451552895, 0),
(254, 1, 1, 'moodle/grade:viewhidden', 1, 1451552895, 0),
(255, 1, 3, 'moodle/grade:import', 1, 1451552895, 0),
(256, 1, 1, 'moodle/grade:import', 1, 1451552895, 0),
(257, 1, 4, 'moodle/grade:export', 1, 1451552895, 0),
(258, 1, 3, 'moodle/grade:export', 1, 1451552895, 0),
(259, 1, 1, 'moodle/grade:export', 1, 1451552895, 0),
(260, 1, 3, 'moodle/grade:manage', 1, 1451552895, 0),
(261, 1, 1, 'moodle/grade:manage', 1, 1451552895, 0),
(262, 1, 3, 'moodle/grade:edit', 1, 1451552895, 0),
(263, 1, 1, 'moodle/grade:edit', 1, 1451552895, 0),
(264, 1, 3, 'moodle/grade:managegradingforms', 1, 1451552895, 0),
(265, 1, 1, 'moodle/grade:managegradingforms', 1, 1451552895, 0),
(266, 1, 1, 'moodle/grade:sharegradingforms', 1, 1451552895, 0),
(267, 1, 1, 'moodle/grade:managesharedforms', 1, 1451552895, 0),
(268, 1, 3, 'moodle/grade:manageoutcomes', 1, 1451552895, 0),
(269, 1, 1, 'moodle/grade:manageoutcomes', 1, 1451552895, 0),
(270, 1, 3, 'moodle/grade:manageletters', 1, 1451552895, 0),
(271, 1, 1, 'moodle/grade:manageletters', 1, 1451552895, 0),
(272, 1, 3, 'moodle/grade:hide', 1, 1451552895, 0),
(273, 1, 1, 'moodle/grade:hide', 1, 1451552895, 0),
(274, 1, 3, 'moodle/grade:lock', 1, 1451552895, 0),
(275, 1, 1, 'moodle/grade:lock', 1, 1451552895, 0),
(276, 1, 3, 'moodle/grade:unlock', 1, 1451552895, 0),
(277, 1, 1, 'moodle/grade:unlock', 1, 1451552895, 0),
(278, 1, 7, 'moodle/my:manageblocks', 1, 1451552895, 0),
(279, 1, 4, 'moodle/notes:view', 1, 1451552895, 0),
(280, 1, 3, 'moodle/notes:view', 1, 1451552895, 0),
(281, 1, 1, 'moodle/notes:view', 1, 1451552895, 0),
(282, 1, 4, 'moodle/notes:manage', 1, 1451552895, 0),
(283, 1, 3, 'moodle/notes:manage', 1, 1451552895, 0),
(284, 1, 1, 'moodle/notes:manage', 1, 1451552896, 0),
(285, 1, 1, 'moodle/tag:manage', 1, 1451552896, 0),
(286, 1, 1, 'moodle/tag:edit', 1, 1451552896, 0),
(287, 1, 7, 'moodle/tag:flag', 1, 1451552896, 0),
(288, 1, 4, 'moodle/tag:editblocks', 1, 1451552896, 0),
(289, 1, 3, 'moodle/tag:editblocks', 1, 1451552896, 0),
(290, 1, 1, 'moodle/tag:editblocks', 1, 1451552896, 0),
(291, 1, 6, 'moodle/block:view', 1, 1451552896, 0),
(292, 1, 7, 'moodle/block:view', 1, 1451552896, 0),
(293, 1, 5, 'moodle/block:view', 1, 1451552896, 0),
(294, 1, 4, 'moodle/block:view', 1, 1451552896, 0),
(295, 1, 3, 'moodle/block:view', 1, 1451552896, 0),
(296, 1, 3, 'moodle/block:edit', 1, 1451552896, 0),
(297, 1, 1, 'moodle/block:edit', 1, 1451552896, 0),
(298, 1, 7, 'moodle/portfolio:export', 1, 1451552896, 0),
(299, 1, 5, 'moodle/portfolio:export', 1, 1451552896, 0),
(300, 1, 4, 'moodle/portfolio:export', 1, 1451552896, 0),
(301, 1, 3, 'moodle/portfolio:export', 1, 1451552896, 0),
(302, 1, 8, 'moodle/comment:view', 1, 1451552896, 0),
(303, 1, 6, 'moodle/comment:view', 1, 1451552896, 0),
(304, 1, 7, 'moodle/comment:view', 1, 1451552896, 0),
(305, 1, 5, 'moodle/comment:view', 1, 1451552896, 0),
(306, 1, 4, 'moodle/comment:view', 1, 1451552896, 0),
(307, 1, 3, 'moodle/comment:view', 1, 1451552896, 0),
(308, 1, 1, 'moodle/comment:view', 1, 1451552896, 0),
(309, 1, 7, 'moodle/comment:post', 1, 1451552896, 0),
(310, 1, 5, 'moodle/comment:post', 1, 1451552896, 0),
(311, 1, 4, 'moodle/comment:post', 1, 1451552896, 0),
(312, 1, 3, 'moodle/comment:post', 1, 1451552896, 0),
(313, 1, 1, 'moodle/comment:post', 1, 1451552896, 0),
(314, 1, 3, 'moodle/comment:delete', 1, 1451552896, 0),
(315, 1, 1, 'moodle/comment:delete', 1, 1451552896, 0),
(316, 1, 1, 'moodle/webservice:createtoken', 1, 1451552896, 0),
(317, 1, 7, 'moodle/webservice:createmobiletoken', 1, 1451552896, 0),
(318, 1, 7, 'moodle/rating:view', 1, 1451552896, 0),
(319, 1, 5, 'moodle/rating:view', 1, 1451552896, 0),
(320, 1, 4, 'moodle/rating:view', 1, 1451552896, 0),
(321, 1, 3, 'moodle/rating:view', 1, 1451552896, 0),
(322, 1, 1, 'moodle/rating:view', 1, 1451552896, 0),
(323, 1, 7, 'moodle/rating:viewany', 1, 1451552896, 0),
(324, 1, 5, 'moodle/rating:viewany', 1, 1451552896, 0),
(325, 1, 4, 'moodle/rating:viewany', 1, 1451552896, 0),
(326, 1, 3, 'moodle/rating:viewany', 1, 1451552896, 0),
(327, 1, 1, 'moodle/rating:viewany', 1, 1451552896, 0),
(328, 1, 7, 'moodle/rating:viewall', 1, 1451552896, 0),
(329, 1, 5, 'moodle/rating:viewall', 1, 1451552896, 0),
(330, 1, 4, 'moodle/rating:viewall', 1, 1451552896, 0),
(331, 1, 3, 'moodle/rating:viewall', 1, 1451552896, 0),
(332, 1, 1, 'moodle/rating:viewall', 1, 1451552896, 0),
(333, 1, 7, 'moodle/rating:rate', 1, 1451552896, 0),
(334, 1, 5, 'moodle/rating:rate', 1, 1451552896, 0),
(335, 1, 4, 'moodle/rating:rate', 1, 1451552896, 0),
(336, 1, 3, 'moodle/rating:rate', 1, 1451552896, 0),
(337, 1, 1, 'moodle/rating:rate', 1, 1451552896, 0),
(338, 1, 1, 'moodle/course:publish', 1, 1451552896, 0),
(339, 1, 4, 'moodle/course:markcomplete', 1, 1451552896, 0),
(340, 1, 3, 'moodle/course:markcomplete', 1, 1451552896, 0),
(341, 1, 1, 'moodle/course:markcomplete', 1, 1451552896, 0),
(342, 1, 1, 'moodle/community:add', 1, 1451552896, 0),
(343, 1, 4, 'moodle/community:add', 1, 1451552896, 0),
(344, 1, 3, 'moodle/community:add', 1, 1451552896, 0),
(345, 1, 1, 'moodle/community:download', 1, 1451552896, 0),
(346, 1, 3, 'moodle/community:download', 1, 1451552896, 0),
(347, 1, 1, 'moodle/badges:manageglobalsettings', 1, 1451552896, 0),
(348, 1, 7, 'moodle/badges:viewbadges', 1, 1451552896, 0),
(349, 1, 7, 'moodle/badges:manageownbadges', 1, 1451552896, 0),
(350, 1, 7, 'moodle/badges:viewotherbadges', 1, 1451552896, 0),
(351, 1, 7, 'moodle/badges:earnbadge', 1, 1451552896, 0),
(352, 1, 1, 'moodle/badges:createbadge', 1, 1451552896, 0),
(353, 1, 3, 'moodle/badges:createbadge', 1, 1451552896, 0),
(354, 1, 1, 'moodle/badges:deletebadge', 1, 1451552896, 0),
(355, 1, 3, 'moodle/badges:deletebadge', 1, 1451552896, 0),
(356, 1, 1, 'moodle/badges:configuredetails', 1, 1451552896, 0),
(357, 1, 3, 'moodle/badges:configuredetails', 1, 1451552896, 0),
(358, 1, 1, 'moodle/badges:configurecriteria', 1, 1451552896, 0),
(359, 1, 3, 'moodle/badges:configurecriteria', 1, 1451552896, 0),
(360, 1, 1, 'moodle/badges:configuremessages', 1, 1451552896, 0),
(361, 1, 3, 'moodle/badges:configuremessages', 1, 1451552896, 0),
(362, 1, 1, 'moodle/badges:awardbadge', 1, 1451552896, 0),
(363, 1, 4, 'moodle/badges:awardbadge', 1, 1451552896, 0),
(364, 1, 3, 'moodle/badges:awardbadge', 1, 1451552896, 0),
(365, 1, 1, 'moodle/badges:viewawarded', 1, 1451552896, 0),
(366, 1, 4, 'moodle/badges:viewawarded', 1, 1451552896, 0),
(367, 1, 3, 'moodle/badges:viewawarded', 1, 1451552896, 0),
(368, 1, 6, 'mod/assign:view', 1, 1451552907, 0),
(369, 1, 5, 'mod/assign:view', 1, 1451552907, 0),
(370, 1, 4, 'mod/assign:view', 1, 1451552907, 0),
(371, 1, 3, 'mod/assign:view', 1, 1451552907, 0),
(372, 1, 1, 'mod/assign:view', 1, 1451552907, 0),
(373, 1, 5, 'mod/assign:submit', 1, 1451552907, 0),
(374, 1, 4, 'mod/assign:grade', 1, 1451552907, 0),
(375, 1, 3, 'mod/assign:grade', 1, 1451552907, 0),
(376, 1, 1, 'mod/assign:grade', 1, 1451552907, 0),
(377, 1, 4, 'mod/assign:exportownsubmission', 1, 1451552907, 0),
(378, 1, 3, 'mod/assign:exportownsubmission', 1, 1451552907, 0),
(379, 1, 1, 'mod/assign:exportownsubmission', 1, 1451552907, 0),
(380, 1, 5, 'mod/assign:exportownsubmission', 1, 1451552907, 0),
(381, 1, 3, 'mod/assign:addinstance', 1, 1451552907, 0),
(382, 1, 1, 'mod/assign:addinstance', 1, 1451552907, 0),
(383, 1, 4, 'mod/assign:grantextension', 1, 1451552907, 0),
(384, 1, 3, 'mod/assign:grantextension', 1, 1451552907, 0),
(385, 1, 1, 'mod/assign:grantextension', 1, 1451552907, 0),
(386, 1, 3, 'mod/assign:revealidentities', 1, 1451552907, 0),
(387, 1, 1, 'mod/assign:revealidentities', 1, 1451552907, 0),
(388, 1, 3, 'mod/assign:reviewgrades', 1, 1451552907, 0),
(389, 1, 1, 'mod/assign:reviewgrades', 1, 1451552907, 0),
(390, 1, 3, 'mod/assign:releasegrades', 1, 1451552907, 0),
(391, 1, 1, 'mod/assign:releasegrades', 1, 1451552907, 0),
(392, 1, 3, 'mod/assign:managegrades', 1, 1451552907, 0),
(393, 1, 1, 'mod/assign:managegrades', 1, 1451552907, 0),
(394, 1, 3, 'mod/assign:manageallocations', 1, 1451552907, 0),
(395, 1, 1, 'mod/assign:manageallocations', 1, 1451552907, 0),
(396, 1, 3, 'mod/assign:viewgrades', 1, 1451552907, 0),
(397, 1, 1, 'mod/assign:viewgrades', 1, 1451552907, 0),
(398, 1, 4, 'mod/assign:viewgrades', 1, 1451552907, 0),
(399, 1, 1, 'mod/assign:viewblinddetails', 1, 1451552907, 0),
(400, 1, 4, 'mod/assign:receivegradernotifications', 1, 1451552907, 0),
(401, 1, 3, 'mod/assign:receivegradernotifications', 1, 1451552907, 0),
(402, 1, 1, 'mod/assign:receivegradernotifications', 1, 1451552907, 0),
(403, 1, 6, 'mod/assignment:view', 1, 1451552907, 0),
(404, 1, 5, 'mod/assignment:view', 1, 1451552907, 0),
(405, 1, 4, 'mod/assignment:view', 1, 1451552907, 0),
(406, 1, 3, 'mod/assignment:view', 1, 1451552907, 0),
(407, 1, 1, 'mod/assignment:view', 1, 1451552907, 0),
(408, 1, 3, 'mod/assignment:addinstance', 1, 1451552907, 0),
(409, 1, 1, 'mod/assignment:addinstance', 1, 1451552907, 0),
(410, 1, 5, 'mod/assignment:submit', 1, 1451552907, 0),
(411, 1, 4, 'mod/assignment:grade', 1, 1451552907, 0),
(412, 1, 3, 'mod/assignment:grade', 1, 1451552907, 0),
(413, 1, 1, 'mod/assignment:grade', 1, 1451552907, 0),
(414, 1, 4, 'mod/assignment:exportownsubmission', 1, 1451552907, 0),
(415, 1, 3, 'mod/assignment:exportownsubmission', 1, 1451552907, 0),
(416, 1, 1, 'mod/assignment:exportownsubmission', 1, 1451552907, 0),
(417, 1, 5, 'mod/assignment:exportownsubmission', 1, 1451552907, 0),
(418, 1, 3, 'mod/book:addinstance', 1, 1451552908, 0),
(419, 1, 1, 'mod/book:addinstance', 1, 1451552908, 0),
(420, 1, 6, 'mod/book:read', 1, 1451552908, 0),
(421, 1, 8, 'mod/book:read', 1, 1451552908, 0),
(422, 1, 5, 'mod/book:read', 1, 1451552908, 0),
(423, 1, 4, 'mod/book:read', 1, 1451552908, 0),
(424, 1, 3, 'mod/book:read', 1, 1451552908, 0),
(425, 1, 1, 'mod/book:read', 1, 1451552908, 0),
(426, 1, 4, 'mod/book:viewhiddenchapters', 1, 1451552908, 0),
(427, 1, 3, 'mod/book:viewhiddenchapters', 1, 1451552908, 0),
(428, 1, 1, 'mod/book:viewhiddenchapters', 1, 1451552908, 0),
(429, 1, 3, 'mod/book:edit', 1, 1451552908, 0),
(430, 1, 1, 'mod/book:edit', 1, 1451552908, 0),
(431, 1, 3, 'mod/chat:addinstance', 1, 1451552908, 0),
(432, 1, 1, 'mod/chat:addinstance', 1, 1451552908, 0),
(433, 1, 5, 'mod/chat:chat', 1, 1451552908, 0),
(434, 1, 4, 'mod/chat:chat', 1, 1451552908, 0),
(435, 1, 3, 'mod/chat:chat', 1, 1451552908, 0),
(436, 1, 1, 'mod/chat:chat', 1, 1451552908, 0),
(437, 1, 5, 'mod/chat:readlog', 1, 1451552908, 0),
(438, 1, 4, 'mod/chat:readlog', 1, 1451552908, 0),
(439, 1, 3, 'mod/chat:readlog', 1, 1451552908, 0),
(440, 1, 1, 'mod/chat:readlog', 1, 1451552908, 0),
(441, 1, 4, 'mod/chat:deletelog', 1, 1451552908, 0),
(442, 1, 3, 'mod/chat:deletelog', 1, 1451552908, 0),
(443, 1, 1, 'mod/chat:deletelog', 1, 1451552908, 0),
(444, 1, 4, 'mod/chat:exportparticipatedsession', 1, 1451552908, 0),
(445, 1, 3, 'mod/chat:exportparticipatedsession', 1, 1451552908, 0),
(446, 1, 1, 'mod/chat:exportparticipatedsession', 1, 1451552908, 0),
(447, 1, 4, 'mod/chat:exportsession', 1, 1451552908, 0),
(448, 1, 3, 'mod/chat:exportsession', 1, 1451552908, 0),
(449, 1, 1, 'mod/chat:exportsession', 1, 1451552908, 0),
(450, 1, 3, 'mod/choice:addinstance', 1, 1451552908, 0),
(451, 1, 1, 'mod/choice:addinstance', 1, 1451552908, 0),
(452, 1, 5, 'mod/choice:choose', 1, 1451552908, 0),
(453, 1, 4, 'mod/choice:choose', 1, 1451552908, 0),
(454, 1, 3, 'mod/choice:choose', 1, 1451552908, 0),
(455, 1, 4, 'mod/choice:readresponses', 1, 1451552908, 0),
(456, 1, 3, 'mod/choice:readresponses', 1, 1451552908, 0),
(457, 1, 1, 'mod/choice:readresponses', 1, 1451552908, 0),
(458, 1, 4, 'mod/choice:deleteresponses', 1, 1451552908, 0),
(459, 1, 3, 'mod/choice:deleteresponses', 1, 1451552908, 0),
(460, 1, 1, 'mod/choice:deleteresponses', 1, 1451552908, 0),
(461, 1, 4, 'mod/choice:downloadresponses', 1, 1451552908, 0),
(462, 1, 3, 'mod/choice:downloadresponses', 1, 1451552908, 0),
(463, 1, 1, 'mod/choice:downloadresponses', 1, 1451552908, 0),
(464, 1, 3, 'mod/data:addinstance', 1, 1451552909, 0),
(465, 1, 1, 'mod/data:addinstance', 1, 1451552909, 0),
(466, 1, 8, 'mod/data:viewentry', 1, 1451552909, 0),
(467, 1, 6, 'mod/data:viewentry', 1, 1451552909, 0),
(468, 1, 5, 'mod/data:viewentry', 1, 1451552909, 0),
(469, 1, 4, 'mod/data:viewentry', 1, 1451552909, 0),
(470, 1, 3, 'mod/data:viewentry', 1, 1451552909, 0),
(471, 1, 1, 'mod/data:viewentry', 1, 1451552909, 0),
(472, 1, 5, 'mod/data:writeentry', 1, 1451552909, 0),
(473, 1, 4, 'mod/data:writeentry', 1, 1451552909, 0),
(474, 1, 3, 'mod/data:writeentry', 1, 1451552909, 0),
(475, 1, 1, 'mod/data:writeentry', 1, 1451552909, 0),
(476, 1, 5, 'mod/data:comment', 1, 1451552909, 0),
(477, 1, 4, 'mod/data:comment', 1, 1451552909, 0),
(478, 1, 3, 'mod/data:comment', 1, 1451552909, 0),
(479, 1, 1, 'mod/data:comment', 1, 1451552909, 0),
(480, 1, 4, 'mod/data:rate', 1, 1451552909, 0),
(481, 1, 3, 'mod/data:rate', 1, 1451552909, 0),
(482, 1, 1, 'mod/data:rate', 1, 1451552909, 0),
(483, 1, 4, 'mod/data:viewrating', 1, 1451552909, 0),
(484, 1, 3, 'mod/data:viewrating', 1, 1451552909, 0),
(485, 1, 1, 'mod/data:viewrating', 1, 1451552909, 0),
(486, 1, 4, 'mod/data:viewanyrating', 1, 1451552909, 0),
(487, 1, 3, 'mod/data:viewanyrating', 1, 1451552909, 0),
(488, 1, 1, 'mod/data:viewanyrating', 1, 1451552909, 0),
(489, 1, 4, 'mod/data:viewallratings', 1, 1451552909, 0),
(490, 1, 3, 'mod/data:viewallratings', 1, 1451552909, 0),
(491, 1, 1, 'mod/data:viewallratings', 1, 1451552909, 0),
(492, 1, 4, 'mod/data:approve', 1, 1451552909, 0),
(493, 1, 3, 'mod/data:approve', 1, 1451552909, 0),
(494, 1, 1, 'mod/data:approve', 1, 1451552909, 0),
(495, 1, 4, 'mod/data:manageentries', 1, 1451552909, 0),
(496, 1, 3, 'mod/data:manageentries', 1, 1451552909, 0),
(497, 1, 1, 'mod/data:manageentries', 1, 1451552909, 0),
(498, 1, 4, 'mod/data:managecomments', 1, 1451552909, 0),
(499, 1, 3, 'mod/data:managecomments', 1, 1451552909, 0),
(500, 1, 1, 'mod/data:managecomments', 1, 1451552909, 0),
(501, 1, 3, 'mod/data:managetemplates', 1, 1451552909, 0),
(502, 1, 1, 'mod/data:managetemplates', 1, 1451552909, 0),
(503, 1, 4, 'mod/data:viewalluserpresets', 1, 1451552909, 0),
(504, 1, 3, 'mod/data:viewalluserpresets', 1, 1451552909, 0),
(505, 1, 1, 'mod/data:viewalluserpresets', 1, 1451552909, 0),
(506, 1, 1, 'mod/data:manageuserpresets', 1, 1451552909, 0),
(507, 1, 1, 'mod/data:exportentry', 1, 1451552909, 0),
(508, 1, 4, 'mod/data:exportentry', 1, 1451552909, 0),
(509, 1, 3, 'mod/data:exportentry', 1, 1451552909, 0),
(510, 1, 1, 'mod/data:exportownentry', 1, 1451552909, 0),
(511, 1, 4, 'mod/data:exportownentry', 1, 1451552909, 0),
(512, 1, 3, 'mod/data:exportownentry', 1, 1451552909, 0),
(513, 1, 5, 'mod/data:exportownentry', 1, 1451552909, 0),
(514, 1, 1, 'mod/data:exportallentries', 1, 1451552909, 0),
(515, 1, 4, 'mod/data:exportallentries', 1, 1451552909, 0),
(516, 1, 3, 'mod/data:exportallentries', 1, 1451552909, 0),
(517, 1, 1, 'mod/data:exportuserinfo', 1, 1451552909, 0),
(518, 1, 4, 'mod/data:exportuserinfo', 1, 1451552909, 0),
(519, 1, 3, 'mod/data:exportuserinfo', 1, 1451552909, 0),
(520, 1, 3, 'mod/feedback:addinstance', 1, 1451552909, 0),
(521, 1, 1, 'mod/feedback:addinstance', 1, 1451552909, 0),
(522, 1, 6, 'mod/feedback:view', 1, 1451552909, 0),
(523, 1, 5, 'mod/feedback:view', 1, 1451552909, 0),
(524, 1, 4, 'mod/feedback:view', 1, 1451552909, 0),
(525, 1, 3, 'mod/feedback:view', 1, 1451552909, 0),
(526, 1, 1, 'mod/feedback:view', 1, 1451552909, 0),
(527, 1, 5, 'mod/feedback:complete', 1, 1451552909, 0),
(528, 1, 5, 'mod/feedback:viewanalysepage', 1, 1451552909, 0),
(529, 1, 3, 'mod/feedback:viewanalysepage', 1, 1451552909, 0),
(530, 1, 1, 'mod/feedback:viewanalysepage', 1, 1451552909, 0),
(531, 1, 3, 'mod/feedback:deletesubmissions', 1, 1451552909, 0),
(532, 1, 1, 'mod/feedback:deletesubmissions', 1, 1451552909, 0),
(533, 1, 1, 'mod/feedback:mapcourse', 1, 1451552909, 0),
(534, 1, 3, 'mod/feedback:edititems', 1, 1451552909, 0),
(535, 1, 1, 'mod/feedback:edititems', 1, 1451552909, 0),
(536, 1, 3, 'mod/feedback:createprivatetemplate', 1, 1451552909, 0),
(537, 1, 1, 'mod/feedback:createprivatetemplate', 1, 1451552909, 0),
(538, 1, 3, 'mod/feedback:createpublictemplate', 1, 1451552909, 0),
(539, 1, 1, 'mod/feedback:createpublictemplate', 1, 1451552909, 0),
(540, 1, 3, 'mod/feedback:deletetemplate', 1, 1451552909, 0),
(541, 1, 1, 'mod/feedback:deletetemplate', 1, 1451552909, 0),
(542, 1, 4, 'mod/feedback:viewreports', 1, 1451552909, 0),
(543, 1, 3, 'mod/feedback:viewreports', 1, 1451552909, 0),
(544, 1, 1, 'mod/feedback:viewreports', 1, 1451552909, 0),
(545, 1, 4, 'mod/feedback:receivemail', 1, 1451552909, 0),
(546, 1, 3, 'mod/feedback:receivemail', 1, 1451552909, 0),
(547, 1, 3, 'mod/folder:addinstance', 1, 1451552910, 0),
(548, 1, 1, 'mod/folder:addinstance', 1, 1451552910, 0),
(549, 1, 6, 'mod/folder:view', 1, 1451552910, 0),
(550, 1, 7, 'mod/folder:view', 1, 1451552910, 0),
(551, 1, 3, 'mod/folder:managefiles', 1, 1451552910, 0),
(552, 1, 3, 'mod/forum:addinstance', 1, 1451552910, 0),
(553, 1, 1, 'mod/forum:addinstance', 1, 1451552910, 0),
(554, 1, 8, 'mod/forum:viewdiscussion', 1, 1451552910, 0),
(555, 1, 6, 'mod/forum:viewdiscussion', 1, 1451552910, 0),
(556, 1, 5, 'mod/forum:viewdiscussion', 1, 1451552910, 0),
(557, 1, 4, 'mod/forum:viewdiscussion', 1, 1451552910, 0),
(558, 1, 3, 'mod/forum:viewdiscussion', 1, 1451552910, 0),
(559, 1, 1, 'mod/forum:viewdiscussion', 1, 1451552910, 0),
(560, 1, 4, 'mod/forum:viewhiddentimedposts', 1, 1451552910, 0),
(561, 1, 3, 'mod/forum:viewhiddentimedposts', 1, 1451552910, 0),
(562, 1, 1, 'mod/forum:viewhiddentimedposts', 1, 1451552910, 0),
(563, 1, 5, 'mod/forum:startdiscussion', 1, 1451552910, 0),
(564, 1, 4, 'mod/forum:startdiscussion', 1, 1451552910, 0),
(565, 1, 3, 'mod/forum:startdiscussion', 1, 1451552910, 0),
(566, 1, 1, 'mod/forum:startdiscussion', 1, 1451552910, 0),
(567, 1, 5, 'mod/forum:replypost', 1, 1451552910, 0),
(568, 1, 4, 'mod/forum:replypost', 1, 1451552910, 0),
(569, 1, 3, 'mod/forum:replypost', 1, 1451552910, 0),
(570, 1, 1, 'mod/forum:replypost', 1, 1451552910, 0),
(571, 1, 4, 'mod/forum:addnews', 1, 1451552910, 0),
(572, 1, 3, 'mod/forum:addnews', 1, 1451552910, 0),
(573, 1, 1, 'mod/forum:addnews', 1, 1451552910, 0),
(574, 1, 4, 'mod/forum:replynews', 1, 1451552910, 0),
(575, 1, 3, 'mod/forum:replynews', 1, 1451552910, 0),
(576, 1, 1, 'mod/forum:replynews', 1, 1451552910, 0),
(577, 1, 5, 'mod/forum:viewrating', 1, 1451552910, 0),
(578, 1, 4, 'mod/forum:viewrating', 1, 1451552910, 0),
(579, 1, 3, 'mod/forum:viewrating', 1, 1451552910, 0),
(580, 1, 1, 'mod/forum:viewrating', 1, 1451552910, 0),
(581, 1, 4, 'mod/forum:viewanyrating', 1, 1451552910, 0),
(582, 1, 3, 'mod/forum:viewanyrating', 1, 1451552910, 0),
(583, 1, 1, 'mod/forum:viewanyrating', 1, 1451552910, 0),
(584, 1, 4, 'mod/forum:viewallratings', 1, 1451552910, 0),
(585, 1, 3, 'mod/forum:viewallratings', 1, 1451552910, 0),
(586, 1, 1, 'mod/forum:viewallratings', 1, 1451552910, 0),
(587, 1, 4, 'mod/forum:rate', 1, 1451552910, 0),
(588, 1, 3, 'mod/forum:rate', 1, 1451552910, 0),
(589, 1, 1, 'mod/forum:rate', 1, 1451552910, 0),
(590, 1, 5, 'mod/forum:createattachment', 1, 1451552910, 0),
(591, 1, 4, 'mod/forum:createattachment', 1, 1451552910, 0),
(592, 1, 3, 'mod/forum:createattachment', 1, 1451552910, 0),
(593, 1, 1, 'mod/forum:createattachment', 1, 1451552910, 0),
(594, 1, 5, 'mod/forum:deleteownpost', 1, 1451552910, 0),
(595, 1, 4, 'mod/forum:deleteownpost', 1, 1451552910, 0),
(596, 1, 3, 'mod/forum:deleteownpost', 1, 1451552910, 0),
(597, 1, 1, 'mod/forum:deleteownpost', 1, 1451552910, 0),
(598, 1, 4, 'mod/forum:deleteanypost', 1, 1451552910, 0),
(599, 1, 3, 'mod/forum:deleteanypost', 1, 1451552910, 0),
(600, 1, 1, 'mod/forum:deleteanypost', 1, 1451552910, 0),
(601, 1, 4, 'mod/forum:splitdiscussions', 1, 1451552910, 0),
(602, 1, 3, 'mod/forum:splitdiscussions', 1, 1451552910, 0),
(603, 1, 1, 'mod/forum:splitdiscussions', 1, 1451552910, 0),
(604, 1, 4, 'mod/forum:movediscussions', 1, 1451552910, 0),
(605, 1, 3, 'mod/forum:movediscussions', 1, 1451552910, 0),
(606, 1, 1, 'mod/forum:movediscussions', 1, 1451552910, 0),
(607, 1, 4, 'mod/forum:editanypost', 1, 1451552910, 0),
(608, 1, 3, 'mod/forum:editanypost', 1, 1451552910, 0),
(609, 1, 1, 'mod/forum:editanypost', 1, 1451552910, 0),
(610, 1, 4, 'mod/forum:viewqandawithoutposting', 1, 1451552910, 0),
(611, 1, 3, 'mod/forum:viewqandawithoutposting', 1, 1451552910, 0),
(612, 1, 1, 'mod/forum:viewqandawithoutposting', 1, 1451552910, 0),
(613, 1, 4, 'mod/forum:viewsubscribers', 1, 1451552910, 0),
(614, 1, 3, 'mod/forum:viewsubscribers', 1, 1451552910, 0),
(615, 1, 1, 'mod/forum:viewsubscribers', 1, 1451552910, 0),
(616, 1, 4, 'mod/forum:managesubscriptions', 1, 1451552910, 0),
(617, 1, 3, 'mod/forum:managesubscriptions', 1, 1451552910, 0),
(618, 1, 1, 'mod/forum:managesubscriptions', 1, 1451552910, 0),
(619, 1, 4, 'mod/forum:postwithoutthrottling', 1, 1451552910, 0),
(620, 1, 3, 'mod/forum:postwithoutthrottling', 1, 1451552910, 0),
(621, 1, 1, 'mod/forum:postwithoutthrottling', 1, 1451552910, 0),
(622, 1, 4, 'mod/forum:exportdiscussion', 1, 1451552910, 0),
(623, 1, 3, 'mod/forum:exportdiscussion', 1, 1451552910, 0),
(624, 1, 1, 'mod/forum:exportdiscussion', 1, 1451552910, 0),
(625, 1, 4, 'mod/forum:exportpost', 1, 1451552910, 0),
(626, 1, 3, 'mod/forum:exportpost', 1, 1451552910, 0),
(627, 1, 1, 'mod/forum:exportpost', 1, 1451552910, 0),
(628, 1, 4, 'mod/forum:exportownpost', 1, 1451552910, 0),
(629, 1, 3, 'mod/forum:exportownpost', 1, 1451552910, 0),
(630, 1, 1, 'mod/forum:exportownpost', 1, 1451552910, 0),
(631, 1, 5, 'mod/forum:exportownpost', 1, 1451552910, 0),
(632, 1, 4, 'mod/forum:addquestion', 1, 1451552910, 0),
(633, 1, 3, 'mod/forum:addquestion', 1, 1451552910, 0),
(634, 1, 1, 'mod/forum:addquestion', 1, 1451552910, 0),
(635, 1, 5, 'mod/forum:allowforcesubscribe', 1, 1451552910, 0),
(636, 1, 4, 'mod/forum:allowforcesubscribe', 1, 1451552910, 0),
(637, 1, 3, 'mod/forum:allowforcesubscribe', 1, 1451552910, 0),
(638, 1, 8, 'mod/forum:allowforcesubscribe', 1, 1451552910, 0),
(639, 1, 4, 'mod/forum:canposttomygroups', 1, 1451552910, 0),
(640, 1, 3, 'mod/forum:canposttomygroups', 1, 1451552910, 0),
(641, 1, 1, 'mod/forum:canposttomygroups', 1, 1451552910, 0),
(642, 1, 3, 'mod/glossary:addinstance', 1, 1451552911, 0),
(643, 1, 1, 'mod/glossary:addinstance', 1, 1451552911, 0),
(644, 1, 8, 'mod/glossary:view', 1, 1451552911, 0),
(645, 1, 6, 'mod/glossary:view', 1, 1451552911, 0),
(646, 1, 5, 'mod/glossary:view', 1, 1451552911, 0),
(647, 1, 4, 'mod/glossary:view', 1, 1451552911, 0),
(648, 1, 3, 'mod/glossary:view', 1, 1451552911, 0),
(649, 1, 1, 'mod/glossary:view', 1, 1451552911, 0),
(650, 1, 5, 'mod/glossary:write', 1, 1451552911, 0),
(651, 1, 4, 'mod/glossary:write', 1, 1451552911, 0),
(652, 1, 3, 'mod/glossary:write', 1, 1451552911, 0),
(653, 1, 1, 'mod/glossary:write', 1, 1451552911, 0),
(654, 1, 4, 'mod/glossary:manageentries', 1, 1451552911, 0),
(655, 1, 3, 'mod/glossary:manageentries', 1, 1451552911, 0),
(656, 1, 1, 'mod/glossary:manageentries', 1, 1451552911, 0),
(657, 1, 4, 'mod/glossary:managecategories', 1, 1451552911, 0),
(658, 1, 3, 'mod/glossary:managecategories', 1, 1451552911, 0),
(659, 1, 1, 'mod/glossary:managecategories', 1, 1451552911, 0),
(660, 1, 5, 'mod/glossary:comment', 1, 1451552911, 0),
(661, 1, 4, 'mod/glossary:comment', 1, 1451552911, 0),
(662, 1, 3, 'mod/glossary:comment', 1, 1451552911, 0),
(663, 1, 1, 'mod/glossary:comment', 1, 1451552911, 0),
(664, 1, 4, 'mod/glossary:managecomments', 1, 1451552911, 0),
(665, 1, 3, 'mod/glossary:managecomments', 1, 1451552911, 0),
(666, 1, 1, 'mod/glossary:managecomments', 1, 1451552911, 0),
(667, 1, 4, 'mod/glossary:import', 1, 1451552911, 0),
(668, 1, 3, 'mod/glossary:import', 1, 1451552911, 0),
(669, 1, 1, 'mod/glossary:import', 1, 1451552911, 0),
(670, 1, 4, 'mod/glossary:export', 1, 1451552911, 0),
(671, 1, 3, 'mod/glossary:export', 1, 1451552911, 0),
(672, 1, 1, 'mod/glossary:export', 1, 1451552911, 0),
(673, 1, 4, 'mod/glossary:approve', 1, 1451552911, 0),
(674, 1, 3, 'mod/glossary:approve', 1, 1451552911, 0),
(675, 1, 1, 'mod/glossary:approve', 1, 1451552911, 0),
(676, 1, 4, 'mod/glossary:rate', 1, 1451552911, 0),
(677, 1, 3, 'mod/glossary:rate', 1, 1451552911, 0),
(678, 1, 1, 'mod/glossary:rate', 1, 1451552911, 0),
(679, 1, 4, 'mod/glossary:viewrating', 1, 1451552911, 0),
(680, 1, 3, 'mod/glossary:viewrating', 1, 1451552911, 0),
(681, 1, 1, 'mod/glossary:viewrating', 1, 1451552911, 0),
(682, 1, 4, 'mod/glossary:viewanyrating', 1, 1451552911, 0),
(683, 1, 3, 'mod/glossary:viewanyrating', 1, 1451552911, 0),
(684, 1, 1, 'mod/glossary:viewanyrating', 1, 1451552911, 0),
(685, 1, 4, 'mod/glossary:viewallratings', 1, 1451552911, 0),
(686, 1, 3, 'mod/glossary:viewallratings', 1, 1451552911, 0),
(687, 1, 1, 'mod/glossary:viewallratings', 1, 1451552911, 0),
(688, 1, 4, 'mod/glossary:exportentry', 1, 1451552911, 0),
(689, 1, 3, 'mod/glossary:exportentry', 1, 1451552911, 0),
(690, 1, 1, 'mod/glossary:exportentry', 1, 1451552911, 0),
(691, 1, 4, 'mod/glossary:exportownentry', 1, 1451552911, 0),
(692, 1, 3, 'mod/glossary:exportownentry', 1, 1451552911, 0),
(693, 1, 1, 'mod/glossary:exportownentry', 1, 1451552911, 0),
(694, 1, 5, 'mod/glossary:exportownentry', 1, 1451552911, 0),
(695, 1, 6, 'mod/imscp:view', 1, 1451552911, 0),
(696, 1, 7, 'mod/imscp:view', 1, 1451552911, 0),
(697, 1, 3, 'mod/imscp:addinstance', 1, 1451552911, 0),
(698, 1, 1, 'mod/imscp:addinstance', 1, 1451552911, 0),
(699, 1, 3, 'mod/label:addinstance', 1, 1451552911, 0),
(700, 1, 1, 'mod/label:addinstance', 1, 1451552911, 0),
(701, 1, 3, 'mod/lesson:addinstance', 1, 1451552912, 0),
(702, 1, 1, 'mod/lesson:addinstance', 1, 1451552912, 0),
(703, 1, 3, 'mod/lesson:edit', 1, 1451552912, 0),
(704, 1, 1, 'mod/lesson:edit', 1, 1451552912, 0),
(705, 1, 4, 'mod/lesson:grade', 1, 1451552912, 0),
(706, 1, 3, 'mod/lesson:grade', 1, 1451552912, 0),
(707, 1, 1, 'mod/lesson:grade', 1, 1451552912, 0),
(708, 1, 4, 'mod/lesson:viewreports', 1, 1451552912, 0),
(709, 1, 3, 'mod/lesson:viewreports', 1, 1451552912, 0),
(710, 1, 1, 'mod/lesson:viewreports', 1, 1451552912, 0),
(711, 1, 4, 'mod/lesson:manage', 1, 1451552912, 0),
(712, 1, 3, 'mod/lesson:manage', 1, 1451552912, 0),
(713, 1, 1, 'mod/lesson:manage', 1, 1451552912, 0),
(714, 1, 3, 'mod/lesson:manageoverrides', 1, 1451552912, 0),
(715, 1, 1, 'mod/lesson:manageoverrides', 1, 1451552912, 0),
(716, 1, 5, 'mod/lti:view', 1, 1451552912, 0),
(717, 1, 4, 'mod/lti:view', 1, 1451552912, 0),
(718, 1, 3, 'mod/lti:view', 1, 1451552912, 0),
(719, 1, 1, 'mod/lti:view', 1, 1451552912, 0),
(720, 1, 3, 'mod/lti:addinstance', 1, 1451552912, 0),
(721, 1, 1, 'mod/lti:addinstance', 1, 1451552912, 0),
(722, 1, 4, 'mod/lti:manage', 1, 1451552912, 0),
(723, 1, 3, 'mod/lti:manage', 1, 1451552912, 0),
(724, 1, 1, 'mod/lti:manage', 1, 1451552912, 0),
(725, 1, 3, 'mod/lti:addcoursetool', 1, 1451552912, 0),
(726, 1, 1, 'mod/lti:addcoursetool', 1, 1451552912, 0),
(727, 1, 3, 'mod/lti:requesttooladd', 1, 1451552912, 0),
(728, 1, 1, 'mod/lti:requesttooladd', 1, 1451552912, 0),
(729, 1, 6, 'mod/page:view', 1, 1451552912, 0),
(730, 1, 7, 'mod/page:view', 1, 1451552912, 0),
(731, 1, 3, 'mod/page:addinstance', 1, 1451552912, 0),
(732, 1, 1, 'mod/page:addinstance', 1, 1451552912, 0),
(733, 1, 6, 'mod/quiz:view', 1, 1451552913, 0),
(734, 1, 5, 'mod/quiz:view', 1, 1451552913, 0),
(735, 1, 4, 'mod/quiz:view', 1, 1451552913, 0),
(736, 1, 3, 'mod/quiz:view', 1, 1451552913, 0),
(737, 1, 1, 'mod/quiz:view', 1, 1451552913, 0),
(738, 1, 3, 'mod/quiz:addinstance', 1, 1451552913, 0),
(739, 1, 1, 'mod/quiz:addinstance', 1, 1451552913, 0),
(740, 1, 5, 'mod/quiz:attempt', 1, 1451552913, 0),
(741, 1, 5, 'mod/quiz:reviewmyattempts', 1, 1451552913, 0),
(742, 1, 3, 'mod/quiz:manage', 1, 1451552913, 0),
(743, 1, 1, 'mod/quiz:manage', 1, 1451552913, 0),
(744, 1, 3, 'mod/quiz:manageoverrides', 1, 1451552913, 0),
(745, 1, 1, 'mod/quiz:manageoverrides', 1, 1451552913, 0),
(746, 1, 4, 'mod/quiz:preview', 1, 1451552913, 0),
(747, 1, 3, 'mod/quiz:preview', 1, 1451552913, 0),
(748, 1, 1, 'mod/quiz:preview', 1, 1451552913, 0),
(749, 1, 4, 'mod/quiz:grade', 1, 1451552913, 0),
(750, 1, 3, 'mod/quiz:grade', 1, 1451552913, 0),
(751, 1, 1, 'mod/quiz:grade', 1, 1451552913, 0),
(752, 1, 4, 'mod/quiz:regrade', 1, 1451552913, 0),
(753, 1, 3, 'mod/quiz:regrade', 1, 1451552913, 0),
(754, 1, 1, 'mod/quiz:regrade', 1, 1451552913, 0),
(755, 1, 4, 'mod/quiz:viewreports', 1, 1451552913, 0),
(756, 1, 3, 'mod/quiz:viewreports', 1, 1451552913, 0),
(757, 1, 1, 'mod/quiz:viewreports', 1, 1451552913, 0),
(758, 1, 3, 'mod/quiz:deleteattempts', 1, 1451552913, 0),
(759, 1, 1, 'mod/quiz:deleteattempts', 1, 1451552913, 0),
(760, 1, 6, 'mod/resource:view', 1, 1451552913, 0),
(761, 1, 7, 'mod/resource:view', 1, 1451552913, 0),
(762, 1, 3, 'mod/resource:addinstance', 1, 1451552913, 0),
(763, 1, 1, 'mod/resource:addinstance', 1, 1451552913, 0),
(764, 1, 3, 'mod/scorm:addinstance', 1, 1451552913, 0),
(765, 1, 1, 'mod/scorm:addinstance', 1, 1451552913, 0),
(766, 1, 4, 'mod/scorm:viewreport', 1, 1451552913, 0),
(767, 1, 3, 'mod/scorm:viewreport', 1, 1451552913, 0),
(768, 1, 1, 'mod/scorm:viewreport', 1, 1451552913, 0),
(769, 1, 5, 'mod/scorm:skipview', 1, 1451552913, 0),
(770, 1, 5, 'mod/scorm:savetrack', 1, 1451552913, 0),
(771, 1, 4, 'mod/scorm:savetrack', 1, 1451552913, 0),
(772, 1, 3, 'mod/scorm:savetrack', 1, 1451552913, 0),
(773, 1, 1, 'mod/scorm:savetrack', 1, 1451552913, 0),
(774, 1, 5, 'mod/scorm:viewscores', 1, 1451552913, 0),
(775, 1, 4, 'mod/scorm:viewscores', 1, 1451552913, 0),
(776, 1, 3, 'mod/scorm:viewscores', 1, 1451552913, 0),
(777, 1, 1, 'mod/scorm:viewscores', 1, 1451552913, 0),
(778, 1, 4, 'mod/scorm:deleteresponses', 1, 1451552913, 0),
(779, 1, 3, 'mod/scorm:deleteresponses', 1, 1451552913, 0),
(780, 1, 1, 'mod/scorm:deleteresponses', 1, 1451552913, 0),
(781, 1, 3, 'mod/survey:addinstance', 1, 1451552914, 0),
(782, 1, 1, 'mod/survey:addinstance', 1, 1451552914, 0),
(783, 1, 5, 'mod/survey:participate', 1, 1451552914, 0),
(784, 1, 4, 'mod/survey:participate', 1, 1451552914, 0),
(785, 1, 3, 'mod/survey:participate', 1, 1451552914, 0),
(786, 1, 1, 'mod/survey:participate', 1, 1451552914, 0),
(787, 1, 4, 'mod/survey:readresponses', 1, 1451552914, 0),
(788, 1, 3, 'mod/survey:readresponses', 1, 1451552914, 0),
(789, 1, 1, 'mod/survey:readresponses', 1, 1451552914, 0),
(790, 1, 4, 'mod/survey:download', 1, 1451552914, 0),
(791, 1, 3, 'mod/survey:download', 1, 1451552914, 0),
(792, 1, 1, 'mod/survey:download', 1, 1451552914, 0),
(793, 1, 6, 'mod/url:view', 1, 1451552914, 0),
(794, 1, 7, 'mod/url:view', 1, 1451552914, 0),
(795, 1, 3, 'mod/url:addinstance', 1, 1451552914, 0),
(796, 1, 1, 'mod/url:addinstance', 1, 1451552914, 0),
(797, 1, 3, 'mod/wiki:addinstance', 1, 1451552914, 0),
(798, 1, 1, 'mod/wiki:addinstance', 1, 1451552914, 0),
(799, 1, 6, 'mod/wiki:viewpage', 1, 1451552914, 0),
(800, 1, 5, 'mod/wiki:viewpage', 1, 1451552914, 0),
(801, 1, 4, 'mod/wiki:viewpage', 1, 1451552914, 0),
(802, 1, 3, 'mod/wiki:viewpage', 1, 1451552914, 0),
(803, 1, 1, 'mod/wiki:viewpage', 1, 1451552914, 0),
(804, 1, 5, 'mod/wiki:editpage', 1, 1451552914, 0),
(805, 1, 4, 'mod/wiki:editpage', 1, 1451552914, 0),
(806, 1, 3, 'mod/wiki:editpage', 1, 1451552914, 0),
(807, 1, 1, 'mod/wiki:editpage', 1, 1451552914, 0),
(808, 1, 5, 'mod/wiki:createpage', 1, 1451552914, 0),
(809, 1, 4, 'mod/wiki:createpage', 1, 1451552914, 0),
(810, 1, 3, 'mod/wiki:createpage', 1, 1451552914, 0),
(811, 1, 1, 'mod/wiki:createpage', 1, 1451552914, 0),
(812, 1, 5, 'mod/wiki:viewcomment', 1, 1451552914, 0),
(813, 1, 4, 'mod/wiki:viewcomment', 1, 1451552914, 0),
(814, 1, 3, 'mod/wiki:viewcomment', 1, 1451552914, 0),
(815, 1, 1, 'mod/wiki:viewcomment', 1, 1451552914, 0),
(816, 1, 5, 'mod/wiki:editcomment', 1, 1451552914, 0),
(817, 1, 4, 'mod/wiki:editcomment', 1, 1451552914, 0),
(818, 1, 3, 'mod/wiki:editcomment', 1, 1451552914, 0),
(819, 1, 1, 'mod/wiki:editcomment', 1, 1451552914, 0),
(820, 1, 4, 'mod/wiki:managecomment', 1, 1451552914, 0),
(821, 1, 3, 'mod/wiki:managecomment', 1, 1451552914, 0),
(822, 1, 1, 'mod/wiki:managecomment', 1, 1451552914, 0),
(823, 1, 4, 'mod/wiki:managefiles', 1, 1451552914, 0),
(824, 1, 3, 'mod/wiki:managefiles', 1, 1451552914, 0),
(825, 1, 1, 'mod/wiki:managefiles', 1, 1451552914, 0),
(826, 1, 4, 'mod/wiki:overridelock', 1, 1451552914, 0),
(827, 1, 3, 'mod/wiki:overridelock', 1, 1451552914, 0),
(828, 1, 1, 'mod/wiki:overridelock', 1, 1451552914, 0),
(829, 1, 4, 'mod/wiki:managewiki', 1, 1451552914, 0),
(830, 1, 3, 'mod/wiki:managewiki', 1, 1451552914, 0),
(831, 1, 1, 'mod/wiki:managewiki', 1, 1451552914, 0),
(832, 1, 6, 'mod/workshop:view', 1, 1451552915, 0),
(833, 1, 5, 'mod/workshop:view', 1, 1451552915, 0),
(834, 1, 4, 'mod/workshop:view', 1, 1451552915, 0),
(835, 1, 3, 'mod/workshop:view', 1, 1451552915, 0),
(836, 1, 1, 'mod/workshop:view', 1, 1451552915, 0),
(837, 1, 3, 'mod/workshop:addinstance', 1, 1451552915, 0),
(838, 1, 1, 'mod/workshop:addinstance', 1, 1451552915, 0),
(839, 1, 4, 'mod/workshop:switchphase', 1, 1451552915, 0),
(840, 1, 3, 'mod/workshop:switchphase', 1, 1451552915, 0),
(841, 1, 1, 'mod/workshop:switchphase', 1, 1451552915, 0),
(842, 1, 3, 'mod/workshop:editdimensions', 1, 1451552915, 0),
(843, 1, 1, 'mod/workshop:editdimensions', 1, 1451552915, 0),
(844, 1, 5, 'mod/workshop:submit', 1, 1451552915, 0),
(845, 1, 5, 'mod/workshop:peerassess', 1, 1451552915, 0),
(846, 1, 4, 'mod/workshop:manageexamples', 1, 1451552915, 0),
(847, 1, 3, 'mod/workshop:manageexamples', 1, 1451552915, 0),
(848, 1, 1, 'mod/workshop:manageexamples', 1, 1451552915, 0),
(849, 1, 4, 'mod/workshop:allocate', 1, 1451552915, 0),
(850, 1, 3, 'mod/workshop:allocate', 1, 1451552915, 0),
(851, 1, 1, 'mod/workshop:allocate', 1, 1451552915, 0),
(852, 1, 4, 'mod/workshop:publishsubmissions', 1, 1451552915, 0),
(853, 1, 3, 'mod/workshop:publishsubmissions', 1, 1451552915, 0),
(854, 1, 1, 'mod/workshop:publishsubmissions', 1, 1451552915, 0),
(855, 1, 5, 'mod/workshop:viewauthornames', 1, 1451552915, 0),
(856, 1, 4, 'mod/workshop:viewauthornames', 1, 1451552915, 0),
(857, 1, 3, 'mod/workshop:viewauthornames', 1, 1451552915, 0),
(858, 1, 1, 'mod/workshop:viewauthornames', 1, 1451552915, 0),
(859, 1, 4, 'mod/workshop:viewreviewernames', 1, 1451552915, 0),
(860, 1, 3, 'mod/workshop:viewreviewernames', 1, 1451552915, 0),
(861, 1, 1, 'mod/workshop:viewreviewernames', 1, 1451552915, 0),
(862, 1, 4, 'mod/workshop:viewallsubmissions', 1, 1451552915, 0),
(863, 1, 3, 'mod/workshop:viewallsubmissions', 1, 1451552915, 0),
(864, 1, 1, 'mod/workshop:viewallsubmissions', 1, 1451552915, 0),
(865, 1, 5, 'mod/workshop:viewpublishedsubmissions', 1, 1451552915, 0),
(866, 1, 4, 'mod/workshop:viewpublishedsubmissions', 1, 1451552915, 0),
(867, 1, 3, 'mod/workshop:viewpublishedsubmissions', 1, 1451552915, 0),
(868, 1, 1, 'mod/workshop:viewpublishedsubmissions', 1, 1451552915, 0),
(869, 1, 5, 'mod/workshop:viewauthorpublished', 1, 1451552915, 0),
(870, 1, 4, 'mod/workshop:viewauthorpublished', 1, 1451552915, 0),
(871, 1, 3, 'mod/workshop:viewauthorpublished', 1, 1451552915, 0),
(872, 1, 1, 'mod/workshop:viewauthorpublished', 1, 1451552915, 0),
(873, 1, 4, 'mod/workshop:viewallassessments', 1, 1451552915, 0),
(874, 1, 3, 'mod/workshop:viewallassessments', 1, 1451552915, 0),
(875, 1, 1, 'mod/workshop:viewallassessments', 1, 1451552915, 0),
(876, 1, 4, 'mod/workshop:overridegrades', 1, 1451552915, 0),
(877, 1, 3, 'mod/workshop:overridegrades', 1, 1451552915, 0),
(878, 1, 1, 'mod/workshop:overridegrades', 1, 1451552915, 0),
(879, 1, 4, 'mod/workshop:ignoredeadlines', 1, 1451552915, 0),
(880, 1, 3, 'mod/workshop:ignoredeadlines', 1, 1451552915, 0),
(881, 1, 1, 'mod/workshop:ignoredeadlines', 1, 1451552915, 0),
(882, 1, 1, 'enrol/category:config', 1, 1451552917, 0),
(883, 1, 3, 'enrol/category:config', 1, 1451552917, 0),
(884, 1, 3, 'enrol/cohort:config', 1, 1451552917, 0),
(885, 1, 1, 'enrol/cohort:config', 1, 1451552917, 0),
(886, 1, 1, 'enrol/cohort:unenrol', 1, 1451552917, 0),
(887, 1, 1, 'enrol/database:unenrol', 1, 1451552917, 0),
(888, 1, 1, 'enrol/database:config', 1, 1451552917, 0),
(889, 1, 3, 'enrol/database:config', 1, 1451552917, 0),
(890, 1, 1, 'enrol/guest:config', 1, 1451552917, 0),
(891, 1, 3, 'enrol/guest:config', 1, 1451552917, 0),
(892, 1, 1, 'enrol/imsenterprise:config', 1, 1451552917, 0),
(893, 1, 3, 'enrol/imsenterprise:config', 1, 1451552917, 0),
(894, 1, 1, 'enrol/ldap:manage', 1, 1451552918, 0);
INSERT INTO `mdl_role_capabilities` (`id`, `contextid`, `roleid`, `capability`, `permission`, `timemodified`, `modifierid`) VALUES
(895, 1, 1, 'enrol/manual:config', 1, 1451552918, 0),
(896, 1, 1, 'enrol/manual:enrol', 1, 1451552918, 0),
(897, 1, 3, 'enrol/manual:enrol', 1, 1451552918, 0),
(898, 1, 1, 'enrol/manual:manage', 1, 1451552918, 0),
(899, 1, 3, 'enrol/manual:manage', 1, 1451552918, 0),
(900, 1, 1, 'enrol/manual:unenrol', 1, 1451552918, 0),
(901, 1, 3, 'enrol/manual:unenrol', 1, 1451552918, 0),
(902, 1, 1, 'enrol/meta:config', 1, 1451552918, 0),
(903, 1, 3, 'enrol/meta:config', 1, 1451552918, 0),
(904, 1, 1, 'enrol/meta:selectaslinked', 1, 1451552918, 0),
(905, 1, 1, 'enrol/meta:unenrol', 1, 1451552918, 0),
(906, 1, 1, 'enrol/mnet:config', 1, 1451552918, 0),
(907, 1, 3, 'enrol/mnet:config', 1, 1451552918, 0),
(908, 1, 1, 'enrol/paypal:config', 1, 1451552918, 0),
(909, 1, 1, 'enrol/paypal:manage', 1, 1451552918, 0),
(910, 1, 3, 'enrol/paypal:manage', 1, 1451552918, 0),
(911, 1, 1, 'enrol/paypal:unenrol', 1, 1451552918, 0),
(912, 1, 3, 'enrol/self:config', 1, 1451552919, 0),
(913, 1, 1, 'enrol/self:config', 1, 1451552919, 0),
(914, 1, 3, 'enrol/self:manage', 1, 1451552919, 0),
(915, 1, 1, 'enrol/self:manage', 1, 1451552919, 0),
(916, 1, 1, 'enrol/self:holdkey', -1000, 1451552919, 0),
(917, 1, 5, 'enrol/self:unenrolself', 1, 1451552919, 0),
(918, 1, 3, 'enrol/self:unenrol', 1, 1451552919, 0),
(919, 1, 1, 'enrol/self:unenrol', 1, 1451552919, 0),
(920, 1, 7, 'message/airnotifier:managedevice', 1, 1451552919, 0),
(921, 1, 3, 'block/activity_modules:addinstance', 1, 1451552920, 0),
(922, 1, 1, 'block/activity_modules:addinstance', 1, 1451552920, 0),
(923, 1, 3, 'block/activity_results:addinstance', 1, 1451552920, 0),
(924, 1, 1, 'block/activity_results:addinstance', 1, 1451552920, 0),
(925, 1, 7, 'block/admin_bookmarks:myaddinstance', 1, 1451552920, 0),
(926, 1, 3, 'block/admin_bookmarks:addinstance', 1, 1451552920, 0),
(927, 1, 1, 'block/admin_bookmarks:addinstance', 1, 1451552920, 0),
(928, 1, 3, 'block/badges:addinstance', 1, 1451552920, 0),
(929, 1, 1, 'block/badges:addinstance', 1, 1451552920, 0),
(930, 1, 7, 'block/badges:myaddinstance', 1, 1451552920, 0),
(931, 1, 3, 'block/blog_menu:addinstance', 1, 1451552920, 0),
(932, 1, 1, 'block/blog_menu:addinstance', 1, 1451552920, 0),
(933, 1, 3, 'block/blog_recent:addinstance', 1, 1451552920, 0),
(934, 1, 1, 'block/blog_recent:addinstance', 1, 1451552920, 0),
(935, 1, 3, 'block/blog_tags:addinstance', 1, 1451552920, 0),
(936, 1, 1, 'block/blog_tags:addinstance', 1, 1451552920, 0),
(937, 1, 7, 'block/calendar_month:myaddinstance', 1, 1451552920, 0),
(938, 1, 3, 'block/calendar_month:addinstance', 1, 1451552920, 0),
(939, 1, 1, 'block/calendar_month:addinstance', 1, 1451552920, 0),
(940, 1, 7, 'block/calendar_upcoming:myaddinstance', 1, 1451552920, 0),
(941, 1, 3, 'block/calendar_upcoming:addinstance', 1, 1451552920, 0),
(942, 1, 1, 'block/calendar_upcoming:addinstance', 1, 1451552921, 0),
(943, 1, 7, 'block/comments:myaddinstance', 1, 1451552921, 0),
(944, 1, 3, 'block/comments:addinstance', 1, 1451552921, 0),
(945, 1, 1, 'block/comments:addinstance', 1, 1451552921, 0),
(946, 1, 7, 'block/community:myaddinstance', 1, 1451552921, 0),
(947, 1, 3, 'block/community:addinstance', 1, 1451552921, 0),
(948, 1, 1, 'block/community:addinstance', 1, 1451552921, 0),
(949, 1, 3, 'block/completionstatus:addinstance', 1, 1451552921, 0),
(950, 1, 1, 'block/completionstatus:addinstance', 1, 1451552921, 0),
(951, 1, 7, 'block/course_list:myaddinstance', 1, 1451552921, 0),
(952, 1, 3, 'block/course_list:addinstance', 1, 1451552921, 0),
(953, 1, 1, 'block/course_list:addinstance', 1, 1451552921, 0),
(954, 1, 7, 'block/course_overview:myaddinstance', 1, 1451552921, 0),
(955, 1, 3, 'block/course_overview:addinstance', 1, 1451552921, 0),
(956, 1, 1, 'block/course_overview:addinstance', 1, 1451552921, 0),
(957, 1, 3, 'block/course_summary:addinstance', 1, 1451552921, 0),
(958, 1, 1, 'block/course_summary:addinstance', 1, 1451552921, 0),
(959, 1, 3, 'block/feedback:addinstance', 1, 1451552921, 0),
(960, 1, 1, 'block/feedback:addinstance', 1, 1451552921, 0),
(961, 1, 7, 'block/glossary_random:myaddinstance', 1, 1451552921, 0),
(962, 1, 3, 'block/glossary_random:addinstance', 1, 1451552921, 0),
(963, 1, 1, 'block/glossary_random:addinstance', 1, 1451552921, 0),
(964, 1, 7, 'block/html:myaddinstance', 1, 1451552921, 0),
(965, 1, 3, 'block/html:addinstance', 1, 1451552922, 0),
(966, 1, 1, 'block/html:addinstance', 1, 1451552922, 0),
(967, 1, 3, 'block/login:addinstance', 1, 1451552922, 0),
(968, 1, 1, 'block/login:addinstance', 1, 1451552922, 0),
(969, 1, 7, 'block/mentees:myaddinstance', 1, 1451552922, 0),
(970, 1, 3, 'block/mentees:addinstance', 1, 1451552922, 0),
(971, 1, 1, 'block/mentees:addinstance', 1, 1451552922, 0),
(972, 1, 7, 'block/messages:myaddinstance', 1, 1451552922, 0),
(973, 1, 3, 'block/messages:addinstance', 1, 1451552922, 0),
(974, 1, 1, 'block/messages:addinstance', 1, 1451552922, 0),
(975, 1, 7, 'block/mnet_hosts:myaddinstance', 1, 1451552922, 0),
(976, 1, 3, 'block/mnet_hosts:addinstance', 1, 1451552922, 0),
(977, 1, 1, 'block/mnet_hosts:addinstance', 1, 1451552922, 0),
(978, 1, 7, 'block/myprofile:myaddinstance', 1, 1451552922, 0),
(979, 1, 3, 'block/myprofile:addinstance', 1, 1451552922, 0),
(980, 1, 1, 'block/myprofile:addinstance', 1, 1451552922, 0),
(981, 1, 7, 'block/navigation:myaddinstance', 1, 1451552922, 0),
(982, 1, 3, 'block/navigation:addinstance', 1, 1451552922, 0),
(983, 1, 1, 'block/navigation:addinstance', 1, 1451552922, 0),
(984, 1, 7, 'block/news_items:myaddinstance', 1, 1451552923, 0),
(985, 1, 3, 'block/news_items:addinstance', 1, 1451552923, 0),
(986, 1, 1, 'block/news_items:addinstance', 1, 1451552923, 0),
(987, 1, 7, 'block/online_users:myaddinstance', 1, 1451552923, 0),
(988, 1, 3, 'block/online_users:addinstance', 1, 1451552923, 0),
(989, 1, 1, 'block/online_users:addinstance', 1, 1451552923, 0),
(990, 1, 7, 'block/online_users:viewlist', 1, 1451552923, 0),
(991, 1, 6, 'block/online_users:viewlist', 1, 1451552923, 0),
(992, 1, 5, 'block/online_users:viewlist', 1, 1451552923, 0),
(993, 1, 4, 'block/online_users:viewlist', 1, 1451552923, 0),
(994, 1, 3, 'block/online_users:viewlist', 1, 1451552923, 0),
(995, 1, 1, 'block/online_users:viewlist', 1, 1451552923, 0),
(996, 1, 3, 'block/participants:addinstance', 1, 1451552923, 0),
(997, 1, 1, 'block/participants:addinstance', 1, 1451552923, 0),
(998, 1, 7, 'block/private_files:myaddinstance', 1, 1451552923, 0),
(999, 1, 3, 'block/private_files:addinstance', 1, 1451552923, 0),
(1000, 1, 1, 'block/private_files:addinstance', 1, 1451552923, 0),
(1001, 1, 3, 'block/quiz_results:addinstance', 1, 1451552923, 0),
(1002, 1, 1, 'block/quiz_results:addinstance', 1, 1451552923, 0),
(1003, 1, 3, 'block/recent_activity:addinstance', 1, 1451552923, 0),
(1004, 1, 1, 'block/recent_activity:addinstance', 1, 1451552923, 0),
(1005, 1, 7, 'block/recent_activity:viewaddupdatemodule', 1, 1451552923, 0),
(1006, 1, 7, 'block/recent_activity:viewdeletemodule', 1, 1451552923, 0),
(1007, 1, 7, 'block/rss_client:myaddinstance', 1, 1451552923, 0),
(1008, 1, 3, 'block/rss_client:addinstance', 1, 1451552923, 0),
(1009, 1, 1, 'block/rss_client:addinstance', 1, 1451552923, 0),
(1010, 1, 4, 'block/rss_client:manageownfeeds', 1, 1451552923, 0),
(1011, 1, 3, 'block/rss_client:manageownfeeds', 1, 1451552923, 0),
(1012, 1, 1, 'block/rss_client:manageownfeeds', 1, 1451552923, 0),
(1013, 1, 1, 'block/rss_client:manageanyfeeds', 1, 1451552923, 0),
(1014, 1, 3, 'block/search_forums:addinstance', 1, 1451552924, 0),
(1015, 1, 1, 'block/search_forums:addinstance', 1, 1451552924, 0),
(1016, 1, 3, 'block/section_links:addinstance', 1, 1451552924, 0),
(1017, 1, 1, 'block/section_links:addinstance', 1, 1451552924, 0),
(1018, 1, 3, 'block/selfcompletion:addinstance', 1, 1451552924, 0),
(1019, 1, 1, 'block/selfcompletion:addinstance', 1, 1451552924, 0),
(1020, 1, 7, 'block/settings:myaddinstance', 1, 1451552924, 0),
(1021, 1, 3, 'block/settings:addinstance', 1, 1451552924, 0),
(1022, 1, 1, 'block/settings:addinstance', 1, 1451552924, 0),
(1023, 1, 3, 'block/site_main_menu:addinstance', 1, 1451552924, 0),
(1024, 1, 1, 'block/site_main_menu:addinstance', 1, 1451552924, 0),
(1025, 1, 3, 'block/social_activities:addinstance', 1, 1451552924, 0),
(1026, 1, 1, 'block/social_activities:addinstance', 1, 1451552924, 0),
(1027, 1, 3, 'block/tag_flickr:addinstance', 1, 1451552924, 0),
(1028, 1, 1, 'block/tag_flickr:addinstance', 1, 1451552924, 0),
(1029, 1, 3, 'block/tag_youtube:addinstance', 1, 1451552924, 0),
(1030, 1, 1, 'block/tag_youtube:addinstance', 1, 1451552924, 0),
(1031, 1, 7, 'block/tags:myaddinstance', 1, 1451552924, 0),
(1032, 1, 3, 'block/tags:addinstance', 1, 1451552924, 0),
(1033, 1, 1, 'block/tags:addinstance', 1, 1451552924, 0),
(1034, 1, 4, 'report/completion:view', 1, 1451552927, 0),
(1035, 1, 3, 'report/completion:view', 1, 1451552927, 0),
(1036, 1, 1, 'report/completion:view', 1, 1451552927, 0),
(1037, 1, 4, 'report/courseoverview:view', 1, 1451552927, 0),
(1038, 1, 3, 'report/courseoverview:view', 1, 1451552927, 0),
(1039, 1, 1, 'report/courseoverview:view', 1, 1451552927, 0),
(1040, 1, 4, 'report/log:view', 1, 1451552927, 0),
(1041, 1, 3, 'report/log:view', 1, 1451552927, 0),
(1042, 1, 1, 'report/log:view', 1, 1451552927, 0),
(1043, 1, 4, 'report/log:viewtoday', 1, 1451552927, 0),
(1044, 1, 3, 'report/log:viewtoday', 1, 1451552927, 0),
(1045, 1, 1, 'report/log:viewtoday', 1, 1451552927, 0),
(1046, 1, 4, 'report/loglive:view', 1, 1451552927, 0),
(1047, 1, 3, 'report/loglive:view', 1, 1451552927, 0),
(1048, 1, 1, 'report/loglive:view', 1, 1451552927, 0),
(1049, 1, 4, 'report/outline:view', 1, 1451552927, 0),
(1050, 1, 3, 'report/outline:view', 1, 1451552927, 0),
(1051, 1, 1, 'report/outline:view', 1, 1451552927, 0),
(1052, 1, 4, 'report/participation:view', 1, 1451552927, 0),
(1053, 1, 3, 'report/participation:view', 1, 1451552927, 0),
(1054, 1, 1, 'report/participation:view', 1, 1451552927, 0),
(1055, 1, 1, 'report/performance:view', 1, 1451552927, 0),
(1056, 1, 4, 'report/progress:view', 1, 1451552927, 0),
(1057, 1, 3, 'report/progress:view', 1, 1451552927, 0),
(1058, 1, 1, 'report/progress:view', 1, 1451552927, 0),
(1059, 1, 1, 'report/security:view', 1, 1451552928, 0),
(1060, 1, 4, 'report/stats:view', 1, 1451552928, 0),
(1061, 1, 3, 'report/stats:view', 1, 1451552928, 0),
(1062, 1, 1, 'report/stats:view', 1, 1451552928, 0),
(1063, 1, 6, 'report/usersessions:manageownsessions', -1000, 1451552928, 0),
(1064, 1, 7, 'report/usersessions:manageownsessions', 1, 1451552928, 0),
(1065, 1, 1, 'report/usersessions:manageownsessions', 1, 1451552928, 0),
(1066, 1, 4, 'gradeexport/ods:view', 1, 1451552928, 0),
(1067, 1, 3, 'gradeexport/ods:view', 1, 1451552928, 0),
(1068, 1, 1, 'gradeexport/ods:view', 1, 1451552928, 0),
(1069, 1, 1, 'gradeexport/ods:publish', 1, 1451552928, 0),
(1070, 1, 4, 'gradeexport/txt:view', 1, 1451552928, 0),
(1071, 1, 3, 'gradeexport/txt:view', 1, 1451552928, 0),
(1072, 1, 1, 'gradeexport/txt:view', 1, 1451552928, 0),
(1073, 1, 1, 'gradeexport/txt:publish', 1, 1451552928, 0),
(1074, 1, 4, 'gradeexport/xls:view', 1, 1451552928, 0),
(1075, 1, 3, 'gradeexport/xls:view', 1, 1451552928, 0),
(1076, 1, 1, 'gradeexport/xls:view', 1, 1451552928, 0),
(1077, 1, 1, 'gradeexport/xls:publish', 1, 1451552928, 0),
(1078, 1, 4, 'gradeexport/xml:view', 1, 1451552928, 0),
(1079, 1, 3, 'gradeexport/xml:view', 1, 1451552928, 0),
(1080, 1, 1, 'gradeexport/xml:view', 1, 1451552928, 0),
(1081, 1, 1, 'gradeexport/xml:publish', 1, 1451552928, 0),
(1082, 1, 3, 'gradeimport/csv:view', 1, 1451552928, 0),
(1083, 1, 1, 'gradeimport/csv:view', 1, 1451552928, 0),
(1084, 1, 3, 'gradeimport/direct:view', 1, 1451552928, 0),
(1085, 1, 1, 'gradeimport/direct:view', 1, 1451552928, 0),
(1086, 1, 3, 'gradeimport/xml:view', 1, 1451552928, 0),
(1087, 1, 1, 'gradeimport/xml:view', 1, 1451552928, 0),
(1088, 1, 1, 'gradeimport/xml:publish', 1, 1451552928, 0),
(1089, 1, 4, 'gradereport/grader:view', 1, 1451552928, 0),
(1090, 1, 3, 'gradereport/grader:view', 1, 1451552928, 0),
(1091, 1, 1, 'gradereport/grader:view', 1, 1451552928, 0),
(1092, 1, 4, 'gradereport/history:view', 1, 1451552929, 0),
(1093, 1, 3, 'gradereport/history:view', 1, 1451552929, 0),
(1094, 1, 1, 'gradereport/history:view', 1, 1451552929, 0),
(1095, 1, 4, 'gradereport/outcomes:view', 1, 1451552929, 0),
(1096, 1, 3, 'gradereport/outcomes:view', 1, 1451552929, 0),
(1097, 1, 1, 'gradereport/outcomes:view', 1, 1451552929, 0),
(1098, 1, 5, 'gradereport/overview:view', 1, 1451552929, 0),
(1099, 1, 1, 'gradereport/overview:view', 1, 1451552929, 0),
(1100, 1, 3, 'gradereport/singleview:view', 1, 1451552929, 0),
(1101, 1, 1, 'gradereport/singleview:view', 1, 1451552929, 0),
(1102, 1, 5, 'gradereport/user:view', 1, 1451552929, 0),
(1103, 1, 4, 'gradereport/user:view', 1, 1451552929, 0),
(1104, 1, 3, 'gradereport/user:view', 1, 1451552929, 0),
(1105, 1, 1, 'gradereport/user:view', 1, 1451552929, 0),
(1106, 1, 7, 'repository/alfresco:view', 1, 1451552930, 0),
(1107, 1, 7, 'repository/areafiles:view', 1, 1451552930, 0),
(1108, 1, 7, 'repository/boxnet:view', 1, 1451552930, 0),
(1109, 1, 2, 'repository/coursefiles:view', 1, 1451552930, 0),
(1110, 1, 4, 'repository/coursefiles:view', 1, 1451552930, 0),
(1111, 1, 3, 'repository/coursefiles:view', 1, 1451552930, 0),
(1112, 1, 1, 'repository/coursefiles:view', 1, 1451552930, 0),
(1113, 1, 7, 'repository/dropbox:view', 1, 1451552930, 0),
(1114, 1, 7, 'repository/equella:view', 1, 1451552930, 0),
(1115, 1, 2, 'repository/filesystem:view', 1, 1451552930, 0),
(1116, 1, 4, 'repository/filesystem:view', 1, 1451552930, 0),
(1117, 1, 3, 'repository/filesystem:view', 1, 1451552930, 0),
(1118, 1, 1, 'repository/filesystem:view', 1, 1451552930, 0),
(1119, 1, 7, 'repository/flickr:view', 1, 1451552930, 0),
(1120, 1, 7, 'repository/flickr_public:view', 1, 1451552930, 0),
(1121, 1, 7, 'repository/googledocs:view', 1, 1451552930, 0),
(1122, 1, 2, 'repository/local:view', 1, 1451552931, 0),
(1123, 1, 4, 'repository/local:view', 1, 1451552931, 0),
(1124, 1, 3, 'repository/local:view', 1, 1451552931, 0),
(1125, 1, 1, 'repository/local:view', 1, 1451552931, 0),
(1126, 1, 7, 'repository/merlot:view', 1, 1451552931, 0),
(1127, 1, 7, 'repository/picasa:view', 1, 1451552931, 0),
(1128, 1, 7, 'repository/recent:view', 1, 1451552931, 0),
(1129, 1, 7, 'repository/s3:view', 1, 1451552931, 0),
(1130, 1, 7, 'repository/skydrive:view', 1, 1451552931, 0),
(1131, 1, 7, 'repository/upload:view', 1, 1451552931, 0),
(1132, 1, 7, 'repository/url:view', 1, 1451552931, 0),
(1133, 1, 7, 'repository/user:view', 1, 1451552931, 0),
(1134, 1, 2, 'repository/webdav:view', 1, 1451552931, 0),
(1135, 1, 4, 'repository/webdav:view', 1, 1451552931, 0),
(1136, 1, 3, 'repository/webdav:view', 1, 1451552931, 0),
(1137, 1, 1, 'repository/webdav:view', 1, 1451552931, 0),
(1138, 1, 7, 'repository/wikimedia:view', 1, 1451552932, 0),
(1139, 1, 7, 'repository/youtube:view', 1, 1451552932, 0),
(1140, 1, 1, 'tool/customlang:view', 1, 1451552934, 0),
(1141, 1, 1, 'tool/customlang:edit', 1, 1451552934, 0),
(1142, 1, 4, 'tool/monitor:subscribe', 1, 1451552935, 0),
(1143, 1, 3, 'tool/monitor:subscribe', 1, 1451552935, 0),
(1144, 1, 1, 'tool/monitor:subscribe', 1, 1451552935, 0),
(1145, 1, 4, 'tool/monitor:managerules', 1, 1451552935, 0),
(1146, 1, 3, 'tool/monitor:managerules', 1, 1451552935, 0),
(1147, 1, 1, 'tool/monitor:managerules', 1, 1451552935, 0),
(1148, 1, 1, 'tool/monitor:managetool', 1, 1451552935, 0),
(1149, 1, 1, 'tool/uploaduser:uploaduserpictures', 1, 1451552936, 0),
(1150, 1, 3, 'booktool/importhtml:import', 1, 1451552938, 0),
(1151, 1, 1, 'booktool/importhtml:import', 1, 1451552938, 0),
(1152, 1, 6, 'booktool/print:print', 1, 1451552939, 0),
(1153, 1, 8, 'booktool/print:print', 1, 1451552939, 0),
(1154, 1, 5, 'booktool/print:print', 1, 1451552939, 0),
(1155, 1, 4, 'booktool/print:print', 1, 1451552939, 0),
(1156, 1, 3, 'booktool/print:print', 1, 1451552939, 0),
(1157, 1, 1, 'booktool/print:print', 1, 1451552939, 0),
(1158, 1, 4, 'quiz/grading:viewstudentnames', 1, 1451552940, 0),
(1159, 1, 3, 'quiz/grading:viewstudentnames', 1, 1451552940, 0),
(1160, 1, 1, 'quiz/grading:viewstudentnames', 1, 1451552940, 0),
(1161, 1, 4, 'quiz/grading:viewidnumber', 1, 1451552940, 0),
(1162, 1, 3, 'quiz/grading:viewidnumber', 1, 1451552940, 0),
(1163, 1, 1, 'quiz/grading:viewidnumber', 1, 1451552940, 0),
(1164, 1, 4, 'quiz/statistics:view', 1, 1451552940, 0),
(1165, 1, 3, 'quiz/statistics:view', 1, 1451552940, 0),
(1166, 1, 1, 'quiz/statistics:view', 1, 1451552940, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_context_levels`
--

CREATE TABLE IF NOT EXISTS `mdl_role_context_levels` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `roleid` bigint(10) NOT NULL,
  `contextlevel` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolecontleve_conrol_uix` (`contextlevel`,`roleid`),
  KEY `mdl_rolecontleve_rol_ix` (`roleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lists which roles can be assigned at which context levels. T' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `mdl_role_context_levels`
--

INSERT INTO `mdl_role_context_levels` (`id`, `roleid`, `contextlevel`) VALUES
(1, 1, 10),
(4, 2, 10),
(2, 1, 40),
(5, 2, 40),
(3, 1, 50),
(6, 3, 50),
(8, 4, 50),
(10, 5, 50),
(7, 3, 70),
(9, 4, 70),
(11, 5, 70);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_names`
--

CREATE TABLE IF NOT EXISTS `mdl_role_names` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `contextid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolename_rolcon_uix` (`roleid`,`contextid`),
  KEY `mdl_rolename_rol_ix` (`roleid`),
  KEY `mdl_rolename_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='role names in native strings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_role_sortorder`
--

CREATE TABLE IF NOT EXISTS `mdl_role_sortorder` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `roleid` bigint(10) NOT NULL,
  `contextid` bigint(10) NOT NULL,
  `sortoder` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rolesort_userolcon_uix` (`userid`,`roleid`,`contextid`),
  KEY `mdl_rolesort_use_ix` (`userid`),
  KEY `mdl_rolesort_rol_ix` (`roleid`),
  KEY `mdl_rolesort_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='sort order of course managers in a course' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scale`
--

CREATE TABLE IF NOT EXISTS `mdl_scale` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scale` longtext NOT NULL,
  `description` longtext NOT NULL,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_scal_cou_ix` (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Defines grading scales' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scale_history`
--

CREATE TABLE IF NOT EXISTS `mdl_scale_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `action` bigint(10) NOT NULL DEFAULT '0',
  `oldid` bigint(10) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `timemodified` bigint(10) DEFAULT NULL,
  `loggeduser` bigint(10) DEFAULT NULL,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scale` longtext NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_scalhist_act_ix` (`action`),
  KEY `mdl_scalhist_old_ix` (`oldid`),
  KEY `mdl_scalhist_cou_ix` (`courseid`),
  KEY `mdl_scalhist_log_ix` (`loggeduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='History table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scormtype` varchar(50) NOT NULL DEFAULT 'local',
  `reference` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `version` varchar(9) NOT NULL DEFAULT '',
  `maxgrade` double NOT NULL DEFAULT '0',
  `grademethod` tinyint(2) NOT NULL DEFAULT '0',
  `whatgrade` bigint(10) NOT NULL DEFAULT '0',
  `maxattempt` bigint(10) NOT NULL DEFAULT '1',
  `forcecompleted` tinyint(1) NOT NULL DEFAULT '0',
  `forcenewattempt` tinyint(1) NOT NULL DEFAULT '0',
  `lastattemptlock` tinyint(1) NOT NULL DEFAULT '0',
  `displayattemptstatus` tinyint(1) NOT NULL DEFAULT '1',
  `displaycoursestructure` tinyint(1) NOT NULL DEFAULT '0',
  `updatefreq` tinyint(1) NOT NULL DEFAULT '0',
  `sha1hash` varchar(40) DEFAULT NULL,
  `md5hash` varchar(32) NOT NULL DEFAULT '',
  `revision` bigint(10) NOT NULL DEFAULT '0',
  `launch` bigint(10) NOT NULL DEFAULT '0',
  `skipview` tinyint(1) NOT NULL DEFAULT '1',
  `hidebrowse` tinyint(1) NOT NULL DEFAULT '0',
  `hidetoc` tinyint(1) NOT NULL DEFAULT '0',
  `nav` tinyint(1) NOT NULL DEFAULT '1',
  `navpositionleft` bigint(10) DEFAULT '-100',
  `navpositiontop` bigint(10) DEFAULT '-100',
  `auto` tinyint(1) NOT NULL DEFAULT '0',
  `popup` tinyint(1) NOT NULL DEFAULT '0',
  `options` varchar(255) NOT NULL DEFAULT '',
  `width` bigint(10) NOT NULL DEFAULT '100',
  `height` bigint(10) NOT NULL DEFAULT '600',
  `timeopen` bigint(10) NOT NULL DEFAULT '0',
  `timeclose` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `completionstatusrequired` tinyint(1) DEFAULT NULL,
  `completionscorerequired` tinyint(2) DEFAULT NULL,
  `displayactivityname` smallint(4) NOT NULL DEFAULT '1',
  `autocommit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_scor_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='each table is one SCORM module and its configuration' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_aicc_session`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_aicc_session` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `scormid` bigint(10) NOT NULL DEFAULT '0',
  `hacpsession` varchar(255) NOT NULL DEFAULT '',
  `scoid` bigint(10) DEFAULT '0',
  `scormmode` varchar(50) DEFAULT NULL,
  `scormstatus` varchar(255) DEFAULT NULL,
  `attempt` bigint(10) DEFAULT NULL,
  `lessonstatus` varchar(255) DEFAULT NULL,
  `sessiontime` varchar(255) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_scoraiccsess_sco_ix` (`scormid`),
  KEY `mdl_scoraiccsess_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used by AICC HACP to store session information' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_scoes`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_scoes` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scorm` bigint(10) NOT NULL DEFAULT '0',
  `manifest` varchar(255) NOT NULL DEFAULT '',
  `organization` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '',
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `launch` longtext NOT NULL,
  `scormtype` varchar(5) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_scorscoe_sco_ix` (`scorm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='each SCO part of the SCORM module' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_scoes_data`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_scoes_data` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_scorscoedata_sco_ix` (`scoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains variable data get from packages' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_scoes_track`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_scoes_track` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `scormid` bigint(10) NOT NULL DEFAULT '0',
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `attempt` bigint(10) NOT NULL DEFAULT '1',
  `element` varchar(255) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorscoetrac_usescosco_uix` (`userid`,`scormid`,`scoid`,`attempt`,`element`),
  KEY `mdl_scorscoetrac_use_ix` (`userid`),
  KEY `mdl_scorscoetrac_ele_ix` (`element`),
  KEY `mdl_scorscoetrac_sco_ix` (`scormid`),
  KEY `mdl_scorscoetrac_sco2_ix` (`scoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='to track SCOes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_seq_mapinfo`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_seq_mapinfo` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `objectiveid` bigint(10) NOT NULL DEFAULT '0',
  `targetobjectiveid` bigint(10) NOT NULL DEFAULT '0',
  `readsatisfiedstatus` tinyint(1) NOT NULL DEFAULT '1',
  `readnormalizedmeasure` tinyint(1) NOT NULL DEFAULT '1',
  `writesatisfiedstatus` tinyint(1) NOT NULL DEFAULT '0',
  `writenormalizedmeasure` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorseqmapi_scoidobj_uix` (`scoid`,`id`,`objectiveid`),
  KEY `mdl_scorseqmapi_sco_ix` (`scoid`),
  KEY `mdl_scorseqmapi_obj_ix` (`objectiveid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SCORM2004 objective mapinfo description' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_seq_objective`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_seq_objective` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `primaryobj` tinyint(1) NOT NULL DEFAULT '0',
  `objectiveid` varchar(255) NOT NULL DEFAULT '',
  `satisfiedbymeasure` tinyint(1) NOT NULL DEFAULT '1',
  `minnormalizedmeasure` float(11,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorseqobje_scoid_uix` (`scoid`,`id`),
  KEY `mdl_scorseqobje_sco_ix` (`scoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SCORM2004 objective description' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_seq_rolluprule`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_seq_rolluprule` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `childactivityset` varchar(15) NOT NULL DEFAULT '',
  `minimumcount` bigint(10) NOT NULL DEFAULT '0',
  `minimumpercent` float(11,4) NOT NULL DEFAULT '0.0000',
  `conditioncombination` varchar(3) NOT NULL DEFAULT 'all',
  `action` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorseqroll_scoid_uix` (`scoid`,`id`),
  KEY `mdl_scorseqroll_sco_ix` (`scoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SCORM2004 sequencing rule' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_seq_rolluprulecond`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_seq_rolluprulecond` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `rollupruleid` bigint(10) NOT NULL DEFAULT '0',
  `operator` varchar(5) NOT NULL DEFAULT 'noOp',
  `cond` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorseqroll_scorolid_uix` (`scoid`,`rollupruleid`,`id`),
  KEY `mdl_scorseqroll_sco2_ix` (`scoid`),
  KEY `mdl_scorseqroll_rol_ix` (`rollupruleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SCORM2004 sequencing rule' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_seq_rulecond`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_seq_rulecond` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `ruleconditionsid` bigint(10) NOT NULL DEFAULT '0',
  `refrencedobjective` varchar(255) NOT NULL DEFAULT '',
  `measurethreshold` float(11,4) NOT NULL DEFAULT '0.0000',
  `operator` varchar(5) NOT NULL DEFAULT 'noOp',
  `cond` varchar(30) NOT NULL DEFAULT 'always',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorseqrule_idscorul_uix` (`id`,`scoid`,`ruleconditionsid`),
  KEY `mdl_scorseqrule_sco2_ix` (`scoid`),
  KEY `mdl_scorseqrule_rul_ix` (`ruleconditionsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SCORM2004 rule condition' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_scorm_seq_ruleconds`
--

CREATE TABLE IF NOT EXISTS `mdl_scorm_seq_ruleconds` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `scoid` bigint(10) NOT NULL DEFAULT '0',
  `conditioncombination` varchar(3) NOT NULL DEFAULT 'all',
  `ruletype` tinyint(2) NOT NULL DEFAULT '0',
  `action` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_scorseqrule_scoid_uix` (`scoid`,`id`),
  KEY `mdl_scorseqrule_sco_ix` (`scoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SCORM2004 rule conditions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_sessions`
--

CREATE TABLE IF NOT EXISTS `mdl_sessions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `state` bigint(10) NOT NULL DEFAULT '0',
  `sid` varchar(128) NOT NULL DEFAULT '',
  `userid` bigint(10) NOT NULL,
  `sessdata` longtext,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `firstip` varchar(45) DEFAULT NULL,
  `lastip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_sess_sid_uix` (`sid`),
  KEY `mdl_sess_sta_ix` (`state`),
  KEY `mdl_sess_tim_ix` (`timecreated`),
  KEY `mdl_sess_tim2_ix` (`timemodified`),
  KEY `mdl_sess_use_ix` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Database based session storage - now recommended' AUTO_INCREMENT=319 ;

--
-- Dumping data for table `mdl_sessions`
--

INSERT INTO `mdl_sessions` (`id`, `state`, `sid`, `userid`, `sessdata`, `timecreated`, `timemodified`, `firstip`, `lastip`) VALUES
(308, 0, 'd8280b6bbf8767fb7235a213a67f9746', 0, NULL, 1455292199, 1455295621, '109.87.201.130', '109.87.201.130'),
(312, 0, '52b7625a86aa3ae9e069012ea0bb5246', 0, NULL, 1455293546, 1455293546, '8.26.21.19', '8.26.21.19'),
(313, 0, '215fa4916297cda2ebf65cf927d5ebf4', 0, NULL, 1455293705, 1455293705, '8.26.21.19', '8.26.21.19'),
(314, 0, 'f10b10f9c2f2b1b6ba100cb0ed3c4a7d', 0, NULL, 1455293987, 1455293987, '8.26.21.19', '8.26.21.19'),
(315, 0, 'b34d9c0c53e13654e46fddbe45443932', 0, NULL, 1455295116, 1455295116, '109.87.201.130', '109.87.201.130'),
(316, 0, '1f0110a0af2a33b6f05a2371dda2a76b', 0, NULL, 1455295174, 1455295174, '8.26.21.19', '8.26.21.19'),
(317, 0, '4ce89bf33a6e43b2887e16185769f3be', 0, NULL, 1455295318, 1455295318, '8.26.21.19', '8.26.21.19'),
(318, 0, 'e782f1dce217124cb829f6db24df6b49', 0, NULL, 1455295385, 1455295385, '8.26.21.19', '8.26.21.19');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_stats_daily`
--

CREATE TABLE IF NOT EXISTS `mdl_stats_daily` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `stattype` varchar(20) NOT NULL DEFAULT 'activity',
  `stat1` bigint(10) NOT NULL DEFAULT '0',
  `stat2` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_statdail_cou_ix` (`courseid`),
  KEY `mdl_statdail_tim_ix` (`timeend`),
  KEY `mdl_statdail_rol_ix` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='to accumulate daily stats' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_stats_monthly`
--

CREATE TABLE IF NOT EXISTS `mdl_stats_monthly` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `stattype` varchar(20) NOT NULL DEFAULT 'activity',
  `stat1` bigint(10) NOT NULL DEFAULT '0',
  `stat2` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_statmont_cou_ix` (`courseid`),
  KEY `mdl_statmont_tim_ix` (`timeend`),
  KEY `mdl_statmont_rol_ix` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To accumulate monthly stats' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_stats_user_daily`
--

CREATE TABLE IF NOT EXISTS `mdl_stats_user_daily` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `statsreads` bigint(10) NOT NULL DEFAULT '0',
  `statswrites` bigint(10) NOT NULL DEFAULT '0',
  `stattype` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_statuserdail_cou_ix` (`courseid`),
  KEY `mdl_statuserdail_use_ix` (`userid`),
  KEY `mdl_statuserdail_rol_ix` (`roleid`),
  KEY `mdl_statuserdail_tim_ix` (`timeend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To accumulate daily stats per course/user' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_stats_user_monthly`
--

CREATE TABLE IF NOT EXISTS `mdl_stats_user_monthly` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `statsreads` bigint(10) NOT NULL DEFAULT '0',
  `statswrites` bigint(10) NOT NULL DEFAULT '0',
  `stattype` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_statusermont_cou_ix` (`courseid`),
  KEY `mdl_statusermont_use_ix` (`userid`),
  KEY `mdl_statusermont_rol_ix` (`roleid`),
  KEY `mdl_statusermont_tim_ix` (`timeend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To accumulate monthly stats per course/user' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_stats_user_weekly`
--

CREATE TABLE IF NOT EXISTS `mdl_stats_user_weekly` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `statsreads` bigint(10) NOT NULL DEFAULT '0',
  `statswrites` bigint(10) NOT NULL DEFAULT '0',
  `stattype` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_statuserweek_cou_ix` (`courseid`),
  KEY `mdl_statuserweek_use_ix` (`userid`),
  KEY `mdl_statuserweek_rol_ix` (`roleid`),
  KEY `mdl_statuserweek_tim_ix` (`timeend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To accumulate weekly stats per course/user' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_stats_weekly`
--

CREATE TABLE IF NOT EXISTS `mdl_stats_weekly` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '0',
  `roleid` bigint(10) NOT NULL DEFAULT '0',
  `stattype` varchar(20) NOT NULL DEFAULT 'activity',
  `stat1` bigint(10) NOT NULL DEFAULT '0',
  `stat2` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_statweek_cou_ix` (`courseid`),
  KEY `mdl_statweek_tim_ix` (`timeend`),
  KEY `mdl_statweek_rol_ix` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To accumulate weekly stats' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_survey`
--

CREATE TABLE IF NOT EXISTS `mdl_survey` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `template` bigint(10) NOT NULL DEFAULT '0',
  `days` mediumint(6) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext NOT NULL,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `questions` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_surv_cou_ix` (`course`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Each record is one SURVEY module with its configuration' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mdl_survey`
--

INSERT INTO `mdl_survey` (`id`, `course`, `template`, `days`, `timecreated`, `timemodified`, `name`, `intro`, `introformat`, `questions`) VALUES
(1, 0, 0, 0, 985017600, 985017600, 'collesaname', 'collesaintro', 0, '25,26,27,28,29,30,43,44'),
(2, 0, 0, 0, 985017600, 985017600, 'collespname', 'collespintro', 0, '31,32,33,34,35,36,43,44'),
(3, 0, 0, 0, 985017600, 985017600, 'collesapname', 'collesapintro', 0, '37,38,39,40,41,42,43,44'),
(4, 0, 0, 0, 985017600, 985017600, 'attlsname', 'attlsintro', 0, '65,67,68'),
(5, 0, 0, 0, 985017600, 985017600, 'ciqname', 'ciqintro', 0, '69,70,71,72,73');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_survey_analysis`
--

CREATE TABLE IF NOT EXISTS `mdl_survey_analysis` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `survey` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `notes` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_survanal_use_ix` (`userid`),
  KEY `mdl_survanal_sur_ix` (`survey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='text about each survey submission' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_survey_answers`
--

CREATE TABLE IF NOT EXISTS `mdl_survey_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `survey` bigint(10) NOT NULL DEFAULT '0',
  `question` bigint(10) NOT NULL DEFAULT '0',
  `time` bigint(10) NOT NULL DEFAULT '0',
  `answer1` longtext NOT NULL,
  `answer2` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_survansw_use_ix` (`userid`),
  KEY `mdl_survansw_sur_ix` (`survey`),
  KEY `mdl_survansw_que_ix` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='the answers to each questions filled by the users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_survey_questions`
--

CREATE TABLE IF NOT EXISTS `mdl_survey_questions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL DEFAULT '',
  `shorttext` varchar(30) NOT NULL DEFAULT '',
  `multi` varchar(100) NOT NULL DEFAULT '',
  `intro` varchar(50) NOT NULL DEFAULT '',
  `type` smallint(3) NOT NULL DEFAULT '0',
  `options` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='the questions conforming one survey' AUTO_INCREMENT=74 ;

--
-- Dumping data for table `mdl_survey_questions`
--

INSERT INTO `mdl_survey_questions` (`id`, `text`, `shorttext`, `multi`, `intro`, `type`, `options`) VALUES
(1, 'colles1', 'colles1short', '', '', 1, 'scaletimes5'),
(2, 'colles2', 'colles2short', '', '', 1, 'scaletimes5'),
(3, 'colles3', 'colles3short', '', '', 1, 'scaletimes5'),
(4, 'colles4', 'colles4short', '', '', 1, 'scaletimes5'),
(5, 'colles5', 'colles5short', '', '', 1, 'scaletimes5'),
(6, 'colles6', 'colles6short', '', '', 1, 'scaletimes5'),
(7, 'colles7', 'colles7short', '', '', 1, 'scaletimes5'),
(8, 'colles8', 'colles8short', '', '', 1, 'scaletimes5'),
(9, 'colles9', 'colles9short', '', '', 1, 'scaletimes5'),
(10, 'colles10', 'colles10short', '', '', 1, 'scaletimes5'),
(11, 'colles11', 'colles11short', '', '', 1, 'scaletimes5'),
(12, 'colles12', 'colles12short', '', '', 1, 'scaletimes5'),
(13, 'colles13', 'colles13short', '', '', 1, 'scaletimes5'),
(14, 'colles14', 'colles14short', '', '', 1, 'scaletimes5'),
(15, 'colles15', 'colles15short', '', '', 1, 'scaletimes5'),
(16, 'colles16', 'colles16short', '', '', 1, 'scaletimes5'),
(17, 'colles17', 'colles17short', '', '', 1, 'scaletimes5'),
(18, 'colles18', 'colles18short', '', '', 1, 'scaletimes5'),
(19, 'colles19', 'colles19short', '', '', 1, 'scaletimes5'),
(20, 'colles20', 'colles20short', '', '', 1, 'scaletimes5'),
(21, 'colles21', 'colles21short', '', '', 1, 'scaletimes5'),
(22, 'colles22', 'colles22short', '', '', 1, 'scaletimes5'),
(23, 'colles23', 'colles23short', '', '', 1, 'scaletimes5'),
(24, 'colles24', 'colles24short', '', '', 1, 'scaletimes5'),
(25, 'collesm1', 'collesm1short', '1,2,3,4', 'collesmintro', 1, 'scaletimes5'),
(26, 'collesm2', 'collesm2short', '5,6,7,8', 'collesmintro', 1, 'scaletimes5'),
(27, 'collesm3', 'collesm3short', '9,10,11,12', 'collesmintro', 1, 'scaletimes5'),
(28, 'collesm4', 'collesm4short', '13,14,15,16', 'collesmintro', 1, 'scaletimes5'),
(29, 'collesm5', 'collesm5short', '17,18,19,20', 'collesmintro', 1, 'scaletimes5'),
(30, 'collesm6', 'collesm6short', '21,22,23,24', 'collesmintro', 1, 'scaletimes5'),
(31, 'collesm1', 'collesm1short', '1,2,3,4', 'collesmintro', 2, 'scaletimes5'),
(32, 'collesm2', 'collesm2short', '5,6,7,8', 'collesmintro', 2, 'scaletimes5'),
(33, 'collesm3', 'collesm3short', '9,10,11,12', 'collesmintro', 2, 'scaletimes5'),
(34, 'collesm4', 'collesm4short', '13,14,15,16', 'collesmintro', 2, 'scaletimes5'),
(35, 'collesm5', 'collesm5short', '17,18,19,20', 'collesmintro', 2, 'scaletimes5'),
(36, 'collesm6', 'collesm6short', '21,22,23,24', 'collesmintro', 2, 'scaletimes5'),
(37, 'collesm1', 'collesm1short', '1,2,3,4', 'collesmintro', 3, 'scaletimes5'),
(38, 'collesm2', 'collesm2short', '5,6,7,8', 'collesmintro', 3, 'scaletimes5'),
(39, 'collesm3', 'collesm3short', '9,10,11,12', 'collesmintro', 3, 'scaletimes5'),
(40, 'collesm4', 'collesm4short', '13,14,15,16', 'collesmintro', 3, 'scaletimes5'),
(41, 'collesm5', 'collesm5short', '17,18,19,20', 'collesmintro', 3, 'scaletimes5'),
(42, 'collesm6', 'collesm6short', '21,22,23,24', 'collesmintro', 3, 'scaletimes5'),
(43, 'howlong', '', '', '', 1, 'howlongoptions'),
(44, 'othercomments', '', '', '', 0, NULL),
(45, 'attls1', 'attls1short', '', '', 1, 'scaleagree5'),
(46, 'attls2', 'attls2short', '', '', 1, 'scaleagree5'),
(47, 'attls3', 'attls3short', '', '', 1, 'scaleagree5'),
(48, 'attls4', 'attls4short', '', '', 1, 'scaleagree5'),
(49, 'attls5', 'attls5short', '', '', 1, 'scaleagree5'),
(50, 'attls6', 'attls6short', '', '', 1, 'scaleagree5'),
(51, 'attls7', 'attls7short', '', '', 1, 'scaleagree5'),
(52, 'attls8', 'attls8short', '', '', 1, 'scaleagree5'),
(53, 'attls9', 'attls9short', '', '', 1, 'scaleagree5'),
(54, 'attls10', 'attls10short', '', '', 1, 'scaleagree5'),
(55, 'attls11', 'attls11short', '', '', 1, 'scaleagree5'),
(56, 'attls12', 'attls12short', '', '', 1, 'scaleagree5'),
(57, 'attls13', 'attls13short', '', '', 1, 'scaleagree5'),
(58, 'attls14', 'attls14short', '', '', 1, 'scaleagree5'),
(59, 'attls15', 'attls15short', '', '', 1, 'scaleagree5'),
(60, 'attls16', 'attls16short', '', '', 1, 'scaleagree5'),
(61, 'attls17', 'attls17short', '', '', 1, 'scaleagree5'),
(62, 'attls18', 'attls18short', '', '', 1, 'scaleagree5'),
(63, 'attls19', 'attls19short', '', '', 1, 'scaleagree5'),
(64, 'attls20', 'attls20short', '', '', 1, 'scaleagree5'),
(65, 'attlsm1', 'attlsm1', '45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64', 'attlsmintro', 1, 'scaleagree5'),
(66, '-', '-', '-', '-', 0, '-'),
(67, 'attlsm2', 'attlsm2', '63,62,59,57,55,49,52,50,48,47', 'attlsmintro', -1, 'scaleagree5'),
(68, 'attlsm3', 'attlsm3', '46,54,45,51,60,53,56,58,61,64', 'attlsmintro', -1, 'scaleagree5'),
(69, 'ciq1', 'ciq1short', '', '', 0, NULL),
(70, 'ciq2', 'ciq2short', '', '', 0, NULL),
(71, 'ciq3', 'ciq3short', '', '', 0, NULL),
(72, 'ciq4', 'ciq4short', '', '', 0, NULL),
(73, 'ciq5', 'ciq5short', '', '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tag`
--

CREATE TABLE IF NOT EXISTS `mdl_tag` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `rawname` varchar(255) NOT NULL DEFAULT '',
  `tagtype` varchar(255) DEFAULT NULL,
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `flag` smallint(4) DEFAULT '0',
  `timemodified` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_tag_nam_uix` (`name`),
  KEY `mdl_tag_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tag table - this generic table will replace the old "tags" t' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tag_correlation`
--

CREATE TABLE IF NOT EXISTS `mdl_tag_correlation` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `tagid` bigint(10) NOT NULL,
  `correlatedtags` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_tagcorr_tag_ix` (`tagid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The rationale for the ''tag_correlation'' table is performance' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tag_instance`
--

CREATE TABLE IF NOT EXISTS `mdl_tag_instance` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `tagid` bigint(10) NOT NULL,
  `component` varchar(100) DEFAULT NULL,
  `itemtype` varchar(255) NOT NULL DEFAULT '',
  `itemid` bigint(10) NOT NULL,
  `contextid` bigint(10) DEFAULT NULL,
  `tiuserid` bigint(10) NOT NULL DEFAULT '0',
  `ordering` bigint(10) DEFAULT NULL,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_taginst_iteitetagtiu_uix` (`itemtype`,`itemid`,`tagid`,`tiuserid`),
  KEY `mdl_taginst_tag_ix` (`tagid`),
  KEY `mdl_taginst_con_ix` (`contextid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tag_instance table holds the information of associations bet' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_task_adhoc`
--

CREATE TABLE IF NOT EXISTS `mdl_task_adhoc` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `component` varchar(255) NOT NULL DEFAULT '',
  `classname` varchar(255) NOT NULL DEFAULT '',
  `nextruntime` bigint(10) NOT NULL,
  `faildelay` bigint(10) DEFAULT NULL,
  `customdata` longtext,
  `blocking` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_taskadho_nex_ix` (`nextruntime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of adhoc tasks waiting to run.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_task_scheduled`
--

CREATE TABLE IF NOT EXISTS `mdl_task_scheduled` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `component` varchar(255) NOT NULL DEFAULT '',
  `classname` varchar(255) NOT NULL DEFAULT '',
  `lastruntime` bigint(10) DEFAULT NULL,
  `nextruntime` bigint(10) DEFAULT NULL,
  `blocking` tinyint(2) NOT NULL DEFAULT '0',
  `minute` varchar(25) NOT NULL DEFAULT '',
  `hour` varchar(25) NOT NULL DEFAULT '',
  `day` varchar(25) NOT NULL DEFAULT '',
  `month` varchar(25) NOT NULL DEFAULT '',
  `dayofweek` varchar(25) NOT NULL DEFAULT '',
  `faildelay` bigint(10) DEFAULT NULL,
  `customised` tinyint(2) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_tasksche_cla_uix` (`classname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='List of scheduled tasks to be run by cron.' AUTO_INCREMENT=43 ;

--
-- Dumping data for table `mdl_task_scheduled`
--

INSERT INTO `mdl_task_scheduled` (`id`, `component`, `classname`, `lastruntime`, `nextruntime`, `blocking`, `minute`, `hour`, `day`, `month`, `dayofweek`, `faildelay`, `customised`, `disabled`) VALUES
(1, 'moodle', '\\core\\task\\session_cleanup_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(2, 'moodle', '\\core\\task\\delete_unconfirmed_users_task', 0, 1451556000, 0, '0', '*', '*', '*', '*', 0, 0, 0),
(3, 'moodle', '\\core\\task\\delete_incomplete_users_task', 0, 1451556300, 0, '5', '*', '*', '*', '*', 0, 0, 0),
(4, 'moodle', '\\core\\task\\backup_cleanup_task', 0, 1451553000, 0, '10', '*', '*', '*', '*', 0, 0, 0),
(5, 'moodle', '\\core\\task\\tag_cron_task', 0, 1451553600, 0, '20', '*', '*', '*', '*', 0, 0, 0),
(6, 'moodle', '\\core\\task\\context_cleanup_task', 0, 1451553900, 0, '25', '*', '*', '*', '*', 0, 0, 0),
(7, 'moodle', '\\core\\task\\cache_cleanup_task', 0, 1451554200, 0, '30', '*', '*', '*', '*', 0, 0, 0),
(8, 'moodle', '\\core\\task\\messaging_cleanup_task', 0, 1451554500, 0, '35', '*', '*', '*', '*', 0, 0, 0),
(9, 'moodle', '\\core\\task\\send_new_user_passwords_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(10, 'moodle', '\\core\\task\\send_failed_login_notifications_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(11, 'moodle', '\\core\\task\\create_contexts_task', 0, 1451606400, 1, '0', '0', '*', '*', '*', 0, 0, 0),
(12, 'moodle', '\\core\\task\\legacy_plugin_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(13, 'moodle', '\\core\\task\\grade_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(14, 'moodle', '\\core\\task\\events_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(15, 'moodle', '\\core\\task\\completion_regular_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(16, 'moodle', '\\core\\task\\completion_daily_task', 0, 1451584980, 0, '3', '18', '*', '*', '*', 0, 0, 0),
(17, 'moodle', '\\core\\task\\portfolio_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(18, 'moodle', '\\core\\task\\plagiarism_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(19, 'moodle', '\\core\\task\\calendar_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(20, 'moodle', '\\core\\task\\blog_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(21, 'moodle', '\\core\\task\\question_cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(22, 'moodle', '\\core\\task\\registration_cron_task', 0, 1452056760, 0, '6', '5', '*', '*', '3', 0, 0, 0),
(23, 'moodle', '\\core\\task\\check_for_updates_task', 0, 1451556000, 0, '0', '*/2', '*', '*', '*', 0, 0, 0),
(24, 'moodle', '\\core\\task\\cache_cron_task', 0, 1451555400, 0, '50', '*', '*', '*', '*', 0, 0, 0),
(25, 'moodle', '\\core\\task\\automated_backup_task', 0, 1451555400, 0, '50', '*', '*', '*', '*', 0, 0, 0),
(26, 'moodle', '\\core\\task\\badges_cron_task', 0, 1451553000, 0, '*/5', '*', '*', '*', '*', 0, 0, 0),
(27, 'moodle', '\\core\\task\\file_temp_cleanup_task', 0, 1451566500, 0, '55', '*/6', '*', '*', '*', 0, 0, 0),
(28, 'moodle', '\\core\\task\\file_trash_cleanup_task', 0, 1451566500, 0, '55', '*/6', '*', '*', '*', 0, 0, 0),
(29, 'moodle', '\\core\\task\\stats_cron_task', 0, 1451556000, 0, '0', '*', '*', '*', '*', 0, 0, 0),
(30, 'moodle', '\\core\\task\\password_reset_cleanup_task', 0, 1451563200, 0, '0', '*/6', '*', '*', '*', 0, 0, 0),
(31, 'mod_forum', '\\mod_forum\\task\\cron_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(32, 'auth_cas', '\\auth_cas\\task\\sync_task', 0, 1451606400, 0, '0', '0', '*', '*', '*', 0, 0, 1),
(33, 'auth_ldap', '\\auth_ldap\\task\\sync_task', 0, 1451606400, 0, '0', '0', '*', '*', '*', 0, 0, 1),
(34, 'enrol_flatfile', '\\enrol_flatfile\\task\\flatfile_sync_task', 0, 1451553300, 0, '15', '*', '*', '*', '*', 0, 0, 0),
(35, 'enrol_imsenterprise', '\\enrol_imsenterprise\\task\\cron_task', 0, 1451553000, 0, '10', '*', '*', '*', '*', 0, 0, 0),
(36, 'editor_atto', '\\editor_atto\\task\\autosave_cleanup_task', 0, 1451628300, 0, '5', '6', '*', '*', '0', 0, 0, 0),
(37, 'tool_langimport', '\\tool_langimport\\task\\update_langpacks_task', 0, 1451621640, 0, '14', '4', '*', '*', '*', 0, 0, 0),
(38, 'tool_messageinbound', '\\tool_messageinbound\\task\\pickup_task', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(39, 'tool_messageinbound', '\\tool_messageinbound\\task\\cleanup_task', 0, 1451613300, 0, '55', '1', '*', '*', '*', 0, 0, 0),
(40, 'tool_monitor', '\\tool_monitor\\task\\clean_events', 0, 1451552940, 0, '*', '*', '*', '*', '*', 0, 0, 0),
(41, 'logstore_legacy', '\\logstore_legacy\\task\\cleanup_task', 0, 1451625600, 0, '20', '5', '*', '*', '*', 0, 0, 0),
(42, 'logstore_standard', '\\logstore_standard\\task\\cleanup_task', 0, 1451621340, 0, '9', '4', '*', '*', '*', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_testimonial`
--

CREATE TABLE IF NOT EXISTS `mdl_testimonial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mdl_testimonial`
--

INSERT INTO `mdl_testimonial` (`id`, `content`) VALUES
(1, '<p>Celebrity endorsements have proven very successful in China, where increasing consumerism makes the purchase of an endorsed product into a status symbol. On August 1, 2007, laws were passed banning healthcare professionals and public figures such as movie stars or pop singers from appearing in advertisements for drugs or nutritional supplements. A spokesperson stated, &quot;A celebrity appearing in drug advertising is more likely to mislead consumers, therefore, the state must consider controlling medical advertisements and strengthen the management of national celebrities appearing in medical advertisements.</p>\n');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tool_customlang`
--

CREATE TABLE IF NOT EXISTS `mdl_tool_customlang` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `lang` varchar(20) NOT NULL DEFAULT '',
  `componentid` bigint(10) NOT NULL,
  `stringid` varchar(255) NOT NULL DEFAULT '',
  `original` longtext NOT NULL,
  `master` longtext,
  `local` longtext,
  `timemodified` bigint(10) NOT NULL,
  `timecustomized` bigint(10) DEFAULT NULL,
  `outdated` smallint(3) DEFAULT '0',
  `modified` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_toolcust_lancomstr_uix` (`lang`,`componentid`,`stringid`),
  KEY `mdl_toolcust_com_ix` (`componentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains the working checkout of all strings and their custo' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tool_customlang_components`
--

CREATE TABLE IF NOT EXISTS `mdl_tool_customlang_components` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains the list of all installed plugins that provide thei' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tool_monitor_events`
--

CREATE TABLE IF NOT EXISTS `mdl_tool_monitor_events` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `eventname` varchar(254) NOT NULL DEFAULT '',
  `contextid` bigint(10) NOT NULL,
  `contextlevel` bigint(10) NOT NULL,
  `contextinstanceid` bigint(10) NOT NULL,
  `link` varchar(254) NOT NULL DEFAULT '',
  `courseid` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='A table that keeps a log of events related to subscriptions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tool_monitor_history`
--

CREATE TABLE IF NOT EXISTS `mdl_tool_monitor_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `sid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `timesent` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_toolmonihist_sidusetim_uix` (`sid`,`userid`,`timesent`),
  KEY `mdl_toolmonihist_sid_ix` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table to store history of message notifications sent' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tool_monitor_rules`
--

CREATE TABLE IF NOT EXISTS `mdl_tool_monitor_rules` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `descriptionformat` tinyint(1) NOT NULL,
  `name` varchar(254) NOT NULL DEFAULT '',
  `userid` bigint(10) NOT NULL,
  `courseid` bigint(10) NOT NULL,
  `plugin` varchar(254) NOT NULL DEFAULT '',
  `eventname` varchar(254) NOT NULL DEFAULT '',
  `template` longtext NOT NULL,
  `templateformat` tinyint(1) NOT NULL,
  `frequency` smallint(4) NOT NULL,
  `timewindow` mediumint(5) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_toolmonirule_couuse_ix` (`courseid`,`userid`),
  KEY `mdl_toolmonirule_eve_ix` (`eventname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table to store rules' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_tool_monitor_subscriptions`
--

CREATE TABLE IF NOT EXISTS `mdl_tool_monitor_subscriptions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `ruleid` bigint(10) NOT NULL,
  `cmid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `lastnotificationsent` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_toolmonisubs_couuse_ix` (`courseid`,`userid`),
  KEY `mdl_toolmonisubs_rul_ix` (`ruleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table to store user subscriptions to various rules' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_upgrade_log`
--

CREATE TABLE IF NOT EXISTS `mdl_upgrade_log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `type` bigint(10) NOT NULL,
  `plugin` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `targetversion` varchar(100) DEFAULT NULL,
  `info` varchar(255) NOT NULL DEFAULT '',
  `details` longtext,
  `backtrace` longtext,
  `userid` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_upgrlog_tim_ix` (`timemodified`),
  KEY `mdl_upgrlog_typtim_ix` (`type`,`timemodified`),
  KEY `mdl_upgrlog_use_ix` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Upgrade logging' AUTO_INCREMENT=1089 ;

--
-- Dumping data for table `mdl_upgrade_log`
--

INSERT INTO `mdl_upgrade_log` (`id`, `type`, `plugin`, `version`, `targetversion`, `info`, `details`, `backtrace`, `userid`, `timemodified`) VALUES
(1, 0, 'core', '2015111601.01', '2015111601.01', 'Upgrade savepoint reached', NULL, '', 0, 1451552896),
(2, 0, 'core', '2015111601.01', '2015111601.01', 'Core installed', NULL, '', 0, 1451552905),
(3, 0, 'availability_completion', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(4, 0, 'availability_completion', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(5, 0, 'availability_completion', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(6, 0, 'availability_date', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(7, 0, 'availability_date', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(8, 0, 'availability_date', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(9, 0, 'availability_grade', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(10, 0, 'availability_grade', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(11, 0, 'availability_grade', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(12, 0, 'availability_group', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(13, 0, 'availability_group', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(14, 0, 'availability_group', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(15, 0, 'availability_grouping', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(16, 0, 'availability_grouping', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(17, 0, 'availability_grouping', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(18, 0, 'availability_profile', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(19, 0, 'availability_profile', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(20, 0, 'availability_profile', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(21, 0, 'qtype_calculated', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(22, 0, 'qtype_calculated', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(23, 0, 'qtype_calculated', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552905),
(24, 0, 'qtype_calculatedmulti', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552905),
(25, 0, 'qtype_calculatedmulti', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552905),
(26, 0, 'qtype_calculatedmulti', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(27, 0, 'qtype_calculatedsimple', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(28, 0, 'qtype_calculatedsimple', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(29, 0, 'qtype_calculatedsimple', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(30, 0, 'qtype_ddimageortext', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(31, 0, 'qtype_ddimageortext', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(32, 0, 'qtype_ddimageortext', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(33, 0, 'qtype_ddmarker', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(34, 0, 'qtype_ddmarker', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(35, 0, 'qtype_ddmarker', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(36, 0, 'qtype_ddwtos', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(37, 0, 'qtype_ddwtos', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(38, 0, 'qtype_ddwtos', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(39, 0, 'qtype_description', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(40, 0, 'qtype_description', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(41, 0, 'qtype_description', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(42, 0, 'qtype_essay', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(43, 0, 'qtype_essay', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(44, 0, 'qtype_essay', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(45, 0, 'qtype_gapselect', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(46, 0, 'qtype_gapselect', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(47, 0, 'qtype_gapselect', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(48, 0, 'qtype_match', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(49, 0, 'qtype_match', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(50, 0, 'qtype_match', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(51, 0, 'qtype_missingtype', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(52, 0, 'qtype_missingtype', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(53, 0, 'qtype_missingtype', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(54, 0, 'qtype_multianswer', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(55, 0, 'qtype_multianswer', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(56, 0, 'qtype_multianswer', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(57, 0, 'qtype_multichoice', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(58, 0, 'qtype_multichoice', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(59, 0, 'qtype_multichoice', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(60, 0, 'qtype_numerical', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(61, 0, 'qtype_numerical', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(62, 0, 'qtype_numerical', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(63, 0, 'qtype_random', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(64, 0, 'qtype_random', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552906),
(65, 0, 'qtype_random', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552906),
(66, 0, 'qtype_randomsamatch', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552906),
(67, 0, 'qtype_randomsamatch', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552907),
(68, 0, 'qtype_randomsamatch', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552907),
(69, 0, 'qtype_shortanswer', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552907),
(70, 0, 'qtype_shortanswer', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552907),
(71, 0, 'qtype_shortanswer', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552907),
(72, 0, 'qtype_truefalse', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552907),
(73, 0, 'qtype_truefalse', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552907),
(74, 0, 'qtype_truefalse', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552907),
(75, 0, 'mod_assign', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552907),
(76, 0, 'mod_assign', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552907),
(77, 0, 'mod_assign', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552907),
(78, 0, 'mod_assignment', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552907),
(79, 0, 'mod_assignment', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552907),
(80, 0, 'mod_assignment', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552908),
(81, 0, 'mod_book', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552908),
(82, 0, 'mod_book', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552908),
(83, 0, 'mod_book', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552908),
(84, 0, 'mod_chat', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552908),
(85, 0, 'mod_chat', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552908),
(86, 0, 'mod_chat', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552908),
(87, 0, 'mod_choice', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552908),
(88, 0, 'mod_choice', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552908),
(89, 0, 'mod_choice', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552908),
(90, 0, 'mod_data', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552908),
(91, 0, 'mod_data', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552908),
(92, 0, 'mod_data', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552909),
(93, 0, 'mod_feedback', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552909),
(94, 0, 'mod_feedback', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552909),
(95, 0, 'mod_feedback', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552909),
(96, 0, 'mod_folder', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552910),
(97, 0, 'mod_folder', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552910),
(98, 0, 'mod_folder', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552910),
(99, 0, 'mod_forum', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552910),
(100, 0, 'mod_forum', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552910),
(101, 0, 'mod_forum', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552911),
(102, 0, 'mod_glossary', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552911),
(103, 0, 'mod_glossary', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552911),
(104, 0, 'mod_glossary', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552911),
(105, 0, 'mod_imscp', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552911),
(106, 0, 'mod_imscp', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552911),
(107, 0, 'mod_imscp', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552911),
(108, 0, 'mod_label', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552911),
(109, 0, 'mod_label', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552911),
(110, 0, 'mod_label', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552911),
(111, 0, 'mod_lesson', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552911),
(112, 0, 'mod_lesson', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552912),
(113, 0, 'mod_lesson', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552912),
(114, 0, 'mod_lti', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552912),
(115, 0, 'mod_lti', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552912),
(116, 0, 'mod_lti', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552912),
(117, 0, 'mod_page', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552912),
(118, 0, 'mod_page', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552912),
(119, 0, 'mod_page', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552912),
(120, 0, 'mod_quiz', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552912),
(121, 0, 'mod_quiz', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552913),
(122, 0, 'mod_quiz', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552913),
(123, 0, 'mod_resource', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552913),
(124, 0, 'mod_resource', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552913),
(125, 0, 'mod_resource', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552913),
(126, 0, 'mod_scorm', NULL, '2015111601', 'Starting plugin installation', NULL, '', 0, 1451552913),
(127, 0, 'mod_scorm', '2015111601', '2015111601', 'Upgrade savepoint reached', NULL, '', 0, 1451552913),
(128, 0, 'mod_scorm', '2015111601', '2015111601', 'Plugin installed', NULL, '', 0, 1451552913),
(129, 0, 'mod_survey', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552913),
(130, 0, 'mod_survey', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552913),
(131, 0, 'mod_survey', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552914),
(132, 0, 'mod_url', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552914),
(133, 0, 'mod_url', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552914),
(134, 0, 'mod_url', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552914),
(135, 0, 'mod_wiki', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552914),
(136, 0, 'mod_wiki', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552914),
(137, 0, 'mod_wiki', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552914),
(138, 0, 'mod_workshop', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552914),
(139, 0, 'mod_workshop', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552915),
(140, 0, 'mod_workshop', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552915),
(141, 0, 'auth_cas', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552915),
(142, 0, 'auth_cas', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552915),
(143, 0, 'auth_cas', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552915),
(144, 0, 'auth_db', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552915),
(145, 0, 'auth_db', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552915),
(146, 0, 'auth_db', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552915),
(147, 0, 'auth_email', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552915),
(148, 0, 'auth_email', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552915),
(149, 0, 'auth_email', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552915),
(150, 0, 'auth_fc', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552915),
(151, 0, 'auth_fc', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(152, 0, 'auth_fc', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(153, 0, 'auth_imap', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(154, 0, 'auth_imap', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(155, 0, 'auth_imap', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(156, 0, 'auth_ldap', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(157, 0, 'auth_ldap', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(158, 0, 'auth_ldap', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(159, 0, 'auth_manual', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(160, 0, 'auth_manual', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(161, 0, 'auth_manual', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(162, 0, 'auth_mnet', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(163, 0, 'auth_mnet', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(164, 0, 'auth_mnet', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(165, 0, 'auth_nntp', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(166, 0, 'auth_nntp', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(167, 0, 'auth_nntp', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(168, 0, 'auth_nologin', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(169, 0, 'auth_nologin', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(170, 0, 'auth_nologin', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(171, 0, 'auth_none', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(172, 0, 'auth_none', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(173, 0, 'auth_none', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(174, 0, 'auth_pam', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(175, 0, 'auth_pam', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(176, 0, 'auth_pam', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552916),
(177, 0, 'auth_pop3', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552916),
(178, 0, 'auth_pop3', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552916),
(179, 0, 'auth_pop3', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(180, 0, 'auth_radius', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(181, 0, 'auth_radius', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(182, 0, 'auth_radius', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(183, 0, 'auth_shibboleth', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(184, 0, 'auth_shibboleth', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(185, 0, 'auth_shibboleth', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(186, 0, 'auth_webservice', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(187, 0, 'auth_webservice', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(188, 0, 'auth_webservice', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(189, 0, 'calendartype_gregorian', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(190, 0, 'calendartype_gregorian', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(191, 0, 'calendartype_gregorian', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(192, 0, 'enrol_category', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(193, 0, 'enrol_category', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(194, 0, 'enrol_category', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(195, 0, 'enrol_cohort', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(196, 0, 'enrol_cohort', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(197, 0, 'enrol_cohort', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(198, 0, 'enrol_database', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(199, 0, 'enrol_database', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(200, 0, 'enrol_database', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(201, 0, 'enrol_flatfile', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(202, 0, 'enrol_flatfile', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(203, 0, 'enrol_flatfile', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(204, 0, 'enrol_guest', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(205, 0, 'enrol_guest', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(206, 0, 'enrol_guest', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(207, 0, 'enrol_imsenterprise', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(208, 0, 'enrol_imsenterprise', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(209, 0, 'enrol_imsenterprise', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552917),
(210, 0, 'enrol_ldap', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552917),
(211, 0, 'enrol_ldap', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552917),
(212, 0, 'enrol_ldap', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552918),
(213, 0, 'enrol_manual', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552918),
(214, 0, 'enrol_manual', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552918),
(215, 0, 'enrol_manual', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552918),
(216, 0, 'enrol_meta', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552918),
(217, 0, 'enrol_meta', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552918),
(218, 0, 'enrol_meta', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552918),
(219, 0, 'enrol_mnet', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552918),
(220, 0, 'enrol_mnet', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552918),
(221, 0, 'enrol_mnet', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552918),
(222, 0, 'enrol_paypal', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552918),
(223, 0, 'enrol_paypal', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552918),
(224, 0, 'enrol_paypal', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552918),
(225, 0, 'enrol_self', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552918),
(226, 0, 'enrol_self', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552918),
(227, 0, 'enrol_self', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552919),
(228, 0, 'message_airnotifier', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552919),
(229, 0, 'message_airnotifier', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552919),
(230, 0, 'message_airnotifier', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552919),
(231, 0, 'message_email', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552919),
(232, 0, 'message_email', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552919),
(233, 0, 'message_email', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552919),
(234, 0, 'message_jabber', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552919),
(235, 0, 'message_jabber', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552919),
(236, 0, 'message_jabber', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(237, 0, 'message_popup', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(238, 0, 'message_popup', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(239, 0, 'message_popup', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(240, 0, 'block_activity_modules', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(241, 0, 'block_activity_modules', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(242, 0, 'block_activity_modules', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(243, 0, 'block_activity_results', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(244, 0, 'block_activity_results', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(245, 0, 'block_activity_results', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(246, 0, 'block_admin_bookmarks', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(247, 0, 'block_admin_bookmarks', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(248, 0, 'block_admin_bookmarks', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(249, 0, 'block_badges', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(250, 0, 'block_badges', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(251, 0, 'block_badges', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(252, 0, 'block_blog_menu', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(253, 0, 'block_blog_menu', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(254, 0, 'block_blog_menu', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(255, 0, 'block_blog_recent', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(256, 0, 'block_blog_recent', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(257, 0, 'block_blog_recent', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(258, 0, 'block_blog_tags', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(259, 0, 'block_blog_tags', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(260, 0, 'block_blog_tags', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(261, 0, 'block_calendar_month', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(262, 0, 'block_calendar_month', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(263, 0, 'block_calendar_month', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552920),
(264, 0, 'block_calendar_upcoming', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552920),
(265, 0, 'block_calendar_upcoming', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552920),
(266, 0, 'block_calendar_upcoming', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(267, 0, 'block_comments', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(268, 0, 'block_comments', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(269, 0, 'block_comments', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(270, 0, 'block_community', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(271, 0, 'block_community', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(272, 0, 'block_community', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(273, 0, 'block_completionstatus', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(274, 0, 'block_completionstatus', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(275, 0, 'block_completionstatus', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(276, 0, 'block_course_list', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(277, 0, 'block_course_list', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(278, 0, 'block_course_list', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(279, 0, 'block_course_overview', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(280, 0, 'block_course_overview', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(281, 0, 'block_course_overview', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(282, 0, 'block_course_summary', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(283, 0, 'block_course_summary', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(284, 0, 'block_course_summary', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(285, 0, 'block_feedback', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(286, 0, 'block_feedback', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(287, 0, 'block_feedback', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(288, 0, 'block_glossary_random', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(289, 0, 'block_glossary_random', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(290, 0, 'block_glossary_random', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552921),
(291, 0, 'block_html', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552921),
(292, 0, 'block_html', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552921),
(293, 0, 'block_html', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552922),
(294, 0, 'block_login', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552922),
(295, 0, 'block_login', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552922),
(296, 0, 'block_login', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552922),
(297, 0, 'block_mentees', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552922),
(298, 0, 'block_mentees', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552922),
(299, 0, 'block_mentees', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552922),
(300, 0, 'block_messages', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552922),
(301, 0, 'block_messages', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552922),
(302, 0, 'block_messages', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552922),
(303, 0, 'block_mnet_hosts', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552922),
(304, 0, 'block_mnet_hosts', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552922),
(305, 0, 'block_mnet_hosts', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552922),
(306, 0, 'block_myprofile', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552922),
(307, 0, 'block_myprofile', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552922),
(308, 0, 'block_myprofile', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552922),
(309, 0, 'block_navigation', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552922),
(310, 0, 'block_navigation', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552922),
(311, 0, 'block_navigation', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(312, 0, 'block_news_items', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(313, 0, 'block_news_items', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(314, 0, 'block_news_items', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(315, 0, 'block_online_users', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(316, 0, 'block_online_users', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(317, 0, 'block_online_users', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(318, 0, 'block_participants', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(319, 0, 'block_participants', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(320, 0, 'block_participants', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(321, 0, 'block_private_files', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(322, 0, 'block_private_files', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(323, 0, 'block_private_files', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(324, 0, 'block_quiz_results', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(325, 0, 'block_quiz_results', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(326, 0, 'block_quiz_results', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(327, 0, 'block_recent_activity', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(328, 0, 'block_recent_activity', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(329, 0, 'block_recent_activity', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(330, 0, 'block_rss_client', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(331, 0, 'block_rss_client', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(332, 0, 'block_rss_client', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552923),
(333, 0, 'block_search_forums', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552923),
(334, 0, 'block_search_forums', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552923),
(335, 0, 'block_search_forums', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(336, 0, 'block_section_links', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(337, 0, 'block_section_links', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(338, 0, 'block_section_links', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(339, 0, 'block_selfcompletion', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(340, 0, 'block_selfcompletion', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(341, 0, 'block_selfcompletion', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(342, 0, 'block_settings', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(343, 0, 'block_settings', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(344, 0, 'block_settings', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(345, 0, 'block_site_main_menu', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(346, 0, 'block_site_main_menu', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(347, 0, 'block_site_main_menu', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(348, 0, 'block_social_activities', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(349, 0, 'block_social_activities', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(350, 0, 'block_social_activities', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(351, 0, 'block_tag_flickr', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(352, 0, 'block_tag_flickr', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(353, 0, 'block_tag_flickr', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(354, 0, 'block_tag_youtube', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(355, 0, 'block_tag_youtube', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(356, 0, 'block_tag_youtube', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(357, 0, 'block_tags', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(358, 0, 'block_tags', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(359, 0, 'block_tags', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(360, 0, 'filter_activitynames', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(361, 0, 'filter_activitynames', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(362, 0, 'filter_activitynames', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552924),
(363, 0, 'filter_algebra', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552924),
(364, 0, 'filter_algebra', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552924),
(365, 0, 'filter_algebra', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(366, 0, 'filter_censor', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(367, 0, 'filter_censor', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(368, 0, 'filter_censor', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(369, 0, 'filter_data', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(370, 0, 'filter_data', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(371, 0, 'filter_data', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(372, 0, 'filter_emailprotect', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(373, 0, 'filter_emailprotect', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(374, 0, 'filter_emailprotect', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(375, 0, 'filter_emoticon', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(376, 0, 'filter_emoticon', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(377, 0, 'filter_emoticon', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(378, 0, 'filter_glossary', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(379, 0, 'filter_glossary', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(380, 0, 'filter_glossary', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(381, 0, 'filter_mathjaxloader', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(382, 0, 'filter_mathjaxloader', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(383, 0, 'filter_mathjaxloader', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(384, 0, 'filter_mediaplugin', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(385, 0, 'filter_mediaplugin', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(386, 0, 'filter_mediaplugin', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(387, 0, 'filter_multilang', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(388, 0, 'filter_multilang', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(389, 0, 'filter_multilang', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(390, 0, 'filter_tex', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(391, 0, 'filter_tex', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(392, 0, 'filter_tex', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(393, 0, 'filter_tidy', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(394, 0, 'filter_tidy', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(395, 0, 'filter_tidy', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(396, 0, 'filter_urltolink', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(397, 0, 'filter_urltolink', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552925),
(398, 0, 'filter_urltolink', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552925),
(399, 0, 'editor_atto', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552925),
(400, 0, 'editor_atto', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(401, 0, 'editor_atto', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(402, 0, 'editor_textarea', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(403, 0, 'editor_textarea', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(404, 0, 'editor_textarea', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(405, 0, 'editor_tinymce', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(406, 0, 'editor_tinymce', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(407, 0, 'editor_tinymce', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(408, 0, 'format_singleactivity', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(409, 0, 'format_singleactivity', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(410, 0, 'format_singleactivity', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(411, 0, 'format_social', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(412, 0, 'format_social', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(413, 0, 'format_social', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(414, 0, 'format_topics', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(415, 0, 'format_topics', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(416, 0, 'format_topics', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(417, 0, 'format_weeks', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(418, 0, 'format_weeks', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(419, 0, 'format_weeks', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(420, 0, 'profilefield_checkbox', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(421, 0, 'profilefield_checkbox', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(422, 0, 'profilefield_checkbox', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(423, 0, 'profilefield_datetime', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(424, 0, 'profilefield_datetime', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(425, 0, 'profilefield_datetime', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(426, 0, 'profilefield_menu', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(427, 0, 'profilefield_menu', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(428, 0, 'profilefield_menu', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(429, 0, 'profilefield_text', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(430, 0, 'profilefield_text', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(431, 0, 'profilefield_text', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(432, 0, 'profilefield_textarea', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(433, 0, 'profilefield_textarea', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(434, 0, 'profilefield_textarea', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(435, 0, 'report_backups', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(436, 0, 'report_backups', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(437, 0, 'report_backups', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552926),
(438, 0, 'report_completion', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552926),
(439, 0, 'report_completion', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552926),
(440, 0, 'report_completion', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(441, 0, 'report_configlog', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(442, 0, 'report_configlog', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(443, 0, 'report_configlog', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(444, 0, 'report_courseoverview', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(445, 0, 'report_courseoverview', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(446, 0, 'report_courseoverview', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(447, 0, 'report_eventlist', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(448, 0, 'report_eventlist', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(449, 0, 'report_eventlist', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(450, 0, 'report_log', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(451, 0, 'report_log', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(452, 0, 'report_log', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(453, 0, 'report_loglive', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(454, 0, 'report_loglive', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(455, 0, 'report_loglive', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(456, 0, 'report_outline', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(457, 0, 'report_outline', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(458, 0, 'report_outline', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(459, 0, 'report_participation', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(460, 0, 'report_participation', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(461, 0, 'report_participation', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(462, 0, 'report_performance', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(463, 0, 'report_performance', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(464, 0, 'report_performance', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(465, 0, 'report_progress', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(466, 0, 'report_progress', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(467, 0, 'report_progress', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(468, 0, 'report_questioninstances', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(469, 0, 'report_questioninstances', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(470, 0, 'report_questioninstances', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552927),
(471, 0, 'report_security', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552927),
(472, 0, 'report_security', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552927),
(473, 0, 'report_security', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(474, 0, 'report_stats', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(475, 0, 'report_stats', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(476, 0, 'report_stats', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(477, 0, 'report_usersessions', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(478, 0, 'report_usersessions', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(479, 0, 'report_usersessions', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(480, 0, 'gradeexport_ods', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928);
INSERT INTO `mdl_upgrade_log` (`id`, `type`, `plugin`, `version`, `targetversion`, `info`, `details`, `backtrace`, `userid`, `timemodified`) VALUES
(481, 0, 'gradeexport_ods', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(482, 0, 'gradeexport_ods', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(483, 0, 'gradeexport_txt', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(484, 0, 'gradeexport_txt', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(485, 0, 'gradeexport_txt', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(486, 0, 'gradeexport_xls', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(487, 0, 'gradeexport_xls', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(488, 0, 'gradeexport_xls', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(489, 0, 'gradeexport_xml', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(490, 0, 'gradeexport_xml', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(491, 0, 'gradeexport_xml', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(492, 0, 'gradeimport_csv', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(493, 0, 'gradeimport_csv', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(494, 0, 'gradeimport_csv', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(495, 0, 'gradeimport_direct', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(496, 0, 'gradeimport_direct', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(497, 0, 'gradeimport_direct', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(498, 0, 'gradeimport_xml', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(499, 0, 'gradeimport_xml', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(500, 0, 'gradeimport_xml', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(501, 0, 'gradereport_grader', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(502, 0, 'gradereport_grader', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(503, 0, 'gradereport_grader', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552928),
(504, 0, 'gradereport_history', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552928),
(505, 0, 'gradereport_history', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552928),
(506, 0, 'gradereport_history', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(507, 0, 'gradereport_outcomes', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(508, 0, 'gradereport_outcomes', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(509, 0, 'gradereport_outcomes', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(510, 0, 'gradereport_overview', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(511, 0, 'gradereport_overview', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(512, 0, 'gradereport_overview', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(513, 0, 'gradereport_singleview', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(514, 0, 'gradereport_singleview', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(515, 0, 'gradereport_singleview', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(516, 0, 'gradereport_user', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(517, 0, 'gradereport_user', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(518, 0, 'gradereport_user', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(519, 0, 'gradingform_guide', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(520, 0, 'gradingform_guide', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(521, 0, 'gradingform_guide', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(522, 0, 'gradingform_rubric', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(523, 0, 'gradingform_rubric', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(524, 0, 'gradingform_rubric', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(525, 0, 'mnetservice_enrol', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(526, 0, 'mnetservice_enrol', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(527, 0, 'mnetservice_enrol', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(528, 0, 'webservice_amf', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(529, 0, 'webservice_amf', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(530, 0, 'webservice_amf', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(531, 0, 'webservice_rest', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(532, 0, 'webservice_rest', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(533, 0, 'webservice_rest', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552929),
(534, 0, 'webservice_soap', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552929),
(535, 0, 'webservice_soap', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552929),
(536, 0, 'webservice_soap', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(537, 0, 'webservice_xmlrpc', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(538, 0, 'webservice_xmlrpc', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(539, 0, 'webservice_xmlrpc', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(540, 0, 'repository_alfresco', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(541, 0, 'repository_alfresco', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(542, 0, 'repository_alfresco', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(543, 0, 'repository_areafiles', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(544, 0, 'repository_areafiles', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(545, 0, 'repository_areafiles', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(546, 0, 'repository_boxnet', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(547, 0, 'repository_boxnet', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(548, 0, 'repository_boxnet', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(549, 0, 'repository_coursefiles', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(550, 0, 'repository_coursefiles', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(551, 0, 'repository_coursefiles', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(552, 0, 'repository_dropbox', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(553, 0, 'repository_dropbox', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(554, 0, 'repository_dropbox', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(555, 0, 'repository_equella', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(556, 0, 'repository_equella', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(557, 0, 'repository_equella', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(558, 0, 'repository_filesystem', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(559, 0, 'repository_filesystem', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(560, 0, 'repository_filesystem', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(561, 0, 'repository_flickr', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(562, 0, 'repository_flickr', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(563, 0, 'repository_flickr', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(564, 0, 'repository_flickr_public', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(565, 0, 'repository_flickr_public', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(566, 0, 'repository_flickr_public', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(567, 0, 'repository_googledocs', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(568, 0, 'repository_googledocs', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(569, 0, 'repository_googledocs', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552930),
(570, 0, 'repository_local', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552930),
(571, 0, 'repository_local', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552930),
(572, 0, 'repository_local', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(573, 0, 'repository_merlot', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(574, 0, 'repository_merlot', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(575, 0, 'repository_merlot', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(576, 0, 'repository_picasa', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(577, 0, 'repository_picasa', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(578, 0, 'repository_picasa', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(579, 0, 'repository_recent', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(580, 0, 'repository_recent', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(581, 0, 'repository_recent', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(582, 0, 'repository_s3', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(583, 0, 'repository_s3', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(584, 0, 'repository_s3', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(585, 0, 'repository_skydrive', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(586, 0, 'repository_skydrive', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(587, 0, 'repository_skydrive', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(588, 0, 'repository_upload', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(589, 0, 'repository_upload', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(590, 0, 'repository_upload', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(591, 0, 'repository_url', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(592, 0, 'repository_url', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(593, 0, 'repository_url', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(594, 0, 'repository_user', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(595, 0, 'repository_user', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(596, 0, 'repository_user', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552931),
(597, 0, 'repository_webdav', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552931),
(598, 0, 'repository_webdav', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552931),
(599, 0, 'repository_webdav', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(600, 0, 'repository_wikimedia', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(601, 0, 'repository_wikimedia', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(602, 0, 'repository_wikimedia', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(603, 0, 'repository_youtube', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(604, 0, 'repository_youtube', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(605, 0, 'repository_youtube', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(606, 0, 'portfolio_boxnet', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(607, 0, 'portfolio_boxnet', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(608, 0, 'portfolio_boxnet', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(609, 0, 'portfolio_download', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(610, 0, 'portfolio_download', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(611, 0, 'portfolio_download', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(612, 0, 'portfolio_flickr', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(613, 0, 'portfolio_flickr', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(614, 0, 'portfolio_flickr', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(615, 0, 'portfolio_googledocs', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(616, 0, 'portfolio_googledocs', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(617, 0, 'portfolio_googledocs', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(618, 0, 'portfolio_mahara', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(619, 0, 'portfolio_mahara', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(620, 0, 'portfolio_mahara', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(621, 0, 'portfolio_picasa', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(622, 0, 'portfolio_picasa', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(623, 0, 'portfolio_picasa', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(624, 0, 'qbehaviour_adaptive', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(625, 0, 'qbehaviour_adaptive', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(626, 0, 'qbehaviour_adaptive', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(627, 0, 'qbehaviour_adaptivenopenalty', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(628, 0, 'qbehaviour_adaptivenopenalty', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(629, 0, 'qbehaviour_adaptivenopenalty', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(630, 0, 'qbehaviour_deferredcbm', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(631, 0, 'qbehaviour_deferredcbm', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(632, 0, 'qbehaviour_deferredcbm', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(633, 0, 'qbehaviour_deferredfeedback', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(634, 0, 'qbehaviour_deferredfeedback', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(635, 0, 'qbehaviour_deferredfeedback', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552932),
(636, 0, 'qbehaviour_immediatecbm', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552932),
(637, 0, 'qbehaviour_immediatecbm', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552932),
(638, 0, 'qbehaviour_immediatecbm', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(639, 0, 'qbehaviour_immediatefeedback', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(640, 0, 'qbehaviour_immediatefeedback', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(641, 0, 'qbehaviour_immediatefeedback', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(642, 0, 'qbehaviour_informationitem', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(643, 0, 'qbehaviour_informationitem', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(644, 0, 'qbehaviour_informationitem', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(645, 0, 'qbehaviour_interactive', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(646, 0, 'qbehaviour_interactive', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(647, 0, 'qbehaviour_interactive', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(648, 0, 'qbehaviour_interactivecountback', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(649, 0, 'qbehaviour_interactivecountback', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(650, 0, 'qbehaviour_interactivecountback', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(651, 0, 'qbehaviour_manualgraded', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(652, 0, 'qbehaviour_manualgraded', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(653, 0, 'qbehaviour_manualgraded', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(654, 0, 'qbehaviour_missing', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(655, 0, 'qbehaviour_missing', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(656, 0, 'qbehaviour_missing', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(657, 0, 'qformat_aiken', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(658, 0, 'qformat_aiken', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(659, 0, 'qformat_aiken', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(660, 0, 'qformat_blackboard_six', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(661, 0, 'qformat_blackboard_six', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(662, 0, 'qformat_blackboard_six', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(663, 0, 'qformat_examview', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(664, 0, 'qformat_examview', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(665, 0, 'qformat_examview', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(666, 0, 'qformat_gift', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(667, 0, 'qformat_gift', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(668, 0, 'qformat_gift', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(669, 0, 'qformat_missingword', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(670, 0, 'qformat_missingword', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(671, 0, 'qformat_missingword', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(672, 0, 'qformat_multianswer', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(673, 0, 'qformat_multianswer', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(674, 0, 'qformat_multianswer', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(675, 0, 'qformat_webct', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(676, 0, 'qformat_webct', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(677, 0, 'qformat_webct', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552933),
(678, 0, 'qformat_xhtml', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552933),
(679, 0, 'qformat_xhtml', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552933),
(680, 0, 'qformat_xhtml', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(681, 0, 'qformat_xml', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(682, 0, 'qformat_xml', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(683, 0, 'qformat_xml', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(684, 0, 'tool_assignmentupgrade', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(685, 0, 'tool_assignmentupgrade', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(686, 0, 'tool_assignmentupgrade', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(687, 0, 'tool_availabilityconditions', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(688, 0, 'tool_availabilityconditions', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(689, 0, 'tool_availabilityconditions', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(690, 0, 'tool_behat', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(691, 0, 'tool_behat', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(692, 0, 'tool_behat', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(693, 0, 'tool_capability', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(694, 0, 'tool_capability', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(695, 0, 'tool_capability', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(696, 0, 'tool_customlang', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(697, 0, 'tool_customlang', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(698, 0, 'tool_customlang', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(699, 0, 'tool_dbtransfer', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(700, 0, 'tool_dbtransfer', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(701, 0, 'tool_dbtransfer', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(702, 0, 'tool_filetypes', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(703, 0, 'tool_filetypes', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(704, 0, 'tool_filetypes', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(705, 0, 'tool_generator', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(706, 0, 'tool_generator', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(707, 0, 'tool_generator', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(708, 0, 'tool_health', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(709, 0, 'tool_health', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(710, 0, 'tool_health', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(711, 0, 'tool_innodb', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(712, 0, 'tool_innodb', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(713, 0, 'tool_innodb', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(714, 0, 'tool_installaddon', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(715, 0, 'tool_installaddon', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(716, 0, 'tool_installaddon', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552934),
(717, 0, 'tool_langimport', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552934),
(718, 0, 'tool_langimport', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552934),
(719, 0, 'tool_langimport', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(720, 0, 'tool_log', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(721, 0, 'tool_log', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(722, 0, 'tool_log', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(723, 0, 'tool_messageinbound', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(724, 0, 'tool_messageinbound', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(725, 0, 'tool_messageinbound', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(726, 0, 'tool_monitor', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(727, 0, 'tool_monitor', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(728, 0, 'tool_monitor', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(729, 0, 'tool_multilangupgrade', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(730, 0, 'tool_multilangupgrade', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(731, 0, 'tool_multilangupgrade', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(732, 0, 'tool_phpunit', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(733, 0, 'tool_phpunit', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(734, 0, 'tool_phpunit', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(735, 0, 'tool_profiling', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(736, 0, 'tool_profiling', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(737, 0, 'tool_profiling', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552935),
(738, 0, 'tool_replace', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552935),
(739, 0, 'tool_replace', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552935),
(740, 0, 'tool_replace', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(741, 0, 'tool_spamcleaner', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(742, 0, 'tool_spamcleaner', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(743, 0, 'tool_spamcleaner', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(744, 0, 'tool_task', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(745, 0, 'tool_task', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(746, 0, 'tool_task', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(747, 0, 'tool_templatelibrary', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(748, 0, 'tool_templatelibrary', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(749, 0, 'tool_templatelibrary', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(750, 0, 'tool_unsuproles', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(751, 0, 'tool_unsuproles', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(752, 0, 'tool_unsuproles', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(753, 0, 'tool_uploadcourse', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(754, 0, 'tool_uploadcourse', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(755, 0, 'tool_uploadcourse', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(756, 0, 'tool_uploaduser', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(757, 0, 'tool_uploaduser', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(758, 0, 'tool_uploaduser', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(759, 0, 'tool_xmldb', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(760, 0, 'tool_xmldb', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(761, 0, 'tool_xmldb', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(762, 0, 'cachestore_file', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(763, 0, 'cachestore_file', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(764, 0, 'cachestore_file', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(765, 0, 'cachestore_memcache', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(766, 0, 'cachestore_memcache', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(767, 0, 'cachestore_memcache', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(768, 0, 'cachestore_memcached', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(769, 0, 'cachestore_memcached', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(770, 0, 'cachestore_memcached', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(771, 0, 'cachestore_mongodb', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(772, 0, 'cachestore_mongodb', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(773, 0, 'cachestore_mongodb', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552936),
(774, 0, 'cachestore_session', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552936),
(775, 0, 'cachestore_session', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552936),
(776, 0, 'cachestore_session', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(777, 0, 'cachestore_static', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(778, 0, 'cachestore_static', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(779, 0, 'cachestore_static', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(780, 0, 'cachelock_file', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(781, 0, 'cachelock_file', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(782, 0, 'cachelock_file', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(783, 0, 'theme_base', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(784, 0, 'theme_base', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(785, 0, 'theme_base', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(786, 0, 'theme_bootstrapbase', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(787, 0, 'theme_bootstrapbase', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(788, 0, 'theme_bootstrapbase', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(789, 0, 'theme_canvas', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(790, 0, 'theme_canvas', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(791, 0, 'theme_canvas', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(792, 0, 'theme_clean', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(793, 0, 'theme_clean', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(794, 0, 'theme_clean', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(795, 0, 'theme_more', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(796, 0, 'theme_more', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(797, 0, 'theme_more', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(798, 0, 'assignsubmission_comments', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(799, 0, 'assignsubmission_comments', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(800, 0, 'assignsubmission_comments', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(801, 0, 'assignsubmission_file', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(802, 0, 'assignsubmission_file', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(803, 0, 'assignsubmission_file', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(804, 0, 'assignsubmission_onlinetext', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(805, 0, 'assignsubmission_onlinetext', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(806, 0, 'assignsubmission_onlinetext', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552937),
(807, 0, 'assignfeedback_comments', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552937),
(808, 0, 'assignfeedback_comments', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552937),
(809, 0, 'assignfeedback_comments', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(810, 0, 'assignfeedback_editpdf', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(811, 0, 'assignfeedback_editpdf', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(812, 0, 'assignfeedback_editpdf', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(813, 0, 'assignfeedback_file', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(814, 0, 'assignfeedback_file', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(815, 0, 'assignfeedback_file', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(816, 0, 'assignfeedback_offline', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(817, 0, 'assignfeedback_offline', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(818, 0, 'assignfeedback_offline', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(819, 0, 'assignment_offline', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(820, 0, 'assignment_offline', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(821, 0, 'assignment_offline', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(822, 0, 'assignment_online', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(823, 0, 'assignment_online', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(824, 0, 'assignment_online', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(825, 0, 'assignment_upload', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(826, 0, 'assignment_upload', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(827, 0, 'assignment_upload', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(828, 0, 'assignment_uploadsingle', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(829, 0, 'assignment_uploadsingle', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(830, 0, 'assignment_uploadsingle', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(831, 0, 'booktool_exportimscp', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(832, 0, 'booktool_exportimscp', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(833, 0, 'booktool_exportimscp', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(834, 0, 'booktool_importhtml', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(835, 0, 'booktool_importhtml', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(836, 0, 'booktool_importhtml', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552938),
(837, 0, 'booktool_print', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552938),
(838, 0, 'booktool_print', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552938),
(839, 0, 'booktool_print', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(840, 0, 'datafield_checkbox', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(841, 0, 'datafield_checkbox', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(842, 0, 'datafield_checkbox', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(843, 0, 'datafield_date', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(844, 0, 'datafield_date', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(845, 0, 'datafield_date', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(846, 0, 'datafield_file', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(847, 0, 'datafield_file', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(848, 0, 'datafield_file', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(849, 0, 'datafield_latlong', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(850, 0, 'datafield_latlong', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(851, 0, 'datafield_latlong', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(852, 0, 'datafield_menu', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(853, 0, 'datafield_menu', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(854, 0, 'datafield_menu', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(855, 0, 'datafield_multimenu', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(856, 0, 'datafield_multimenu', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(857, 0, 'datafield_multimenu', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(858, 0, 'datafield_number', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(859, 0, 'datafield_number', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(860, 0, 'datafield_number', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(861, 0, 'datafield_picture', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(862, 0, 'datafield_picture', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(863, 0, 'datafield_picture', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(864, 0, 'datafield_radiobutton', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(865, 0, 'datafield_radiobutton', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(866, 0, 'datafield_radiobutton', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(867, 0, 'datafield_text', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(868, 0, 'datafield_text', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(869, 0, 'datafield_text', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(870, 0, 'datafield_textarea', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(871, 0, 'datafield_textarea', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(872, 0, 'datafield_textarea', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(873, 0, 'datafield_url', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(874, 0, 'datafield_url', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(875, 0, 'datafield_url', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(876, 0, 'datapreset_imagegallery', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(877, 0, 'datapreset_imagegallery', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(878, 0, 'datapreset_imagegallery', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552939),
(879, 0, 'ltiservice_memberships', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552939),
(880, 0, 'ltiservice_memberships', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552939),
(881, 0, 'ltiservice_memberships', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(882, 0, 'ltiservice_profile', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(883, 0, 'ltiservice_profile', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(884, 0, 'ltiservice_profile', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(885, 0, 'ltiservice_toolproxy', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(886, 0, 'ltiservice_toolproxy', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(887, 0, 'ltiservice_toolproxy', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(888, 0, 'ltiservice_toolsettings', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(889, 0, 'ltiservice_toolsettings', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(890, 0, 'ltiservice_toolsettings', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(891, 0, 'quiz_grading', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(892, 0, 'quiz_grading', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(893, 0, 'quiz_grading', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(894, 0, 'quiz_overview', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(895, 0, 'quiz_overview', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(896, 0, 'quiz_overview', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(897, 0, 'quiz_responses', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(898, 0, 'quiz_responses', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(899, 0, 'quiz_responses', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(900, 0, 'quiz_statistics', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(901, 0, 'quiz_statistics', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(902, 0, 'quiz_statistics', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(903, 0, 'quizaccess_delaybetweenattempts', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(904, 0, 'quizaccess_delaybetweenattempts', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(905, 0, 'quizaccess_delaybetweenattempts', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(906, 0, 'quizaccess_ipaddress', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(907, 0, 'quizaccess_ipaddress', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(908, 0, 'quizaccess_ipaddress', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(909, 0, 'quizaccess_numattempts', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(910, 0, 'quizaccess_numattempts', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(911, 0, 'quizaccess_numattempts', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(912, 0, 'quizaccess_openclosedate', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(913, 0, 'quizaccess_openclosedate', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552940),
(914, 0, 'quizaccess_openclosedate', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552940),
(915, 0, 'quizaccess_password', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552940),
(916, 0, 'quizaccess_password', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(917, 0, 'quizaccess_password', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(918, 0, 'quizaccess_safebrowser', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(919, 0, 'quizaccess_safebrowser', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(920, 0, 'quizaccess_safebrowser', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(921, 0, 'quizaccess_securewindow', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(922, 0, 'quizaccess_securewindow', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(923, 0, 'quizaccess_securewindow', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(924, 0, 'quizaccess_timelimit', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(925, 0, 'quizaccess_timelimit', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(926, 0, 'quizaccess_timelimit', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(927, 0, 'scormreport_basic', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(928, 0, 'scormreport_basic', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(929, 0, 'scormreport_basic', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(930, 0, 'scormreport_graphs', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(931, 0, 'scormreport_graphs', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(932, 0, 'scormreport_graphs', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(933, 0, 'scormreport_interactions', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(934, 0, 'scormreport_interactions', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(935, 0, 'scormreport_interactions', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(936, 0, 'scormreport_objectives', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(937, 0, 'scormreport_objectives', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(938, 0, 'scormreport_objectives', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(939, 0, 'workshopform_accumulative', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(940, 0, 'workshopform_accumulative', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(941, 0, 'workshopform_accumulative', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(942, 0, 'workshopform_comments', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941),
(943, 0, 'workshopform_comments', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552941),
(944, 0, 'workshopform_comments', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552941),
(945, 0, 'workshopform_numerrors', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552941);
INSERT INTO `mdl_upgrade_log` (`id`, `type`, `plugin`, `version`, `targetversion`, `info`, `details`, `backtrace`, `userid`, `timemodified`) VALUES
(946, 0, 'workshopform_numerrors', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(947, 0, 'workshopform_numerrors', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(948, 0, 'workshopform_rubric', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(949, 0, 'workshopform_rubric', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(950, 0, 'workshopform_rubric', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(951, 0, 'workshopallocation_manual', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(952, 0, 'workshopallocation_manual', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(953, 0, 'workshopallocation_manual', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(954, 0, 'workshopallocation_random', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(955, 0, 'workshopallocation_random', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(956, 0, 'workshopallocation_random', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(957, 0, 'workshopallocation_scheduled', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(958, 0, 'workshopallocation_scheduled', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(959, 0, 'workshopallocation_scheduled', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(960, 0, 'workshopeval_best', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(961, 0, 'workshopeval_best', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(962, 0, 'workshopeval_best', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(963, 0, 'atto_accessibilitychecker', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(964, 0, 'atto_accessibilitychecker', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(965, 0, 'atto_accessibilitychecker', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(966, 0, 'atto_accessibilityhelper', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(967, 0, 'atto_accessibilityhelper', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(968, 0, 'atto_accessibilityhelper', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(969, 0, 'atto_align', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(970, 0, 'atto_align', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(971, 0, 'atto_align', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552942),
(972, 0, 'atto_backcolor', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552942),
(973, 0, 'atto_backcolor', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552942),
(974, 0, 'atto_backcolor', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(975, 0, 'atto_bold', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(976, 0, 'atto_bold', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(977, 0, 'atto_bold', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(978, 0, 'atto_charmap', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(979, 0, 'atto_charmap', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(980, 0, 'atto_charmap', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(981, 0, 'atto_clear', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(982, 0, 'atto_clear', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(983, 0, 'atto_clear', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(984, 0, 'atto_collapse', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(985, 0, 'atto_collapse', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(986, 0, 'atto_collapse', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(987, 0, 'atto_emoticon', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(988, 0, 'atto_emoticon', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(989, 0, 'atto_emoticon', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(990, 0, 'atto_equation', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(991, 0, 'atto_equation', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(992, 0, 'atto_equation', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(993, 0, 'atto_fontcolor', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(994, 0, 'atto_fontcolor', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(995, 0, 'atto_fontcolor', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(996, 0, 'atto_html', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(997, 0, 'atto_html', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(998, 0, 'atto_html', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(999, 0, 'atto_image', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(1000, 0, 'atto_image', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(1001, 0, 'atto_image', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(1002, 0, 'atto_indent', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(1003, 0, 'atto_indent', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(1004, 0, 'atto_indent', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(1005, 0, 'atto_italic', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(1006, 0, 'atto_italic', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(1007, 0, 'atto_italic', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552943),
(1008, 0, 'atto_link', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552943),
(1009, 0, 'atto_link', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552943),
(1010, 0, 'atto_link', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1011, 0, 'atto_managefiles', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1012, 0, 'atto_managefiles', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1013, 0, 'atto_managefiles', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1014, 0, 'atto_media', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1015, 0, 'atto_media', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1016, 0, 'atto_media', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1017, 0, 'atto_noautolink', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1018, 0, 'atto_noautolink', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1019, 0, 'atto_noautolink', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1020, 0, 'atto_orderedlist', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1021, 0, 'atto_orderedlist', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1022, 0, 'atto_orderedlist', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1023, 0, 'atto_rtl', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1024, 0, 'atto_rtl', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1025, 0, 'atto_rtl', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1026, 0, 'atto_strike', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1027, 0, 'atto_strike', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1028, 0, 'atto_strike', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1029, 0, 'atto_subscript', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1030, 0, 'atto_subscript', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1031, 0, 'atto_subscript', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1032, 0, 'atto_superscript', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1033, 0, 'atto_superscript', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1034, 0, 'atto_superscript', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1035, 0, 'atto_table', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1036, 0, 'atto_table', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1037, 0, 'atto_table', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1038, 0, 'atto_title', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1039, 0, 'atto_title', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1040, 0, 'atto_title', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1041, 0, 'atto_underline', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1042, 0, 'atto_underline', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1043, 0, 'atto_underline', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552944),
(1044, 0, 'atto_undo', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552944),
(1045, 0, 'atto_undo', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552944),
(1046, 0, 'atto_undo', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1047, 0, 'atto_unorderedlist', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1048, 0, 'atto_unorderedlist', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1049, 0, 'atto_unorderedlist', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1050, 0, 'tinymce_ctrlhelp', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1051, 0, 'tinymce_ctrlhelp', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1052, 0, 'tinymce_ctrlhelp', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1053, 0, 'tinymce_managefiles', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1054, 0, 'tinymce_managefiles', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1055, 0, 'tinymce_managefiles', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1056, 0, 'tinymce_moodleemoticon', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1057, 0, 'tinymce_moodleemoticon', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1058, 0, 'tinymce_moodleemoticon', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1059, 0, 'tinymce_moodleimage', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1060, 0, 'tinymce_moodleimage', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1061, 0, 'tinymce_moodleimage', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1062, 0, 'tinymce_moodlemedia', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1063, 0, 'tinymce_moodlemedia', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1064, 0, 'tinymce_moodlemedia', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1065, 0, 'tinymce_moodlenolink', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1066, 0, 'tinymce_moodlenolink', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1067, 0, 'tinymce_moodlenolink', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1068, 0, 'tinymce_pdw', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1069, 0, 'tinymce_pdw', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1070, 0, 'tinymce_pdw', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1071, 0, 'tinymce_spellchecker', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1072, 0, 'tinymce_spellchecker', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1073, 0, 'tinymce_spellchecker', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1074, 0, 'tinymce_wrap', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1075, 0, 'tinymce_wrap', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1076, 0, 'tinymce_wrap', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552945),
(1077, 0, 'logstore_database', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552945),
(1078, 0, 'logstore_database', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552945),
(1079, 0, 'logstore_database', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552946),
(1080, 0, 'logstore_legacy', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552946),
(1081, 0, 'logstore_legacy', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552946),
(1082, 0, 'logstore_legacy', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552946),
(1083, 0, 'logstore_standard', NULL, '2015111600', 'Starting plugin installation', NULL, '', 0, 1451552946),
(1084, 0, 'logstore_standard', '2015111600', '2015111600', 'Upgrade savepoint reached', NULL, '', 0, 1451552946),
(1085, 0, 'logstore_standard', '2015111600', '2015111600', 'Plugin installed', NULL, '', 0, 1451552946),
(1086, 0, 'theme_lambda', NULL, '2015110809', 'Starting plugin installation', NULL, '', 2, 1451892662),
(1087, 0, 'theme_lambda', '2015110809', '2015110809', 'Upgrade savepoint reached', NULL, '', 2, 1451892662),
(1088, 0, 'theme_lambda', '2015110809', '2015110809', 'Plugin installed', NULL, '', 2, 1451892662);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_url`
--

CREATE TABLE IF NOT EXISTS `mdl_url` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `externalurl` longtext NOT NULL,
  `display` smallint(4) NOT NULL DEFAULT '0',
  `displayoptions` longtext,
  `parameters` longtext,
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_url_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='each record is one url resource' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user`
--

CREATE TABLE IF NOT EXISTS `mdl_user` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `auth` varchar(20) NOT NULL DEFAULT 'manual',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `policyagreed` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `mnethostid` bigint(10) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `idnumber` varchar(255) NOT NULL DEFAULT '',
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `emailstop` tinyint(1) NOT NULL DEFAULT '0',
  `icq` varchar(15) NOT NULL DEFAULT '',
  `skype` varchar(50) NOT NULL DEFAULT '',
  `yahoo` varchar(50) NOT NULL DEFAULT '',
  `aim` varchar(50) NOT NULL DEFAULT '',
  `msn` varchar(50) NOT NULL DEFAULT '',
  `phone1` varchar(20) NOT NULL DEFAULT '',
  `phone2` varchar(20) NOT NULL DEFAULT '',
  `institution` varchar(255) NOT NULL DEFAULT '',
  `department` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(120) NOT NULL DEFAULT '',
  `country` varchar(2) NOT NULL DEFAULT '',
  `lang` varchar(30) NOT NULL DEFAULT 'en',
  `calendartype` varchar(30) NOT NULL DEFAULT 'gregorian',
  `theme` varchar(50) NOT NULL DEFAULT '',
  `timezone` varchar(100) NOT NULL DEFAULT '99',
  `firstaccess` bigint(10) NOT NULL DEFAULT '0',
  `lastaccess` bigint(10) NOT NULL DEFAULT '0',
  `lastlogin` bigint(10) NOT NULL DEFAULT '0',
  `currentlogin` bigint(10) NOT NULL DEFAULT '0',
  `lastip` varchar(45) NOT NULL DEFAULT '',
  `secret` varchar(15) NOT NULL DEFAULT '',
  `picture` bigint(10) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '1',
  `mailformat` tinyint(1) NOT NULL DEFAULT '1',
  `maildigest` tinyint(1) NOT NULL DEFAULT '0',
  `maildisplay` tinyint(2) NOT NULL DEFAULT '2',
  `autosubscribe` tinyint(1) NOT NULL DEFAULT '1',
  `trackforums` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `trustbitmask` bigint(10) NOT NULL DEFAULT '0',
  `imagealt` varchar(255) DEFAULT NULL,
  `lastnamephonetic` varchar(255) DEFAULT NULL,
  `firstnamephonetic` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `alternatename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_user_mneuse_uix` (`mnethostid`,`username`),
  KEY `mdl_user_del_ix` (`deleted`),
  KEY `mdl_user_con_ix` (`confirmed`),
  KEY `mdl_user_fir_ix` (`firstname`),
  KEY `mdl_user_las_ix` (`lastname`),
  KEY `mdl_user_cit_ix` (`city`),
  KEY `mdl_user_cou_ix` (`country`),
  KEY `mdl_user_las2_ix` (`lastaccess`),
  KEY `mdl_user_ema_ix` (`email`),
  KEY `mdl_user_aut_ix` (`auth`),
  KEY `mdl_user_idn_ix` (`idnumber`),
  KEY `mdl_user_fir2_ix` (`firstnamephonetic`),
  KEY `mdl_user_las3_ix` (`lastnamephonetic`),
  KEY `mdl_user_mid_ix` (`middlename`),
  KEY `mdl_user_alt_ix` (`alternatename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='One record for each person' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mdl_user`
--

INSERT INTO `mdl_user` (`id`, `auth`, `confirmed`, `policyagreed`, `deleted`, `suspended`, `mnethostid`, `username`, `password`, `idnumber`, `firstname`, `lastname`, `email`, `emailstop`, `icq`, `skype`, `yahoo`, `aim`, `msn`, `phone1`, `phone2`, `institution`, `department`, `address`, `city`, `country`, `lang`, `calendartype`, `theme`, `timezone`, `firstaccess`, `lastaccess`, `lastlogin`, `currentlogin`, `lastip`, `secret`, `picture`, `url`, `description`, `descriptionformat`, `mailformat`, `maildigest`, `maildisplay`, `autosubscribe`, `trackforums`, `timecreated`, `timemodified`, `trustbitmask`, `imagealt`, `lastnamephonetic`, `firstnamephonetic`, `middlename`, `alternatename`) VALUES
(1, 'manual', 1, 0, 0, 0, 1, 'guest', '$2y$10$um/W6.dQwt7rCH/kGxxf5uP3JwXUqOHPmX.vb1vp5HPymrn98Ci/W', '', 'Guest user', ' ', 'root@localhost', 0, '', '', '', '', '', '', '', '', '', '', '', '', 'en', 'gregorian', '', '99', 0, 0, 0, 0, '', '', 0, '', 'This user is a special user that allows read-only access to some courses.', 1, 1, 0, 2, 1, 0, 0, 1451552894, 0, NULL, NULL, NULL, NULL, NULL),
(2, 'manual', 1, 0, 0, 0, 1, 'admin', '$2y$10$GZBf.FEUKzXM/AmQMK4e0ujIoOvDZi90AuzSf6Z10STiRCP.B2f3S', '', 'Admin', 'User', 'some@gmail.com', 0, '', '', '', '', '', '', '', '', '', '', '', '', 'en', 'gregorian', '', '99', 1451552995, 1455293291, 1455291723, 1455293291, '109.87.201.130', '', 0, '', 'This is admin user.', 1, 1, 0, 1, 1, 0, 0, 1451553059, 0, NULL, '', '', '', ''),
(3, 'manual', 1, 0, 0, 0, 1, 'manager', '$2y$10$3sIvhhEBlzYIbTyIp3YameSagh64th72C2kG9V1eSE1BxX2NXu3dS', '', 'Manager', 'User', 'manager@gmail.com', 0, '', '', '', '', '', '', '', '', '', '', '', '', 'en', 'gregorian', '', '99', 1453291360, 1453362673, 1453362521, 1453362673, '109.87.201.130', '', 0, '', '<p>This is manager user</p>', 1, 1, 0, 0, 1, 0, 1453291188, 1453291188, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_devices`
--

CREATE TABLE IF NOT EXISTS `mdl_user_devices` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `appid` varchar(128) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `model` varchar(32) NOT NULL DEFAULT '',
  `platform` varchar(32) NOT NULL DEFAULT '',
  `version` varchar(32) NOT NULL DEFAULT '',
  `pushid` varchar(255) NOT NULL DEFAULT '',
  `uuid` varchar(255) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_userdevi_pususe_uix` (`pushid`,`userid`),
  KEY `mdl_userdevi_uuiuse_ix` (`uuid`,`userid`),
  KEY `mdl_userdevi_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table stores user''s mobile devices information in order' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_enrolments`
--

CREATE TABLE IF NOT EXISTS `mdl_user_enrolments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `status` bigint(10) NOT NULL DEFAULT '0',
  `enrolid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `timestart` bigint(10) NOT NULL DEFAULT '0',
  `timeend` bigint(10) NOT NULL DEFAULT '2147483647',
  `modifierid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_userenro_enruse_uix` (`enrolid`,`userid`),
  KEY `mdl_userenro_enr_ix` (`enrolid`),
  KEY `mdl_userenro_use_ix` (`userid`),
  KEY `mdl_userenro_mod_ix` (`modifierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Users participating in courses (aka enrolled users) - everyb' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_info_category`
--

CREATE TABLE IF NOT EXISTS `mdl_user_info_category` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Customisable fields categories' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_info_data`
--

CREATE TABLE IF NOT EXISTS `mdl_user_info_data` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `fieldid` bigint(10) NOT NULL DEFAULT '0',
  `data` longtext NOT NULL,
  `dataformat` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_userinfodata_usefie_uix` (`userid`,`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Data for the customisable user fields' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_info_field`
--

CREATE TABLE IF NOT EXISTS `mdl_user_info_field` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(255) NOT NULL DEFAULT 'shortname',
  `name` longtext NOT NULL,
  `datatype` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `descriptionformat` tinyint(2) NOT NULL DEFAULT '0',
  `categoryid` bigint(10) NOT NULL DEFAULT '0',
  `sortorder` bigint(10) NOT NULL DEFAULT '0',
  `required` tinyint(2) NOT NULL DEFAULT '0',
  `locked` tinyint(2) NOT NULL DEFAULT '0',
  `visible` smallint(4) NOT NULL DEFAULT '0',
  `forceunique` tinyint(2) NOT NULL DEFAULT '0',
  `signup` tinyint(2) NOT NULL DEFAULT '0',
  `defaultdata` longtext,
  `defaultdataformat` tinyint(2) NOT NULL DEFAULT '0',
  `param1` longtext,
  `param2` longtext,
  `param3` longtext,
  `param4` longtext,
  `param5` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Customisable user profile fields' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_lastaccess`
--

CREATE TABLE IF NOT EXISTS `mdl_user_lastaccess` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `courseid` bigint(10) NOT NULL DEFAULT '0',
  `timeaccess` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_userlast_usecou_uix` (`userid`,`courseid`),
  KEY `mdl_userlast_use_ix` (`userid`),
  KEY `mdl_userlast_cou_ix` (`courseid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='To keep track of course page access times, used in online pa' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `mdl_user_lastaccess`
--

INSERT INTO `mdl_user_lastaccess` (`id`, `userid`, `courseid`, `timeaccess`) VALUES
(1, 2, 6, 1454401165),
(2, 2, 5, 1454401100),
(3, 2, 4, 1454401106),
(4, 2, 3, 1454401120),
(5, 2, 7, 1454401281),
(6, 2, 2, 1454401146),
(7, 2, 13, 1454401185),
(8, 2, 12, 1454401199),
(9, 2, 11, 1454401220),
(10, 2, 10, 1454401234),
(11, 2, 9, 1454401249),
(12, 2, 8, 1454401265);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_password_history`
--

CREATE TABLE IF NOT EXISTS `mdl_user_password_history` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `hash` varchar(255) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_userpasshist_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='A rotating log of hashes of previously used passwords for ea' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_password_resets`
--

CREATE TABLE IF NOT EXISTS `mdl_user_password_resets` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `timerequested` bigint(10) NOT NULL,
  `timererequested` bigint(10) NOT NULL DEFAULT '0',
  `token` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mdl_userpassrese_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table tracking password reset confirmation tokens' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_preferences`
--

CREATE TABLE IF NOT EXISTS `mdl_user_preferences` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(1333) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_userpref_usenam_uix` (`userid`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Allows modules to store arbitrary user preferences' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `mdl_user_preferences`
--

INSERT INTO `mdl_user_preferences` (`id`, `userid`, `name`, `value`) VALUES
(1, 2, 'auth_manual_passwordupdatetime', '1451553059'),
(2, 2, 'email_bounce_count', '1'),
(3, 2, 'email_send_count', '1'),
(4, 2, 'login_failed_count_since_success', '11'),
(6, 3, 'auth_forcepasswordchange', '0'),
(7, 3, 'email_bounce_count', '1'),
(8, 3, 'email_send_count', '1'),
(10, 2, 'userselector_preserveselected', '0'),
(11, 2, 'userselector_autoselectunique', '0'),
(12, 2, 'userselector_searchanywhere', '0');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_user_private_key`
--

CREATE TABLE IF NOT EXISTS `mdl_user_private_key` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `script` varchar(128) NOT NULL DEFAULT '',
  `value` varchar(128) NOT NULL DEFAULT '',
  `userid` bigint(10) NOT NULL,
  `instance` bigint(10) DEFAULT NULL,
  `iprestriction` varchar(255) DEFAULT NULL,
  `validuntil` bigint(10) DEFAULT NULL,
  `timecreated` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_userprivkey_scrval_ix` (`script`,`value`),
  KEY `mdl_userprivkey_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='access keys used in cookieless scripts - rss, etc.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT 'Wiki',
  `intro` longtext,
  `introformat` smallint(4) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `firstpagetitle` varchar(255) NOT NULL DEFAULT 'First Page',
  `wikimode` varchar(20) NOT NULL DEFAULT 'collaborative',
  `defaultformat` varchar(20) NOT NULL DEFAULT 'creole',
  `forceformat` tinyint(1) NOT NULL DEFAULT '1',
  `editbegin` bigint(10) NOT NULL DEFAULT '0',
  `editend` bigint(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_wiki_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores Wiki activity configuration' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki_links`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki_links` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subwikiid` bigint(10) NOT NULL DEFAULT '0',
  `frompageid` bigint(10) NOT NULL DEFAULT '0',
  `topageid` bigint(10) NOT NULL DEFAULT '0',
  `tomissingpage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_wikilink_fro_ix` (`frompageid`),
  KEY `mdl_wikilink_sub_ix` (`subwikiid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Page wiki links' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki_locks`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki_locks` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `pageid` bigint(10) NOT NULL DEFAULT '0',
  `sectionname` varchar(255) DEFAULT NULL,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `lockedat` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Manages page locks' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki_pages`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki_pages` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subwikiid` bigint(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT 'title',
  `cachedcontent` longtext NOT NULL,
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `timerendered` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `pageviews` bigint(10) NOT NULL DEFAULT '0',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_wikipage_subtituse_uix` (`subwikiid`,`title`,`userid`),
  KEY `mdl_wikipage_sub_ix` (`subwikiid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores wiki pages' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki_subwikis`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki_subwikis` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `wikiid` bigint(10) NOT NULL DEFAULT '0',
  `groupid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_wikisubw_wikgrouse_uix` (`wikiid`,`groupid`,`userid`),
  KEY `mdl_wikisubw_wik_ix` (`wikiid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores subwiki instances' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki_synonyms`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki_synonyms` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `subwikiid` bigint(10) NOT NULL DEFAULT '0',
  `pageid` bigint(10) NOT NULL DEFAULT '0',
  `pagesynonym` varchar(255) NOT NULL DEFAULT 'Pagesynonym',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_wikisyno_pagpag_uix` (`pageid`,`pagesynonym`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores wiki pages synonyms' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_wiki_versions`
--

CREATE TABLE IF NOT EXISTS `mdl_wiki_versions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `pageid` bigint(10) NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  `contentformat` varchar(20) NOT NULL DEFAULT 'creole',
  `version` mediumint(5) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_wikivers_pag_ix` (`pageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores wiki page history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `intro` longtext,
  `introformat` smallint(3) NOT NULL DEFAULT '0',
  `instructauthors` longtext,
  `instructauthorsformat` smallint(3) NOT NULL DEFAULT '0',
  `instructreviewers` longtext,
  `instructreviewersformat` smallint(3) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL,
  `phase` smallint(3) DEFAULT '0',
  `useexamples` tinyint(2) DEFAULT '0',
  `usepeerassessment` tinyint(2) DEFAULT '0',
  `useselfassessment` tinyint(2) DEFAULT '0',
  `grade` decimal(10,5) DEFAULT '80.00000',
  `gradinggrade` decimal(10,5) DEFAULT '20.00000',
  `strategy` varchar(30) NOT NULL DEFAULT '',
  `evaluation` varchar(30) NOT NULL DEFAULT '',
  `gradedecimals` smallint(3) DEFAULT '0',
  `nattachments` smallint(3) DEFAULT '0',
  `latesubmissions` tinyint(2) DEFAULT '0',
  `maxbytes` bigint(10) DEFAULT '100000',
  `examplesmode` smallint(3) DEFAULT '0',
  `submissionstart` bigint(10) DEFAULT '0',
  `submissionend` bigint(10) DEFAULT '0',
  `assessmentstart` bigint(10) DEFAULT '0',
  `assessmentend` bigint(10) DEFAULT '0',
  `phaseswitchassessment` tinyint(2) NOT NULL DEFAULT '0',
  `conclusion` longtext,
  `conclusionformat` smallint(3) NOT NULL DEFAULT '1',
  `overallfeedbackmode` smallint(3) DEFAULT '1',
  `overallfeedbackfiles` smallint(3) DEFAULT '0',
  `overallfeedbackmaxbytes` bigint(10) DEFAULT '100000',
  PRIMARY KEY (`id`),
  KEY `mdl_work_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table keeps information about the module instances and ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopallocation_scheduled`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopallocation_scheduled` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `enabled` tinyint(2) NOT NULL DEFAULT '0',
  `submissionend` bigint(10) NOT NULL,
  `timeallocated` bigint(10) DEFAULT NULL,
  `settings` longtext,
  `resultstatus` bigint(10) DEFAULT NULL,
  `resultmessage` varchar(1333) DEFAULT NULL,
  `resultlog` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_worksche_wor_uix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the allocation settings for the scheduled allocator' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopeval_best_settings`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopeval_best_settings` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `comparison` smallint(3) DEFAULT '5',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_workbestsett_wor_uix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Settings for the grading evaluation subplugin Comparison wit' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_accumulative`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_accumulative` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `sort` bigint(10) DEFAULT '0',
  `description` longtext,
  `descriptionformat` smallint(3) DEFAULT '0',
  `grade` bigint(10) NOT NULL,
  `weight` mediumint(5) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_workaccu_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The assessment dimensions definitions of Accumulative gradin' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_comments`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_comments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `sort` bigint(10) DEFAULT '0',
  `description` longtext,
  `descriptionformat` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_workcomm_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The assessment dimensions definitions of Comments strategy f' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_numerrors`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_numerrors` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `sort` bigint(10) DEFAULT '0',
  `description` longtext,
  `descriptionformat` smallint(3) DEFAULT '0',
  `descriptiontrust` bigint(10) DEFAULT NULL,
  `grade0` varchar(50) DEFAULT NULL,
  `grade1` varchar(50) DEFAULT NULL,
  `weight` mediumint(5) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mdl_worknume_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The assessment dimensions definitions of Number of errors gr' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_numerrors_map`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_numerrors_map` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `nonegative` bigint(10) NOT NULL,
  `grade` decimal(10,5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_worknumemap_wornon_uix` (`workshopid`,`nonegative`),
  KEY `mdl_worknumemap_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This maps the number of errors to a percentual grade for sub' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_rubric`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_rubric` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `sort` bigint(10) DEFAULT '0',
  `description` longtext,
  `descriptionformat` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_workrubr_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The assessment dimensions definitions of Rubric grading stra' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_rubric_config`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_rubric_config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `layout` varchar(30) DEFAULT 'list',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_workrubrconf_wor_uix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Configuration table for the Rubric grading strategy' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshopform_rubric_levels`
--

CREATE TABLE IF NOT EXISTS `mdl_workshopform_rubric_levels` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `dimensionid` bigint(10) NOT NULL,
  `grade` decimal(10,5) NOT NULL,
  `definition` longtext,
  `definitionformat` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_workrubrleve_dim_ix` (`dimensionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The definition of rubric rating scales' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_aggregations`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_aggregations` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `gradinggrade` decimal(10,5) DEFAULT NULL,
  `timegraded` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_workaggr_woruse_uix` (`workshopid`,`userid`),
  KEY `mdl_workaggr_wor_ix` (`workshopid`),
  KEY `mdl_workaggr_use_ix` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Aggregated grades for assessment are stored here. The aggreg' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_assessments`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_assessments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `submissionid` bigint(10) NOT NULL,
  `reviewerid` bigint(10) NOT NULL,
  `weight` bigint(10) NOT NULL DEFAULT '1',
  `timecreated` bigint(10) DEFAULT '0',
  `timemodified` bigint(10) DEFAULT '0',
  `grade` decimal(10,5) DEFAULT NULL,
  `gradinggrade` decimal(10,5) DEFAULT NULL,
  `gradinggradeover` decimal(10,5) DEFAULT NULL,
  `gradinggradeoverby` bigint(10) DEFAULT NULL,
  `feedbackauthor` longtext,
  `feedbackauthorformat` smallint(3) DEFAULT '0',
  `feedbackauthorattachment` smallint(3) DEFAULT '0',
  `feedbackreviewer` longtext,
  `feedbackreviewerformat` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_workasse_sub_ix` (`submissionid`),
  KEY `mdl_workasse_gra_ix` (`gradinggradeoverby`),
  KEY `mdl_workasse_rev_ix` (`reviewerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about the made assessment and automatically calculated ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_assessments_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_assessments_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `submissionid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timegraded` bigint(10) NOT NULL DEFAULT '0',
  `timeagreed` bigint(10) NOT NULL DEFAULT '0',
  `grade` double NOT NULL DEFAULT '0',
  `gradinggrade` smallint(3) NOT NULL DEFAULT '0',
  `teachergraded` smallint(3) NOT NULL DEFAULT '0',
  `mailed` smallint(3) NOT NULL DEFAULT '0',
  `resubmission` smallint(3) NOT NULL DEFAULT '0',
  `donotuse` smallint(3) NOT NULL DEFAULT '0',
  `generalcomment` longtext,
  `teachercomment` longtext,
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workasseold_use_ix` (`userid`),
  KEY `mdl_workasseold_mai_ix` (`mailed`),
  KEY `mdl_workasseold_wor_ix` (`workshopid`),
  KEY `mdl_workasseold_sub_ix` (`submissionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_assessments table to be dropped later in Moo' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_comments_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_comments_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `assessmentid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `mailed` tinyint(2) NOT NULL DEFAULT '0',
  `comments` longtext NOT NULL,
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workcommold_use_ix` (`userid`),
  KEY `mdl_workcommold_mai_ix` (`mailed`),
  KEY `mdl_workcommold_wor_ix` (`workshopid`),
  KEY `mdl_workcommold_ass_ix` (`assessmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_comments table to be dropped later in Moodle' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_elements_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_elements_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `elementno` smallint(3) NOT NULL DEFAULT '0',
  `description` longtext NOT NULL,
  `scale` smallint(3) NOT NULL DEFAULT '0',
  `maxscore` smallint(3) NOT NULL DEFAULT '1',
  `weight` smallint(3) NOT NULL DEFAULT '11',
  `stddev` double NOT NULL DEFAULT '0',
  `totalassessments` bigint(10) NOT NULL DEFAULT '0',
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workelemold_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_elements table to be dropped later in Moodle' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_grades`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_grades` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `assessmentid` bigint(10) NOT NULL,
  `strategy` varchar(30) NOT NULL DEFAULT '',
  `dimensionid` bigint(10) NOT NULL,
  `grade` decimal(10,5) NOT NULL,
  `peercomment` longtext,
  `peercommentformat` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_workgrad_assstrdim_uix` (`assessmentid`,`strategy`,`dimensionid`),
  KEY `mdl_workgrad_ass_ix` (`assessmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='How the reviewers filled-up the grading forms, given grades ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_grades_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_grades_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `assessmentid` bigint(10) NOT NULL DEFAULT '0',
  `elementno` bigint(10) NOT NULL DEFAULT '0',
  `feedback` longtext NOT NULL,
  `grade` smallint(3) NOT NULL DEFAULT '0',
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workgradold_wor_ix` (`workshopid`),
  KEY `mdl_workgradold_ass_ix` (`assessmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_grades table to be dropped later in Moodle 2' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `wtype` smallint(3) NOT NULL DEFAULT '0',
  `nelements` smallint(3) NOT NULL DEFAULT '1',
  `nattachments` smallint(3) NOT NULL DEFAULT '0',
  `phase` tinyint(2) NOT NULL DEFAULT '0',
  `format` tinyint(2) NOT NULL DEFAULT '0',
  `gradingstrategy` tinyint(2) NOT NULL DEFAULT '1',
  `resubmit` tinyint(2) NOT NULL DEFAULT '0',
  `agreeassessments` tinyint(2) NOT NULL DEFAULT '0',
  `hidegrades` tinyint(2) NOT NULL DEFAULT '0',
  `anonymous` tinyint(2) NOT NULL DEFAULT '0',
  `includeself` tinyint(2) NOT NULL DEFAULT '0',
  `maxbytes` bigint(10) NOT NULL DEFAULT '100000',
  `submissionstart` bigint(10) NOT NULL DEFAULT '0',
  `assessmentstart` bigint(10) NOT NULL DEFAULT '0',
  `submissionend` bigint(10) NOT NULL DEFAULT '0',
  `assessmentend` bigint(10) NOT NULL DEFAULT '0',
  `releasegrades` bigint(10) NOT NULL DEFAULT '0',
  `grade` smallint(3) NOT NULL DEFAULT '0',
  `gradinggrade` smallint(3) NOT NULL DEFAULT '0',
  `ntassessments` smallint(3) NOT NULL DEFAULT '0',
  `assessmentcomps` smallint(3) NOT NULL DEFAULT '2',
  `nsassessments` smallint(3) NOT NULL DEFAULT '0',
  `overallocation` smallint(3) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `teacherweight` smallint(3) NOT NULL DEFAULT '1',
  `showleaguetable` smallint(3) NOT NULL DEFAULT '0',
  `usepassword` smallint(3) NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL DEFAULT '',
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workold_cou_ix` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop table to be dropped later in Moodle 2.x' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_rubrics_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_rubrics_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `elementno` bigint(10) NOT NULL DEFAULT '0',
  `rubricno` smallint(3) NOT NULL DEFAULT '0',
  `description` longtext NOT NULL,
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workrubrold_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_rubrics table to be dropped later in Moodle ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_stockcomments_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_stockcomments_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `elementno` bigint(10) NOT NULL DEFAULT '0',
  `comments` longtext NOT NULL,
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_workstocold_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_stockcomments table to be dropped later in M' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_submissions`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_submissions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL,
  `example` tinyint(2) DEFAULT '0',
  `authorid` bigint(10) NOT NULL,
  `timecreated` bigint(10) NOT NULL,
  `timemodified` bigint(10) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext,
  `contentformat` smallint(3) NOT NULL DEFAULT '0',
  `contenttrust` smallint(3) NOT NULL DEFAULT '0',
  `attachment` tinyint(2) DEFAULT '0',
  `grade` decimal(10,5) DEFAULT NULL,
  `gradeover` decimal(10,5) DEFAULT NULL,
  `gradeoverby` bigint(10) DEFAULT NULL,
  `feedbackauthor` longtext,
  `feedbackauthorformat` smallint(3) DEFAULT '0',
  `timegraded` bigint(10) DEFAULT NULL,
  `published` tinyint(2) DEFAULT '0',
  `late` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_worksubm_wor_ix` (`workshopid`),
  KEY `mdl_worksubm_gra_ix` (`gradeoverby`),
  KEY `mdl_worksubm_aut_ix` (`authorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Info about the submission and the aggregation of the grade f' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_workshop_submissions_old`
--

CREATE TABLE IF NOT EXISTS `mdl_workshop_submissions_old` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `workshopid` bigint(10) NOT NULL DEFAULT '0',
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `mailed` tinyint(2) NOT NULL DEFAULT '0',
  `description` longtext NOT NULL,
  `gradinggrade` smallint(3) NOT NULL DEFAULT '0',
  `finalgrade` smallint(3) NOT NULL DEFAULT '0',
  `late` smallint(3) NOT NULL DEFAULT '0',
  `nassessments` bigint(10) NOT NULL DEFAULT '0',
  `newplugin` varchar(28) DEFAULT NULL,
  `newid` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_worksubmold_use_ix` (`userid`),
  KEY `mdl_worksubmold_mai_ix` (`mailed`),
  KEY `mdl_worksubmold_wor_ix` (`workshopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Legacy workshop_submissions table to be dropped later in Moo' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
