<table  cellspacing="0" cellpadding="5" width='100%'>
{if  isset( $smarty.session.master) && $mydata.id != ''}
     <tr>
         <td class="title">##Edit language record##</td>
     </tr>
     <tr>
        <td class='normal'>
        {$mydata.upgmsg}
          {if $mydata.printok}
                  <form action='editlrec.php' method="post" >
                         <table class="normal" cellpadding="3" cellspacing="0" border="0" width="100%">
                                <tr>
                                        <td>##Key##</td>
                                        <td width="80%">{$mydata.w}
                                        </td>
                                </tr>
                                  <tr>
                                          <td>##Value##</td>
                                          <td width="80%"><textarea name="snewm" cols="40" rows="7">
                                          {$mydata.m}</textarea>
                                          </td>
                                  </tr>
                                <input type="hidden" name="id" value='{$mydata.id}' />
                                <input type="hidden" name="updatik" value="1" />
                          </table>
                        <input type="submit" value='##Update##' />
                </form>

        { /if }
      </td>
    </tr>
{ else}
    <tr>
       <td class="normal">##You should re-login using the master password to perform this action.##</td>
    </tr>

{/if}
</table>