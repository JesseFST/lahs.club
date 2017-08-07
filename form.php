<?php
if (isset($_POST['first_name'])) {
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$year = $_POST['year'];

	require_once("conf.ini.php");
	if (substr($email, 0, 5) == "10001" && substr($email, -9) == "@mvla.net") {
		$connection = mysqli_connect("localhost", $username, $password, $database);
		$query = mysqli_query($connection, "SELECT `email` FROM `users` WHERE `email` = '$email';");
		if (mysqli_num_rows($query) == 0) {
			mysqli_query($connection, "INSERT INTO `users` VALUES('$first_name', '$last_name', '$email', '$year');");
			file_get_contents("https://slack.com/api/users.admin.invite?token=$token&email=$email");

		$linux_username = strtolower($first_name . $last_name);
		$password = generateRandomString(8);

		$mail->setFrom('hack@lahs.club', 'LAHS Hack Club');
		$mail->addAddress($email);
		$mail->addBCC('hack@lahs.club');
		$mail->isHTML(true);

		$mail->Subject = 'Welcome to LAHS Hack Club!';
		$mail->Body = "<head><link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'></head><body style='font-family: \"Roboto\", sans-serif;'><div style='width: 90%; height: 100%; padding: 5% 5%;'><div style='width: calc(100% - 20px); padding: 10px; background-color: #1565c0; color: white; text-align: center; font-size: 2em; margin: 0'>Welcome to LAHS Hack Club!</div><div style='background-color: #efefef; padding: 10px;'><p>Dear $first_name,</p><p>Welcome to the LAHS Hack Club! We're super excited to have you! Please keep in mind that we meet once per week on Thursdays at lunch in the APCS room (Room 723), so be sure to be there! If you're new to LAHS, room 723 is on the second floor above the science rooms -- check out your planner for a map! You can find our club's website for more information at <a href='https://lahs.club'>lahs.club</a>.</p><p>You have automatically been added to our server and mailing list, and an invite to our Slack has been sent to your email. If you've never used Slack before, it's basically a messaging service that we use to communicate!</p><p>Finally, we've added you to our dedicated server, solely for LAHS Hack Club members! Your username is <b>$linux_username</b> and your password is <b>$password</b>. You will be prompted to change your password when you log in via SSH on <b>server.lahs.club</b>, but if you have no idea what we're talking about, no worries! We'll go over it during our meetings. Keep this email for reference!</p><p>We can't wait to see you next Thursday!</p><p>Best Regards,</p><p>The LAHS Hack Club Team</p></div></div></body>";

		$mail->send();

		exec('yes "" | adduser ' . $linux_username . ' --disabled-login');
		exec('yes "' . $password . '" | passwd ' . $linux_username);
		exec('chown -hR ' . $linux_username . ' /home/' . $linux_username);
		exec('chage -d 0 ' . $linux_username);

			echo "success";
		} else {
			echo "email_exists";
		}
	} else {
		echo "invalid_email";
	}
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}