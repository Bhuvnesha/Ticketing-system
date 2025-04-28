<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $email = \Config\Services::email();

        $email->setFrom('bhuvneshanand28@gmail.com', 'Bhuvnesh');
        $email->setTo('test@gmail.com');
        // $email->setCC('another@example.com');
        // $email->setBCC('hidden@example.com');
        
        $email->setSubject('Test Email from CodeIgniter 4');
        $email->setMessage('Hello, this is a test email from CodeIgniter 4.');

        if ($email->send()) {
            echo 'Email successfully sent';
        } else {
            echo 'Email not sent';
            // Uncomment below to see errors
            echo $email->printDebugger();
        }
    }

    public function sendHtmlEmail()
	{
	    $email = \Config\Services::email();

	    $email->setFrom('bhuvneshanand28@gmail.com', 'Bhuvnesh');
	    $email->setTo('test@gmail.com');
	    
	    $email->setSubject('HTML Email Test');
	    
	    $message = view('email_template'); // Load a view file for the email content
	    // Or create HTML manually:
	    // $message = '<h1>Welcome</h1><p>This is an HTML email</p>';
	    
	    $email->setMessage($message);

	    if ($email->send()) {
	        echo 'HTML email sent successfully';
	    } else {
	        echo 'Failed to send email';
	    }
	}
}