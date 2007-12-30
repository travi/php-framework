<?php
/*
 * Created on Mar 5, 2007
 * By Matt Travi
 */

if(empty($_POST['Submit']))
{
	list($content,$title) = content();
	if(isset($page))
	{
		$page->setTitle($title);
		if($title == "Results")
		{
			list($status,$msg,$redirectTo) = $content;
			$page->redirect($status,$msg,$redirectTo);
		}
		else
			$page->addToContent($content);
	}
}
else
{
	list($status,$msg,$redirectTo) = process();
	$page->redirect($status,$msg,$redirectTo);
}
?>
