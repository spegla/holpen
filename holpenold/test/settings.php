<?php
// SETUP YOUR LINK MANAGER
// Detailed information found in the readme.htm file
// File last modified: 1.7 @ April 18, 2009

/* Password for admin area */
$settings['apass']='admin';

/* Your website URL */
$settings['site_url']='http://www.domain.com';

/* Your website title */
$settings['site_title']="My lovely website";

/* Your website description */
$settings['site_desc']="This is a brief description of my website.";

/* Show "add a link" form on the bottom of links page? 1 = YES, 0 = NO */
$settings['show_form']=1;

/* Send you an e-mail everytime someone adds a link? 1=YES, 0=NO */
$settings['notify']=0;

/* Admin e-mail */
$settings['admin_email']='you@yourdomain.com';

/* Maximum number of links */
$settings['max_links']=10000;

/* Allow generation of new pages; 1=YES, 0=NO */
$settings['allow_pages']=1;

/* Number of links per page */
$settings['max_per_page']=30;

/* Approve links manually? 1=YES, 0=NO */
$settings['man_approval']=0;

/* URL of the approve.php file on your server */
$settings['url_approval']='http://www.yourdomain.com/links/approve.php';

/* Prevent automated submissions (recommended YES)? 1 = YES, 0 = NO */
$settings['autosubmit']=1;

/* Checksum - just type some digits and chars. Used to help prevent SPAM */
$settings['filter_sum']='5fcbd40c97e1497d88ae4e8fc53e7a8d';

/* Enable SPAM filter? 1=YES, 0=NO */
$settings['spam_filter']=1;

/* Block superlatives from title and description? 1=YES, 0=NO */
$settings['superlatives']=1;

/* Use normal links? 0=NORMAL, 1=REDIRECT ALL, 2=REDIRECT RECIPROCAL ONLY */
$settings['clean']=0;

/* Add rel="nofollow" attribute to links? 0=NO, 1=YES, 2=FOR RECIPROCAL ONLY */
$settings['use_nofollow']=0;

/* Where to add new links? 0 = top of list, 1 = end of list */
$settings['add_to']=1;

/* Name of the file where link URLs and other info is stored */
$settings['linkfile']='linkinfo.txt';

/* Name of the file where banned websites are stored */
$settings['banfile']='banned_websites.txt';

/* Display website URL after Title? 1=YES, 0=NO */
$settings['show_url']=1;

/* Display Google PageRank? 0=NO, 1=YES, 2=IN ADMIN PANEL ONLY */
$settings['show_pr']=1;

/* Minimum Google PageRank to accept website? A value from 0 to 10 */
$settings['min_pr']=0;

/* Minimum Google PageRank of reciprocal links page? A value from 0 to 10 */
$settings['min_pr_rec']=0;

/* Block links with rel="nofollow"? 1=YES, 0=NO */
$settings['block_nofollow']=1;

/* Block link from pages with meta robots nonidex or nofollow? 1=YES, 0=NO */
$settings['block_meta_rob']=1;

/* Block duplicate entries (same website added more than once)? 1=YES, 0=NO */
$settings['block_duplicates']=1;

/* Display website thumbnails? 0=NO, 1=YES, 2=FEATURED LINKS ONLY */
$settings['show_thumbshots']=2;

/* URL of your thumbshots service */
$settings['thumb_url']='http://open.thumbshots.org/image.pxf?url=';

/* Turn debug mode on? 1=YES, 0=NO */
$settings['debug']=0;

/* Which sections to hide by default */
$settings['hide']=array();


/*******************
* DO NOT EDIT BELOW
*******************/
$settings['verzija']='1.7';
$settings['delimiter']="\t";

if (!defined('IN_SCRIPT')) {die('Invalid attempt!');}
if ($settings['debug'])
{
    error_reporting(E_ALL ^ E_NOTICE);
}
else
{
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
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
    if (preg_match("/\D/",$in) || $in=='')
    {
        if ($error)
        {
            problem($error);
        }
        else
        {
            return '0';
        }
    }
    return $in;
}
?>
