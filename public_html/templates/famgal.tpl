<script type="text/javascript">
function delConfirm(s)
{ldelim}
        a = confirm('##Are you sure you would like to remove this item ?##');
		
        if (a)
              location.href=s;
{rdelim}
function verform1()
{ldelim}
        if (document.form1.sfile.value.length < 1)
        {ldelim}
             alert('##Please choose a file to upload##');
             document.form1.sfile.focus();
             return false;
        {rdelim}
    return true;
{rdelim}
</script>
<font class="title">##Image Gallery##</font>
<br /><br />
<font class="normal">

{if !isset( $smarty.session.read_only )}

        <a href='famgal.php?ID={$mydata.ID}&amp;add=1'>##Add Item##</a> |

{/if}

<a href='famgal.php?ID={$mydata.ID}&amp;view=1'>##View List##</a> |
<a href='famgal.php?ID={$mydata.ID}&amp;tnail=1'>##View Thumbnails##</a>
{* Back button removed
 | <a href='load.php?ID={$mydata.ID}'>Back</a> *}
</font>
<br />

{if $mydata.imgmsg} <br /><font class="normal">{$mydata.imgmsg}</font> {/if}
<br />
		
{if $mydata.edit == 1 && ( !isset( $smarty.session.read_only) ||  $smarty.session.my_rec == $ID ) }
        {if $mydata.edit_recs}

                <br />
                <form action=famgal.php method="post" enctype='multipart/form-data'>
                        <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                <tr>
                                        <td>##File:##
                                        </td>
                                        <td>
                                                <input type="file" name="sfile" size="30" />
                                        </td>
                                </tr>
                                <tr>
                                        <td>##Title:##
                                        </td>
                                        <td><input type="text" name="stitle" size="50" value="{$a.title|stripslashes}" />
                                        </td>
                                </tr>
                                <tr>
                                        <td valign="top">##Description:##</td>
                                        <td><textarea cols="60" class="normal" rows="3" name="sdescr">{$a.descr|stripslashes}</textarea>
                                        </td>
                                </tr>
                                <tr>
                                        <td colspan="2">
                                                <input type="submit" value='##Update##' />

                                                <input type="button" value='##Delete##' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;del=1&amp;kd={$a.kd}&amp;fp={$mydata.fp}&amp;mid={$mydata.mid}")' />
{* Back button removed
                                                <input type="button" value='##Back##' onclick='location.href="famgal.php?ID={$mydata.ID}' />
*}
                                        </td>
                                </tr>
                                <input type="hidden" name="fp" value='{$mydata.fp}' />

                        {if  $a.kd == 1}
                                        <input type="hidden" name="kd" value='1' />
                                {/if}

                                <input type="hidden" name="edik" value='1' />
                                <input type="hidden" name="edit" value='1' />
                                <input type="hidden" name="ID" value='{$mydata.ID}' />
                                <input type="hidden" name="mid" value='{$mydata.mid}' />
                        </table>
                </form>

    {/if}

{ elseif !isset( $smarty.session.edit_only) && $mydata.add == 1 && ( !isset( $smarty.session.read_only) || $smarty.session.my_rec == $mydata.ID )}

    {if $mydata.addik == 1}
                <br />
                <font class="normal">{$mydata.addmsg}</font>

    { else}

                <br />
                <form action="famgal.php" method="post" enctype='multipart/form-data' name="form1" onsubmit='return verform1()'>
                        <table class="normal" cellpadding="5" cellspacing="0" border="0">
                                <tr><td>##File:##</td>
                                        <td><input type="file" name="sfile" class="normal" size="50" />
                                        </td>
                                </tr>
                                <tr>
                                        <td>##Title:##</td>
                                        <td><input type="text" name="stitle" class="normal" size="50" />
                                        </td>
                                </tr>
                                <tr>
                                        <td valign="top">##Description:##</td>
                                        <td><textarea cols="60" class="normal" rows="3" name="sdescr"></textarea>
                                        </td>
                                </tr>
                                <tr>
                                        <td>##Place in:##</td>
                                        <td><select name="sindi" class="normal">
                                                <option value="1">##Individual Gallery##</option>
                                                {if $mydata.fp != false}
                                            <option value="0">##Family Gallery##</option>
                                                {/if}
                                                <option value="2">##Tree Gallery##</option>
                                                </select>
                                        </td>
                                </tr>
                                <tr>
                                        <td><input type="submit" class="normal" value='##Upload##' />
                                        </td>
                                </tr>
                        </table>
                                <input type="hidden" name="fp" value='{$mydata.fp}' />
                                <input type="hidden" name="addik" value='1' />
                                <input type="hidden" name="add" value='1' />
                                <input type="hidden" name="ID" value='{$mydata.ID}' />
                </form>

        {/if}

