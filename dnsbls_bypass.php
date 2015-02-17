<?php
include 'inc/functions.php';
include "inc/lib/recaptcha/recaptchalib.php";
require_once 'Net/DNS2.php';
checkBan('*');
// My nameserver was broken and I couldn't edit resolv.conf so I just did this instead
$dns = new Net_DNS2_Resolver(array('nameservers' => array('8.8.8.8')));
$result = $dns->query(RECAPTCHA_VERIFY_SERVER, "A");
if ($result and $result->answer[0]) {
	$RECAPTCHA_VERIFY_SERVER_IP = $result->answer[0]->address;
} else {
	$RECAPTCHA_VERIFY_SERVER_IP = RECAPTCHA_VERIFY_SERVER;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$ayah_html = recaptcha_get_html($config['recaptcha_public'], NULL, TRUE);
	$body = Element("8chan/dnsbls.html", array("config" => $config, "ayah_html" => $ayah_html));

	echo Element("page.html", array("config" => $config, "body" => $body, "title" => _("Bypass DNSBL"), "subtitle" => _("Post even if blocked")));
} else {
	$score = recaptcha_check_answer($config['recaptcha_private'],
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"],
				array(),
				$RECAPTCHA_VERIFY_SERVER_IP);

	if ($score->is_valid) {
		$tor = checkDNSBL($_SERVER['REMOTE_ADDR']);
		if (!$tor) {
			$query = prepare('INSERT INTO ``dnsbl_bypass`` VALUES(:ip, NOW()) ON DUPLICATE KEY UPDATE `created`=NOW()');
			$query->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
			$query->execute() or error(db_error($query));
		} else {
			$cookie = bin2hex(openssl_random_pseudo_bytes(16));
			$query = prepare('INSERT INTO ``tor_cookies`` VALUES(:cookie, NOW(), 0)');
			$query->bindValue(':cookie', $cookie);
			$query->execute() or error(db_error($query));
			setcookie("tor", $cookie);
		}
		echo Element("page.html", array("config" => $config, "body" => '', "title" => _("Success!"), "subtitle" => _("You may now go back and make your post.")));
	} else {
		error(_('You failed the CAPTCHA') . _('. <a href="https://8ch.net/dnsbls_bypass.php">Try again.</a> If it\'s not working, email admin@8chan.co for support.'));
	}
}
