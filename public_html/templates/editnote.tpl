{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="5">
{if $mydata.sid != ''}
  <tr>
   <td class="title" align="left">{$mydata.hdr}</td>
  </tr>
        {if $mydata.err}
          <tr>
             <td align="left" style="color: #FF0000;">{$mydata.err}</td>
          </tr>
        {/if}
        <tr>
         <td align="left">
                    <form action='editnote.php' method="post">
                 <table width='100%' class="normal">
                         <tr>
                                 <td width='10%'><textarea name="snote" class="normal" rows="9" cols="67">{$mydata.note}</textarea>
                                 </td>
                         </tr>
                 </table><br />
                         <input type="hidden" name="sid" value='{$mydata.sid}' />
                         <input type="hidden" name="postik" value='1' />
                         <input type="hidden" name="ID" value='{$mydata.ID}' />
                         <input type="hidden" name="xtag" value='{$mydata.xtag}' />
                         <input type="hidden" name="mr" value='{$mydata.mr}' />
                         <input type="hidden" name="ct" value='{$mydata.ct}' />

                 {if !isset( $smarty.session.read_only) || $smarty.session.my_rec != $mydata.ID}

                         <input type="submit" value='##Submit##' />


                         <input type="button" onclick='exit1();' value='##Close##' />

                 {/if}
                 </form>
                         {if $mydata.postik == 1}
                         <script type="text/javascript" >
                                         alert("##The notes submission was successful. Click OK to close this popup confirmation and the note editing window, and return to the list of notes##");
                                         exit1();
                         </script>
                         {/if}
          </td>
        </tr>
{ else}

  <tr>
    <td class="normal" align="left">##Incorrect SID Information##</td>
    </td>
  </tr>
{/if}
</table>


{* Do NOT uncomment these lines *}
{* This text is passed by PHP, added here for the language editor
##No Data Updated##
##Note##
*}

{include file=popup_footer.tpl}