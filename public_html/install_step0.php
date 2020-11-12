<?php
/**
 * TUFaT, The Ultimate Family Tree Creator
 * Copyright 1999 - 2007, Darren G. Gates, All Rights Reserved
 * http://www.tufat.com
 * info@tufat.com
 * Selling the code for this script without prior written consent of
 * Darren G. Gates is expressly forbidden. The license of TUFaT
 * permits you to use install this script on one domain or one
 * physical server. Taking credit for any part of this software is a
 * violation of the copyright. TUFaT comes with no guarantees
 * for reliability or accuracy – in other words, you use this script
 * at your own risk! By using this software, you accept these risks,
 * and agree to indemnify Darren G. Gates for any liability that
 * might arise from its use. You must obtain permission from Darren
 * G. Gates before redistributing TUFaT in any form, over the
 * Internet or any other medium. In ALL cases this copyright notice,
 * as well as the (c) tufat.com notice on the actual TUFaT pages,
 * must remain intact. Removing or modifying this copyright notice
 * is a violation of the license agreement and may subject you to
 * legal proceedings.
 */
print <<<STEP0END
        <font class="title">Welcome!</font><br /><br />
        <table width='100%' class="normal">
                <tr>
                        <td>
                                <p class="normal">Thank you for choosing TUFaT. This is a 5-step installation wizard. In the first step, TUFaT will check for some basic system requirements, like a suitable version of PHP and write permissions for the folders "temp", "templates_c" and "cache"  and  config.php file.</p>

                                <p class="normal">In the second and third steps, you will input your database access information and TUFaT will create the necessary database tables, and in the final steps you will specify some configuration options for using TUFaT, which will be written to a configuration file on your server.</p>

                                <p class="normal">Please note the status messages on the right-side of each screen. If an error is found, then a red message is displayed. You should take the necessary steps to correct these messages as they appear before continuing the installation wizard. If you are unsure how to make the corrections, please contact your website administrator (usually, the web host for your domain) for more information.</p>
                        </td>
                </tr>
                <tr>
                        <td>Ok, let's begin! Click the button below to start the wizard.
                        </td>
                </tr>
        </table>
        <br />

        <input type="button" onclick='location.href="install.php?step=1"' value='Start Installation' />


STEP0END;

?>