{include file=popup_header.tpl}

<script type="text/javascript">
function val()
{ ldelim}
    d = document.f;

    if (d.tname.value.length == 0)
    { ldelim}
        alert('##Please enter tree name##');
        d.tname.focus();
        return false;

   {rdelim}

    return true;
{rdelim}
</script>


<form action='reqpass.php' name="f" onsubmit='return val()' method="post">
    <table width="100%" border="0" cellspacing="10" cellpadding="2">
        <tr>
            <td colspan="2" class="normal">
                ##req_pass_text##
            </td>
        </tr>
        <tr>
            <td width="17%" class="normal" align="right">
                    ##Tree Name:##
            </td>
            <td class="normal" width="83%">
               <select name="tname" class="normal">
                  {html_options options="$namelist"}
               </select>
            </td>
        </tr>
        <tr>
            <td width="17%" class="normal" align="right">
                ##Your Name:##
            </td>
            <td class="normal" width="83%">
                <input name="tyn" type="text" class="txtinput1"  value="" maxlength="255" />

            </td>
        </tr>
        <tr>
            <td nowrap class="normal" align="right">
                   ##Contact Information:##
            </td>
            <td class="normal" width="83%">
                <input name="tcinf" type="text" class="txtinput1"  value="" maxlength="255" />
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="login" value='##Send Request##' class="normal" />
            </td>
        </tr>
    </table>
</form>
<br />

{include file=popup_footer.tpl}