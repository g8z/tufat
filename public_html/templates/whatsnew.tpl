{* This is the what's new page *}

<font class="title">##What's New##</font>
<br /><br />
<font class="subtitle">##10 Newest Individuals##</font><br /><br />

{* Show newest 10 individuals added in last 30 days *}

{if $new10ind > 0}
   <table class="normal" width='90%' cellpadding="2" border="0" cellspacing="0">
      <tr style="background: #eeeeee" >
         <td><b>##GEDCOM ID##</b></td>
         <td><b>##Name##</b></td>
         <td><b>{if $animalPedigree}
                   ##Call Name##
                { else}
                   ##Surname##
                                {/if}
                </b></td>
         <td><b>##Time/Date Added##</b></td>
      </tr>

      {foreach from=$newest10individuals key=key item=disprec}
         <tr>
            <td>{$disprec->ID}</td>
            <td><a href="load.php?ID={$disprec->ID}">{$disprec->mname}</a></td>
            <td><a href="load.php?ID={$disprec->ID}">{$disprec->surn}</a></td>
            <td>{$disprec->ta}</td>
         </tr>

     {/foreach}

   </table>
   <br />
{/if}

{* Show the newest 10 images added in last 30 days *}

<font class="subtitle">##10 Newest Individual Images##</font>
<br />
<br />
<table class="normal" cellpadding="2" border="0" cellspacing="0" width="90%">
        <tr style="background: #eeeeee" >
                <td><b>##Thumbnail##</b></td>
                <td><b>##Name##</b></td>
                <td><b>{if $animalPedigree}
                   ##Call Name##
                {else}
                   ##Surname##
                                {/if}</b></td>
                <td><b>##Time/Date Added##</b></td>
        </tr>

{if $new10imgs > 0}
        {foreach from=$new10indimgs key=key item=disprec}
        <tr>
                <td><a href="load.php?ID={$disprec->ID}"><img  alt=""  src="image.php?ID={$disprec->ID}&amp;type=portrait&maxw=50&maxh=50"  /></a>
                </td>
                <td><a href="load.php?ID={$disprec->ID}">{$disprec->name}</a></td>
                <td><a href="load.php?ID={$disprec->ID}">{$disprec->surname}</a></td>
            <td>{$disprec->ta}</td>
        </tr>

        {/foreach}
{/if}
</table>
<br />

{* Now show 10 newest Tree Images *}
<font class="subtitle">##10 Newest Tree Images##</font><br /><br />

<table class="normal" width='90%' cellpadding="2" border="0" cellspacing="0">
        <tr style="background: #eeeeee" >
                <td width='70'><b>##Thumbnail##</b></td>
                <td><b>##Title##</b></td>
                <td><b>##Time/Date Added##</b></td>
        </tr>

{if $newest10treeimgs > 0}

        {foreach from=$new10treeimgrecs key=key item=disprec}
         <tr>
            <td><a href="viewfamgalim.php?wn=1&amp;kd=1&amp;mid={$disprec->ID}"><img  alt=""  src="image.php?wn=1&amp;kd=1&amp;mid={$disprec->ID}&amp;indi=2" width="50" height="50" /></a></td>
            <td><a href="viewfamgalim.php?kd=1&amp;mid={$disprec->ID}">{$disprec->title}</a></td>
            <td>{$disprec->ta}</td>
         </tr>
        {/foreach}
{/if}
</table>
<script type="text/javascript">
        var inmenu=0;
</script>
<br />