function MM_reloadPage(init)
{  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}

MM_reloadPage(true);

function Validate_passwords(theForm){
        var pass = theForm.pass.value.trim();
        var rpass = theForm.rpass.value.trim();
        var apass = theForm.apass.value.trim();
        if ( !alphanumericString( pass ) )
        {
                alert( "The general access password may contain only alphanumeric characters" );
                return;
        }
        if ( !alphanumericString( rpass ) )
        {
                alert( "The read-only password may contain only alphanumeric characters" );
                return;
        }
        if ( !alphanumericString( apass ) )
        {
                alert( "The administrative password may contain only alphanumeric characters" );
                return;
        }

        // check to make sure all passwords are unique
        //if ( $pass == $rpass || $pass == $apass || $rpass == $apass )
        if ( pass == rpass || pass == apass || rpass == apass )
        {
                alert( "All passwords must be unique." );
                return;
        }

        theForm.submit();
}

function changeBGC(targetID,bgcolor){
        if (document.getElementById) {
                document.getElementById(targetID).style.backgroundColor = bgcolor;
        }

        else if(document.all){
                document.all(targetID).style.backgroundColor = bgcolor;
        }
        else if(document.layers){
                document.layers[targetID].bgColor=bgcolor;
        }
}

String.prototype.trim = function()
{
  var x=this;

  x=x.replace( /^\s*/, "" );
  x=x.replace( /\s*$/, "" );

  return x;
}

function alphanumericString( checkStr )
{
        var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var allValid = true;

        for ( var i = 0; i < checkStr.length; i++ )
        {
                ch = checkStr.charAt(i);
                for ( var j = 0;  j < checkOK.length;  j++ )
                        if ( ch == checkOK.charAt(j) )
                                break;

                if ( j == checkOK.length )
                {
                        allValid = false;
                        break;
                }
        }
        return allValid;
}


function getSelectedRadio(buttonGroup) {
   // returns the array number of the selected radio button or -1 if no button is selected
   if (buttonGroup[0]) { // if the button group is an array (one button is not an array)
      for (var i=0; i<buttonGroup.length; i++) {
         if (buttonGroup[i].checked) {
            return i
         }
      }
   } else {
      if (buttonGroup.checked) { return 0; } // if the one button is checked, return zero
   }
   // if we get to this point, no radio button is selected
   return -1;
}

function getSelectedRadioValue(buttonGroup) {
   // returns the value of the selected radio button or "" if no button is selected
   var i = getSelectedRadio(buttonGroup);
   if (i == -1) {
      return "";
   } else {
      if (buttonGroup[i]) { // Make sure the button group is an array (not just one button)
         return buttonGroup[i].value;
      } else { // The button group is just the one button, and it is checked
         return buttonGroup.value;
      }
   }
   return "";
}

/** Same as validate but for marriages only (no gender check). */
function validateMarriage( theForm )
{
        // combine date fields
        for( i = 0; i < theForm.elements.length; i++ )
        {
                if ( theForm.elements[i].name.indexOf( "_date_1" ) > 0 )
                {
                        var month = theForm.elements[i];
                        var day = theForm.elements[i + 1];
                        var year = theForm.elements[i + 2];

                        theForm.elements[i + 3].value = year.value + "-" + month.value + "-" + day.value;

                        var test = theForm.elements[i + 3];

                        if ( test.value == "-0-0" || test.value == "0-0-0" )
                                test.value = "";

                        month.value = "";
                        day.value = "";
                        year.value = "";

                        i = i + 3;
                }
        }
        document.theForm.submitForm.value = "1";
        document.theForm.submit();
        return true;
}

function validateSpouseSelection( myForm )
{
        // myForm.ID = spouse selection from drop-list
        if ( myForm.ID.selectedIndex == 0 ) {
                alert( "Please choose an individual from the list." );
        }
        else {
                myForm.submitForm.value=1;
                myForm.submit();
        }
}

