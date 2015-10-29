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
ini_set('user_agent', 'LinkMan '.$settings['verzija'].' by http://www.phpjunkyard.com');

/* Accepting any more links? */
$lines = @file($settings['linkfile']);
if (count($lines)>$settings['max_links'])
{
    problem('We are not accepting any more links at the moment. We appologize for the inconvenience!');
}

/* Check user input */
$name  = pj_input($_POST['name'],'Please enter your name!');
$email = pj_input($_POST['email'],'Please enter your e-mail address!');
if (!preg_match('/([\w\-]+\@[\w\-]+\.[\w\-]+)/',$email))
{
    problem('Please enter a valid e-mail address!');
}
$title = pj_input($_POST['title'],'Please enter the title (name) of your website!');
if (strlen($title)>50)
{
    problem('Title is too long! Limit website title to 50 chars!');
}

$url   = pj_input($_POST['url'],'Please enter the URL of your website!');
if (!(preg_match('/(http:\/\/+[\w\-]+\.[\w\-]+)/i',$url)))
{
    problem('Please enter valid URL of your website!');
}

$recurl = pj_input($_POST['recurl'],'Please enter the url where a reciprocal link to our site is placed!');
if (!(preg_match('/(http:\/\/+[\w\-]+\.[\w\-]+)/i',$recurl)))
{
    problem('Please enter valid URL of the page where the reciprocal link to our site is placed!');
}

/* Compare URL and Reciprocal page URL */
$parsed_url = parse_url($url);
$parsed_rec = parse_url($recurl);
if ($parsed_url['host'] != $parsed_rec['host'])
{
    problem('The reciprocal link must be placed under the same (sub)domain as your link is!');
}

$url    = str_replace('&amp;','&',$url);
$recurl = str_replace('&amp;','&',$recurl);

$description = pj_input($_POST['description'],'Please write a short description of your website!');
if (strlen($description)>200)
{
    problem('Description is too long! Description of your website is limited to 200 chars!');
}

/* Check if the website is banned */
if ($mydata = file_get_contents($settings['banfile']))
{
	/* Check website URL */
	$regex = str_replace(array('http://www.','http://'),'http://(www\.)?',$url);
	$regex = preg_replace('/index\.[^\/]+$/','',$regex);
	$regex = str_replace('/','\/',rtrim($regex,'/'));
	$regex = '/'.$regex.'\/?(index\.[^\/]+)?/i';
	if (preg_match($regex,$mydata))
	{
		problem('This website has been permanently banned from our link exchange!');
	}

	/* Check reciprocal link URL */
	$regex = str_replace(array('http://www.','http://'),'http://(www\.)?',$recurl);
	$regex = preg_replace('/index\.[^\/]+$/','',$regex);
	$regex = str_replace('/','\/',rtrim($regex,'/'));
	$regex = '/'.$regex.'\/?(index\.[^\/]+)?/i';
	if (preg_match($regex,$mydata))
	{
		problem('This website has been permanently banned from our link exchange!');
	}

	unset($mydata);
}

/* Check title and description for possible spam problems */
eval(gzinflate(base64_decode('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')));

if ($settings['spam_filter'])
{
    $test = pj_checkTitleDesc($title, $description, $url, $settings['superlatives']);
    if ($test === true)
    {
        $test = '';
    }
    elseif ($test == 'superlatives')
    {
        problem('Don\'t use superlatives (words like best, biggest, cheapest, largest) in title and description!');
    }
    elseif ($test == 'text')
    {
        problem('Your link failed SPAM test, we are forced to reject it.');
    }
    elseif ($test == 'url')
    {
        problem('Your link failed SPAM test, we are forced to reject it.');
    }
}

if ($settings['autosubmit'])
{
    session_start();
    if (empty($_SESSION['checked']))
    {
        $_SESSION['checked']  = 'N';
        $_SESSION['secnum']   = rand(10000,99999);
        $_SESSION['checksum'] = $_SESSION['secnum'].$settings['filter_sum'].date('dmy');
    }
    if ($_SESSION['checked'] == 'N')
    {
        print_secimg();
    }
    elseif ($_SESSION['checked'] == $settings['filter_sum'])
    {
        $_SESSION['checked'] = 'N';
        $secnumber = pj_isNumber($_POST['secnumber']);
        if(empty($secnumber))
        {
            print_secimg(1);
        }
        if (!check_secnum($secnumber,$_SESSION['checksum']))
        {
            print_secimg(2);
        }
    }
    else
    {
        problem('Internal script error. Wrong session parameters!');
    }
}

