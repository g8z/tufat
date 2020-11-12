<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
        <link href="templates/{$smarty.session.templateID}/tufat.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table>

{foreach from=$lists key=key item=keyurl}

        <tr>
                <td colspan="2"><font class="subtitle">

        {if $mydata.sn == $key}

                <span id="{$keyurl}"><font face='Arial'><b>

        { else}

                  <a href='listsurn.php?sn={$keyurl}#{$keyurl}'  onclick='parent.pindi.location.href="listindi.php?surn={$keyurl}"'>

                {/if}
        {if empty( $key )}
                {if !$mydata.animalPedigree}

                    ##(no surname given)##

            { else}

                ##(no name given)##

                        {/if}
                { else}

             {$key}

        {/if}
               {if $mydata.sn == $key}

                </b /></span>

        { else}

                        </a>

                {/if}

               </font>
               </td>
        </tr>

{/foreach}

</table>