{ elseif !isset( $smarty.session.edit_only ) && $mydata.del == 1 && $mydata.mid != '' && ( !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $ID  )}

        <br />
        <font class="normal">{$mydata.delmsg}</font>

{elseif ( $mydata.view == 1 || !isset( $mydata.add ) || !isset( $mydata.tnail ) || !isset( $mydata.del ) ) && $mydata.tnail != 1}

        <br />
        <font class="normal" size="3"><b>##Individual Gallery##</b></font>
        <br />

        {if $mydata.askarchive}

                {if $mydata.archcnt > 0}

                        <a href='downgal.php?indi=1&amp;ID={$mydata.ID}'>##Export compressed##</a>
                        <font class="normal"> | </font>
                        <a href='downgal.php?indi=1&amp;ID={$mydata.ID}&amp;zip=1'>##Export as ZIP##</a>
                        <font class="normal"> | </font>

                {/if}
        {if !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID }

                        <font class="normal"><a href='impgal.php?indi=1&amp;ID={$mydata.ID}'>##Import compressed##</a></font><br />

                {/if}
    {/if}
    {if $mydata.archcnt > 0}

                <br /><font class="normal"><b>{$mydata.archcntmsg}</b></font>
                <br />
                <table  class="normal" cellpadding="5" cellspacing="0" border="0">

                        {foreach from="$archlist" key=key item=dtls}

                                <tr>
                                        <td>
                                                 ##File Name:## {$dtls->name} {$dtls->data}
                                                <br />
                                                ##Title:## {$dtls->title|stripslashes}<br />

                                {if $dtls->type == 0}

                                                      <a href='viewfamgalim.php?indi=1&amp;ID={$mydata.ID}&amp;mid={$dtls->mid}'>##View##</a> 
                                                      
                                              {if $read_only!=1}| <a href='famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$dtls->mid}'> ##Edit## </a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$dtls->mid}")'>##Delete##</a> 
                                              {/if}

                                                { else}

                                                        <a href='viewfamgal.php?ID={$mydata.ID}&amp;mid={$dtls->mid}'>##View##</a>
                                               {if $read_only!=1}|<a href='famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$dtls->mid}'> ##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$dtls->mid}")'>##Delete##</a>
											   {/if}
                                                {/if}
                                {if $mydata.cid != $dtls->mid}

                                                         | <a href='famgal.php?ID={$mydata.ID}&amp;hid={$dtls->mid}'>##Bump up##</a>

                                                {/if}

                                        </td>
                                </tr>
                                <tr><td width="100%"><hr /></td><td width="1">&nbsp;</td></tr>

            {/foreach}

                </table>

    {else}

                <br /><font class="normal">##The gallery is empty##</font><br />

    {/if}

    {if $mydata.fp != false}

        <br />
        <font class="normal" size="3"><b>##Family Gallery##</b>
        </font>
        <br />

                {if $mydata.tarok == 1}
                        {if $mydata.famgalcnt > 0}
                                <a href='downgal.php?fid={$mydata.fp}'>##Export compressed##</a>
                                <font class="normal"> | </font>
                                <a href='downgal.php?fid={$mydata.fp}&amp;zip=1'>##Export as ZIP##</a>
                                <font class="normal"> | </font>
                        {/if}
                        {if !isset( $smarty.session.read_only) || $smarty.session.my_rec == $mydata.ID}
                                  <font class="normal"><a href='impgal.php?ID={$mydata.ID}&amp;fid={$mydata.fp}'>##Import compressed##</a></font><br />
                        {/if}
                {/if}
                {if $mydata.famgalcnt > 0}
                     <br /><font class="normal"><b>{$mydata.famgalfoundmsg}</b></font><br />
                             <table  class="normal" cellpadding="5" cellspacing="0" border="0">

                        {foreach from="$famgallist" key=key item=dtls}

                                <tr>
                                        <td>
                                                 ##File Name:## {$dtls->name} {$dtls->data}
                                                <br />
                                                Title: {$dtls->title}<br />

                                {if $dtls->type == 0}

                                                        <a href='viewfamgalim.php?indi=0&amp;ID={$mydata.ID}&amp;mid={$dtls->mid}&amp;fp={$mydata.fp}'>##View##</a>
                                                        {if $read_only!=1} |
                                                        <a href='famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$dtls->mid}'> ##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$dtls->mid}")'>##Delete##</a>
														{/if}
                                                { else}

                                                        <a href='viewfamgal.php?ID={$mydata.ID}&amp;indi=0&amp;mid={$dtls->mid}&amp;fp={$mydata.fp}'>
														##View##</a>
                                                {if $read_only!=1} |
                                                        <a href='famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$dtls->mid}'>
														 ##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$dtls->mid}")'>##Delete##</a>
												{/if}
                                                {/if}
                                {if $mydata.famcid != $dtls->mid}
                                                         | <a href='famgal.php?ID={$mydata.ID}&amp;hid={$dtls->mid}'>##Bump up##</a>

                                                {/if}

                                        </td>
                                </tr>
                                <tr><td width="100%"><hr />hjhj</td><td width="1">&nbsp;</td></tr>

            {/foreach}

                        </table>

                { else}

                        <br /><font class="normal">##The gallery is empty##</font><br />

                {/if}
    {/if}

        <br />
        <font class="normal" size="3"><b>##Tree Gallery##</b>
        </font><br />

        {if $mydata.tarok == 1}


                {if $mydata.treecnt > 0}

                        <a href='downgal.php?kd=1&amp;tree={$mydata.user}'>##Export compressed##</a>
                        <font class="normal"> | </font>
                        <a href='downgal.php?kd=1&amp;tree={$mydata.user}&amp;zip=1'>##Export as ZIP##</a>
                <font class="normal"> | </font>

                {/if}
                {if !isset( $smarty.session.read_only ) ||  $smarty.session.my_rec == $mydata.ID}

                        <font class="normal"><a href='impgal.php?ID={$mydata.ID}&amp;kd=1&amp;tree={$mydata.user}'>##Import compressed##</a></font><br />

                {/if}
       {/if}

    {if $mydata.treecnt > 0}

                <br /><font class="normal"><b>{$mydata.treecntmsg}</b></font><br />
                <table  class="normal" cellpadding="5" cellspacing="0" border="0">

                        {foreach from="$treegallist" key=key item=dtls}

                                <tr>
                                        <td>
                                                 ##File Name:## {$dtls->name} {$dtls->data}
                                                <br />
                                                Title: {$dtls->title}<br />

                                {if $dtls->type == 0}

                                                        <a href='viewfamgalim.php?indi=2&amp;ID={$mydata.ID}&amp;mid={$dtls->mid}&amp;kd=1'>
                                                        ##View##</a>{if $read_only!=1} | 
                                                        <a href='famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$dtls->mid}'> ##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$dtls->mid}")'>##Delete##</a>

                                                 {/if}{ else}

                                                        <a href='viewfamgal.php?ID={$mydata.ID}&amp;mid={$dtls->mid}&amp;kd=1'>##View##</a>
                                               {if $read_only!=1} |
                                                        <a href='famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$dtls->mid}'> ##Edit##</a> |	<a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$dtls->mid}")'>##Delete##</a>
 												{/if}
                                                {/if}
                                {if $mydata.treecid != $dtls->mid}

                                                         | <a href='famgal.php?ID={$mydata.ID}&amp;hid={$dtls->mid}'>##Bump up##</a>

                                                {/if}

                                        </td>
                                </tr>
                                <tr><td width="100%"><hr /></td><td width="1">&nbsp;</td></tr>

            {/foreach}

                </table>

        { else}

                <br /><font class="normal">##The gallery is empty##</font>

        {/if}

