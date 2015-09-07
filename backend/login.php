<?php // index.php
require_once("lib/OpenIDConnectClient.php");
$oidc = new OpenIDConnectClient("https://accounts.google.com/o/oauth2/auth");
$oidc->register();
$client_id = $oidc->getClientID();
$client_secret = $oidc->getClientSecret();

echo $client_id;
echo $client_secret;
?>

<a href="<?php echo $openid->authUrl() ?>">Login with Google</a>