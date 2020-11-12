<script type="text/javascript">
        var inmenu=0;
</script>

<table>
<tr>
<td align="center">
<a href='famgal.php?ID={$mydata.ID}&amp;tnail=1&amp;'> ##View Thumbnails## </a>
</td>
</tr>
<tr>
<td align="center">
{if $mydata.semail == ''}

        <a href="image.php?indi={$mydata.indi}&kd={$mydata.kd}&ID={$mydata.ID}&mid={$mydata.mid}&fp={$mydata.fp}"><img  alt="" src="image.php?indi={$mydata.indi}&amp;kd={$mydata.kd}&amp;ID={$mydata.ID}&amp;mid={$mydata.mid}&amp;fp={$mydata.fp}" /></a>

{/if}
</td>
</tr>
</table>

{if $mydata.recscnt > 0}
        {if $mydata.semail != ""}

                <br /><font class="normal">

                {if $mydata.invalidemail}

                        ##The e-mail 'to' address that you have specified appears to contain invalid syntax.##

                { else}

                        {if $mydata.emailsentmsg}
	                        ##Congratulations! The e-mail to## {$mydata.semail} ##was sent successfully##
	                      {else}
	                     	##E-Mail sending problem##:<br />
	                     	{$mydata.email_error}
	                     {/if}

                {/if}

                </font><br /><br />

        { else}

                <br /><br />
                <table class="normal" width='50%'>
                        <tr>
                                <td>##Size##:
                                        {$mydata.msgsize} KB<br />
                                </td>
                        </tr>
                        <tr>
                                <td>##Title##: {$mydata.title}
                                </td>
                        </tr>
                        <tr>
                                <td>##Description##: {$mydata.descr}
                                </td>
                        </tr>
                </table>
                <br />
                        <form action = 'viewfamgalim.php' method="post">
                <table class="normal">
                                <tr>
                                        <td>##E-Mail To## </td>
                                        <td><input type="text" name="semail" class="normal" size="40" />
                                        </td>
                                </tr>
                                <tr>
                                        <td>##E-Mail From##</td>
                                        <td><input type="text" name="sfrom" class="normal" size="40" /></td>
                                </tr>
                                <tr>
                                        <td>##E-Mail Subject##</td>
                                        <td><input type="text" name="ssubj" class="normal" size="40" /></td>
                                </tr>
                                <tr>
                                        <td>##E-Mail Body##</td>
                                        <td><textarea cols="60" rows="5" class="normal" name="sbody"></textarea></td>
                                </tr>
                                <tr>
                                        <td><input type="submit" value="Send" />
                                                <input type="hidden" name="fp" value="{$mydata.fp}" />
                                        </td>
                                        <td></td>
                                </tr>
                </table>
                                <input type="hidden" name="mid" value="{$mydata.mid}" />
                                <input type="hidden" name="indi" value="{$mydata.indi}" />
                                <input type="hidden" name="ID" value="{$mydata.ID}" />                
                        </form>
        {/if}
{/if}
