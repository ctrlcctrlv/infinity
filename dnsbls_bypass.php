<?php
include 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$captcha = generate_captcha($config['captcha']['extra']);

	$html = "{$captcha['html']}<br/>
		<input class='captcha_text' name='captcha_text' size='25' maxlength='6' autocomplete='off' type='text'>
		<input class='captcha_cookie' name='captcha_cookie' type='hidden' autocomplete='off' value='{$captcha['cookie']}'><br/>";

	$body = Element("8chan/dnsbls.html", array("config" => $config, "ayah_html" => $html));

	echo Element("page.html", array("config" => $config, "body" => $body, "title" => _("Bypass DNSBL"), "subtitle" => _("Post even if blocked")));
} else {
	$resp = file_get_contents($config['captcha']['provider_check'] . "?" . http_build_query([
		'mode' => 'check',
		'text' => $_POST['captcha_text'],
		'extra' => $config['captcha']['extra'],
		'cookie' => $_POST['captcha_cookie']
	]));

	if ($resp === '1') {
		$tor = checkDNSBL($_SERVER['REMOTE_ADDR']);
		if (!$tor) {
			$query = prepare('INSERT INTO ``dnsbl_bypass`` VALUES(:ip, NOW(), 0) ON DUPLICATE KEY UPDATE `created`=NOW(),`uses`=0');
			$query->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
			$query->execute() or error(db_error($query));
		}
		$cookie = bin2hex(openssl_random_pseudo_bytes(16));
		$query = prepare('INSERT INTO ``tor_cookies`` VALUES(:cookie, NOW(), 0)');
		$query->bindValue(':cookie', $cookie);
		$query->execute() or error(db_error($query));
		setcookie("tor", $cookie, time()+60*60*3);
	
		echo Element("page.html", array("config" => $config, "body" => '', "title" => _("Success!"), "subtitle" => _("You may now go back and make your post.")));
	} else {
		error(_('You failed the CAPTCHA') . _('. <a href="https://8ch.net/dnsbls_bypass.php">Try again.</a> If it\'s not working, email admin@8chan.co for support.'));
	}
}