/* Check for duplicate links */
if ($settings['block_duplicates'])
{
    $mydata = file_get_contents($settings['linkfile']);

    /* Check website URL */
    $regex = str_replace(array('http://www.','http://'),'http://(www\.)?',$url);
    $regex = preg_replace('/index\.[^\/]+$/','',$regex);
    $regex = str_replace('/','\/',rtrim($regex,'/'));
    $regex = '/'.$regex.'\/?(index\.[^\/]+)?\s/i';
    if (preg_match($regex,$mydata))
    {
        problem('Please don\'t submit the same website more than once or we will be forced to delete all your links!');
    }

    /* Check reciprocal link URL */
    $regex = str_replace(array('http://www.','http://'),'http://(www\.)?',$recurl);
    $regex = preg_replace('/index\.[^\/]+$/','',$regex);
    $regex = str_replace('/','\/',rtrim($regex,'/'));
    $regex = '/'.$regex.'\/?(index\.[^\/]+)?\s/i';
    if (preg_match($regex,$mydata))
    {
        problem('Please don\'t submit multiple websites with the same reciprocal link URL or we will be forced to delete all your links!');
    }

    unset($mydata);
}

/* Get HTML code of the reciprocal link URL */
$html = @file_get_contents($recurl) or problem('Can\'t open remote URL!');
$html = strtolower($html);
$site_url = strtolower($settings['site_url']);

/* Block links with the meta "robots" noindex or nofollow tags? */
if ($settings['block_meta_rob']==1 && preg_match('/<meta([^>]+)(noindex|nofollow)(.*)>/siU',$html,$meta))
{
    problem(
        'Please don\'t place the reciprocal link to a page with the meta robots noindex or nofollow tag:<br />'.
        htmlspecialchars($meta[0])
    );
}

$found    = 0;
$nofollow = 0;

if (preg_match_all('/<a\s[^>]*href=([\"\']??)([^" >]*?)\\1([^>]*)>/siU', $html, $matches, PREG_SET_ORDER)) {
    foreach($matches as $match)
    {
        if ($match[2] == $settings['site_url'] || $match[2] == $settings['site_url'].'/')
        {
            $found = 1;
            if (strstr($match[3],'nofollow'))
            {
                $nofollow = 1;
            }
            break;
        }
    }
}

if ($found == 0)
{
    problem(
        'Our URL (<a href="'.$settings['site_url'].'">'.$settings['site_url'].
        '</a>) wasn\'t found on your reciprocal links page (<a href="'.$recurl.'">'.
        $recurl.'</a>)!<br><br>Please make sure you place this exact URL on your
        links page before submitting your link!'
    );
}

/* Block links with rel="nofollow" attribute? */
if ($settings['block_nofollow'] && $nofollow == 1)
{
    problem('Please don\'t use rel=&quot;nofollow&quot; link attribute for the reciprocal link!');
}

/* Check Google PageRank */
if ($settings['show_pr'] || $settings['min_pr'] || $settings['min_pr_rec'])
{
    require('pagerank.php');
    $pr = getpr($url);
    $pr = empty($pr) ? 0 : $pr;

    if ($settings['min_pr'] && ($pr < $settings['min_pr']))
    {
        problem('Unfortunately we accept only websites with Google PageRank '.$settings['min_pr'].'/10 or higher. Please try submitting your website again in a few months.');
    }

    if ($settings['min_pr_rec'])
    {
        $pr_rec = getpr($recurl);
        $pr_rec = empty($pr_rec) ? 0 : $pr_rec;
        if ($pr_rec < $settings['min_pr_rec'])
        {
            problem('Please place the reciprocal link to <a href="'.$settings['site_url'].'">'.$settings['site_url'].'</a> on a page with Google PageRank '.$settings['min_pr_rec'].'/10 or higher.');
        }
    }
}

$replacement = "$name$settings[delimiter]$email$settings[delimiter]$title$settings[delimiter]$url$settings[delimiter]$recurl$settings[delimiter]$description$settings[delimiter]0$settings[delimiter]$pr\n";

