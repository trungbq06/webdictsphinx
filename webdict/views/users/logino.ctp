<?php
// app/views/users/login.ctp
if (isset($message)) {
    echo '<p class="error">'.$message.'</p>';
}
echo $form->create('User', array('type' => 'post', 'action' => 'logino'));
echo $form->input('OpenidUrl.openid', array('label' => false));
echo $form->end('Login');
?>