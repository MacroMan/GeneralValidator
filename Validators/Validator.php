<?php

class Validator {

	protected $error;
	protected $value;

	public function validate(string $value) {

	}

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
