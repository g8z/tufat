<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<html>
<title>##Events Notes##</title>
<link href="templates/{$smarty.session.templateID}/tufat.css" rel="stylesheet" type="text/css" />
<script src="javascript.js" type="text/javascript"></script>
</head>
<body>
{if $mydata.xtag != '' && $mydata.ID !=''}

        <font class="title">{$mydata.taghdr01} ##Events Notes##</font>
        <br />
        <br /><br />

        {if !isset( $smarty.session.read_only) || $smarty.session.my_rec == $mydata.ID}

                <font class="normal">
                <a href='showevenn.php?add=1&amp;ID={$mydata.ID}&amp;xtag={$mydata.xtag}'>##Add Notes##</a> |
                <a href='#' onclick='window.close()' >##Close Window##</a>
                </font>
        {/if}

        <br />

        {if $mydata.del == 1 && $mydata.mid > 0}

                {if !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID}

                        <font class="normal">{$mydata.delmsg} </font><br />

                {/if}

        {/if}

        {if $mydata.recscnt > 0}

                <br />
                <table width='100%'>

                {foreach from=$recsList key=key item=disp}

                        <tr>
                                <td>
                                        {if !isset( $smarty.session.read_only ) || $smarty.session.my_rec == $mydata.ID}

                                                <input type='button' onclick='if (confirm(\"##Are you sure?##\"))   location.href="showevenn.php?ID={$mydata.ID}&amp;del=1&amp;xtag={$mydata.xtag}&amp;mid={$disp->hid}"' value='##Del##' />


                                        {/if}

                                        <a href='#'  onclick="launchCentered('editnote.php?sid={$disp->hid}','400',"380",'resize=0')" >
                                         ##Note## # {$disp->hid}
                                        </a>
                                </td>
                        </tr>

                {/foreach}
                </table>

        {/if}

        {if $mydata.nf != 1}

                <font class="normal">##Notes list is empty##</font>

        {/if}

{ else}

        <font class="normal">##new_sinfo##</font>
        <br />

{/if}

{* do not uncomment these lines *}
{*  this is text passed by PHP, added here for the language editor

##Note # $mid has been deleted successfully.##
##Note delete problem.##

*}