{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
   <td class="title" align="left">##Source## {$message}</td>
  </tr>
  <tr>
    <td align="left" class="normal">
        {if $mydata.err}
                <font color="red">{$mydata.err}</font><br />
        {/if}
        <form action='editsour.php' method="post">
          <input type="hidden" name="ID" value="{$mydata.sour}" />
          <input type="hidden" name="SOUR" value="@S{$mydata.sour}@" />
            <table width='300' class="normal" cellpadding="3"  cellspacing="0" border="0">
              <tr>
                <td>##Title##</td>
                <td><input name="sour_titl" class="normal" size="38" value='{$mydata.titl}' /></td>
              </tr>
              <tr>
                <td>##Author##</td>
                <td><input name="sour_auth" class="normal" size="38" value='{$mydata.auth}' />
                </td>
              </tr>
              <tr>
                <td>##Reliability##</td>
                <td>
                  {html_options options=$quay_values name="sour_quay" selected=$mydata.quay class="normal"}
                  <font class="normal">##3 = Highest Reliability##</font>
                </td>
              </tr>
              <tr>
                   <td>##Publication##</td>
                   <td><input name="sour_publ" size="38" class="normal" value='{$mydata.publ}' />
                   </td>
              </tr>
              <tr>
                   <td>##Call number##</td>
                   <td><input name="sour_caln" size="38" class="normal" value='{$mydata.caln}' />
                   </td>
              </tr>
              <tr>
                <td valign=top>##Notes##</td>
                <td><textarea name="sour_note" class="normal" rows="4" cols="40">{$mydata.note}</textarea>
                </td>
              </tr>
            </table>
            {if !isset($smarty.session.read_only ) || $smarty.session.my_rec != $mydata.ID}
              <input type="submit" value='##Submit##' />
              <input type="button" onclick='exit1();' value='##Close##' />
            {/if}
        </form>

        {if $mydata.postik == 1}

      {*  <script language='JavaScript'>
                 alert('##The source has been updated or added successfully. Click OK to close this popup confirmation and the source editing window. The parent window will refresh automatically to reflect this change.## ');
                exit1();
        </script> *}

        {/if}
</table>

{include file=popup_footer.tpl}