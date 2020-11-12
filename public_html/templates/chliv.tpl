
{* This file is the display file for chliv.php  *}

{if isset($smarty.session.admin) || isset($smarty.session.master) }

        <font class="title">##Information Hide##</font><br/><br />
        <font class="normal">##chliv_txt_1##
        </font><br /><br />
        <form action="chliv.php" method="get" name="chliv_form">

                <font class='normal'>##Hide Type##</font>
                <select name="shtyp">
                        <option {if $mydata.hidetype == 1} selected="selected" {/if} value="1"> ##hop1##</option>
                        <option {if $mydata.hidetype == 2} selected="selected" {/if} value="2"> ##hop2##</option>
                        <option {if $mydata.hidetype == 3} selected="selected" {/if} value="3"> ##hop3##</option>
                        <option {if $mydata.hidetype == 4} selected="selected" {/if} value="4"> ##hop4##</option>
                        <option {if $mydata.hidetype == 5} selected="selected" {/if} value="5"> ##hop5##</option>
                </select> 
                <br /><br />
                <input type="hidden" name="installik" value="1" />
                <input type="submit" value='##Change##' />
        </form>

{ else}

        <br />
        <table width='100%'>
                <tr>
                        <td class="normal">##To use this feature, please login using the "Administrator" password that was created during installation##.
                        </td>
                </tr>
        </table>

{/if}
<script type="text/javascript">
        var inmenu=4;
</script>