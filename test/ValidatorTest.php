<?php

include "./Validators/Validator.php";
include "./Validators/EmailValidator.php";
include "./Validators/LengthValidator.php";

class ValidatorTest extends PHPUnit_Framework_TestCase {

	public function testEmail() {
		$validator = new EmailValidator();

		// Test email compliant with RFC 822, except comments, whitespace folding and dotless domains
		$this->assertTrue($validator->validate("prettyandsimple@example.com"));
		$this->assertTrue($validator->validate("very.common@example.com"));
		$this->assertTrue($validator->validate("disposable.style.email.with+symbol@example.com"));
		$this->assertTrue($validator->validate("other.email-with-dash@example.com"));
		$this->assertTrue($validator->validate("x@example.com"));
		$this->assertTrue($validator->validate('"very.unusual.@.unusual.com"@example.com'));
		$this->assertTrue($validator->validate('"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com'));
		$this->assertTrue($validator->validate("example-indeed@strange-example.com"));
		$this->assertTrue($validator->validate("#!$%&'*+-/=?^_`{}|~@example.org"));
		$this->assertTrue($validator->validate("example@s.solutions"));
		$this->assertTrue($validator->validate("user@[IPv6:2001:DB8::1]"));

		$this->assertFalse($validator->validate("Abc.example.com"));
		$this->assertFalse($validator->validate("A@b@c@example.com"));
		$this->assertFalse($validator->validate('a"b(c)d,e:f;g<h>i[j\k]l@example.com'));
		$this->assertFalse($validator->validate('just"not"right@example.com'));
		$this->assertFalse($validator->validate('this is"not\allowed@example.com'));
		$this->assertFalse($validator->validate('this\ still\"not\\allowed@example.com'));
		$this->assertFalse($validator->validate("1234567890123456789012345678901234567890123456789012345678901234+x@example.com"));
		$this->assertFalse($validator->validate("john..doe@example.com"));
		$this->assertFalse($validator->validate("john.doe@example..com"));
		$this->assertFalse($validator->validate("very.common@example.com "));
		$this->assertFalse($validator->validate(" very.common@example.com"));
		$this->assertEquals("Invalid email address according to RFC 822", $validator->getError());
	}

	public function testUrl() {

	}

	public function testCountryCode() {

	}

	public function testLength() {
		$validator = new LengthValidator(4, 20);

		// Return true if strlen in range and error is null
		$this->assertTrue($validator->validate("This is my string"));
		$this->assertNull($validator->getError());

		// String too long with relevant error
		$this->assertFalse($validator->validate("This is my string that is far too long"));
		$this->assertEquals("String longer than max length 20", $validator->getError());

		// String too short with relevant error
		$this->assertFalse($validator->validate("Srt"));
		$this->assertEquals("String shorter than min length 4", $validator->getError());

		// Return true if strlen in range and error is null, even after a previous error
		$this->assertTrue($validator->validate("This is my string"));
		$this->assertNull($validator->getError());

		// Lower the max property and check validate is now false
		$validator->setMax(4);
		$this->assertFalse($validator->validate("This is my string"));
	}

	public function testPassword() {

	}

}
