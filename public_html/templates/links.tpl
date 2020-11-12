<script type="text/javascript">
function delConfirm(s)
{ldelim}
        a = confirm("##Are you sure you would like to remove this link?##");
        if (a) {ldelim}
                location.href=s;
        {rdelim}
{rdelim}
function checkInput() {ldelim}
	if ( document.linksform.slink.value.trim() == '' ) {ldelim}
		alert("##Please input a URL##");
		return false;
	{rdelim}
	else if ( document.linksform.stitle.value.trim() == '' ) {ldelim}
		alert("##Please input a title##");
		return false;
	{rdelim}
	return true;
{rdelim}
</script>

<font class="title">##Links Gallery##</font>
<br /><br />
<font class="normal">

{if !isset( $smarty.session.read_only )}

        <a href='links.php?ID={$mydata.ID}&amp;add=1'>##Add Links##</a> |

{/if}

<a href='links.php?ID={$mydata.ID}&amp;view=1'>##View##</a></font>
<br />

{if $mydata.edit == 1 && !isset( $smarty.session.read_only ) ||  $smarty.session.my_rec == $mydata.ID}
        {if $mydata.edik == 1}
                {if $mydata.loadok}
                
                        <font class="normal">##The link has been successfully updated to the gallery##
                        </font>

                {else}

                        <font class="normal">##There was a problem performing the update. Please contact your system administrator.##
                        </font>

                {/if}
        { else}
                {if $mydata.editcnt > 0}

                        <br />
                        <form action=links.php method="post" name=linksform>
                                <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                        <tr>
                                                <td>##URL Link:##</td>
                                                <td><input type="text" name="slink" size="30" value='{$a.link}' />
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>##Title:##</td>
                                                <td><input type="text" name="stitle" size="50" value="{$a.title|stripslashes}" />
                                                </td>
                                        </tr>
                                        <tr>
                                                <td valign="top">##Description:##</td>
                                                <td><textarea cols="60" class="normal" rows="3" name="sdescr">{$a.descr|stripslashes}</textarea>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <input type="submit" value='##Update##' onclick="return checkInput();" />
                                                        <input type="button" value='##Delete##' onclick='delConfirm("links.php?ID={$mydata.ID}&amp;del=1&amp;fp={$mydata.fp}&amp;mid={$mydata.mid}")' />
                                                </td>
                                        </tr>
                                </table>
				<input type="hidden" name="edik" value='1' />
				<input type="hidden" name="edit" value='1' />
				<input type="hidden" name="ID" value='{$mydata.ID}' />
				<input type="hidden" name="mid" value='{$mydata.mid}' />
                        </form>

                {/if}
        {/if}
{ elseif !isset( $smarty.session.edit_only ) && $mydata.add == 1 && ( !isset( $smarty.session.read_only) || $smarty.session.my_rec == $mydata.ID  ) }
        {if $mydata.addik == 1}

                <br />

                {if $mydata.addok}

                        <font class="normal">##The link has been successfully added to the gallery.##
                        </font>

                { else}

                        <font class="normal">##There was a problem adding the link. Please contact a system Administrator.##
                        </font>

                {/if}
        { else}

                <br />
                <form action=links.php method="post">
                        <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                <tr>
                                        <td>##URL Link:##</td>
                                        <td><input type="text" name="slink" size="30" />
                                        </td>
                                </tr>
                                <tr>
                                        <td>##Title:##</td>
                                        <td><input type="text" name="stitle" class="normal" size="50" />
                                        </td>
                                </tr>
                                <tr>
                                        <td valign="top">##Description:##</td>
                                        <td><textarea cols="60" class="normal" rows="3" name="sdescr"></textarea></td>
                                </tr>
                                <tr>
                                        <td><input type="submit" class="normal" value='##Add##' />
                                        </td>
                                </tr>
                        </table>
                                <input type="hidden" name="addik" value='1' />
                                <input type="hidden" name="add" value='1' />
                                <input type="hidden" name="ID" value='{$mydata.ID}' />
                </form>

        {/if}
{elseif  !isset( $smarty.session.edit_only) && $mydata.del == 1 && $mydata.mid != '' && ( !isset( $smarty.session.read_only ) ||  $smarty.session.my_rec == $mydata.ID )}

        <br />

        {if $mydata.delok}

                <font class="normal">##Delete successfull##
                </font>

        { else}

                <font class="normal">##Delete problem##
                </font>

        {/if}
{ elseif ( $mydata.view == 1 || !isset( $mydata.add ) || !isset( $mydata.tnail ) || !isset( $mydata.del ) ) && $mydata.tnail != 1 }

        {if $mydata.recscnt > 0}

                <br />
                <font class="normal"><b>{$mydata.recscnt} ##items found##</b></font>
                <br /><br />
                <table  class="normal" cellpadding="0" cellspacing="0" border="0" width="300">

                        {foreach from=$linksList key=key item=disp}

                        <tr>
                                <td colspan="2">
                                        <table class="normal"  width='100%' cellpadding="2" cellspacing="0" border="0">
                                                <tr>
                                                        <td><a href='{$disp->link}' target='_blank'>{$disp->title|stripslashes}</a>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td>{$disp->descr|stripslashes|nl2br}</td>
                                                </tr>
                                                <tr>
                                                        <td><a href='links.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$disp->id}'>##Edit Link##</a> | 
														<a href='links.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$disp->id}'>##Delete##</a>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                        <tr><td width="100%"><hr /></td><td width="1">&nbsp;</td></tr>
                        {/foreach}

                </table>

        { else}

                <br />
                <font class="normal">##The gallery is empty##</font>

        { /if}
{/if}
<script type="text/javascript">
        var inmenu=0;
</script>