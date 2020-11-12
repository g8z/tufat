{* This is the .tpl file for selecting parent record  *}

<br />
        <b><font size="3" class="normal">
        ##Change## {$mydata.persontype_text} {$mydata.person_name}
        </font>
        </b>
        <br /><br />
        <form action="edit.php" name="change_parent" method="post" >
                <select class='normal' name="snid">
                        { html_options options="$snids" selected="$pid"}
                </select>
                <input type="submit" value='##Update##' />
                <input type="button" value='##Cancel##'  onclick="location.href='load.php?ID={$mydata.ID}'" />
                <input type="hidden" name="ID" value="{$mydata.ID}" />
                <input type="hidden" name="chparent" value="2" />
                <input type="hidden" name="isex"  value="{$mydata.isex}" />
                <input type="hidden" name="personType" value="{$mydata.personType}" />
        </form >
<script type="text/javascript">
        var inmenu=0;
</script>