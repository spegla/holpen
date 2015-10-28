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

$approve = intval($_GET['approve']);

$hash = pj_input($_GET['id'],'Missing ID hash. Please make sure you copy the full approval/rejection URL!');
$hash = preg_replace('/[^a-z0-9]/','',$hash);
$file = 'apptmp/'.$hash.'.txt';

/* Check if the file hash is correct */
if (!file_exists($file))
{
	problem('Wrong link ID hash. Possible problems:<br><br>- the link has already been approved or rejected<br>- you didn\'t copy the full approval/rejection URL');
}

/* Reject the link */
if (!$approve)
{
	unlink($file);
	?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
	<link rel="STYLESHEET" type="text/css" href="style.css">
	<title>LinkMan admin panel</title>
	</head>
	<body marginheight="5" topmargin="5">

	<p>&nbsp;</p>

	<div align="center">
	<table width="400">
	<tr>
	<td align="center" class="head">Link rejected</td>
	</tr>
	<tr>
	<td align="center" class="dol">
    <p>&nbsp;</p>
	<p>The selected link has been rejected from the link exchange.</p>
    <p>&nbsp;</p>
	</td>
	</tr>
	</table>
	</div>

	</body>
	</html>
	<?php
	exit();
}

/* Approve link */
$replacement = trim(@file_get_contents('apptmp/'.$hash.'.txt'));
if (empty($replacement))
{
	problem('This link doesn\'t exist or has already been approved or rejected!');
}
$replacement .= "\n";

if ($settings['add_to'] == 0)
{
        /* Get existing lines */
        $lines = file($settings['linkfile']);
        
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

unlink($file);
?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
	<link rel="STYLESHEET" type="text/css" href="style.css">
	<title>LinkMan admin panel</title>
	</head>
	<body marginheight="5" topmargin="5">

	<p>&nbsp;</p>

	<div align="center">
	<table width="400">
	<tr>
	<td align="center" class="head">Link approved</td>
	</tr>
	<tr>
	<td align="center" class="dol">
    <p>&nbsp;</p>
	<p>The selected link has been approved and included in the link exchange.</p>
    <p>&nbsp;</p>
	</td>
	</tr>
	</table>
	</div>

	</body>
	</html>
<?php
exit();



/*** FUNCTIONS ***/

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
?>
