{* Name changed from index.tpl to index_page.tpl to accomodate new template processing *}
{* 2006/06/12 Pat K <cicada@edencomputing.com> *}
        {* This is the default TPL file for index.php *}

{if $mydata.individuals == 0}
        <table border="0" cellpadding="5" cellspacing="0" width='100%' align="left">
                <tr>
                 <td class="subtitle" align="left">##Welcome to TUFaT, The Ultimate Family Tree creation system. ##</td>
                </tr>
                <tr>
                  <td class="normal" align="left">##with_tufat_you_will##</td>
                </tr>
                <tr>
                  <td class="normal" align="left">##since_i_created##</td>
                </tr>

                            {if $mydata.animalPedigree}
                                  <tr><td class="normal" align="left">##you_will_need_pe##</td></tr>
                            { else}
                                  <tr><td class="normal" align="left">##you_will_need##</td></tr>
                            {/if}
                                {if !isset( $smarty.session.edit_only) && !isset( $smarty.session.read_only )       }
                                  <tr><td class="normal" align="left"><a href="edit.php?new=1">##Create the first record in this family tree##</a><span class="normal">## ...or... ##</span> <a href="import.php">##Generate this tree from an imported GEDCOM file##</a>
                                  </td></tr>
                                { else}
                                  <tr><td class="normal" align="left">##There are no individuals in this family tree yet.##</td></tr>
                                {/if}
                                  <tr><td class="normal" align="left">##Enjoy, and thanks for using the system!##</td></tr>
                                  <tr><td class="normal" align="left"><a href="mailto:g8z@yahoo.com">Darren Gates</a>, ##creator of TUFaT##</td></tr>
 </table>
{ else}

        <table border="0" cellpadding="5" width='100%'cellspacing="0">
                <tr>
                        <td class="title" align="left">{$mydata.treeName}</td>
                </tr>
                <tr>
	                {if !$mydata.animalPedigree}
                        <td class="normal" align="left">##Click on an individual's name to load the record. You may return to this page at any time by clicking the 'Entire Tree' link at the left. Records are sorted here by family name, although different families who share a family name are placed together.##</td>
                   {else}
                       <td class="normal" align="left">##Click on an animal's name to load the record. You may return to this page at any time by clicking the 'Entire Tree' link at the left. Records are sorted here by family name, although different families who share a family name are placed together.##</td>
                    {/if}
                </tr>
        </table>
        {if !isset( $smarty.session.edit_only )}
            <table border="0" cellpadding="5" cellspacing="0">
               <tr>
                 <td align="left"><a href="edit.php?new=1">##Create a new record in this family tree##</a></td>
               </tr>
            </table>

        {/if}
        {if !$mydata.animalPedigree}
                {if !isset( $smarty.session.edit_only ) && $mydata.members_count > 300}
                         |
                {/if}
                {if $mydata.norm != 1 && $mydata.members_count > 300}
                           {if $mydata.members_count > 300}
                                <a href="index.php?sby={$mydata.sby1}&amp;norm=1">##Switch to non frame view##
                                </a><br />
                        {/if}
                        <br />
                        <table border="0" height='380' width='100%' cellpadding="5" cellspacing="0">
                                <tr>
                                        <td width='50%' class="normal"><b>##Surnames##</b></td>
                                        <td width='50%' class="normal"><b>##Individuals##</b></td>
                                </tr>
                                <tr>
                                        <td width='50%'  height='100%'>
                                                <iframe frameborder="0" width='100%' height='100%' src='listsurn.php'>
                                                </iframe>
                                        </td>
                                        <td width='50%'>
                                                <iframe frameborder="0" border="0" height='100%'  width='100%' name="pindi" src="listindi.php">
                                                </iframe>
                                        </td>
                                </tr>
                        </table>
                        <br />

                { else}
                           {if $mydata.members_count > 300}
                               <a href="index.php?sby={$mydata.sby1}&amp;norm=">##Switch to frame view##</a>
                        {/if}
                        {if $mydata.family_count > 0}
                        {/if}
                  
                       {if $families}
                        <table border="0" cellpadding="5" cellspacing="0" width='100%' >
                                <tr>

                                {foreach name=cols from=$families key=key item=family}
                                        <td valign="top" width="33%">
                                                <table border="0" cellpadding="1" cellspacing="0" width='100%'>

                                                {foreach name=loop1 from=$family key=key item=members}
                                                        <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                                <td colspan="2" class="subtitle">{$key}</td>
                                                        </tr>
                                                        {foreach from=$members key=key item=member}
                                                                {if $key !='' and $member != ''}
                                                                  <tr>
                                                                    <td width="4">&nbsp;</td>
                                                                    <td><a href="load.php?ID={$key}">{$member}</a></td>
                                                                  </tr>
                                                                {/if}
                                                        {/foreach}
                                                {/foreach}
                                             </table>
                                        </td>
                                {/foreach}
                              </tr>
                        </table>
                       {/if}
                        <br />
                {/if}
        {/if}
         {if $mydata.list_count > 0 || $mydata.animalPedigree}

                       {if $mydata.animalPedigree}
                       <form action="index.php" method="post" name="index_01">
                            <table cellpadding="5" cellspacing="0" border="0">
                                    <tr>
                                            <td class="normal">
                                                ##View by##
                                                <select name="sby" class="normal">
                                                { html_options options=$mydata.sby_list selected=$mydata.sby}
                                                </select>
                                                <input type="submit" value="##Submit##" />
                                            </td>
                                    </tr>
                            </table>
                    </form>
                    <table>
                        {foreach name=famly from=$newlist key=family_name item=members}
                             {if $smarty.foreach.famly.iteration % $mydata.columns == 1}
                               <tr>
                             {/if}
                                 <td valign="top">
                                {foreach from=$members key=key item=item}
                                                                  <tr>
                                                                    <td width="4">&nbsp;</td>
                                                                    <td><a href="load.php?ID={$key}">{$item}</a></td>
                                                                  </tr>
                                {/foreach}
                                </td>
                           {if $smarty.foreach.famly.iteration % $mydata.columns == 0}
                              </tr>
                           {/if}
                                {/foreach}
                                      <tr>
                                                                    <td width="10">&nbsp;</td>
                                                                    <td></td>
                                                                  </tr>

                </table>
         { else}
         <br />
                              <font class="normal">&nbsp;&nbsp;##The following individuals do not belong to a family unit:##</font><br /><br />

                                {foreach from=$newlist key=key item=member}
				 
				 {if $member}
                                 &nbsp;&nbsp;<a href="load.php?ID={$key}">{$member}</a><br />
                                 {assign var=found value=true}
                                 {/if}
                              				 
                                {/foreach}
                                
                                {if $found eq false}
                                 &nbsp;&nbsp;##(none)##<br />
                                {/if}

                                <br />
                        {/if}
                {/if}
            <table border="0" cellpadding="0" cellspacing="0">
               <tr>
                 <td align="left" class="normalSmall">##Last Login##:&nbsp;{$lastLogin}</td>
              </tr>
               <tr>
                 <td align="left" class="normalSmall">##Tree Created##:&nbsp;{$dateCreated}</td>
              </tr>
            </table>
{/if}
<script type="text/javascript">
        var inmenu=0;
</script>


{* Do NOT uncomment these lines *}
{*  This text is passed by PHP, included here for the language editor

##(no surname given)##
##(no name given)##
##Animal Name##  ##Owner## ##Breed##

*}