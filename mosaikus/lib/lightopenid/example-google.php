<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'openid.php';
try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID('sslmasisa.com.ve');
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
			$openid->required = array('namePerson/first', 'contact/email','media/image/default',);
            header('Location: ' . $openid->authUrl());
        }
		?>
		<form action="?login" method="post">
			<button>Login with Google</button>
		</form>
		<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        //echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
		if($openid->validate()) {
			$returnVariables = $openid->getAttributes();
			echo 'User ' . $openid->identity . ' has logged in with this email address ' . $returnVariables['contact/email']. $returnVariables['media/image/default'];
		}
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
