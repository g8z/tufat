{* {if $loadphp}

<script type="text/javascript">
        var loadmenu={ldelim}
        format:{ldelim}
                left:6,
                top:351,
                width:140,
                height:100,
                no_images:true,
                animation:0,
                padding:0,
                indent:3,
                back_bgcolor:"",
                link_class:"leftNavItem",
                bgcolor:"",
                level_indent:0,
                y_offset:3,
                dont_resize_back:0
        {rdelim},
        sub:[
                {ldelim}html:'Actions', link:'menuMain',
                        sub:[
                                {ldelim}html:'Edit Details', link:'menuItem', url:'edit.php?ID={$ID}'{rdelim},
                                {if $mydata.bio != ""}
                                        {ldelim}html:"Download Biography", link:'menuItem', url:'getbio.php?ID={$ID}'{rdelim},
                                { else if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                        {if !$smarty.session.edit_only}
                                                {ldelim}html:"Add Biography", link:'menuItem', url:'upload.php?ID={$ID}'{rdelim},
                                        { /if }
                                { /if  }
                                {ldelim}html:'Show More Info',link:'menuItem', url:'moreinfo.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Photo Gallery',link:'menuItem', url:'famgal.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Show Links',link:'menuItem', url:'links.php?ID={$ID}'{rdelim}
                                {if !$smarty.session.read_only || $smarty.session.my_rec == $mydata.ID}
                                        {if !$smarty.session.edit_only}

                                                ,
                                                {ldelim}html:'{$mytrans.addspouse_text}', link:'menuItem', url:'edit.php?sp1={$ID}&personID={$ID}&personType=partner'{rdelim},
                                                {ldelim}html:'Add Son', link:'menuItem', url:'edit.php?sex=m&personID={$ID}&personType=son'{rdelim},
                                                {ldelim}html:'Add Daughter',link:'menuItem', url:'edit.php?sex=f&personID={$ID}&personType=daughter'{rdelim},
                                                {ldelim}html:'Add Sibling', link:'menuItem', url:'edit.php?personID={$fatherID}&personType=sibling'{rdelim}

                                        {/if}
                                {/if}
                        ]
                {rdelim}
        ]
{rdelim}
var loadmenutree=new TufatTree('load1',loadmenu);
loadmenutree.expandAll();
loadmenutree.draw(1);
</script>
{/if}
*}