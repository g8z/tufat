{if isset( $smarty.session.master)}
	{if $showform}
	<table>
		<tr><td class='title'>##Language Options##</td></tr>
	</table>
	<form name="langselect" action="editlang.php" method="post">
		{foreach item=item key=key from=$reqvars}
			<input type='hidden' name='{$key}' value='{$item}' />
		{/foreach}
	<br />
	<table>
		<tr>
			<td class='normal'>##Choose Language: ##</td>
			<td class='normal'>
					<select name="elang" onchange="submit();">
           			{html_options options="$languages" selected="$elang"}
       			</select>
       	</td>
       	<td style="width: 50px;">&nbsp;</td>
       	{if $elang != $default_lang}
       	<td><a href="editlang.php?removelang={$elang}">##Remove This Language##</a></td>
       	<td style="width: 50px;">&nbsp;</td>       	
       	{/if}
       	<td><a href="editlang.php?newlang=1">##Create a New Language##</a></td>
		</tr>
	</table>
	<br />
	<table>
		<tr>
			<td><a href="editlang_adv.php">##Advanced Language Options##</a></td>
       	<td style="width: 20px;">&nbsp;</td>
       	<td class="normal">##Go here to update the language database, or to import / export languages##.</td>
		</tr>
	</table>
	</form>
	<hr style="margin-top: 5px; margin-bottom: 5px;"/>

	<form name="search_tags" action="editlang.php" method="post">
		{foreach item=item key=key from=$reqvars}
			<input type='hidden' name='{$key}' value='{$item}' />
		{/foreach}					
		<input type="hidden" name="search" value="1" />
	<table>
		<tr><td colspan='6'><b>##Search Language Tags##</b></td></tr>
		<tr>
			<td>&nbsp;&nbsp;##Find##&nbsp;</td>
			<td>
				<select name="search_for">
					{html_options options=$search_opts selected=$search_for}				
				</select>
			</td>
			<td>&nbsp;##containing##&nbsp;</td>
			<td><input type="text"  name="searchstr" value="{$searchstr}" /></td>
			<td>&nbsp;##in##&nbsp;
				<select name="search_where">
					{html_options options=$searchw_opts selected=$search_where}				
				</select>			
			</td>
			<td><input type="submit" name="submit" value="##Search##" /></td>
		</tr>
	</table>
	</form>
	<table style="margin-top: 5px; width:100%;">
		<tr>
			<td style="width: 45%;"><hr /></td>
			<td style="width: 10%" align='center'>##or##</td>
			<td style="width: 45%;"><hr /></td>
		</tr>	
	</table>
	
	<table style="width: 100%; margin-bottom: 5px;">
		<tr>
			<td colspan='2'><b>##Choose a Template##</b></td>
		</tr>
{* Uncomment this section if you use templates in paths other than the default *}
{*		
		<tr>
			<td>&nbsp;&nbsp;##Template Path:##</td>
			<td>
				<i>{$template_path}</i>
				<form name='selfile' method="post" action="editlang.php">
						{foreach item=item key=key from=$reqvars}
						<input type='hidden' name='{$key}' value='{$item}' />
						{/foreach}
				<input type="text" style="width: 250px;" name="tpl_path" value='{$template_path}' />
				<input type="hidden" name="tplfilename" value="" />
				<input type="submit" name="submit" value="##Go##" />
				</form>
			</td>
		</tr>
*}
		<tr>
			<td>&nbsp;&nbsp;##Choose Template:##</td>
			<td>
				<form name='selfile' method="post" action="editlang.php">
					{foreach item=item key=key from=$reqvars}
						<input type='hidden' name='{$key}' value='{$item}' />
					{/foreach}
				<input type="hidden" name="tpl_path" value="{$template_path}" />
				<select name='tplfilename'>
					<option>--</option>
					{html_options options=$filelist selected=$tplfilename}
				</select>
				<input type="submit" name="submit" value="##Go##" />
				</form>
			</td>
		</tr>
	</table>
	<hr style="margin-top: 5px; margin-bottom: 5px;"/>


	{if $msg}		<table><tr><td style="color: red;">{$msg}</td></tr></table> {/if}
	{if $tpl_tags}	

	<form name="editlang" action="editlang.php" method="post">
	<input type="hidden" name="action" value="updatelang" />
	<input type="hidden" name="elang" value="{$elang}" />
	<input type="hidden" name="page" value="{$thispage + 1}" />
		{foreach item=item key=key from=$reqvars}
			<input type='hidden' name='{$key}' value='{$item}' />
		{/foreach}
	{foreach from=$tpl_tags item=item key=key}
		<input type="hidden" name="tpltags_orig[{$key}]" value="{$item}" />
	{/foreach}
	<table>
		<tr>
			<td colspan='2' width="100%">
					<table width="100%">
						<tr>
						{if $search_where AND $search_for}
							<td align='left'>
									<b>##Search Results##:</b>
							</td>
						{elseif $tplfilename}
							<td align='left'>
								##Language tags found in## <b>{$tplfilename}</b>
								<br />&nbsp;&nbsp;[&nbsp;<b>{$languages[$elang]}</b>&nbsp;]
							</td>
						{else} <td>&nbps;</td>
						{/if}
						</tr>					
						<tr>
							<td align='right' style="text-align: right;">
								{if $totalpages > 1}
								<table cellpadding='2' style="width: 100%;">
									<tr><td align='right'>
										<span style="display: block;">
										{if $recs_low and $recs_high}	
											##Showing## {$recs_low} - {$recs_high} ##of## {$recs_total}&nbsp;|
										{else}
											##Showing## {$recs_total} ##of## {$recs_total}&nbsp;|
										{/if}
											{if $pageprev}<a href="?{if $searchstr}search=1&searchstr={$searchstr}&search_for={$search_for}&search_where={$search_where}&{/if}tpl_path={$template_path}&amp;tplfilename={$tplfilename}&amp;page={$pageprev}"><--&nbsp;##prev##</a>&nbsp;|&nbsp;{/if}
											##Page## {$thispage + 1} ##of## {$totalpages}	
											{if $pagenext}&nbsp;|&nbsp;<a href="?{if $searchstr}search=1&searchstr={$searchstr}&search_for={$search_for}&search_where={$search_where}&{/if}tpl_path={$template_path}&amp;tplfilename={$tplfilename}&amp;page={$pagenext}">##next##&nbsp;--></a>{/if}
										</span>
									</td></tr>
								</table>
								{/if}
							</td>						
						</tr>
						{if $search_where AND $search_for}
						<tr>
							<td colspan='2'>
									##Looking for##&nbsp;<b>{$search_opts[$search_for]}</b> ##containing## 
									"<b>{$searchstr}</b>" ##in## <b>{$searchw_opts[$search_where]}</b>
							</td>
						</tr>
						{/if}
					</table>
			</td>
		</tr>
		<tr><td colspan='2'>&nbsp;</td></tr>
		<tr><td>&nbsp;&nbsp;<b>##Tag##</b></td><td>&nbsp;&nbsp;<b>##Translation##</b></td></tr>
		{foreach item=item key=key from=$tpl_tags}
			<tr>
				<td style="width: 260px; padding: 4px;">
					<table style="width: 100%;">
					<tr>
						<td align='left' style="width: 30px;"><span style="color: #777777;">&#x23;&#x23;</span></td>
						<td align='left'><span><input type='hidden' name="tags[{$key}]" value="{$key}" />{$key}</span></td>
						<td align='right' style="width: 30px;"><span style="color: #777777;">&#x23;&#x23;</span></td>
					</tr>					
					</table>
				</td>
				<td><textarea name="tag_vals[{$key}]" {*style="width: 370px; height: 60px; padding: 5px;"*} rows="3" cols="40">{$item}</textarea></td>
			</tr>
		{/foreach}
		
		<tr><td colspan='2' style='height: 10px;'>&nbsp;</td></tr>
		<tr>
			<td colspan='2' width="100%">
					<table width="100%">
						<tr>
							<td align='left'>&nbsp;
							</td>
							<td align='right' style="text-align: right;">
								{if $totalpages > 1}
								<table cellpadding='2' style="width: 100%;">
									<tr><td align='right'>
										<span style="display: block;">
										{if $recs_low and $recs_high}	
											##Showing## {$recs_low} - {$recs_high} ##of## {$recs_total}&nbsp;|
										{else}
											##Showing## {$recs_total} ##of## {$recs_total}&nbsp;|
										{/if}
											{if $pageprev}<a href="?{if $searchstr}search=1&searchstr={$searchstr}&search_for={$search_for}&search_where={$search_where}&{/if}tpl_path={$template_path}&amp;tplfilename={$tplfilename}&amp;page={$pageprev}"><--&nbsp;##prev##</a>&nbsp;|&nbsp;{/if}
											##Page## {$thispage + 1} ##of## {$totalpages}	
											{if $pagenext}&nbsp;|&nbsp;<a href="?{if $searchstr}search=1&searchstr={$searchstr}&search_for={$search_for}&search_where={$search_where}&{/if}tpl_path={$template_path}&amp;tplfilename={$tplfilename}&amp;page={$pagenext}">##next##&nbsp;--></a>{/if}
										</span>
									</td></tr>
								</table>
								{/if}
							</td>
						</tr>					
					</table>
			</td>
		</tr>

	</table>
	<hr style="margin-top: 5px; margin-bottom: 5px;"/>
	<br />
	<table width="100%">
		<tr><td align='center'><input type="submit" name="submit" value="##Save All Changes on This Page##" /></td></tr>
	</table>
	</form>
	{else}
		<table><tr><td>##No template specified, or no language tags## (##eg.## &#x23;&#x23;....&#x23;&#x23;) ##were found##.</td></tr></table>	
	{/if}
{/if}
<br /><br /><br /><br />

{else}

        <font class="normal"><br /><br />
        ##You should##<a href="login.php">##login##</a> ##as a Master##
        </font>
        <br /><br />


{/if}
{* DO NOT UNCOMMENT THESE LINES

##Invalid search parameters##
##Invalid search string##
##Language tags successfully updated##
##this template##
##all templates##
##translations##
##tags##
##tags or translations##
##tags and translations##
##this template##
##all templates##

*}