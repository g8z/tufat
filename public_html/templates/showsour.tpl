{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
   <td class="title" align="left">{$mydata.tagmsg}</td>
  </tr>
{if !isset($smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID}
    <tr>
      <td align="left" class="normal">
        {if $mydata.optsListcnt > 0}
        <a href="showsour.php?cit=1&amp;ct={$mydata.ct}&amp;add=1&amp;ID={$mydata.ID}&amp;xtag={$mydata.xtag}&amp;mr={$mydata.mr}">
        ##Add Citation##</a> |
        {/if}
                <a href="#" onclick='window.open("listsources.php?ID={$mydata.ID}","open001","top=300,left=300,width=400,height=300,scrollbars=1,resize=1")'>
        ##Add/Edit Sources##</a> |
        <a href="#" onclick='exit1();'>##Close Window##</a>
     </td>
  </tr>
{/if}
{if $mydata.del == 1 && $mydata.mid > 0}
    <tr>
      <td align="left" class="normal">{$mydata.delmsg}</td>
    </tr>
{/if}
{if $mydata.err}
          <tr>
             <td align="left" style="color: #FF0000;">{$mydata.err}</td>
          </tr>
{/if}
{if $mydata.cnt > 0 && $mydata.optsListcnt > 0}
        <tr>
            <td  valign="top" class="normal" align="left">
                <form action='showsour.php' name="ff" method="post">
                <input type="hidden" name="changik" value='1' />
                <input type="hidden" name="xtag" value='{$mydata.xtag}' />
                <input type="hidden" name="mr" value="{$rec->mr}" />
                <input type="hidden" name="ID" value='{$mydata.ID}' />
                
                {foreach from=$recsList key=key item=rec}
                                          <table width='100%'  cellspacing="0" cellpadding="3" border="0">
                                          	{if $rec->noshow}
                                          		<tr><td colspan='2'><i>##New Citation##</i></td></tr>
                                          	{ else}
                                          		<tr><td colspan='2'><i>{$rec->source}</i></td></tr>
                                          	{/if}
                                                <tr>
                                                        <td>##Source##</td>
                                                        <td><select name="cit[{$rec->hid}][smt]" class="normal">

                                                                {foreach from=$mydata.optsList key=key item=opts}
                                                                        <option value='{$opts->id}' {if $opts->id == $rec->smt} SELECTED {/if}>
                                                                        	##Source## # {$opts->id}

                                                                        {if $opts->titl != ""}
                                                                                ( {$opts->titl} )
                                                                        {/if}
                                                                        </option>

                                                                {/foreach}
                                                                </select>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td>##Page Number##
                                                        </td>
                                                        <td><input type="text" name="cit[{$rec->hid}][spage]" size="30" value='{$rec->spage}' />
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td>##Date##</td>
                                                        <td><input type="text" size="30" name="cit[{$rec->hid}][sdate]" value='{$rec->sdate}' />
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td valign=top>##Comments##</td>
                                                        <td><textarea name="cit[{$rec->hid}][snote]" cols="42" rows="2">{$rec->snote|trim|stripslashes}</textarea>
                                                        </td>
                                                </tr>
                                        </table>
                                                <input type="hidden" name="cit[{$rec->hid}][hid]" value='{$rec->hid}' />
                                        <input type="submit" value='##Submit##' />
                                        {if !$rec->noshow}
                                        			<input type='button' onclick='window.open("editsour.php?ID={$rec->smt}","open002","left=300,top=225,width=400,height=300,scrollbars=1,resize=1")' value='##Show Source##' />
			                                   {if !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID}
         		                                       <input type='button' onclick='if (confirm("##Are you sure?##"))   location.href="showsour.php?ID={$mydata.ID}&amp;del=1&amp;xtag={$mydata.xtag}&amp;mr={$rec->mr}&amp;sid={$rec->smt}"' value='##Remove##' />

               	                          {/if}
													 {/if}
													 <br />
													 <br />
                {/foreach}
                 </form>
                   </td></tr>
{/if}
                </table>
  
{* do not uncomment these lines *}
{* this is text passed by PHP, added here for the language editor

##Birth Citations##
##Death Citations##
##Adoption Citations##
##Baptism Citations##
##Bar/Mitzvah Citations##
##Graduation Citations##
##Immigration Citations##
##Confirmation Citations##
##Marriage Citations##
##Divorce Citations##
##Citations##
##Citation has been deleted successfully.##
##Citation delete problem.##

*}

{include file=popup_footer.tpl}
