<?php

namespace MacroMan\GenralValidator;

class EmailValidator extends Validator {

	public function validate(string $address) {
		if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
			$this->error = null;
			return true;
		}

		$this->error = "Invalid email address according to RFC 822";
		return false;
	}

}
