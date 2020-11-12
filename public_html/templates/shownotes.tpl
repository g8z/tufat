{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
     <td align="left" class="title">{$mydata.tagmsg}</td>
  </tr>
{if !isset($smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID}
  <tr>
    <td class="normal" align="left">
        <a href='shownotes.php?ct={$mydata.ct}&amp;add=1&amp;ID={$mydata.ID}&amp;xtag={$mydata.xtag}&amp;mr={$mydata.mr}'>##Add Notes##</a>
        |
        <a href='#' onclick='window.close()'>##Close Window##</a>
    </td>
  </tr>
{/if}
{if $mydata.del == 1 && $mydata.mid > 0}
  <tr>
    <td class="normal" align="left">{$mydata.delmsg}</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
{/if}
{if $mydata.recscnt > 0}
        {foreach from=$recsList item=hid}
                <tr>
                	<td>{$hid->note.str|stripslashes}</td>
                </tr>
                <tr>
                        <td>
                                {if !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID}
								
                                			 <input type='button' onclick='location.href="editnote.php?sid={$hid->hid}&amp;ID={$mydata.ID}&amp;mr={$mydata.mr}&amp;ct={$mydata.ct}&amp;xtag={$mydata.xtag}"' value="##Edit Note##" />	
                                        <input type='button' onclick='if (confirm("##Are you sure?##"))  location.href="shownotes.php?ID={$mydata.ID}&amp;del=1&amp;mr={$mydata.mr}&amp;ct={$mydata.ct}&amp;xtag={$mydata.xtag}&amp;mid={$hid->hid}"' value='##Delete##' />
                                {/if}
                        </td>
                </tr>
        {/foreach}

{/if}
</table>


{* do not uncomment these lines *}
{*  this is text passed by PHP, added here for the language editor

##Birth Notes##
##Death Notes##
##Adoption Notes##
##Baptism Notes##
##Bar/Mitzvah Notes##
##Graduation Notes##
##Immigration Notes##
##Confirmation Notes##
##Marriage Notes##
##Divorce Notes##
##Notes##
##Note # $mid has been deleted successfully.##
##Citation delete problem.##

*}

{include file=popup_footer.tpl}