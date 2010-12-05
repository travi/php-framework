<?php
/*
 * Created on Mar 5, 2007
 * By Matt Travi
 */

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
	content();
}
else if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	list($status,$msg,$redirectTo) = process();
	$page->redirect($status,$msg,$redirectTo);
}
?>
