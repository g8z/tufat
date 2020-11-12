<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>##Contact##</title>
<link href="templates/{$smarty.session.templateID}/tufat.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">

function validateForm( myForm )
{ldelim}

  // sender, recipient, subject, message are all required fields

        if ( myForm.sender.value == "" || myForm.recipient.value == "" || myForm.subject.value == "" || myForm.message.value == "" )
          {ldelim}
              alert( "##Sender, Recipient, Subject, and Message Text are all required.##" );
        {rdelim}
                  else
        {ldelim}
              myForm.submit();
        {rdelim}
{rdelim}

</script>

<form name="emailForm" method="post" action="getAdminInfo.php">
<table width="100%" border="0" cellspacing="10" cellpadding="2">
        <tr>
                <td colspan="2" class="normal"><font class="subtitle">##Contact the## {$mydata.matchTree} ##System Administrator## ##about this Match!##</font></td>
        </tr>
        <tr>
                <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
                <td width="9%" class="normal">##From:##</td>
                <td width="91%"><input name="sender" type="text" class="normal" size="50" /></td>
        </tr>
        <tr>
                <td class="normal">##To:##</td>
                <td><input name="recipient" type="text" class="normal" value="{$mydata.adminmail}" size="50" />
                </td>
        </tr>
        <tr>
            <td class="normal">##Subject:##</td>
                <td><input name="subject" type="text" class="normal" value="##Our family trees may have a common link##" size="50" />
                </td>
        </tr>
        <tr>
                <td colspan="2" class="normal">##Message:##</td>
        </tr>
        <tr>
  		<td colspan="2"><textarea name="message" cols="80" rows="8" class="normal">{$mydata.msg01}</textarea></td>
        </tr>
        <tr>
        <td>&nbsp;</td>
                <td><input name="send" type="button" class="normal" id="send" onclick="javascript:validateForm(document.emailForm);" value="##Send Message##" />
                </td>
        </tr>
        <tr>
                <td colspan="2" class="normal"><span class="normal">##NOTE: A copy of this message will be sent to the address specified in the 'From' field. You may want to add more information about the match or include your name and additional contact information. The message above is just provided as a guideline.##
</span></td>
        </tr>
</table>
</form>

{* Do NOT uncomment these lines *}
{*  This is text passed by PHP, included here for the language editor

##Dear $matchTree tree administrator, A search of the family tree database on $sname suggests that we have a common link in our family trees. The record in your tree with ID# $ID may match a record in the $user tree may match a record in the family tree. I am interested in pursuing this possibility to find common ancestors and relations between our families. Thanks!##

*}
