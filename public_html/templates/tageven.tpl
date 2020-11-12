{include file=popup_header.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
   <td class="title" align="left">
   	{if $mydata.mr == 1}
	   	##Marriage Events##
	   {else}
		   ##Life Events for##&nbsp;{$mydata.name}
   	{/if}</td>
  </tr>
{if !$smarty.session.read_only}
   <tr>
     <td align="left">
        <form action='tageven.php' method="post" name="tagevenform">
				{if !$mydata.addneweven}
                <span class="normal">##Event Type##</span>
                <select name="xtag" class="normal">
                   { html_options options="$tagslist"}
                </select>
                {if !isset($smarty.session.read_only) && $smarty.session.my_rec != $mydata.ID}
                        <input type="submit" value='##Add##' />
                {/if}
           {else}
	           <span class="normal">##Adding a new event:## {$mydata.xtagdisp}</span>&nbsp;&nbsp;<br />
	        {/if}
                <input type="button" onclick='window.close()' value='##Close window##' />
                <input type="hidden" name="ID" value="{$mydata.ID}" />
                <input type="hidden" name="mr" value="{$mydata.mr}" />
                <input type="hidden" name="addik" value="1" />
        </form>
    </td>
   </tr>
   <tr>
    <td><hr /></td>
   </tr>
{/if}
  <tr>
    <td class="normal" align="left">
<form action="tageven.php" method="post" name="tagevenform1">
        {if $mydata.DIV_Y == true}

        {/if}