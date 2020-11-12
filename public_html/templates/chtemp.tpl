{* This is the template file for chtemp.php *}

<font class="title">##Change Template##</font>
<br /> <br />
<form action="chtemp.php" method="get" name="chtemp_form">
        <font class="normal">##Choose Template##</font>
        <select name="stpl">
                {html_options options="$file_list" selected="$templateID"}
        </select>
        <br /><br />
        <input type="hidden" name="installik" value="1" />
        <input type="submit" value="##Change##" />

</form>
<script type="text/javascript">
        var inmenu=1;
</script>