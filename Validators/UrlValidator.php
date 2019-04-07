<?php

namespace MacroMan\GenralValidator;

class UrlValidator extends Validator {

	public function validate(string $url) {
		if (filter_var($url, FILTER_VALIDATE_URL)) {
			$this->error = null;
			return true;
		}

		$this->error = "Invalid URL according to RFC 3986";
		return false;
	}

}
