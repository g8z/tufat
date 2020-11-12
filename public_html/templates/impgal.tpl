
<script type="text/javascript">

function check()
{ldelim}
        if (document.f.sfile.value.indexOf('.zip') > 0)
        {ldelim}
                alert('##The import script does not support ZIP format.##');
                return false;
        {rdelim}
        if (document.f.sfile.value.indexOf('.tar.gz') > 0)
        {ldelim}
                return true;
        {rdelim}
        else
        {ldelim}
                alert('##The import script supports only tar.gz format.##');
                return false;
        {rdelim}
        return true;
{rdelim}
</script>

{if $mydata.sfile  != ''}

        <font class="normal">

        {foreach from=$filesList key=key item=disp}

                {if $disp->vol > 0}

                        ##Importing file## {$disp->fname} ( {$disp->vol} KB ) ##OK## <br />

                { else}

                	##Importing file## {$disp->fname} ##FAIL##<br />

                {/if}
        {/foreach}

        <br />
                <a href='famgal.php?ID={$mydata.ID}'>##Back##</a><br />
        </font>

{ else }

        <table width='100%'>
                <tr>
                        <td>
                                <font class="title">##Import a tar.gz file to your $g gallery##</font>
                        </td>
                </tr>
                <tr>
                        <td>
                                <font class="normal">##imp_gal_1##
                                </font>
                        </td>
                </tr>
        </table>
        <br />
        <form action='impgal.php' method="post" name="f" enctype='multipart/form-data' onsubmit='return check()'>
                <table class="normal">
                        <tr>
                                <td>##File Name##
                                </td>
                                <td><input class="normal" type="file" name="sfile" />
                                </td>
                        </tr>
                        <tr>
                                <td><input type="submit" value='##Import##' />
                                </td>
                        </tr>
                </table>
                        <input type="hidden" name="imp" value="1" />
                        <input type="hidden" name="kd" value="{$mydata.kd}" />
                        <input type="hidden" name="ID" value="{$mydata.ID}" />
                        <input type="hidden" name="indi" value="{$mydata.indi}" />
                        <input type="hidden" name="fid" value="{$mydata.fid}" />                
        </form>

{/if}

<script type="text/javascript">
        var inmenu={if $mydata.ex=='0'}4{ else}3{/if};
</script>