<?php

namespace MacroMan\GenralValidator;

class PasswordValidator extends Validator {

	private $minScore = null;

	public function __construct(int $minScore) {
		$this->minScore = $minScore;
		$this->checkProperties();
	}

	public function setMinScore(int $minScore) {
		$this->minScore = $minScore;
		$this->checkProperties();
	}

	private function checkProperties() {
		if ($this->minScore === null) {
			throw new Exception("Property minScore is not set on class PasswordValidator");
		}
		if ($this->minScore < 0) {
			throw new Exception("Property minScore is below minimum allowed 0 on class PasswordValidator");
		}
	}

	public function validate(string $password) {
		$zxcvbn = new \ZxcvbnPhp\Zxcvbn();
		$strength = $zxcvbn->passwordStrength($password);
		$this->value = $strength['score'];

		if ($this->value >= $this->minScore) {
			$this->error = null;
			return true;
		}

		$this->error = "Password less than the minimum score";
		return false;
	}

}
