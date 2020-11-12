<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head><title>{$title|default:$smarty.const.TREENAME}</title>
<link href="templates/{$smarty.session.templateID}/tufat.css" rel="stylesheet" type="text/css" />

<script src="javascript.js" type="text/javascript"></script>

{*
<script type="text/javascript">
function exit1()
{ldelim}
	self.location.href="shownotes.php?xtag={$mydata.xtag}&ct={$mydata.ct}&ID={$mydata.ID}&mr={$mydata.mr}";
{rdelim}
</script>
*}

<script type="text/javascript">
function exit1()
{ldelim}
	if ( self.opener ) {ldelim}
        	self.opener.location.reload();
        {rdelim}
        window.close();
{rdelim}
</script>

</head>
<body style="margin-height:0px;margin-width:0px;">
