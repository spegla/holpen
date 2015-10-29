<?php
/*******************************************************************************
*  Title: LinkMan reciprocal link manager
*  Version: 1.7 @ April 18, 2009
*  Author: Klemen Stirn
*  Website: http://www.phpjunkyard.com
********************************************************************************
*  COPYRIGHT NOTICE
*  Copyright 2004-2009 Klemen Stirn. All Rights Reserved.
*
*  This script may be used and modified free of charge by anyone
*  AS LONG AS COPYRIGHT NOTICES AND ALL THE COMMENTS REMAIN INTACT.
*  By using this code you agree to indemnify Klemen Stirn from any
*  liability that might arise from it's use.
*
*  Selling the code for this program, in part or full, without prior
*  written consent is expressly forbidden. Using this code, in part or full,
*  to create competing scripts or products is expressly forbidden.
*
*  Obtain permission before redistributing this software over the Internet
*  or in any other medium. In all cases copyright and header must remain
*  intact. This Copyright is in full effect in any country that has
*  International Trade Agreements with the United States of America or
*  with the European Union.
*
*  Removing any of the copyright notices without purchasing a license
*  is illegal! To remove PHPJunkyard copyright notice you must purchase a
*  license for this script. For more information on how to obtain a license
*  please visit the site below:
*  http://www.phpjunkyard.com/copyright-removal.php
*******************************************************************************/

define('IN_SCRIPT',1);
require('settings.php');

if ($settings['verzija'] == '1.6')
{
    $tmp = '';
	foreach ($settings['hide'] as $h)
    {
    	$tmp .= '\''.$h.'\',';
    }
	$settings['hide'] = substr($tmp, 0, -1);
}
elseif ($settings['verzija'] == '1.5')
{
    $tmp = '';
	foreach ($settings['hide'] as $h)
    {
    	$tmp .= '\''.$h.'\',';
    }
	$settings['hide'] = substr($tmp, 0, -1);

	/* New variables in 1.6 */
	$settings['allow_pages']=1;
	$settings['max_per_page']=30;
	$settings['man_approval']=0;
	$settings['url_approval']='http://www.yourdomain.com/links/approve.php';
	if ($settings['max_links']<300)
	{
		$settings['max_links'] = 300;
	}
}
elseif ($settings['verzija'] == '1.4')
{
	$settings['hide'] = '\'divCheck\',\'divRate\',\'divUpdate\'';
    $settings['banfile'] = 'banned_websites.txt';

	/* New variables in 1.6 */
	$settings['allow_pages']=1;
	$settings['max_per_page']=30;
	$settings['man_approval']=0;
	$settings['url_approval']='http://www.yourdomain.com/links/approve.php';
	if ($settings['max_links']<300)
	{
		$settings['max_links'] = 300;
	}
}
else
{
	die('ERROR: This script can only update from version 1.4 upwards (your version is: <b>'.$settings['verzija'].'</b>). To update any other version please see instructions in the <a href="readme.htm">readme.htm</a> file.');
}

/* Fitler sum udpated in 1.7 */
$useChars = 'AEUYBDGHJLMNPQRSTVWXZ123456789#$%&!()-_*,.;<>?+';
$tmp = time();
for($i=1;$i<10;$i++)
{
	$tmp .= $useChars{mt_rand(0,46)};
}
$settings['filter_sum']   = md5($tmp);


$settings_file='<?php
// SETUP YOUR LINK MANAGER
// Detailed information found in the readme.htm file
// File last modified: 1.7 @ April 18, 2009

