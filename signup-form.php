<?php
/*
   Plugin Name: Signup Form
   description: Create a user and send email to user using smtp. For Using this plugin you have to add this shortcode [signupform] to any pages or posts.
   Version: 1.0
   Author: Ankit Nagar
*/


function html_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'User Name (required) <br/>';
	echo '<input type="text" name="user-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["user-name"] ) ? esc_attr( $_POST["user-name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Email (required) <br/>';
	echo '<input type="email" name="user-email" value="' . ( isset( $_POST["user-email"] ) ? esc_attr( $_POST["user-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Password (required) <br/>';
	echo '<input type="password" name="user-password" value="' . ( isset( $_POST["user-password"] ) ? esc_attr( $_POST["user-password"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Security Question (required) <br/>';
	echo '<input type="text" name="user-sec-que" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["user-sec-que"] ) ? esc_attr( $_POST["user-sec-que"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Security Answer (required) <br/>';
	echo '<input type="text" name="user-sec-answer" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["user-sec-answer"] ) ? esc_attr( $_POST["user-sec-answer"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p><input type="submit" name="user-submitted" value="Send"></p>';
	echo '</form>';
}

function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['user-submitted'] ) ) {

		// sanitize form values
		$user_name    = sanitize_text_field( $_POST["user-name"] );
		$user_email   = sanitize_email( $_POST["user-email"] );
		$user_password = $_POST["user-password"];
		$user_sec_que = sanitize_text_field( $_POST["user-sec-que"] );
		$user_sec_answer = sanitize_text_field( $_POST["user-sec-answer"] );

		// get the blog administrator's email address
		$to = $user_email;
		$subject = "Thank you for registering our website. We will contact with you!!";
		$message = '';
		$message = '<html><body>';
		$message .= 'Username = '.$user_name;
		$message .= '<br>';
		$message .= 'Useremail = '.$user_email;
		$message .= '<br>';
		$message .= 'Userpassword = '.$user_password;
		$message .= '<br>';
		$message .= 'Security Question = '.$user_sec_que;
		$message .= '<br>';
		$message .= 'Security Answer = '.$user_sec_answer;
		$message .= "</body></html>";
		
		$headers = "From: $user_name <$user_email>" . "\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p>Thanks for contacting us, expect a response soon.</p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occurred';
		}
	}
}

function create_signup_shortcode() {
	ob_start();
	deliver_mail();
	html_form_code();

	return ob_get_clean();
}

add_shortcode( 'signupform', 'create_signup_shortcode' );

?>