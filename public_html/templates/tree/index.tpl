<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8" />
<style type="text/css">
.leftRepeat {ldelim}
 background-image: url(images/{ $templateID }/leftRepeat.gif?{ $currtime } );
 background-repeat: repeat-y;
 background-position: left top;
 margin: 0px;

{rdelim}
.topRepeat {ldelim}
 background-image: url(images/{ $templateID }/topRepeat.gif?{ $currtime } );
 background-repeat: repeat-x;
 background-position: left top;
{rdelim}

{literal}

  TABLE.calendar { text-align: center; }
  TH.month { font-family: "Arial"; font-size: 11pt; background-color: #E0E0E0; }
  TD.prev-month { font-family: "Arial"; text-align: left; color: white; }
  TD.next-month { font-family: "Arial"; text-align: right; color: white;}
  TH.day-of-week { font-family: "courier new"; font-size: 7pt; color: white; }
  TD.selected-day { font-family: "Arial"; background-color: #FFFFFF; }
  TD.day { font-size: 8pt; background-color: #E0E0E0; }
  TD.day_event { font-size: 8pt; background-color: #E0E0E0; color: #FFFFFF; font-weight:bold; }
  TD.day_event a{color: #FF0000;}
  TD.today { font-family: "Arial"; font-size: 11pt; background-color: #E0E0E0; font-weight: bold; }
{/literal}
</style>
<link href="templates/{ $smarty.session.templateID }/tufat.css" rel="stylesheet" type="text/css" />
<script src="javascript.js" type="text/javascript"></script>

<title> { $smarty.session.treeName } </title>

</head>
<body class="leftRepeat" style="margin-height:0px;margin-width:0px;">
   <table width="100%"  class="topRepeat" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">
      <table border="0" class="leftRepeat" cellpadding="0" cellspacing="0" width="100%">
         <tr>
            <td colspan="2" valign="top"><img  alt=""  name="Tufat_r1_c1" src="images/{ $smarty.session.templateID }/Tufat_r1_c1.gif" /></td>
         </tr>
         <tr>
           <td valign='top' width='1'><img  alt="" name='Tufat_r2_c1' src="images/{$smarty.session.templateID}/Tufat_r2_c1.gif" border="0" style="position:absolute; left:0px; top:89px;" />
           </td>
           <td valign="top" width="99%">
           <div id="main001" style="position:absolute; width: 632px; z-index:1; left:166px; top: 100px;">

         			{$rendered_page}        

                  </div>
            </td>
         </tr>
        </table>
     </td></tr>
	</table>

<div style="position:absolute; width: 150px; z-index:1; left:10px;top:107px;display:block;">
	{$navMenu}

</div>
</body>
</html>