/* Approve manually */
if ($settings['man_approval'])
{
	$tmp = str_replace('www.','',strtolower($parsed_url['host']));
    $tmp = md5($tmp.$settings['filter_sum']);
    $tmp_file = 'apptmp/'.$tmp.'.txt';

    if (file_exists($tmp_file))
    {
    	problem('This link is already pending approval!');
    }

	$fp = fopen($tmp_file,'w') or problem('Couldn\'t open temporary file for writing! Please CHMOD the apptmp folder to 777 (rwxrwxrwx)!');
	flock($fp, LOCK_EX);
	fputs($fp,$replacement);
	flock($fp, LOCK_UN);
	fclose($fp);

$message = "Hello,

A new link is awaiting approval for your links page at $settings[site_url]

Link details:

Name: $name
E-mail: $email
URL: $url
Reciprocal link: $recurl
Title: $title
Description:
$description

To APPROVE the link visit this URL:
$settings[url_approval]?id=$tmp&approve=1

To REJECT the link visit this URL:
$settings[url_approval]?id=$tmp&approve=0


End of message

";
	$headers  = "From: $name <$email>\n";
	$headers .= "Reply-To: $name <$email>\n";
	mail($settings['admin_email'],'New link waiting approval',$message,$headers);

	require_once('header.txt');
	?>
	<p align="center"><b>Your link submitted for approval!</b></p>
	<p>&nbsp;</p>
	<p align="center">Thank you, your link has been submitted for approval and will appear on the links page once approved by the administrator!</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p align="center"><a href="<?php echo $settings['site_url']; ?>">Back to the main page</a></p>
	<?php
	require_once('footer.txt');
	exit();

}
/* Approve automatically */
else
{
	if ($settings['add_to'] == 0)
	{
	    /* Make sure new link is added after any featured ones */
		$i = 0;
        $was_added = 0;
		foreach ($lines as $thisline)
		{
			list($name2,$email2,$title2,$url2,$recurl2,$description2,$featured2,$pr2)=explode($settings['delimiter'],$thisline);
			$featured2 = $featured2 ? 1 : 0;
			if ($featured2 == 0)
			{
				$lines[$i] = $replacement . $thisline;
                $was_added = 1;
				break;
			}
			$i++;
		}

        if ($was_added)
        {
			$replacement = implode('',$lines);
		    $fp = fopen($settings['linkfile'],'w') or problem('Couldn\'t open links file for writing! Please CHMOD all txt files to 666 (rw-rw-rw)!');
		    flock($fp, LOCK_EX);
		    fputs($fp,$replacement);
		    flock($fp, LOCK_UN);
		    fclose($fp);
        }
		else
		{
		    $fp = fopen($settings['linkfile'],'a') or problem('Couldn\'t open links file for appending! Please CHMOD all txt files to 666 (rw-rw-rw)!');
		    flock($fp, LOCK_EX);
		    fputs($fp,$replacement);
		    flock($fp, LOCK_UN);
		    fclose($fp);
		}
	}
	else
	{
	    $fp = fopen($settings['linkfile'],'a') or problem('Couldn\'t open links file for appending! Please CHMOD all txt files to 666 (rw-rw-rw)!');
	    flock($fp, LOCK_EX);
	    fputs($fp,$replacement);
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}

	if($settings['notify'] == 1)
	{
$message = "Hello,

Someone just added a new link to your links page on $settings[site_url]

Link details:

Name: $name
E-mail: $email
URL: $url
Reciprocal link: $recurl
Title: $title
Description:
$description


End of message

";
	    $headers  = "From: $name <$email>\n";
	    $headers .= "Reply-To: $name <$email>\n";
	    mail($settings['admin_email'],'New link submitted',$message,$headers);
	}

	require_once('header.txt');
	?>
	<p align="center"><b>Your link has been added!</b></p>
	<p>&nbsp;</p>
	<p align="center">Thank you, your link has been successfully added to our link exchange (try reloading our links page if you don't see your link there yet)!</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p align="center"><a href="<?php echo $settings['site_url']; ?>">Back to the main page</a></p>
	<?php
	require_once('footer.txt');
	exit();
}


/*** FUNCTION ***/

function problem($problem) {
require_once('header.txt');
echo '
    <p align="center"><font color="#FF0000"><b>ERROR</b></font></p>
    <p>&nbsp;</p>
    <p align="center">'.$problem.'</p>
    <p>&nbsp;</p>
    <p align="center"><a href="javascript:history.go(-1)">Back to the previous page</a></p>
';
require_once('footer.txt');
exit();
}

function print_secimg($message=0) {
global $settings;
$_SESSION['checked']=$settings['filter_sum'];
require_once('header.txt');
?>
<p>&nbsp;</p>

<p align="center"><b>Anti-SPAM check</b></p>

<div align="center"><center>
<table border="0">
<tr>
<td>
    <form action="addlink.php?<?php echo strip_tags(SID); ?>" method="POST" name="form">

    <hr>
    <?php
    if ($message == 1)
    {
        echo '<p align="center"><font color="#FF0000"><b>Please type in the security number</b></font></p>';
    }
    elseif ($message == 2)
    {
        echo '<p align="center"><font color="#FF0000"><b>Wrong security number. Please try again</b></font></p>';
    }
    ?>

    <p>This is a security check that prevents automated signups of this forum (SPAM).
    Please enter the security number displayed below into the input field and click the continue button.</p>

    <p>&nbsp;</p>

    <p>Security number: <b><?php echo $_SESSION['secnum']; ?></b><br>
    Please type in the security number displayed above:
    <input type="text" size="7" name="secnumber" maxlength="5"></p>

    <p>&nbsp;
    <?php
    foreach ($_POST as $k=>$v)
    {
        if ($k == 'secnumber')
        {
            continue;
        }
        echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars(stripslashes($v)).'">';
    }
    ?>
    </p>

    <p align="center"><input type="submit" value=" Continue "></p>

    <hr>

    </form>
</td>
</tr>
</table>
</center></div>

<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
require_once('footer.txt');
exit();
}

function check_secnum($secnumber,$checksum) {
    global $settings;
    $secnumber .= $settings['filter_sum'].date('dmy');
    if ($secnumber == $checksum)
    {
        unset($_SESSION['checked']);
        return true;
    }
    else
    {
        return false;
    }
}
?>
