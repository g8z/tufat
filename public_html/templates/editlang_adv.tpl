{if isset( $smarty.session.master)}
	<table>
		<tr><td class='title'>##Advanced Language Options##</td></tr>
	</table>
	{if $msg}<table><tr><td class='errorBody'>{$msg}</td></tr></table>{/if}
	<br />
	{* begin update lang-database *}
	<table>
		<tr><td><b>##Update Language Database##</b></td></tr>
		<tr><td style="padding-left: 10px;">##This function will scan all templates in your template path looking for multi-language texts enclosed in pairs of hash marks like this##:</td></tr>
		<tr><td style="padding-left: 25px;"><tt>&#x23;&#x23;##English Text##&#x23;&#x23;</tt></td></tr>
		<tr><td style="padding-left: 10px;">##Run this command whenever you make changes to your templates##.</td></tr>
		<tr>
			<td>
			<table style="margin-top: 5px; margin-bottom: 5px;">
				<tr><td>##Template Path:##</td></tr>
				<tr><td><tt>{$template_path}</tt></td></tr>
			</table>
			</td>
		</tr>
		<tr>
		<td style="padding-left: 20px;">
			<form action="editlang_adv.php" method="post">
			<input type="hidden" name="action" value="update_langdb" />
			{foreach item=item key=key from=$reqvars}
				<input type='hidden' name='{$key}' value='{$item}' />
			{/foreach}
				<input type="submit" value="##Update the Language Database##" />
			</form>
		</td>
	</tr>
	</table>
	<br />
	 {* end update lang-database *}
	<hr style="margin-bottom: 5px;" />
	{* begin update langtags *}	
	<table>
		<tr>
			<td><b>##Update Language Tags##</b></td>
		</tr>
		
		<tr><td>&nbsp;&nbsp;##Use these options when restoring a template from a previous version of TUFaT##</td></tr>
		<tr><td>
			<p>&nbsp;&nbsp;##This will replace older style language tags ( tufat<u>&nbsp;</u>mytrans getvalue='...' )  with the newer &#x23;&#x23;...&#x23;&#x23; tag format.##</p>
		</td></tr>
	</table>


	<table>
		<tr><td style="padding-left: 20px;">
			<form action="editlang_adv.php" method="post">
				<input type="hidden" name="tpl_path" value="{$template_path}" />
				<input type="hidden" name="action" value="update_all" />
				<input type="submit" value="##Update Templates##" />&nbsp;&nbsp;(##Will check## {$tpl_count} ##files##)
			</form>
		</td></tr>
	</table>
	<br />
 {* end update langtags *}
	<hr style="margin-bottom: 5px;" />
 {* begin export form *}
	<form name="export_lang" method="post" action="editlang_adv.php">
	<input type="hidden" name="action" value="exportlang" />
		{foreach item=item key=key from=$reqvars}
			<input type='hidden' name='{$key}' value='{$item}' />
		{/foreach}
	<table>
		<tr>
			<td><b>##Export Language##</b></td>
		</tr>
	</table>		
	<table style="padding-left: 10px;">
		<tr>
			<td class="normal">##This function will export texts for one language to an XML file to your application's "temp" directory##: </td>
		</tr>
		<tr><td style="font-size: 10pt;">&nbsp;&nbsp;<tt>{$smarty.const.TEMP_DIR}</tt></td></tr>
		<tr>
			<td class="normal">
				##Texts that do not have a translation will be written to the XML file with the English text enclosed in an XML comment##
				&nbsp;<tt>(&lt;!-- ... --&gt;)</tt>. 
				&nbsp;##Add your translation after the comment (but inside the &lt;text&gt; element)##. 
				&nbsp;##You should only remove the comment marks if you wish to use the English text verbatim as the translation text##.
				&nbsp;##Do not remove or change the <tt>&lt;digest="..."&gt;</tt> attribute##.
			</td>
		</tr>
		</table>
		<table>
		<tr>
			<td>
				<table>
					<tr>
						<td>##Export Language##:</td>
						<td><select name="exp_lang">{html_options options=$languages selected=$export_lang}</select></td>
						<td>
							<table>							
								<tr><td><input type="radio" name="exp_opts" value="all" {if $exp_opts=='all' || $exp_opts==''}CHECKED{/if} />&nbsp;##All Text##</td></tr>
								<tr><td><input type="radio" name="exp_opts" value="trans" {if $exp_opts=='trans'}CHECKED{/if} />&nbsp;##Only Untranslated Text##</td></tr>
							</table>
						</td>
						<td><input type="submit" value="##Export##" /></td>
					</tr>				
				</table>
			</td>
		</tr>
	</table>
	</form>
	{* end export form *}
	<hr style="margin-bottom: 5px;" />	
	{* begin import form *}	
	<form name="importlang" action="editlang_adv.php" method="post">
	<input type="hidden" name="action" value="importlang" />
		{foreach item=item key=key from=$reqvars}
			<input type='hidden' name='{$key}' value='{$item}' />
		{/foreach}
	<table>
		<tr>
			<td><b>##Import Language##</b></td>
		</tr>
	</table>
	<table style="padding-left: 10px;">
		<tr><td class="normal">##This function will import a previously-exported language texts XML file that has had translations added##.</td></tr>
		<tr><td class="normal">##Only non-empty <tt>&lt;text&gt;</tt> elements will be imported. All text enclosed in XML comments will be ignored.##</td></tr>	
		<tr><td class="normal">##To import a new language, place the translated XML file in your application's "temp" directory##:</td></tr>
		<tr><td>&nbsp;&nbsp;<tt>{$smarty.const.TEMP_DIR}</tt></td></tr>
	</table>
	<br />		

	<table>
		<tr>
			<td>##Language files found##: </td>
			<td>
				{if $imp_filelist}
				<select name="imp_file">
					{html_options options=$imp_filelist selected=$imp_file}
				</select>
				{else}
				<select DISABLED><option>##None Found##</option></select>
				{/if}
			</td>
			<td><input type="submit" value="##Import##" /></td>	
		</tr>	
	</table>
	</form>
	{* end import form *}
<br />		
	<hr />
<br /><br /><br /><br />

{else}

        <font class="normal"><br /><br />
        ##You should re-login using the master password to perform this action.##
        </font>
        <br /><br />


{/if}