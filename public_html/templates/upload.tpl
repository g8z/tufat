{if $mydata->nodir}

        <span class="normal">
                <table class="normal" width='100%'>
                        <tr>
                                <td width='100%'>
                                        <span class="normal"Red>##NOTICE: The system could not find a valid temp directory in which to store image data. You will not be able to upload and display portraits correctly without this directory. Please refer to the TUFaT README file for directions on setting up the temp folder.##
                                        </span>
                                </td>
                        </tr>
                </table>
        </span><br />

{elseif $mydata.dirnotok}

        <span class="normal">
                <table class="normal" width='100%'>
                        <tr>
                                <td width='100%' class="normal">
                                    ##NOTICE: The system has detected that the temp directory is not world-writeable and/or world-readable. Portraits that you upload will not be correctly displayed unless the temp directory is world-writeable and world-readable.##
                                </td>
                        </tr>
                </table>
        </span><br />

{/if}

{if $msg}
	<table>
		<tr><td class="errorBody">{$msg}</td></tr>	
	</table><br />
{/if}

<p class="title">##Upload##</p>

<p class="subtitle">##Upload a new portrait for##&nbsp;{$mydata.name}
<span class="normal"><br />
(##images exceeding dimensions of##&nbsp;{$mydata.imgmsg}&nbsp;##will be resized##)</span></p>

<form method="post" enctype="multipart/form-data" action="upload.php">
<input type="hidden" name="MAX_FILE_SIZE" value="10240000" />

        <input class="fileinput3" name="portrait" type="file" id="portrait" size="42" />
        <br /><br />
        <p class="subtitle">##Upload a new biography for##&nbsp;{$mydata.name}
        <span class="normal"><br />
        ##(maximum size 1 MB, must be MS Word, PDF, or ASCII)##
        </span>
        </p>
        <p>
        <input class="fileinput3" name="bio" type="file" id="bio" size="42" />
        </p>
        <p>
        <input type="hidden" name="ID" value="{$mydata.ID}" />
        <input name="submit" type="submit" class="normal" value="##Submit##" />
        <input name="cancel" type="button" class="normal" id="cancel" onclick="javascript:window.location='load.php?ID={$mydata.ID}';"  value="##Cancel##" />
        </p>
</form>
<p class="normal">##Note: Uploading a portrait or biography will##
<font color="#FF0000">##permanently erase##
</font>
##any existing portrait or biography for this individual.##
</p>
<script type="text/javascript">
        var inmenu=0;
</script>