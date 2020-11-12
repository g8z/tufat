{if $mydata.exp != 1}

        <script type="text/javascript">
        function check()
        {ldelim}
                if (document.f.sfile.value.indexOf('.zip') > 0)
                {ldelim}
                        alert('##The import script does not support ZIP format.##');
            return false;
        {rdelim}
                if (document.f.sfile.value.indexOf('.tar.gz') < 0)
        {ldelim}
                        alert('##The import script does not supports only tar.gz file format.##');
            return false;
                {rdelim}

        return true;
        {rdelim}
        </script>

{/if}
{if isset( $smarty.session.master ) || isset($smarty.session.admin) }
        {if $mydata.imp != 1 && $mydata.exp != 1}

                <font class='title'>##Import/Export Tree Portraits##</font><br />
                <br />

        {/if}
    {if $mydata.imp != 1 && $mydata.exp != 1}
                {if $mydata.ex == '0'}
                        <table width='100%'>
                                <tr>
                                        <td>
                                                <font class="subtitle">##Import individual portraits from a tar.gz file##</font>
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <font class="normal">##impp_txt_1##</font><br />
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <font class="normal">##The maximum size of the file upload can be limited by your server's PHP settings to## {$mydata.num}</font>
                                        </td>
                                </tr>
                        </table>
                        <form action='impexpp.php' name="f"  onsubmit='return check()' method="post" enctype='multipart/form-data'>
                        <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                                <td>##File Name##</td>
                                                <td><input class="normal" type="file" name="sfile" /></td>
                                        </tr>
                                        <tr>
                                                <td colspan="2"><input type="submit" value='##Import##' />
                                                </td>
                                        </tr>
                                </table>
                                        <input type="hidden" name="imp" value="1" />
                        </form>

                { else}

                        <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                <tr>
                                        <td>
                                                <font class="subtitle">##Export individual portraits to a tar.gz or .zip file##
                                                </font>
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <font class="normal">##expp_txt_1##
                                                </font><br />
                                        </td>
                                </tr>
                        </table>
                        <form action='impexpp.php' method="post">
                        {if !isset($smarty.session.master)}<input type="hidden" name="stree" value='{$mydata.user}' />{/if}
                        <table class="normal" cellpadding="5" cellspacing="0" border="0">


                           {if isset($smarty.session.master)  }
                                        {if $mydata.treelistcnt > 0}

                                           <tr>
                                                   <td><font class='normal'>##Tree##</font>
                                                   </td>
                                                   <td><select name="stree">

                                                                {foreach from=$treeList key=key item=disp}

                                                                        <option value="{$disp->tree}">
                                                                        {$disp->dname}</option>

                                                                {/foreach}

                                                                </select>
                                                        </td>
                                                </tr>

                                        { else}

                                                <tr>
                                                        <td>
                                                                <font class="normal">##There are no uploaded portraits.##
                                                                </font>
                                                        </td>
                                                </tr>

                                        {/if}
                       {/if}
                                {if $mydata.nop != 1}

                                        <tr>
                                                <td>
                                                        <input type="radio" checked="checked" value="0" name="zip" />tar.gz &nbsp;
                                                        <input type="radio" value="1" name="zip" />ZIP
                                                </td>
                                        </tr>
                                        <tr>
                                                <td><input type="submit" value='##Export##' />
                                                </td>
                                        </tr>

                                {/if}
                                </table>
                                <input type="hidden" name="exporting" value='1' />
                                <input type="hidden" name="exp" value="1" />
                        </form>

                {/if}
        {/if}
        {if $mydata.imp == 1}
                {if $mydata.fn == ''}

                        <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                <tr>
                                        <td><font class="normal">
                                                ##err1_impp##</font>
                                        </td>
                                </tr>
                        </table>
                        <br />
{*        Back button removed
<input type='button' onclick='location.href="index.php"' value='##Back##' />
*}

                { else}

                        <font class="normal">

                                {foreach from=$impList key=key item=disp}
                                        {if $disp->impok}

                                                ##Importing {$disp->txt} file {$disp->fname}## ({$disp->size} KB) OK <br />

                                        { else}

                                                ##Importing file {$disp->fname} FAIL##<br />

                                        { /if }
                       </font>
                    {if $disp->sf == 1}

                                            <table class="normal" cellpadding="5" cellspacing="0" border="0" width="100%">
                                                        <tr>
                                                                <td>
                                                                        <font class="normal">##impp_txt_2##
                                                                        </font>
                                                                </td>
                                                        </tr>
                                                </table>

                                        {/if}
                                {/foreach}

                        </font>

                {/if}
        {/if}
{else}

   <font class="normal">##You should login as a Tree Administrator to perform this action.##</font>

{/if}
<script type="text/javascript">
        var inmenu={if $mydata.ex=='0'}3{else}2{/if};
</script>
