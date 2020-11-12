{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
   <td class="title" align="left">##Add/Edit Sources##</td>
  </tr>
  <tr>
    <td align="left" class="normal">
{if !isset( $smarty.session.read_only) || $smarty.session.my_rec == $mydata.ID}
      <a  href='editsour.php'>
      ##Add Source##</a> |
      <a href='#' onclick='exit1()'>
      ##Close Window##</a>
      <br /><br />

{/if}
{if $mydata.del == 1}
  <table width='300'>
    <tr>
      <td>
        <font class="normal">
          {$mydata.delmsg}
        </font>
      </td>
    </tr>
  </table>
  <br />
{/if}
{if $mydata.recscnt > 0}

        <table width='100%' class="normal">

        {foreach from=$sourcesList key=key item=disp}

                <tr>
                        <td>
                                &nbsp;
                                <a href='editsour.php?ID={$disp->id}'>
                                ##Source## # {$disp->id}
                          	( {$disp->titl|stripslashes} )
                                </a>
                        </td>
                <td>

                                {if !isset($smarty.session.read_only ) ||         $smarty.session.my_rec != $mydata.ID}

                                        <a href='#' onclick='if (confirm("##Are you sure? Citations based on this source will also be removed.##")) location.href="listsources.php?del=1&amp;sid={$disp->id}&amp;ID={$mydata.ID}"'>##Remove##
                                        </a>

                                {/if}

                        </td>
                </tr>

     {/foreach}

        </table>

        <br />
        <a href='#' onclick='exit1()'>##Close Window##</a>

{/if}
    </td>
  </tr>
</table>


{* Do NOT uncomment these lines *}
{*  This is text passed by PHP, added here for the language editor

##Source## ##has been deleted successfully.##
##Source delete problem.##

*}

{include file=popup_footer.tpl}