<script src="tufattree.js"></script>
<script type="text/javascript">
        var ={ldelim}
        format:{ldelim}
                left:641,
                top:110,
                width:150,
                height:100,
                no_images:true,
                animation:0,
                padding:0,
                back_bgcolor:"#99cc33",
                link_class:"leftNavItem",
                bgcolor:"",
                level_indent:0,
                dont_resize_back:0
        {rdelim},
        sub:[
                {ldelim}html:'View', link:'mainMenu',
                        sub:[
                                {ldelim}html:'My Information', url:'load.php?ID={$ID}', link:'menuItem'{rdelim},
                                {ldelim}html:"What's New", url:'whatsnew.php?ID={$ID}', link:'menuItem'{rdelim},
                                {ldelim}html:'Families in Tree', url:'index.php?ID={$ID}'{rdelim},
                                {ldelim}html:'My Generations', url:'generations.php?ID={$ID}'{rdelim},
                                {ldelim}html:'My Ancestors', url:'cshow.php?ID={$ID}'{rdelim},
                                {ldelim}html:'My Cousins', url:'flow.php?ID={$ID}'{rdelim}
                        ]
                {rdelim},
                {ldelim}html:'Tools',
                        sub:[
                                {ldelim}html:'Find Relationships', url:'relfind.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Change Template', url:'chtemp.php?ID={$ID}'{rdelim}
                        ]
                {rdelim},
                {ldelim}html:'Search', url:'search.php?ID={$ID}'{rdelim},
                {ldelim}html:'Help', url:'help.php?ID={$ID}'{rdelim}
                { if $smarty.session.admin }
                ,
                {ldelim}html:'Backup',
                        sub:[
                                {ldelim}html:'All to SQL', url:'backup.php?ID={$ID}'{rdelim},
                                {ldelim}html:'One Tree to SQL', url:'backup.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Export GEDCOM', url:'export.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Export Images/Files', url:'exportpp.php?ID={$ID}'{rdelim}
                        ]
                {rdelim},
                {ldelim}html:'Restore',
                        sub:[
                                {ldelim}html:'All from SQL', url:'backup.php?ID={$ID}'{rdelim},
                                {ldelim}html:'One Tree from SQL', url:'backup.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Import GEDCOM', url:'export.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Import Images/Files', url:'exportpp.php?ID={$ID}'{rdelim}
                        ]
                {rdelim},
                {ldelim}html:'Administration',
                        sub:[
                                {ldelim}html:'Add New Login', url:'backup.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Hide Information', url:'backup.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Set Language', url:'export.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Edit Password', url:'exportpp.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Delete Current Tree', url:'exportpp.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Delete All Trees', url:'exportpp.php?ID={$ID}'{rdelim},
                                {ldelim}html:'Server Settings', url:'exportpp.php?ID={$ID}'{rdelim}
                        ]
                {rdelim}
                { /if }
        ]
{rdelim}
var tree=new TufatTree('menu',TREE_NODES);
tree.draw(1);
</script> 
