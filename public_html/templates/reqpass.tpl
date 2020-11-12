{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="10" cellpadding="2">
<tr>
<td>

{if $mydata.dataok}

	<font class="normal">

	{if $mydata.mailmsg} {* print the mail sent message  *}

		{$mydata.mailmsg}

	{else}  {* Show the problem with mail sending *}

		<font class="subtitle">##E-Mail Error##</font>
		<br />
		<br />
		{$mydata.emimsg}. ##Please contact the## <a href="mailto:{$mydata.supervisoremail}">##site supervisor##</a> ##regarding this error##.

	{/if}

	</font> <br />


{else}
        {$mydata.errmsg}
{/if}


<br />
<a href="javascript:close();">##Close window##</a>
<br />

</td>
</tr>
</table>

{* do not uncomment these lines *}
{*  This is text passed by PHP, added for the language editor

##Your request has been sent to the $tname administrator. If you do not hear from the administrator soon, you may want to contact him or her directly at $aemail##
##There was an error sending the e-mail to $emi##
##Tree Name $tname not found##

*}

{include file=popup_footer.tpl}