<?php

/**
 * PHP-based Simple Capital Captcha
 *
 * @version 1.0
 * @author Nettraction <dev@nettraction.in>
 *
 */
Class CapitalCaptcha {

  private $session_var;
  private $result;
  private $phrase1;
  private $phrase2;
  private $phrase3;

  /**
   * @param string $sess_var
   * @param string $phrase_val
   */
  function __construct($sess_var = 'capital_captcha_result', $phrase_val='') {
	$this->phrase2 = ($phrase_val === '') ? '' : $phrase_val;

	if (!empty($sess_var)) {
	  $this->session_var = $sess_var;
	} else {
	  $this->session_var = 'capital_captcha_result';
	}
  }

  /**
   * Generate a new captcha and save the result into a session variable.
   */
  public function reset_captcha() {
	if (!session_id()) session_start();

	$this->phrase2 = $this->getRandomCountry();
	$this->compute_result();
	// Save to $_SESSION
	$_SESSION[$this->session_var] = $this->result;
  }

  private function getRandomCountry() {
	$countryList = [ 'ایران', 'فرانسه', 'انگلستان' , 'ایتالیا' ];
	return $countryList[rand(0, sizeof($countryList) - 1)];
  }

  private function compute_result() {
	switch ($this->phrase2) {
	  case 'ایران':
		$this->result = 'تهران';
		break;
	  case 'فرانسه':
		$this->result = 'پاریس';
		break;
	  case 'انگلستان':
		$this->result = 'لندن';
		break;
	  case 'ایتالیا':
		$this->result = 'رم';
		break;
	}
  }

  /**
   * @param int $val Value to be compared to the result in session
   * @return boolean TRUE if the value matches; FALSE otherwise
   */
  public function validate($val) {
	if (!session_id()) session_start();

	//normalize arabic
	$val = preg_replace('/آ|إ|أ/i' , 'ا',$val);
	$val = preg_replace('/ي|ئ|ء/i' , 'ی',$val);
	$val = str_replace('ك','ک',$val);
	$val = str_replace('ة','ه',$val);
	$val = str_replace('ؤ','و',$val);

	if ($val == $_SESSION[$this->session_var]) return TRUE;
	else return FALSE;
  }

  /**
   * @param string $format sprintf compatible format with text/html e.g "Compute Sum of {operand1} and {operand2}"
   * @return type
   */
  public function get_captcha_text($format = '{phrase1} {phrase2} {phrase3}') {
	if (!empty($format)) {
	  return str_replace( array('{phrase1}', '{phrase2}', '{phrase3}'), array(
		  $this->phrase1,
		  $this->phrase2,
		  $this->phrase3
	  ), $format);
	} else {
	  return sprintf("%s %s %s", $this->phrase1, $this->phrase2, $this->phrase3);
	}
  }

}