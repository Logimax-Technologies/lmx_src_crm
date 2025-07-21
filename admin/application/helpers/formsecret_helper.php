<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
function get_form_secret_key() {
  $CI =& get_instance();
  $token = md5(uniqid(rand(), true));
  $CI->session->unset_userdata('FORM_SECRET');
  $CI->session->set_userdata('FORM_SECRET', $token);
  return $token;
}

?>