/* Password for admin area */
$settings[\'apass\']=\''.$settings['apass'].'\';

/* Your website URL */
$settings[\'site_url\']=\''.$settings['site_url'].'\';

/* Your website title */
$settings[\'site_title\']=\''.addslashes($settings['site_title']).'\';

/* Your website description */
$settings[\'site_desc\']=\''.addslashes($settings['site_desc']).'\';

/* Show "add a link" form on the bottom of links page? 1 = YES, 0 = NO */
$settings[\'show_form\']='.$settings['show_form'].';

/* Send you an e-mail everytime someone adds a link? 1=YES, 0=NO */
$settings[\'notify\']='.$settings['notify'].';

/* Admin e-mail */
$settings[\'admin_email\']=\''.$settings['admin_email'].'\';

/* Maximum number of links */
$settings[\'max_links\']='.$settings['max_links'].';

/* Allow generation of new pages; 1=YES, 0=NO */
$settings[\'allow_pages\']='.$settings['allow_pages'].';

/* Number of links per page */
$settings[\'max_per_page\']='.$settings['max_per_page'].';

/* Approve links manually? 1=YES, 0=NO */
$settings[\'man_approval\']='.$settings['man_approval'].';

/* URL of the approve.php file on your server */
$settings[\'url_approval\']=\''.$settings['url_approval'].'\';

/* Prevent automated submissions (recommended YES)? 1 = YES, 0 = NO */
$settings[\'autosubmit\']='.$settings['autosubmit'].';

/* Checksum - just type some digits and chars. Used to help prevent SPAM */
$settings[\'filter_sum\']=\''.$settings['filter_sum'].'\';

/* Enable SPAM filter? 1=YES, 0=NO */
$settings[\'spam_filter\']='.$settings['spam_filter'].';

/* Block superlatives from title and description? 1=YES, 0=NO */
$settings[\'superlatives\']='.$settings['superlatives'].';

/* Use normal links? 0=NORMAL, 1=REDIRECT ALL, 2=REDIRECT RECIPROCAL ONLY */
$settings[\'clean\']='.$settings['clean'].';

/* Add rel="nofollow" attribute to links? 0=NO, 1=YES, 2=FOR RECIPROCAL ONLY */
$settings[\'use_nofollow\']='.$settings['use_nofollow'].';

/* Where to add new links? 0 = top of list, 1 = end of list */
$settings[\'add_to\']='.$settings['add_to'].';

/* Name of the file where link URLs and other info is stored */
$settings[\'linkfile\']=\''.$settings['linkfile'].'\';

/* Name of the file where banned websites are stored */
$settings[\'banfile\']=\''.$settings['banfile'].'\';

/* Display website URL after Title? 1=YES, 0=NO */
$settings[\'show_url\']='.$settings['show_url'].';

/* Display Google PageRank? 0=NO, 1=YES, 2=IN ADMIN PANEL ONLY */
$settings[\'show_pr\']='.$settings['show_pr'].';

/* Minimum Google PageRank to accept website? A value from 0 to 10 */
$settings[\'min_pr\']='.$settings['min_pr'].';

/* Minimum Google PageRank of reciprocal links page? A value from 0 to 10 */
$settings[\'min_pr_rec\']='.$settings['min_pr_rec'].';

/* Block links with rel="nofollow"? 1=YES, 0=NO */
$settings[\'block_nofollow\']='.$settings['block_nofollow'].';

/* Block link from pages with meta robots nonidex or nofollow? 1=YES, 0=NO */
$settings[\'block_meta_rob\']='.$settings['block_meta_rob'].';

/* Block duplicate entries (same website added more than once)? 1=YES, 0=NO */
$settings[\'block_duplicates\']='.$settings['block_duplicates'].';

/* Display website thumbnails? 0=NO, 1=YES, 2=FEATURED LINKS ONLY */
$settings[\'show_thumbshots\']='.$settings['show_thumbshots'].';

/* URL of your thumbshots service */
$settings[\'thumb_url\']=\''.$settings['thumb_url'].'\';

/* Turn debug mode on? 1=YES, 0=NO */
$settings[\'debug\']='.$settings['debug'].';

/* Which sections to hide by default */
$settings[\'hide\']=array('.$settings['hide'].');


/*******************
* DO NOT EDIT BELOW
*******************/
$settings[\'verzija\']=\'1.7\';
$settings[\'delimiter\']="\t";

if (!defined(\'IN_SCRIPT\')) {die(\'Invalid attempt!\');}
if ($settings[\'debug\'])
{
    error_reporting(E_ALL ^ E_NOTICE);
}
else
{
    ini_set(\'display_errors\', 0);
    ini_set(\'log_errors\', 1);
}

function pj_input($in,$error=0) {
    $in = trim($in);
    if (strlen($in))
    {
        $in = htmlspecialchars($in);
    }
    elseif ($error)
    {
        problem($error);
    }
    return stripslashes($in);
}

function pj_isNumber($in,$error=0) {
    $in = trim($in);
    if (preg_match("/\D/",$in) || $in==\'\')
    {
        if ($error)
        {
            problem($error);
        }
        else
        {
            return \'0\';
        }
    }
    return $in;
}
?>';  // END $settings_file

$fp = @fopen('settings.php','w') or die('Can\'t open file settings.php for writing, on Linux CHMOD it to 666 (rw-rw-rw-)!');
flock($fp, LOCK_EX);
fputs($fp,$settings_file);
flock($fp, LOCK_UN);
fclose($fp);

?>
<p><font color="#008000"><b>Your settings file has been updated successfully.</b></font></p>

<p><b>PLEASE DELETE UPDATE.PHP FROM YOUR SERVER NOW!</b></p>
