WELCOME TO TUFAT!
(c) 1999-2007 Darren G. Gates

This file contains important installation information for TUFaT. Special thanks to Kevin Alexander, David Gates, Duane Gates, James Sanchez, Jen Nathanson, and Holly Tomren for their suggestions on improving this system and for pointing out bugs. Also, special thanks to Brendon M. for his excellent template assistance, to Lubo, Adrian Tufa, Pat, and Vijay Nair for their programming assistance, and to Vernon Keene for allowing use of his server for testing purposes. I'm sure that there are many others who have assisted in the development of this system, too!


****** WHAT YOU NEED ******

To Install TUFaT, you MUST have PHP 4.3 or better, and access to a server running MySQL. In addition, you must have the authority to create and edit tables in MySQL.


****** STEP 1 - UPLOAD ******

First, copy the entire TUFaT directory structure to the folder of your choice on your web server. For example, suppose your web server has an images folder, a CGI-BIN, a 'documents' folder, and other folders. You should upload the tufat system files such that your server's filesystem looks something like this:

/images (This is NOT the TUFaT images folder)
/cgi-bin
/work documents
/family pictures
/tufat
   /images (This is the TUFaT images folder)
   /temp
   /languages
   /classes
   /css file (tufat.css)
   /php file (e.g. index.php)
   /php file (e.g. config.php)
   /php file (e.g. load.php)
   /...etc...
   
NOTE: The /temp directory MUST exist, even though it is empty. Also, it must be CHMOD'ed to a world readable/writeable mode, like 777. Also CHMOD the "templates_c" and "cache" folders. If you do not know how to CHMOD files and folders, there are a lot of simple tutorials out there on how to do it. Most commercial FTP clients provide the ability to use CHMOD. This is not applicable on Windows-based servers. On Windows, you still must have the /temp directory but it does not need to be CHMOD'ed (it still needs to be world readable/writeable, however).
   
Thus, to access Tufat after uploading, go to:

http://www.yourdomain.com/tufat/

You should set up index.php as the default file for the /tufat directory. The TUFaT program files can be in an directory of your choice. For example, you might choose to use /myfamily or /somefolder/tufat/ or some other path. If, for example, you chose /somefolder/tufat/ to store the files, your path would be:

http://www.yourdomain.com/somefolder/tufat/index.php


****** STEP 2 - INSTALL ******

This part is simple. Just open your browser window to:

http://www.yourdomain.com/{tufat_directory}/install.php

for example, if you used the default TUFaT directory name of "tufat", then you would go to:

http://www.yourdomain.com/tufat/install.php

You will need to know your MySQL login information and database name, as well as the MySQL hostname, which is usually "localhost", but not always!

REDHAT LINUX USERS: Some users of RedHat Linux have reported problems using PHP sessions with TUFaT. It appears that upgrading to the very latest version of PHP solves this problem. You should also check the following PHP value in your php.ini file:

session.use_cookies = 1


****** STEP 3 - SSL INSTALLATION (optional) ******

TUFaT does not come with an SSL (Secure Socket Layer) certificate or RSA key or anthing. It's up to you to install SSL on your server. But I'll give you a summary of how to do it.

What is SSL? Basically, if you have a secure certificate installed on your web server, it means that all communication between a web browser and the server is encrypted using strong, 128-bit encryption. This level of encryption is essentially impossible to break, because it would take the fastest supercomputers in the world millions of years to crack. What does that mean for you? It means that snoopers cannot eavesdrop on your web surfing. In the case of TUFaT, it means that your family tree will be more secure from hackers. Sites protected with SSL begin with https:// instead of http://

To get SSL, you should contact your web server administrator. Most commercial web hosts offer some type of SSL service. The basic steps are:

1. Generate a 'private key' (the administrator typically does this)
2. Purchase a secure certificate from a company like Thawte (www.thawte.com)
3. Install the certificate (the administrator typically does this)

Certificates cost around $100-200 annually. Some web hosts offer a free version of SSL, but such free services typically are not https://www.yourdomain.com, but instead https://www.thewebhost.com/your_account/ or something of the sort. Of course, you can always go without one, but it means that you should be careful about what information you put on your family tree.


****** USING TUFAT WITH < PHP 4.3 ******

TUFaT works in *some* environments with PHP 4.2, but NOT if you are using a RedHat Linux server. You MUST have at least 4.3 if your server uses RedHat Linux. If you are not sure what your server is, create a simple file with only this information:

<?php  phpinfo(); ?>

Then, open this file in your web browser and you will be able to view lots of detailed information about your server, including the PHP version and server type.


****** GETTING ASSISTANCE ******

If you need specialized tools for TUFaT or some customization that is not available in the config.php file, then please email me at g8z@yahoo.com. I am usually willing to do such customizations for free if I think that they will benefit all users. If they are extremely specific and not generally applicable to most family trees, then I will charge my standard programming rate of $25/hour.

I am available for a $25 fee to install this script for you on your server. I will need the database login information and your server's FTP information. Please email me at g8z@yahoo.com if you would like this more advanced form of assistance.


****** LICENSE ******

The $5 puchase of TUFaT includes: Rights to use *this version* of TUFaT on a single domain. TUFaT has a "one script purchase = one domain" license (as of version 2.4). In other words, you may use this software on ONE domain (e.g. www.yourdomain.com) only. To use this software on multiple domains, you must purchase a license for each domain.

If you modify the source code, the license does not change (i.e., changing my source code does not transfer the rights of distribution to you, unless you change at least 90% of it in very substantial ways). If you change the source code, you do so at your own risk. I make no claims for the reliability of this software... it's strictly "use at your own risk". By purchasing and installing this software, you accept these risks and release me of any liability that might come as a result of using this software.

You may not resell this software under any circumstances, and you may not charge others to have it installed. If you know someone who would like this software, you should refer them to www.tufat.com for purchasing information.


****** ADDENDA & DESIGN RATIONALE ******

Since you are being given all of the source code, you may modify TUFaT in any way that you wish, provided that you know a bit of programming. However, I cannot provide any technical support for it once modified. I am willing to answer questions that are posted on the tufat.com bulletin board, but please do not email me your questions, as they will probably go unanswered.

If you liked TUFaT, then please go to whatever forum you downloaded it from (e.g. www.hotscripts.com) and give the program a favorable rating! Thanks. :)