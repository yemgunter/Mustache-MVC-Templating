<?php
/*
 * Name: Yolanda Gunter
 * Assignment: Coding 05
 * Purpose: Templating & Making a 3+ Page Website
 * Notes: Learning then implementing Mustache to create templates.
 */

function redirect($url) {
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    die();
}

function main() {
    //this first if statement tests to make sure we have a valid $_POST array
    if (!empty($_POST)) {
 
        /* Cleaning routines below will strip anything harmful or extraneous out 
         * out of the submitted $_POST variables. */
        $name = substr(strip_tags(trim($_POST['name'])),0,64);
        $subject = substr(strip_tags(trim($_POST['subject'])),0,64);
        $message = substr(strip_tags(trim($_POST['message'])),0,64);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : $email = "";
        
        /* Test cleaned variable here. If we find and empty variable , we stop
         * processing because that means someone tried to send us something malicious or 
         * or incorrect.*/
        
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
         if ($email === false) {
               redirect('templates/error.html');
         }
        
        if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
            
            /* This forms the corect email headers to send an email */
            $headers = "FROM: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
            
            
            /* Now try to send the email. If it succeeds, we redirect to a 
             * success page. if not, we redirect to an error page. */
            if (mail('yolanda@yolandagunter.com', $subject, $name . '\n\n' . $message, $headers)) {
                redirect('templates/success.html');
            } else {
                /* The query string at the end of each error redirect is so we 
                 * can tell which erro trigered. In this case it has no effiect
                 * on the redirect becaue we ignote it up on redirect. In a real
                 * production application we can capture the query string and 
                 * tailor our error message to the type of error. */
                redirect('templates/error.html?error=1');
            }
        } else {
            redirect('templates/error.html?error=2');
        } 
    } else {
            redirect('templates/error.html?error=3');
        }
}

//this calls main - kicks off the script
main();

