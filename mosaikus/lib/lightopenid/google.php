<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'LightOpenID.php';
try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID;
    if(!$openid->mode) {
        if(isset($_GET['login'])) {			
            $openid->identity = 'https://www.google.com/accounts/o8/id';
			$openid->required = array('namePerson/first', 'contact/email');
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
		$openid = new LightOpenID;
		echo $openid->validate() ? 'Logged in.' : 'Failed';
		//if($openid->validate()) {
			$returnVariables = $openid->getAttributes();
			echo 'User ' . $openid->identity . ' has logged in with this email address ' . $returnVariables['contact/email'];
		//}
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
