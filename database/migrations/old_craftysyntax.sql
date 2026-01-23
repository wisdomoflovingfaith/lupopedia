-- =====================================================
-- Migration 1069: Create Live Help Legacy Tables
-- =====================================================
-- WHO: PORTUNUS Migration Steward
-- WHAT: Recreate the Sales Syntax (livehelp_*) tables inside LUPOPEDIA
-- WHEN: 2025-11-08
-- WHY: Provide schema parity before streaming legacy data
-- HOW: Creates legacy tables with modern storage defaults (InnoDB + utf8mb4)
-- Note: Structure only (no seed data). Import pipeline will populate records.
-- =====================================================
-- TODO: WOLFIE ID STANDARD — Primary/foreign keys must be BIGINT(20) UNSIGNED.
-- TODO: STORAGE ENGINE — Convert legacy MyISAM tables to InnoDB with utf8mb4 collation.
-- TODO: SEED DATA — Normalise seeded URLs/content to https://lupopedia.com and current defaults.

-- Table definitions (mirror database/schema/salessyntax.sql)

CREATE TABLE `livehelp_autoinvite` (
  `idnum` int(10) NOT NULL,
  `offline` int(1) NOT NULL DEFAULT 0,
  `isactive` char(1) NOT NULL DEFAULT '',
  `department` int(10) NOT NULL DEFAULT 0,
  `message` text DEFAULT NULL,
  `page` varchar(255) NOT NULL DEFAULT '',
  `visits` int(8) NOT NULL DEFAULT 0,
  `referer` varchar(255) NOT NULL DEFAULT '',
  `typeof` varchar(255) NOT NULL DEFAULT '',
  `seconds` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(10) NOT NULL DEFAULT 0,
  `socialpane` char(1) NOT NULL DEFAULT 'N',
  `excludemobile` char(1) NOT NULL DEFAULT 'N',
  `onlymobile` char(1) NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_autoinvite`
--

INSERT INTO `livehelp_autoinvite` (`idnum`, `offline`, `isactive`, `department`, `message`, `page`, `visits`, `referer`, `typeof`, `seconds`, `user_id`, `socialpane`, `excludemobile`, `onlymobile`) VALUES
(1, 0, 'Y', 0, '4', '', 0, '', 'layer', 30, 0, 'N', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_channels`
--

CREATE TABLE `livehelp_channels` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT 0,
  `statusof` char(1) NOT NULL DEFAULT '',
  `startdate` bigint(8) NOT NULL DEFAULT 0,
  `sessionid` varchar(40) NOT NULL DEFAULT '',
  `website` int(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_channels`
--

INSERT INTO `livehelp_channels` (`id`, `user_id`, `statusof`, `startdate`, `sessionid`, `website`) VALUES
(3, 6, 'P', 20251110191240, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_config`
--

CREATE TABLE `livehelp_config` (
  `version` varchar(25) NOT NULL DEFAULT '3.7.3',
  `site_title` varchar(100) NOT NULL DEFAULT '',
  `use_flush` varchar(10) NOT NULL DEFAULT 'YES',
  `membernum` int(8) NOT NULL DEFAULT 0,
  `show_typing` char(1) NOT NULL DEFAULT '',
  `webpath` varchar(255) NOT NULL DEFAULT '',
  `s_webpath` varchar(255) NOT NULL DEFAULT '',
  `speaklanguage` varchar(60) NOT NULL DEFAULT 'English',
  `scratch_space` text DEFAULT NULL,
  `admin_refresh` varchar(30) NOT NULL DEFAULT 'auto',
  `maxexe` int(5) DEFAULT 180,
  `refreshrate` int(5) NOT NULL DEFAULT 1,
  `chatmode` varchar(60) NOT NULL DEFAULT 'xmlhttp-flush-refresh',
  `adminsession` char(1) NOT NULL DEFAULT 'Y',
  `ignoreips` text DEFAULT NULL,
  `directoryid` varchar(32) NOT NULL DEFAULT '',
  `tracking` char(1) NOT NULL DEFAULT 'N',
  `colorscheme` varchar(30) NOT NULL DEFAULT 'white',
  `matchip` char(1) NOT NULL DEFAULT 'N',
  `gethostnames` char(1) NOT NULL DEFAULT 'N',
  `maxrecords` int(10) NOT NULL DEFAULT 75000,
  `maxreferers` int(10) NOT NULL DEFAULT 50,
  `maxvisits` int(10) NOT NULL DEFAULT 75,
  `maxmonths` int(10) NOT NULL DEFAULT 12,
  `maxoldhits` int(10) NOT NULL DEFAULT 1,
  `showgames` char(1) NOT NULL DEFAULT 'Y',
  `showsearch` char(1) NOT NULL DEFAULT 'Y',
  `showdirectory` char(1) NOT NULL DEFAULT 'Y',
  `usertracking` char(1) NOT NULL DEFAULT 'N',
  `resetbutton` char(1) NOT NULL DEFAULT 'N',
  `keywordtrack` char(1) NOT NULL DEFAULT 'N',
  `reftracking` char(1) NOT NULL DEFAULT 'N',
  `topkeywords` int(10) NOT NULL DEFAULT 50,
  `everythingelse` text DEFAULT NULL,
  `rememberusers` char(1) NOT NULL DEFAULT 'Y',
  `smtp_host` varchar(255) NOT NULL DEFAULT '',
  `smtp_username` varchar(60) NOT NULL DEFAULT '',
  `smtp_password` varchar(60) NOT NULL DEFAULT '',
  `owner_email` varchar(255) NOT NULL DEFAULT '',
  `topframeheight` int(8) NOT NULL DEFAULT 85,
  `topbackground` varchar(156) NOT NULL DEFAULT 'header_images/customersupports.png',
  `usecookies` char(1) NOT NULL DEFAULT 'Y',
  `smtp_portnum` int(10) NOT NULL DEFAULT 25,
  `showoperator` char(1) NOT NULL DEFAULT 'Y',
  `chatcolors` text DEFAULT NULL,
  `floatxy` varchar(42) NOT NULL DEFAULT '200|160',
  `sessiontimeout` int(8) NOT NULL DEFAULT 60,
  `theme` varchar(42) NOT NULL DEFAULT 'vanilla',
  `operatorstimeout` int(4) NOT NULL DEFAULT 4,
  `operatorssessionout` int(8) NOT NULL DEFAULT 45,
  `maxrequests` int(8) NOT NULL DEFAULT 99999,
  `ignoreagent` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_config`
--

INSERT INTO `livehelp_config` (`version`, `site_title`, `use_flush`, `membernum`, `show_typing`, `webpath`, `s_webpath`, `speaklanguage`, `scratch_space`, `admin_refresh`, `maxexe`, `refreshrate`, `chatmode`, `adminsession`, `ignoreips`, `directoryid`, `tracking`, `colorscheme`, `matchip`, `gethostnames`, `maxrecords`, `maxreferers`, `maxvisits`, `maxmonths`, `maxoldhits`, `showgames`, `showsearch`, `showdirectory`, `usertracking`, `resetbutton`, `keywordtrack`, `reftracking`, `topkeywords`, `everythingelse`, `rememberusers`, `smtp_host`, `smtp_username`, `smtp_password`, `owner_email`, `topframeheight`, `topbackground`, `usecookies`, `smtp_portnum`, `showoperator`, `chatcolors`, `floatxy`, `sessiontimeout`, `theme`, `operatorstimeout`, `operatorssessionout`, `maxrequests`, `ignoreagent`) VALUES
('3.7.3', 'Live Help!', 'YES', 0, 'Y', 'http://lupopedia.com/', 'https://lupopedia.com/', 'English', '\n Welcome to Sales Syntax Live Help \n\n All the administrative functions are located to the left of this text. \n \nYou can use this section to keep notes for yourself and other admins, etc. \n \nTo change the text that is located in this box just click on the small edit \nbutton on the top right corner of this box. \n        ', 'auto', 180, 1, 'xmlhttp-flush-refresh', 'Y', '', '', 'Y', 'white', 'N', 'N', 75000, 50, 75, 12, 1, 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', 50, 'NYYY', 'Y', '', 'captain', '1q2w3e4r!', 'lupopedia@gmail.com', 85, 'header_images/customersupports.png', 'Y', 25, 'Y', 'fefdcd,cbcefe,caedbe,cccbba,aecddc,EBBEAA,faacaa,fbddef,cfaaef,aedcbd,bbffff,fedabf;040662,240462,520500,404062,100321,662640,242642,151035,051411,442662,442022,200220;426446,224646,466286,828468,866482,484668,504342,224882,486882,824864,668266,444468', '200|160', 60, 'vanilla', 4, 45, 99999, '');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_departments`
--

CREATE TABLE `livehelp_departments` (
  `recno` int(5) NOT NULL,
  `nameof` varchar(30) NOT NULL DEFAULT '',
  `onlineimage` varchar(255) NOT NULL DEFAULT '',
  `offlineimage` varchar(255) NOT NULL DEFAULT '',
  `layerinvite` varchar(255) NOT NULL DEFAULT '',
  `requirename` char(1) NOT NULL DEFAULT '',
  `messageemail` varchar(60) NOT NULL DEFAULT '',
  `leaveamessage` varchar(10) NOT NULL DEFAULT '',
  `opening` text DEFAULT NULL,
  `offline` text DEFAULT NULL,
  `creditline` char(1) NOT NULL DEFAULT 'L',
  `imagemap` text DEFAULT NULL,
  `whilewait` text DEFAULT NULL,
  `timeout` int(5) NOT NULL DEFAULT 150,
  `leavetxt` text DEFAULT NULL,
  `topframeheight` int(10) NOT NULL DEFAULT 85,
  `topbackground` varchar(255) NOT NULL DEFAULT '',
  `botbackground` varchar(255) NOT NULL DEFAULT '',
  `midbackground` varchar(255) NOT NULL DEFAULT '',
  `topbackcolor` varchar(255) NOT NULL DEFAULT '',
  `midbackcolor` varchar(255) NOT NULL DEFAULT '',
  `botbackcolor` varchar(255) NOT NULL DEFAULT '',
  `colorscheme` varchar(255) NOT NULL DEFAULT '',
  `speaklanguage` varchar(60) NOT NULL DEFAULT '',
  `busymess` text DEFAULT NULL,
  `emailfun` char(1) NOT NULL DEFAULT 'Y',
  `dbfun` char(1) NOT NULL DEFAULT 'Y',
  `everythingelse` text DEFAULT NULL,
  `ordering` int(8) NOT NULL DEFAULT 0,
  `smiles` char(1) NOT NULL DEFAULT 'Y',
  `visible` int(1) NOT NULL DEFAULT 1,
  `theme` varchar(45) NOT NULL DEFAULT 'vanilla',
  `showtimestamp` char(1) NOT NULL DEFAULT 'N',
  `website` int(8) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_departments`
--

INSERT INTO `livehelp_departments` (`recno`, `nameof`, `onlineimage`, `offlineimage`, `layerinvite`, `requirename`, `messageemail`, `leaveamessage`, `opening`, `offline`, `creditline`, `imagemap`, `whilewait`, `timeout`, `leavetxt`, `topframeheight`, `topbackground`, `botbackground`, `midbackground`, `topbackcolor`, `midbackcolor`, `botbackcolor`, `colorscheme`, `speaklanguage`, `busymess`, `emailfun`, `dbfun`, `everythingelse`, `ordering`, `smiles`, `visible`, `theme`, `showtimestamp`, `website`) VALUES
(1, 'default', 'onoff_images/online1.gif', 'onoff_images/offline1.gif', 'dhtmlimage.gif', 'Y', 'lupopedia@gmail.com', 'YES', '<blockquote>Welcome to our Live Help. Please enter your name in the input box below to begin.</blockquote>', '<blockquote>Sorry no operators are currently online to provide Live support at this time.</blockquote>', 'L', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,400,197><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,157,213,257><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=237,157,400,257></MAP>', 'Please be patient while an operator is contacted... ', 150, '<h3><SPAN CLASS=wh>LEAVE A MESSAGE:</SPAN></h3>Please type in your comments/questions in the below box <br> and provide an e-mail address so we can get back to you', 75, 'header_images/customersupports.png', 'themes/vanilla/patch.png', 'themes/vanilla/softyellowomi.png', '#FFFFFF', '#FFFFDD', '#FFFFFF', 'white', '', '<blockquote>Sorry all operators are currently helping other clients and are unable to provide Live support at this time.<br>Would you like to continue to wait for an operator or leave a message?<br><table width=450><tr><td><a href=livehelp.php?page=livehelp.php&department=[department]&tab=1 target=_top><font size=+1>Continue to wait</font></a></td><td align=center><b>or</b></td><td><a href=leavemessage.php?department=[department]><font size=+1>Leave A Message</a></td></tr></table><blockquote>', 'Y', 'Y', NULL, 0, 'Y', 1, 'vanilla', 'N', 1);

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_emailque`
--

CREATE TABLE `livehelp_emailque` (
  `id` int(8) NOT NULL,
  `messageid` int(8) NOT NULL,
  `towho` varchar(60) NOT NULL,
  `dateof` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_emails`
--

CREATE TABLE `livehelp_emails` (
  `id` int(8) NOT NULL,
  `fromemail` varchar(60) NOT NULL,
  `subject` varchar(60) NOT NULL,
  `bodyof` text NOT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_identity_daily`
--

CREATE TABLE `livehelp_identity_daily` (
  `id` int(11) UNSIGNED NOT NULL,
  `isnamed` char(1) NOT NULL DEFAULT 'N',
  `groupidentity` int(11) NOT NULL DEFAULT 0,
  `groupusername` int(11) NOT NULL DEFAULT 0,
  `identity` varchar(100) NOT NULL DEFAULT '',
  `cookieid` varchar(40) NOT NULL DEFAULT '',
  `ipaddress` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `dateof` bigint(14) NOT NULL DEFAULT 0,
  `uservisits` int(10) NOT NULL DEFAULT 0,
  `seconds` int(10) NOT NULL DEFAULT 0,
  `useragent` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_identity_monthly`
--

CREATE TABLE `livehelp_identity_monthly` (
  `id` int(11) UNSIGNED NOT NULL,
  `isnamed` char(1) NOT NULL DEFAULT 'N',
  `groupidentity` int(11) NOT NULL DEFAULT 0,
  `groupusername` int(11) NOT NULL DEFAULT 0,
  `identity` varchar(100) NOT NULL DEFAULT '',
  `cookieid` varchar(40) NOT NULL DEFAULT '',
  `ipaddress` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `dateof` bigint(14) NOT NULL DEFAULT 0,
  `uservisits` int(10) NOT NULL DEFAULT 0,
  `seconds` int(10) NOT NULL DEFAULT 0,
  `useragent` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_keywords_daily`
--

CREATE TABLE `livehelp_keywords_daily` (
  `recno` int(11) NOT NULL,
  `parentrec` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `referer` varchar(255) NOT NULL DEFAULT '',
  `pageurl` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `dateof` int(8) NOT NULL DEFAULT 0,
  `levelvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `directvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_keywords_monthly`
--

CREATE TABLE `livehelp_keywords_monthly` (
  `recno` int(11) NOT NULL,
  `parentrec` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `referer` varchar(255) NOT NULL DEFAULT '',
  `pageurl` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `dateof` int(8) NOT NULL DEFAULT 0,
  `levelvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `directvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_layerinvites`
--

CREATE TABLE `livehelp_layerinvites` (
  `layerid` int(10) NOT NULL DEFAULT 0,
  `name` varchar(60) NOT NULL DEFAULT '',
  `imagename` varchar(60) NOT NULL DEFAULT '',
  `imagemap` text DEFAULT NULL,
  `department` varchar(60) NOT NULL DEFAULT '',
  `user` int(10) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_layerinvites`
--

INSERT INTO `livehelp_layerinvites` (`layerid`, `name`, `imagename`, `imagemap`, `department`, `user`) VALUES
(1, '', 'layer-Man_invite.gif', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,400,197><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,157,213,257><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=237,157,400,257></MAP>', '', 0),
(2, '', 'layer-Phone.gif', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,472,150><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=241,150,484,256><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=0,150,241,250></MAP>', '', 0),
(3, '', 'layer-Help_button.gif', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,400,197><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,157,213,257><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=237,157,400,257></MAP>', '', 0),
(4, '', 'layer-Woman_invite.png', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=126,71,429,172><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=311,5,440,45></MAP>', '', 0),
(5, '', 'layer-Subsilver.gif', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,419,216><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,216,319,279><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=326,218,429,280></MAP>', '', 0),
(6, '', 'layer-Help_buttonoffline.gif', '<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,400,197><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,157,213,257><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=237,157,400,257></MAP>', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_leads`
--

CREATE TABLE `livehelp_leads` (
  `id` int(8) NOT NULL,
  `email` varchar(90) NOT NULL,
  `phone` varchar(90) NOT NULL,
  `source` varchar(45) NOT NULL,
  `status` varchar(10) NOT NULL,
  `data` text NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `date_entered` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_leavemessage`
--

CREATE TABLE `livehelp_leavemessage` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(200) NOT NULL DEFAULT '',
  `department` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `dateof` bigint(14) NOT NULL DEFAULT 0,
  `sessiondata` text DEFAULT NULL,
  `deliminated` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_messages`
--

CREATE TABLE `livehelp_messages` (
  `id_num` int(10) NOT NULL,
  `message` text DEFAULT NULL,
  `channel` int(10) NOT NULL DEFAULT 0,
  `timeof` bigint(14) NOT NULL DEFAULT 0,
  `saidfrom` int(10) NOT NULL DEFAULT 0,
  `saidto` int(10) NOT NULL DEFAULT 0,
  `typeof` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_modules`
--

CREATE TABLE `livehelp_modules` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `adminpath` varchar(255) NOT NULL DEFAULT '',
  `query_string` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_modules`
--

INSERT INTO `livehelp_modules` (`id`, `name`, `path`, `adminpath`, `query_string`) VALUES
(1, 'Live Help!', 'livehelp.php', '', ''),
(2, 'Contact', 'leavemessage.php', '', ''),
(3, 'Q & A', 'user_qa.php', 'qa.php', '');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_modules_dep`
--

CREATE TABLE `livehelp_modules_dep` (
  `rec` int(10) NOT NULL,
  `departmentid` int(10) NOT NULL DEFAULT 0,
  `modid` int(10) NOT NULL DEFAULT 0,
  `ordernum` int(8) NOT NULL DEFAULT 0,
  `isactive` char(1) NOT NULL DEFAULT 'N',
  `defaultset` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_modules_dep`
--

INSERT INTO `livehelp_modules_dep` (`rec`, `departmentid`, `modid`, `ordernum`, `isactive`, `defaultset`) VALUES
(1, 1, 1, 1, 'N', ''),
(2, 1, 2, 2, 'N', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_operator_channels`
--

CREATE TABLE `livehelp_operator_channels` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT 0,
  `channel` int(10) NOT NULL DEFAULT 0,
  `userid` int(10) NOT NULL DEFAULT 0,
  `statusof` char(1) NOT NULL DEFAULT '',
  `startdate` bigint(8) NOT NULL DEFAULT 0,
  `bgcolor` varchar(10) NOT NULL DEFAULT '000000',
  `txtcolor` varchar(10) NOT NULL DEFAULT '000000',
  `channelcolor` varchar(10) NOT NULL DEFAULT 'F7FAFF',
  `txtcolor_alt` varchar(10) NOT NULL DEFAULT '000000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_operator_departments`
--

CREATE TABLE `livehelp_operator_departments` (
  `recno` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT 0,
  `department` int(10) NOT NULL DEFAULT 0,
  `extra` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_operator_departments`
--

INSERT INTO `livehelp_operator_departments` (`recno`, `user_id`, `department`, `extra`) VALUES
(1, 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_operator_history`
--

CREATE TABLE `livehelp_operator_history` (
  `id` int(11) UNSIGNED NOT NULL,
  `opid` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `action` varchar(60) NOT NULL DEFAULT '',
  `dateof` bigint(14) NOT NULL DEFAULT 0,
  `sessionid` varchar(40) NOT NULL DEFAULT '',
  `transcriptid` int(10) NOT NULL DEFAULT 0,
  `totaltime` int(10) NOT NULL DEFAULT 0,
  `channel` int(10) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_operator_history`
--

INSERT INTO `livehelp_operator_history` (`id`, `opid`, `action`, `dateof`, `sessionid`, `transcriptid`, `totaltime`, `channel`) VALUES
(1, 1, 'login', 20251110173812, '9ec900e34645b1ecd0b6bbcb4762da96', 0, 0, 0),
(2, 1, 'login', 20251110174745, '37d8444428c18c4eddb29535e4c6019a', 0, 0, 0),
(3, 1, 'Started Monitoring Traffic', 20251110174932, '37d8444428c18c4eddb29535e4c6019a', 0, 0, 0),
(4, 1, 'startchat', 20251110175141, '', 0, 0, 1),
(5, 1, 'login', 20251110180303, '0dffa1920500a3eb41d1d47e5af63a49', 0, 0, 0),
(6, 1, 'Stopchat', 20251110181001, '', 1, 0, 0),
(7, 1, 'startchat', 20251110181020, '', 0, 0, 1),
(8, 1, 'startchat', 20251110181804, '', 0, 0, 1),
(9, 1, 'Stopchat', 20251110184125, '', 2, 0, 0),
(10, 1, 'startchat', 20251110184404, '', 0, 0, 1),
(11, 1, 'startchat', 20251110184944, '', 0, 0, 1),
(12, 1, 'startchat', 20251110185041, '', 0, 0, 1),
(13, 1, 'login', 20251110191219, '94287cdd0bfa2fc18bdb97492a1a5d44', 0, 0, 0),
(14, 1, 'startchat', 20251110191304, '', 0, 0, 3),
(15, 1, 'Stopchat', 20251110191430, '', 3, 0, 0),
(16, 1, 'Stopped Monitoring Traffic', 20251110191305, '94287cdd0bfa2fc18bdb97492a1a5d44', 0, 5013, 0);

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_paths_firsts`
--

CREATE TABLE `livehelp_paths_firsts` (
  `id` int(11) UNSIGNED NOT NULL,
  `visit_recno` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `exit_recno` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `dateof` int(8) NOT NULL DEFAULT 0,
  `visits` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_paths_monthly`
--

CREATE TABLE `livehelp_paths_monthly` (
  `id` int(11) UNSIGNED NOT NULL,
  `visit_recno` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `exit_recno` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `dateof` int(8) NOT NULL DEFAULT 0,
  `visits` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_qa`
--

CREATE TABLE `livehelp_qa` (
  `recno` int(10) NOT NULL,
  `parent` int(10) NOT NULL DEFAULT 0,
  `question` text DEFAULT NULL,
  `typeof` varchar(10) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `username` varchar(60) NOT NULL DEFAULT '',
  `ordernum` int(10) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_questions`
--

CREATE TABLE `livehelp_questions` (
  `id` int(10) NOT NULL,
  `department` int(10) NOT NULL DEFAULT 0,
  `ordering` int(8) NOT NULL DEFAULT 0,
  `headertext` text DEFAULT NULL,
  `fieldtype` varchar(30) NOT NULL DEFAULT '',
  `options` text DEFAULT NULL,
  `flags` varchar(60) NOT NULL DEFAULT '',
  `module` varchar(60) NOT NULL DEFAULT '',
  `required` char(1) NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_questions`
--

INSERT INTO `livehelp_questions` (`id`, `department`, `ordering`, `headertext`, `fieldtype`, `options`, `flags`, `module`, `required`) VALUES
(1, 1, 0, 'E-mail:', 'email', '', '', 'leavemessage', 'Y'),
(2, 1, 0, 'Question:', 'textarea', '', '', 'leavemessage', 'N'),
(3, 1, 0, 'Name', 'username', '', '', 'livehelp', 'N'),
(5, 1, 1, 'Question', 'textarea', '', '', 'livehelp', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_quick`
--

CREATE TABLE `livehelp_quick` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `typeof` varchar(30) NOT NULL DEFAULT '',
  `message` text DEFAULT NULL,
  `visiblity` varchar(20) NOT NULL DEFAULT '',
  `department` varchar(60) NOT NULL DEFAULT '0',
  `user` int(10) NOT NULL DEFAULT 0,
  `ishtml` varchar(3) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_referers_daily`
--

CREATE TABLE `livehelp_referers_daily` (
  `recno` int(11) NOT NULL,
  `pageurl` varchar(255) NOT NULL DEFAULT '0',
  `dateof` int(8) NOT NULL DEFAULT 0,
  `levelvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `directvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `parentrec` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `level` int(10) NOT NULL DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_referers_monthly`
--

CREATE TABLE `livehelp_referers_monthly` (
  `recno` int(11) NOT NULL,
  `pageurl` varchar(255) NOT NULL DEFAULT '0',
  `dateof` int(8) NOT NULL DEFAULT 0,
  `levelvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `directvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `parentrec` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `level` int(10) NOT NULL DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_sessions`
--

CREATE TABLE `livehelp_sessions` (
  `session_id` varchar(100) NOT NULL DEFAULT '',
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_smilies`
--

CREATE TABLE `livehelp_smilies` (
  `smilies_id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `smile_url` varchar(100) DEFAULT NULL,
  `emoticon` varchar(75) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_smilies`
--

INSERT INTO `livehelp_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`) VALUES
(1, ':D', 'icon_biggrin.gif', 'Very Happy'),
(2, ':-D', 'icon_biggrin.gif', 'Very Happy'),
(3, ':grin:', 'icon_biggrin.gif', 'Very Happy'),
(4, ':)', 'icon_smile.gif', 'Smile'),
(5, ':-)', 'icon_smile.gif', 'Smile'),
(6, ':smile:', 'icon_smile.gif', 'Smile'),
(7, ':(', 'icon_sad.gif', 'Sad'),
(8, ':-(', 'icon_sad.gif', 'Sad'),
(9, ':sad:', 'icon_sad.gif', 'Sad'),
(10, ':o', 'icon_surprised.gif', 'Surprised'),
(11, ':-o', 'icon_surprised.gif', 'Surprised'),
(12, ':eek:', 'icon_surprised.gif', 'Surprised'),
(13, ':shock:', 'icon_eek.gif', 'Shocked'),
(14, ':?', 'icon_confused.gif', 'Confused'),
(15, ':-?', 'icon_confused.gif', 'Confused'),
(16, ':???:', 'icon_confused.gif', 'Confused'),
(17, '8)', 'icon_cool.gif', 'Cool'),
(18, '8-)', 'icon_cool.gif', 'Cool'),
(19, ':cool:', 'icon_cool.gif', 'Cool'),
(20, ':lol:', 'icon_lol.gif', 'Laughing'),
(21, ':x', 'icon_mad.gif', 'Mad'),
(22, ':-x', 'icon_mad.gif', 'Mad'),
(23, ':mad:', 'icon_mad.gif', 'Mad'),
(24, ':P', 'icon_razz.gif', 'Razz'),
(25, ':-P', 'icon_razz.gif', 'Razz'),
(26, ':razz:', 'icon_razz.gif', 'Razz'),
(27, ':oops:', 'icon_redface.gif', 'Embarassed'),
(28, ':cry:', 'icon_cry.gif', 'Crying or Very sad'),
(29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad'),
(30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil'),
(31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes'),
(32, ':wink:', 'icon_wink.gif', 'Wink'),
(33, ';)', 'icon_wink.gif', 'Wink'),
(34, ';-)', 'icon_wink.gif', 'Wink'),
(35, ':!:', 'icon_exclaim.gif', 'Exclamation'),
(36, ':?:', 'icon_question.gif', 'Question'),
(37, ':idea:', 'icon_idea.gif', 'Idea'),
(38, ':arrow:', 'icon_arrow.gif', 'Arrow'),
(39, ':|', 'icon_neutral.gif', 'Neutral'),
(40, ':-|', 'icon_neutral.gif', 'Neutral'),
(41, ':neutral:', 'icon_neutral.gif', 'Neutral'),
(42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_transcripts`
--

CREATE TABLE `livehelp_transcripts` (
  `recno` int(10) NOT NULL,
  `who` varchar(100) NOT NULL DEFAULT '',
  `endtime` bigint(14) DEFAULT NULL,
  `transcript` text DEFAULT NULL,
  `sessionid` varchar(40) NOT NULL DEFAULT '',
  `sessiondata` text DEFAULT NULL,
  `department` int(10) NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL DEFAULT '',
  `starttime` bigint(14) NOT NULL DEFAULT 0,
  `duration` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `operators` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_transcripts`
--

INSERT INTO `livehelp_transcripts` (`recno`, `who`, `endtime`, `transcript`, `sessionid`, `sessiondata`, `department`, `email`, `starttime`, `duration`, `operators`) VALUES
(1, 'fdsa', 20251110175157, ' <b>fdsa : </b> <b>Question</b><br>fdsa <br> <b>captain : </b> How may I help You? <br> <b>captain : </b> fdsa fdsa fdsa fds afds afdsa fds afdsa fd <br>', 'b9a61c9e3f85e5a1c030bc1944506c16', '<table width=100%><tr><td colspan=2 bgcolor=DDDDDD>User Information</td></tr><tr><td align=left><b>Referer:</b> <a href=http://lupopedia.com/what_was_crafty_syntax.php target=_blank>http://lupopedia.com/what_was_crafty_syntax.php</a><br><b>Status:</b>chat<br><b>Departments</b> default<br><b>E-mail :</b><br><b>SessionID :</b>b9a61c9e3f85e5a1c030bc1944506c16<br><b>identity :</b>96.3.230-cslhVISITOR<br><b>HostName:</b>host_lookup_not_enabled<br><b>User Agent :</b>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0<br><b>Browser :</b>Edge<br><b>Browser_Version :</b>142.0.0.0<br><b>Ip :</b>96.3.230.164<br><b>Cookied :</b>Y<br><b>New Session :</b>N<br><b> Question:</b> <br><font color=000000>fdsa</font>', 1, '', 20251110175141, 16, 'X1X'),
(2, 'fdsa', 20251110184120, ' <b>captain : </b> How may I help You? <br> <b>captain : </b> fdsaf fds afd f f f sfdsa fds afds fdsa <br> <b>fdsa : </b> fdsafd <br>', 'b9a61c9e3f85e5a1c030bc1944506c16', '<table width=100%><tr><td colspan=2 bgcolor=DDDDDD>User Information</td></tr><tr><td align=left><b>Referer:</b> <a href=http://lupopedia.com/what_was_crafty_syntax.php target=_blank>http://lupopedia.com/what_was_crafty_syntax.php</a><br><b>Status:</b>chat<br><b>Departments</b> default<br><b>E-mail :</b><br><b>SessionID :</b>b9a61c9e3f85e5a1c030bc1944506c16<br><b>identity :</b>96.3.230-cslhVISITOR<br><b>HostName:</b>host_lookup_not_enabled<br><b>User Agent :</b>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0<br><b>Browser :</b>Edge<br><b>Browser_Version :</b>142.0.0.0<br><b>Ip :</b>96.3.230.164<br><b>Cookied :</b>Y<br><b>New Session :</b>N<br>', 1, '', 20251110181804, 1396, 'X1X'),
(3, 'fdsaf', 20251110191335, ' <b>fdsaf : </b> <b>Question</b><br>fdsaf <br> <b>captain : </b> How may I help You? <br> <b>captain : </b> fdsa fds fds afds fdsa fdsa fdsa fdsa  <br> <b>fdsaf : </b> this is my life story  <br>', 'd50d366e8e40f632be429c1e6c981885', '<table width=100%><tr><td colspan=2 bgcolor=DDDDDD>User Information</td></tr><tr><td align=left><b>Referer:</b> <a href=http://lupopedia.com/lh/admin_options.php?tab=live target=_blank>http://lupopedia.com/lh/admin_options.php?tab=live</a><br><b>Status:</b>chat<br><b>Departments</b> default<br><b>E-mail :</b><br><b>SessionID :</b>d50d366e8e40f632be429c1e6c981885<br><b>identity :</b>96.3.230-cslhVISITOR<br><b>HostName:</b>host_lookup_not_enabled<br><b>User Agent :</b>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36<br><b>Browser :</b>Chrome<br><b>Browser_Version :</b>142.0.0.0<br><b>Ip :</b>96.3.230.164<br><b>Cookied :</b>Y<br><b>New Session :</b>N<br><b> Question:</b> <br><font color=000000>fdsaf</font>', 1, '', 20251110191304, 31, 'X1X');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_users`
--

CREATE TABLE `livehelp_users` (
  `user_id` int(10) NOT NULL,
  `lastaction` bigint(14) DEFAULT 0,
  `username` varchar(30) NOT NULL DEFAULT '',
  `displayname` varchar(42) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `isonline` char(1) NOT NULL DEFAULT '',
  `isoperator` char(1) NOT NULL DEFAULT 'N',
  `onchannel` int(10) NOT NULL DEFAULT 0,
  `isadmin` char(1) NOT NULL DEFAULT 'N',
  `department` int(5) NOT NULL DEFAULT 0,
  `identity` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT '',
  `isnamed` char(1) NOT NULL DEFAULT 'N',
  `showedup` bigint(14) DEFAULT NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `camefrom` varchar(255) NOT NULL DEFAULT '',
  `show_arrival` char(1) NOT NULL DEFAULT 'N',
  `user_alert` char(1) NOT NULL DEFAULT 'A',
  `auto_invite` char(1) NOT NULL DEFAULT 'N',
  `istyping` char(1) NOT NULL DEFAULT '3',
  `visits` int(8) NOT NULL DEFAULT 0,
  `jsrn` int(5) NOT NULL DEFAULT 0,
  `hostname` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `ipaddress` varchar(255) NOT NULL DEFAULT '',
  `sessionid` varchar(40) NOT NULL DEFAULT '',
  `typing_alert` char(1) NOT NULL DEFAULT 'N',
  `authenticated` char(1) NOT NULL DEFAULT '',
  `cookied` char(1) NOT NULL DEFAULT 'N',
  `sessiondata` text DEFAULT NULL,
  `expires` bigint(14) NOT NULL DEFAULT 0,
  `greeting` text DEFAULT NULL,
  `photo` varchar(255) NOT NULL DEFAULT '',
  `chataction` bigint(14) DEFAULT 0,
  `new_session` char(1) NOT NULL DEFAULT 'Y',
  `showtype` int(10) NOT NULL DEFAULT 1,
  `chattype` char(1) NOT NULL DEFAULT 'Y',
  `externalchats` varchar(255) NOT NULL DEFAULT '',
  `layerinvite` int(10) NOT NULL DEFAULT 0,
  `askquestions` char(1) NOT NULL DEFAULT 'Y',
  `showvisitors` char(1) NOT NULL DEFAULT 'N',
  `cookieid` varchar(40) NOT NULL DEFAULT '',
  `cellphone` varchar(255) NOT NULL DEFAULT '',
  `lastcalled` bigint(14) NOT NULL DEFAULT 0,
  `ismobile` char(1) DEFAULT 'N',
  `cell_invite` char(1) DEFAULT 'N',
  `useimage` char(1) NOT NULL DEFAULT 'N',
  `firstdepartment` int(11) NOT NULL DEFAULT 0,
  `alertchat` varchar(45) NOT NULL DEFAULT '',
  `alerttyping` varchar(45) NOT NULL DEFAULT '',
  `alertinsite` varchar(45) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_users`
--

INSERT INTO `livehelp_users` (`user_id`, `lastaction`, `username`, `displayname`, `password`, `isonline`, `isoperator`, `onchannel`, `isadmin`, `department`, `identity`, `status`, `isnamed`, `showedup`, `email`, `camefrom`, `show_arrival`, `user_alert`, `auto_invite`, `istyping`, `visits`, `jsrn`, `hostname`, `useragent`, `ipaddress`, `sessionid`, `typing_alert`, `authenticated`, `cookied`, `sessiondata`, `expires`, `greeting`, `photo`, `chataction`, `new_session`, `showtype`, `chattype`, `externalchats`, `layerinvite`, `askquestions`, `showvisitors`, `cookieid`, `cellphone`, `lastcalled`, `ismobile`, `cell_invite`, `useimage`, `firstdepartment`, `alertchat`, `alerttyping`, `alertinsite`) VALUES
(1, 20251110191420, 'captain', 'captain', MD5('password'), 'N', 'Y', 0, 'Y', 0, '96.3.230-cslhOPERATOR', 'offline', 'Y', 0, 'lupopedia@gmail.com', '', 'N', 'A', 'Y', '3', 4, 1, 'host_lookup_not_enabled', '', '96.3.230.164', '94287cdd0bfa2fc18bdb97492a1a5d44', 'N', 'Y', 'Y', NULL, 20251110193420, 'How may I help You?', '', 0, 'N', 1, 'x', '', 0, 'Y', 'N', '8f61e951668fa84f7e676491dad3d994', '', 0, 'N', 'N', 'N', 0, 'new_chats.wav', 'click_x.wav', 'youve_got_visitors.wav'),
(6, 20251110192414, 'fdsaf', '', '', '', 'N', 3, 'N', 1, '96.3.230-cslhVISITOR', 'stopped', 'Y', 20251110191234, '', 'http://lupopedia.com/lh/admin_options.php?tab=live', 'N', '1', 'N', '3', 77, 2, 'host_lookup_not_enabled', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '96.3.230.164', 'd50d366e8e40f632be429c1e6c981885', 'N', '', 'Y', '', 20251110194414, NULL, '', 20251110191429, 'N', 1, '', '', 0, 'Y', 'N', '8f61e951668fa84f7e676491dad3d994', '', 0, 'N', 'N', 'N', 1, '', '', ''),
(7, 20251110192452, '96.3.230.164', '', '', '', 'N', -1, 'N', 1, '96.3.230-cslhVISITOR', 'Visiting', 'N', 20251110192450, '', 'http://lupopedia.com/index.php', 'N', '0', 'N', '3', 2, 0, 'host_lookup_not_enabled', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '96.3.230.164', 'b9a61c9e3f85e5a1c030bc1944506c16', 'N', '', 'Y', NULL, 20251110194452, NULL, '', 0, 'N', 1, 'Y', '', 0, 'Y', 'N', '7558b5ff5eb9ea9de782537717f7b855', '', 0, 'N', 'N', 'N', 1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_visits_daily`
--

CREATE TABLE `livehelp_visits_daily` (
  `recno` int(11) NOT NULL,
  `pageurl` varchar(255) NOT NULL DEFAULT '0',
  `dateof` int(8) NOT NULL DEFAULT 0,
  `levelvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `directvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `parentrec` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `level` int(10) NOT NULL DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_visits_monthly`
--

CREATE TABLE `livehelp_visits_monthly` (
  `recno` int(11) NOT NULL,
  `pageurl` varchar(255) NOT NULL DEFAULT '0',
  `dateof` int(8) NOT NULL DEFAULT 0,
  `levelvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `directvisits` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `parentrec` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `level` int(10) NOT NULL DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_visit_track`
--

CREATE TABLE `livehelp_visit_track` (
  `recno` int(10) NOT NULL,
  `sessionid` varchar(40) NOT NULL DEFAULT '0',
  `location` varchar(255) NOT NULL DEFAULT '',
  `page` bigint(14) NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL DEFAULT '',
  `whendone` bigint(14) NOT NULL DEFAULT 0,
  `referrer` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_visit_track`
--

INSERT INTO `livehelp_visit_track` (`recno`, `sessionid`, `location`, `page`, `title`, `whendone`, `referrer`) VALUES
(7, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/what_was_crafty_syntax.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110191521, 'http://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby'),
(6, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110191434, 'http://lupopedia.com/lh/wentaway.php?savepage=1&department=1'),
(5, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110191234, 'http://lupopedia.com/lh/admin_options.php?tab=live'),
(8, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/index.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110191609, 'http://lupopedia.com/what_was_crafty_syntax.php'),
(9, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/what_was_crafty_syntax.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110191839, 'http://lupopedia.com/index.php'),
(10, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/salessyntax_changelog.php', 1, 'Sales Syntax Changelog - LUPOPEDIA - Ontology Knowledge Platform', 20251110191850, 'http://lupopedia.com/what_was_crafty_syntax.php'),
(11, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/index.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110192208, 'http://lupopedia.com/salessyntax_changelog.php'),
(12, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/what_was_crafty_syntax.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110192357, 'http://lupopedia.com/index.php'),
(13, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/salessyntax_changelog.php', 1, 'Sales Syntax Changelog - LUPOPEDIA - Ontology Knowledge Platform', 20251110192408, 'http://lupopedia.com/what_was_crafty_syntax.php'),
(14, 'd50d366e8e40f632be429c1e6c981885', 'http://lupopedia.com/what_was_crafty_syntax.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110192411, 'http://lupopedia.com/index.php'),
(15, 'b9a61c9e3f85e5a1c030bc1944506c16', 'http://lupopedia.com/what_was_crafty_syntax.php', 1, 'LUPOPEDIA - Ontology Knowledge Platform', 20251110192450, 'http://lupopedia.com/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `livehelp_websites`
--

CREATE TABLE `livehelp_websites` (
  `id` int(11) NOT NULL,
  `site_name` varchar(45) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `defaultdepartment` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `livehelp_websites`
--

INSERT INTO `livehelp_websites` (`id`, `site_name`, `site_url`, `defaultdepartment`) VALUES
(1, 'Your Website', 'http://lupopedia.com/lh', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `livehelp_autoinvite`
--
ALTER TABLE `livehelp_autoinvite`
  ADD PRIMARY KEY (`idnum`);

--
-- Indexes for table `livehelp_channels`
--
ALTER TABLE `livehelp_channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livehelp_config`
--
ALTER TABLE `livehelp_config`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `livehelp_departments`
--
ALTER TABLE `livehelp_departments`
  ADD PRIMARY KEY (`recno`);

--
-- Indexes for table `livehelp_emailque`
--
ALTER TABLE `livehelp_emailque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messageid` (`messageid`);

--
-- Indexes for table `livehelp_emails`
--
ALTER TABLE `livehelp_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livehelp_identity_daily`
--
ALTER TABLE `livehelp_identity_daily`
  ADD PRIMARY KEY (`id`),
  ADD KEY `isnamed` (`isnamed`),
  ADD KEY `groupidentity` (`groupidentity`),
  ADD KEY `groupusername` (`groupusername`),
  ADD KEY `identity` (`identity`),
  ADD KEY `cookieid` (`cookieid`),
  ADD KEY `username` (`username`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_identity_monthly`
--
ALTER TABLE `livehelp_identity_monthly`
  ADD PRIMARY KEY (`id`),
  ADD KEY `isnamed` (`isnamed`),
  ADD KEY `groupidentity` (`groupidentity`),
  ADD KEY `groupusername` (`groupusername`),
  ADD KEY `identity` (`identity`),
  ADD KEY `cookieid` (`cookieid`),
  ADD KEY `username` (`username`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_keywords_daily`
--
ALTER TABLE `livehelp_keywords_daily`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `department` (`department`),
  ADD KEY `levelvisits` (`levelvisits`),
  ADD KEY `dateof` (`dateof`),
  ADD KEY `referer` (`referer`);

--
-- Indexes for table `livehelp_keywords_monthly`
--
ALTER TABLE `livehelp_keywords_monthly`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `department` (`department`),
  ADD KEY `levelvisits` (`levelvisits`),
  ADD KEY `dateof` (`dateof`),
  ADD KEY `referer` (`referer`);

--
-- Indexes for table `livehelp_layerinvites`
--
ALTER TABLE `livehelp_layerinvites`
  ADD PRIMARY KEY (`layerid`);

--
-- Indexes for table `livehelp_leads`
--
ALTER TABLE `livehelp_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`,`source`,`status`);

--
-- Indexes for table `livehelp_leavemessage`
--
ALTER TABLE `livehelp_leavemessage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `livehelp_messages`
--
ALTER TABLE `livehelp_messages`
  ADD PRIMARY KEY (`id_num`),
  ADD KEY `channel` (`channel`),
  ADD KEY `timeof` (`timeof`);

--
-- Indexes for table `livehelp_modules`
--
ALTER TABLE `livehelp_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livehelp_modules_dep`
--
ALTER TABLE `livehelp_modules_dep`
  ADD PRIMARY KEY (`rec`);

--
-- Indexes for table `livehelp_operator_channels`
--
ALTER TABLE `livehelp_operator_channels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel` (`channel`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `livehelp_operator_departments`
--
ALTER TABLE `livehelp_operator_departments`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `livehelp_operator_history`
--
ALTER TABLE `livehelp_operator_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opid` (`opid`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_paths_firsts`
--
ALTER TABLE `livehelp_paths_firsts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_recno` (`visit_recno`,`dateof`,`visits`);

--
-- Indexes for table `livehelp_paths_monthly`
--
ALTER TABLE `livehelp_paths_monthly`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_recno` (`visit_recno`),
  ADD KEY `dateof` (`dateof`),
  ADD KEY `visits` (`visits`);

--
-- Indexes for table `livehelp_qa`
--
ALTER TABLE `livehelp_qa`
  ADD PRIMARY KEY (`recno`);

--
-- Indexes for table `livehelp_questions`
--
ALTER TABLE `livehelp_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `livehelp_quick`
--
ALTER TABLE `livehelp_quick`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livehelp_referers_daily`
--
ALTER TABLE `livehelp_referers_daily`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `department` (`department`),
  ADD KEY `pageurl` (`pageurl`),
  ADD KEY `parentrec` (`parentrec`),
  ADD KEY `levelvisits` (`levelvisits`),
  ADD KEY `directvisits` (`directvisits`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_referers_monthly`
--
ALTER TABLE `livehelp_referers_monthly`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `department` (`department`),
  ADD KEY `pageurl` (`pageurl`),
  ADD KEY `parentrec` (`parentrec`),
  ADD KEY `levelvisits` (`levelvisits`),
  ADD KEY `directvisits` (`directvisits`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_sessions`
--
ALTER TABLE `livehelp_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `expires` (`expires`);

--
-- Indexes for table `livehelp_smilies`
--
ALTER TABLE `livehelp_smilies`
  ADD PRIMARY KEY (`smilies_id`);

--
-- Indexes for table `livehelp_transcripts`
--
ALTER TABLE `livehelp_transcripts`
  ADD PRIMARY KEY (`recno`);

--
-- Indexes for table `livehelp_users`
--
ALTER TABLE `livehelp_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `expires` (`expires`),
  ADD KEY `sessionid` (`sessionid`);

--
-- Indexes for table `livehelp_visits_daily`
--
ALTER TABLE `livehelp_visits_daily`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `department` (`department`),
  ADD KEY `pageurl` (`pageurl`),
  ADD KEY `parentrec` (`parentrec`),
  ADD KEY `levelvisits` (`levelvisits`),
  ADD KEY `directvisits` (`directvisits`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_visits_monthly`
--
ALTER TABLE `livehelp_visits_monthly`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `department` (`department`),
  ADD KEY `pageurl` (`pageurl`),
  ADD KEY `parentrec` (`parentrec`),
  ADD KEY `levelvisits` (`levelvisits`),
  ADD KEY `directvisits` (`directvisits`),
  ADD KEY `dateof` (`dateof`);

--
-- Indexes for table `livehelp_visit_track`
--
ALTER TABLE `livehelp_visit_track`
  ADD PRIMARY KEY (`recno`),
  ADD KEY `sessionid` (`sessionid`),
  ADD KEY `location` (`location`),
  ADD KEY `page` (`page`),
  ADD KEY `whendone` (`whendone`);

--
-- Indexes for table `livehelp_websites`
--
ALTER TABLE `livehelp_websites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `livehelp_autoinvite`
--
ALTER TABLE `livehelp_autoinvite`
  MODIFY `idnum` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `livehelp_channels`
--
ALTER TABLE `livehelp_channels`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `livehelp_departments`
--
ALTER TABLE `livehelp_departments`
  MODIFY `recno` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `livehelp_emailque`
--
ALTER TABLE `livehelp_emailque`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_emails`
--
ALTER TABLE `livehelp_emails`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_identity_daily`
--
ALTER TABLE `livehelp_identity_daily`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_identity_monthly`
--
ALTER TABLE `livehelp_identity_monthly`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_keywords_daily`
--
ALTER TABLE `livehelp_keywords_daily`
  MODIFY `recno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_keywords_monthly`
--
ALTER TABLE `livehelp_keywords_monthly`
  MODIFY `recno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_leads`
--
ALTER TABLE `livehelp_leads`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_leavemessage`
--
ALTER TABLE `livehelp_leavemessage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_messages`
--
ALTER TABLE `livehelp_messages`
  MODIFY `id_num` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `livehelp_modules`
--
ALTER TABLE `livehelp_modules`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `livehelp_modules_dep`
--
ALTER TABLE `livehelp_modules_dep`
  MODIFY `rec` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `livehelp_operator_channels`
--
ALTER TABLE `livehelp_operator_channels`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `livehelp_operator_departments`
--
ALTER TABLE `livehelp_operator_departments`
  MODIFY `recno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `livehelp_operator_history`
--
ALTER TABLE `livehelp_operator_history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `livehelp_paths_firsts`
--
ALTER TABLE `livehelp_paths_firsts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_paths_monthly`
--
ALTER TABLE `livehelp_paths_monthly`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_qa`
--
ALTER TABLE `livehelp_qa`
  MODIFY `recno` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_questions`
--
ALTER TABLE `livehelp_questions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `livehelp_quick`
--
ALTER TABLE `livehelp_quick`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_referers_daily`
--
ALTER TABLE `livehelp_referers_daily`
  MODIFY `recno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_referers_monthly`
--
ALTER TABLE `livehelp_referers_monthly`
  MODIFY `recno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_smilies`
--
ALTER TABLE `livehelp_smilies`
  MODIFY `smilies_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `livehelp_transcripts`
--
ALTER TABLE `livehelp_transcripts`
  MODIFY `recno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `livehelp_users`
--
ALTER TABLE `livehelp_users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `livehelp_visits_daily`
--
ALTER TABLE `livehelp_visits_daily`
  MODIFY `recno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_visits_monthly`
--
ALTER TABLE `livehelp_visits_monthly`
  MODIFY `recno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livehelp_visit_track`
--
ALTER TABLE `livehelp_visit_track`
  MODIFY `recno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `livehelp_websites`
--
ALTER TABLE `livehelp_websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;