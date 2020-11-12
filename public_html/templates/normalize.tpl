{if ($normalization)}
##You need to process normalization.##<br />
<div style="color: red;">##Make database backup first!##</div>
##It is time consume procedure please keep it to finish.##<br />
<form>
<input type="hidden" name="run" value="1" />
<input type="submit" value="Run normalization" />
</form>
{else}
##Your database does not require normalization at this time.##
{/if}
<br />
<br />
<i>##Normalization solves problems with a person's details, and with relations between persons. You do not need to run this tool if you haven't had any problems.##</i>