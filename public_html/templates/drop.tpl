<table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
                <td class="title">
                        ##Permanently Delete the ##
                        {$smarty.session.user}
                        ## Family Tree##
                </td>
        </tr>
        <tr>
             <td class="normal">
             ##this_will_pdelete##
             </td>
        </tr>
        <tr>
            <td class="normal">
                        ##Only the $user tree will be removed. ##
                        ##All other trees in the database will be left intact.##
           </td>
        </tr>
        <tr>
                <td>
                        <form method="post" action="drop.php" name="dropform">
                                <input type="submit" name="deleteTree" value="##Yes, delete this tree##" class="normal" />
                                <input name="cancel" type="button" class="normal" id="cancel" onclick="javascript:window.location='index.php';" value='##Do NOT delete this tree!## ' />
            </form>
                </td>
        </tr>
</table>
<script type="text/javascript">
        var inmenu=4;
</script>