<?php

abstract class Validator {

	protected $error;
	protected $value;

	abstract public function validate(string $value);

	/**
	 * Quick loader to avoid using new keyworld for simple validation
	 * Example: For the email validator, only the string arg is required: EmailValidator::load()->validate("me@example.com")
	 * @return static Validator
	 */
	public static function load() {
		return new static;
	}

	public function getError() {
		return $this->error;
	}

	public function getValue() {
		return $this->value;
	}

}