function validateLogin( theForm )
{
        var newUsername = theForm.newUsername.value.trim();
        var newPassword = theForm.newPassword.value.trim();
        var readOnlyPassword = theForm.readOnlyPassword.value.trim();
        var adminPassword = theForm.adminPassword.value.trim();
        var email = theForm.email.value.trim();
        var aname = theForm.aname.value.trim();
        var dname = theForm.dname.value.trim();

        // modify the form contents to reflect the trim
        theForm.newUsername.value = newUsername;
        theForm.newPassword.value = newPassword;
        theForm.readOnlyPassword.value = readOnlyPassword;
        theForm.adminPassword.value = adminPassword;
        theForm.email.value = email;
        theForm.aname.value = aname;
        theForm.dname.value = dname;

		//@Begin - (added by Andrew)
		//document.write('HELLO');
        //theForm.newTree.value = 1;
        //theForm.submit();
		//return;
		//End - (added by Andrew)

        // check to make sure login passwords contain no special characters, etc.
        if ( newUsername == "" )
        {
                alert( "Please enter a user name." );
                return;
        }
        if ( newPassword == "" )
        {
                alert( "Please enter a general access password." );
                return;
        }
        if ( readOnlyPassword == "" )
        {
                alert( "Please enter a read-only password." );
                return;
        }
        if ( adminPassword == "" )
        {
                alert( "Please enter an administrative password." );
                return;
        }

        if ( aname == "" )
        {
                alert( "Please enter an administrator's name." );
                return;
        }

        if ( dname == "" )
        {
                alert( "Please enter a descriptive tree name." );
                return;
        }
        if ( email == "" )
        {
                alert( "Please enter an administrative e-mail address." );
                return;
        }
        // check for non-alphanumeric characters
        if ( !alphanumericString( newUsername) )
        {
                alert( "The user ID (tree name) may contain only alphanumeric characters" );
                return;
        }
        if ( !alphanumericString( newPassword ) )
        {
                alert( "The general access password may contain only alphanumeric characters" );
                return;
        }
        if ( !alphanumericString( readOnlyPassword ) )
        {
                alert( "The read-only password may contain only alphanumeric characters" );
                return;
        }
        if ( !alphanumericString( adminPassword ) )
        {
                alert( "The administrative password may contain only alphanumeric characters" );
                return;
        }

        // check to make sure all passwords are unique
        //if ( $newPassword == $readOnlyPassword || $adminPassword == $readOnlyPassword || $newPassword == $adminPassword )
        if ( newPassword == readOnlyPassword || adminPassword == readOnlyPassword || newPassword == adminPassword )
        {
                alert( "All passwords must be unique." );
                return;
        }
	
	/*emailTest = "^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z_-]+\\.)+[a-z]{2,4}$";
	regex = new RegExp(emailTest);
	if (!regex.test(email) || !(email.length > 0)) 	errors += "Error in E-Mail address" + "\n";
	if (errors.length > 0) alert(errors);
	*/
	emailTest = "^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z_-]+\\.)+[a-z]{2,4}$";
	regex = new RegExp(emailTest);
	if (!regex.test(email) || !(email.length > 0)) 
		alert("Incorrect email format");
	
        theForm.newTree.value = 1;
        theForm.submit();
}

function validate( theForm )
{
        // combine date fields
        for( i = 0; i < theForm.elements.length; i++ )
        {
                if ( theForm.elements[i].name.indexOf( "_date_1" ) > 0 && theForm.elements[i+3].value.length == 0)
                {
                        var month = theForm.elements[i];
                        var day = theForm.elements[i + 1];
                        var year = theForm.elements[i + 2];
			
                        theForm.elements[i + 3].value = year.value + "-" + month.value + "-" + day.value;

				
                        var test = theForm.elements[i + 3];

                        // skip empty or invalid dates
                        if ( test.value == "-0-0" || test.value == "0-0-0" || test.value == "--" )
                                test.value = "";

                        month.value = "";
                        day.value = "";
                        year.value = "";

                        i = i + 3;
                }
        }
        
        
        var name = theForm.name.value.trim();
        var gender = getSelectedRadioValue( theForm.sex );
        var surn = theForm.surn.value.trim();
        if ( theForm.birt_date_3 == "" && theForm.deat_date_3 == "" ){ 
        var bd = theForm.birt_date_1.value * 30 + theForm.birt_date_2.value * 1 + theForm.birt_date_3.value * 365;
        var dd = theForm.deat_date_1.value * 30 + theForm.deat_date_2.value * 1 + theForm.deat_date_3.value * 365;
        // alert( dd+"|"+bd );
	        if ( dd<bd ){
	        alert( "Pease check birth and death time" );
	        return false;
        	}
        }
     

        // if gender is a hidden field, get the value

        if ( !gender || gender == "undefined" )
                gender = theForm.sex.value;

        if ( name == "" ) {
                alert( "You must enter a valid name (first & middle)." );
                return false;
        }
        else if ( surn == "" ) {
                alert( "You must enter a valid surname or family name." );
                return false;
        }
        else if ( gender == "" || !gender ) {
                alert( "You must specify a gender." );
                return false;
        }
        else {
                document.theForm.submitForm.value = "1";
                document.theForm.submit();
                return true;
        }
}

function launchCentered( url, width, height ) {
  launchCentered( url, width, height, '' );
}

function launchCentered( url, width, height, options) {
  var left = ( screen.width - width ) / 2;
  var top = ( screen.height - height ) / 2;
  var options = "top="+top+",left="+left+",width="+width+",height="+height+","+options;
  launch( url, options );
}
function launch( url, params ) {
  self.name = 'opener';
  var remote = window.open( url, 'remote', params );
}
