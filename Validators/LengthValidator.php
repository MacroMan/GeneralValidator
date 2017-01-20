<?php

class LengthValidator extends Validator {

	private $min = null;
	private $max = null;

	public function __construct(int $min, int $max) {
		$this->min = $min;
		$this->max = $max;
		$this->checkProperties();
	}

	public function setMin(int $min) {
		$this->min = $min;
		$this->checkProperties();
	}

	public function setMax(int $max) {
		$this->max = $max;
		$this->checkProperties();
	}

	public function validate(string $value) {
		if (strlen($value) < $this->min) {
			$this->error = "String shorter than min length {$this->min}";
			return false;
		}

		if (strlen($value) > $this->max) {
			$this->error = "String longer than max length {$this->max}";
			return false;
		}

		$this->error = null;
		return true;
	}

	private function checkProperties() {
		if ($this->min === null) {
			throw new Exception("Property min is not set on class LengthValidator");
		}
		if ($this->min < 0) {
			throw new Exception("Property min is below minimum allowed 0 on class LengthValidator");
		}
		if ($this->max === null) {
			throw new Exception("Property max is not set on class LengthValidator");
		}
		if ($this->max < 1) {
			throw new Exception("Property max is below minimum allowed 1 on class LengthValidator");
		}
		if ($this->min > $this->max) {
			throw new Exception("Property min is greater than property max on class LengthValidator");
		}
	}

}
