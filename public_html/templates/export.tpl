<form method="post" action="export.php" name="export">
<table width="580" border="0" cellspacing="0" cellpadding="3">
                <tr>
                     <td colspan="2" class="title">##Export your tree to the GEDCOM format##</td>
                </tr>
                <tr><td class="normal" colspan="2">&nbsp;</td></tr>
                <tr>
                     <td colspan="2" class="normal">##the_gedcom_file##</td>
                </tr>
                <tr>
                     <td colspan="2" class="normal">##it_is_standard_practice##</td>
                </tr>
                <tr><td class="normal" colspan="2">&nbsp;</td></tr>
                <tr>
                        <td width="20%" class="normal">##Full Name##
                        </td>
                        <td width="80%">
                                <input name="name" type="text" class="normal" id="name" size="30" maxlength="74" />
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Address, line 1##
                        </td>
                        <td><input name="addr_1" type="text" class="normal" id="addr_1" size="30" maxlength="74" />
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Address, line 2##</td>
                        <td><input name="addr_2" type="text" class="normal" id="addr_2" size="30" maxlength="74" />
                        </td>
                </tr>
                <tr>
                        <td class="normal">##Address, line 3##</td>
                        <td><input name="addr_3" type="text" class="normal" id="addr_3" size="30" maxlength="74" />
                        </td>
                </tr>
                <tr>
                        <td class="normal">##E-Mail address##</td>
                        <td><input name="addr_4" type="text" class="normal" id="addr_4" size="30" maxlength="74" />
                        </td>
                </tr>
                <tr>
                        <td class="normal"> ##Phone Number##</td>
                        <td><input name="phone" type="text" class="normal" id="phone" size="30" maxlength="74" /></td>
                </tr>
                <tr>
                        <td class="normal">##Compression##</td>
                        <td class="normal">
                        <input name="arctype" type="radio" value="no" checked="checked" /> ##No compression##
                        <input name="arctype" type="radio" value="zip" /> ##.zip##
                        <input name="arctype" type="radio" value="gz" /> ##.tar.gz##
                        </td>
                </tr>
                <tr>
                        <td class="normal">&nbsp;</td>
                        <td class="normal"><input type="checkbox" name="paf" /> ##PAF Compatible##</td>
                </tr>
                <tr>
                        <td>&nbsp;</td>
                        <td>
                                <input name="exportSubmit" type="submit" id="exportSubmit" value="##Submit##" />
                                <input name="cancel" type="button" class="normal" id="cancel" onclick="javascript:window.location='load.php?ID={$mydata.$ID}';" value="##Cancel##" />
                        </td>
                </tr>
</table>
        </form>
<script type="text/javascript" >
        var inmenu=2;
</script>