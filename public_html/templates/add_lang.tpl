{if isset($smarty.session.master)}
	<table><tr><td class="title">##Add a New Language##</td></tr></table>

                        <form action='editlang.php' method="post" name="editalllo1">
                                <table>
                                        <tr>  
                                                <td><font class='normal'>##Language Name##</font></td>
                                                <td><input type="text" name="slname" /></td>
                                        </tr>
                                        <tr>
                                                <td><font class='normal'>##Language Encoding##</font>
                                                </td>
                                                <td>
                                                        <select name="slenc" class="normal">
																				{html_options options=$langsList selected="UTF-8"}
                                                        </select>
                                                </td>
                                        </tr>
                                        
                                        <tr>
                                                <td colspan="2">
                                                <input type="hidden" name="add" value="1" />
                                                <input type="submit" value='##Add##' />
                                                </td>
                                        </tr>
                                </table>
                        </form>
{else}

        <font class="normal"><br /><br />
        ##You should re-login using your master password to perform this action.##
        </font>
        <br /><br />


{/if}