{ elseif  $mydata.tnail == 1}
        <br />
        <font class="normal" size="3"><b>##Individual Gallery##</b>
        </font><br />
        {if $mydata.askarchive}

                {if $mydata.archcnt > 0}

                        <a href='downgal.php?indi=1&amp;ID={$mydata.ID}'>##Export compressed##</a>
                        <font class="normal"> | </font>
                        <a href='downgal.php?indi=1&amp;ID={$mydata.ID}&amp;zip=1'>##Export as ZIP##</a>
                        <font class="normal"> | </font>

                {/if}
        {if !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID }

                        <font class="normal"><a href='impgal.php?indi=1&amp;ID={$mydata.ID}'>##Import compressed##</a></font><br />

                {/if}

    {/if}

    {if $mydata.indgalcnt > 0}
				{*

                <font class="normal"><b>{$mydata.tnail_indgalmsg}</b></font><br />
                <br />
                  <font class="normal">

				*}
                {if $mydata.from1 > $mydata.recperpage}
				{*
                        <b>&lt;&lt;</b> <a href='famgal.php?from1={math equation="a - b" a=$mydata.from1 b=$mydata.recperpage}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'><b>##PREVIOUS##</b></a>
				*}
                { else}
				{*
					{if $mydata.tnail > 0 }
	        	        <b>&lt;&lt; ##PREVIOUS##</b>
        	        {/if}
				*}
                {/if}

                &nbsp;

                {foreach from=$pagelists key=key item=val}
			{if $val > 0}
			{*
			<a href='famgal.php?from1={$val}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'>{$key}</a>
			*}
			{else}
			{*
			<b>{$key}</b>
			*}
			{/if}
                {/foreach}

                {math equation="a + b" a=$mydata.from1 b=$mydata.recperpage assign=curcnt}

                {if $curcnt <= $mydata.indgalcnt}
				{*
                        &nbsp;<b><a href='famgal.php?from1={math equation="a + b" a=$mydata.from1 b=$mydata.recperpage}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'>##NEXT##</a> &gt;&gt;</b>
				*}
                { else}
				{*
                        &nbsp;<b>##NEXT## &gt;&gt;</b>
				*}
                {/if}
               {* </font> *}
                <table class="normal" cellpadding="0" cellspacing="0" border="0">

                {foreach name=tnail01 from=$tnail_ind key=key item=disp}

                        {if $smarty.foreach.tnail01.iteration % 5 == 1}

                 <tr>

            {/if}

                        <td width='110' height='100' align="center" >
                                <a href='viewfamgalim.php?indi=1&amp;mid={$disp->mid}&amp;ID={$mydata.ID}'>
                                <img  alt="" src="image.php?indi=1&amp;ID={$mydata.ID}&amp;mid={$disp->mid}" width="90" />
                                {* <img  alt=""  src='{$disp->pictfile}' width="50" height="50" /> *}</a><br />
                <center>{$disp->title}<br />
                <a href='viewfamgalim.php?indi=1&amp;mid={$disp->mid}&amp;ID={$mydata.ID}'>##View##</a>
            {if $read_only!=1}  |  <a href='famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$disp->mid}'>##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;indi=1&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$disp->mid}")'>##Delete##</a>{/if}
                </center>
                        </td>

                        {if $smarty.foreach.tnail01.iteration % 5 == 0}

                 </tr>

            {/if}
                {/foreach}

        </table>

        { else }

              <br /><font class="normal">##The gallery is empty##</font>

        {/if}

    {if $mydata.fp != false}

            <br /><br />
                <font class="normal" size="3"><b>##Family Gallery##</b></font><br />
                {if $mydata.tarok == 1}

                        {if $mydata.famgalcnt > 0}

                                 <a href='downgal.php?fid={$mydata.fp}'>##Export compressed##</a>
                                <font class="normal"> | </font>
                                <a href='downgal.php?fid={$mydata.fp}&amp;zip=1'>##Export as ZIP##</a>
                    <font class="normal"> | </font>

                        {/if}
            {if !isset( $smarty.session.read_only) || $smarty.session.my_rec == $mydata.ID}

                                <font class="normal"><a href='impgal.php?ID={$mydata.ID}&amp;fid={$mydata.fp}'>##Import compressed##</a></font><br />

            {/if}
        {/if}

			
                {if $mydata.tnailfamgalcnt > 0}

                        <font class="normal"><b>{$mydata.tnail_famgalcntmsg}</b></font><br />

                        <font class="normal">
                        {if $mydata.from > $mydata.recperpage}
						{if $mydata.tnailtreecnt > 0}
						{*
                                <b>&lt;&lt;</b> <a href='famgal.php?from={math equation="a - b" a=$mydata.from b=$mydata.recperpage}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'><b>##PREVIOUS##</b></a>
						*}
                        {/if}

                        { else}
						{*
                        <b>&lt;&lt; 11##PREVIOUS##</b>
						*}
                        {/if}

                        &nbsp;

                        {foreach from=$pagelists1 key=key item=val}
                    {if $val > 0}
					{*
                        <a href='famgal.php?from={$val}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'>{$key}</a>
					*}
                    { else}
					{*
                        <b>{$key}</b>
					*}
                    {/if}
                        {/foreach}

                        { math equation="a + b" a=$mydata.from b=$mydata.recperpage assign=curcnt1}

                        {if $curcnt1 <= $mydata.tnailfamgalcnt}
						{*
                                &nbsp;<b><a href='famgal.php?from={math equation="a + b" a=$mydata.from b=$mydata.recperpage}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'>##NEXT##</a> &gt;&gt;</b>
						*}
                        { else}
						{*
                                &nbsp;<b>##NEXT## &gt;&gt;</b>
						*}
                        {/if}
                       </font>
                        <table class="normal" cellpadding="0" cellspacing="0" border="0">

                        {foreach name=tnail02 from=$tnail_fam key=key item=disp}

                                {if $smarty.foreach.tnail02.iteration % 5 == 1}

                         <tr>

                    {/if}

                                <td width='110' height='100' align="center" >
                                        <a href='viewfamgalim.php?mid={$disp->mid}&amp;ID={$mydata.ID}&amp;fp={$mydata.fp}'><img  alt=""  src="image.php?indi=0&amp;ID={$mydata.ID}&amp;mid={$disp->mid}&amp;fp={$mydata.fp}" width="90" />
                                        </a><br />
                    <center>{$disp->title}<br />
                    <a href='viewfamgalim.php?indi=0&amp;mid={$disp->mid}&amp;ID={$mydata.ID}&amp;fp={$mydata.fp}'>##View##</a>{if $read_only!=1} | <a href='famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$disp->mid}'>##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$disp->mid}")'>##Delete##</a>{/if}
                    </center>
                                </td>

                                {if $smarty.foreach.tnail02.iteration % 5 == 0}

                         </tr>

                    {/if}

                        {/foreach}

                        </table>

                { else}

                       <br /><font class="normal">##The gallery is empty##</font>

                {/if}

    {/if}

        <br /><br />
    <font class="normal" size="3"><b>##Tree Gallery##
    </b></font><br />
        {if $mydata.tarok == 1 || $mydata.askarchive}


                {if $mydata.treecnt > 0}

                        <a href='downgal.php?kd=1&amp;tree={$mydata.user}'>##Export compressed##</a>
                        <font class="normal"> | </font>
                        <a href='downgal.php?kd=1&amp;tree={$mydata.user}&amp;zip=1'>##Export as ZIP##</a>
                <font class="normal"> | </font>

                {/if}
        {if !isset( $smarty.session.read_only ) ||  $smarty.session.my_rec == $mydata.ID}

                        <font class="normal"><a href='impgal.php?ID={$mydata.ID}&amp;kd=1&amp;tree={$mydata.user}'>##Import compressed##</a></font><br />

                {/if}
                <br />
    {/if}



    {if $mydata.tnailtreecnt > 0}

                <font class="normal"><b>{$mydata.tnailtreecntmsg}</b></font>
                <br />
                <font class="normal">
                {if $mydata.from2 > $mydata.recperpage}
				{*
                        <b>&lt;&lt;</b> <a href='famgal.php?from2={math equation="a - b" a=$mydata.from2 b=$mydata.recperpage}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'><b>##PREVIOUS##</b></a>
				*}
                { else}
				{*
                <b>&lt;&lt; ##PREVIOUS##</b>
				*}
                {/if}

                &nbsp;

                {* foreach from=$pagelists2 key=key item=val}
            {if $val > 0}
			 
                <a href='famgal.php?from2={$val}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'>{$key}</a>

            { else}

                <b>{$key}</b>

            {/if}
                {/foreach *}

                { math equation="a + b" a=$mydata.from2 b=$mydata.recperpage assign=curcnt3}

                {* if $curcnt3 <= $mydata.tnailtreecnt}
				
                        &nbsp;<b><a href='famgal.php?from2={math equation="a + b" a=$mydata.from2 b=$mydata.recperpage}&amp;tnail=1&amp;ID={$mydata.ID}&amp;sla={$mydata.sla}&amp;srch={$mydata.urlencsrch}'>##NEXT##</a> &gt;&gt;</b>

                { else}

                        &nbsp;<b>##NEXT## &gt;&gt;</b>

                {/if *}
               </font>
                <table class="normal" cellpadding="0" cellspacing="0" border="0">
                {foreach name=tnail03 from=$tnail_tree key=key item=disp}
                  {if $smarty.foreach.tnail03.iteration % 5 == 1}
                 <tr>
                  {/if}
                       <td width='110' height='100' align="center">
                           <a href='viewfamgalim.php?kd=1&amp;indi=2&amp;mid={$disp->mid}&amp;ID={$mydata.ID}'><img  alt=""  src="image.php?indi=2&amp;kd=1&amp;ID={$mydata.ID}&amp;mid={$disp->mid}&amp;fp={$mydata.fp}" width="90" /></a><br />
                          <center>{$disp->title}<br />
                           <a href='viewfamgalim.php?kd=1&amp;indi=2&amp;mid={$disp->mid}&amp;ID={$mydata.ID}&amp;fp={$mydata.fp}'>##View##</a> {if $read_only!=1}                          | <a href='famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;edit=1&amp;mid={$disp->mid}'>##Edit##</a> | <a href='#' onclick='delConfirm("famgal.php?ID={$mydata.ID}&amp;fp={$mydata.fp}&amp;del=1&amp;mid={$disp->mid}")'>##Delete##</a>{/if}
                          </center>
                       </td>
                  {if $smarty.foreach.tnail03.iteration % 5 == 0}
                 </tr>
                  {/if}
                {/foreach}

                </table>

        { else}

                <font class="normal">
                ##The gallery is empty##</font>

        {/if}

{/if}
<script type="text/javascript" >
        var inmenu=0;
</script>

{* Do NOT uncomment these lines *}
{*  This is text passed by PHP, added here for the language editor

##items found##
##The image has been successfully added to the gallery.##
##There was a problem with the upload. Please contact an administrator.##
##The deletion has been made.##
##Deletion problem##

*}