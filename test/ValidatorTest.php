<?php

namespace MacroMan\GenralValidator\Tests;

use PHPUnit\Framework\TestCase;
use MacroMan\GenralValidator\EmailValidator;
use MacroMan\GenralValidator\UrlValidator;
use MacroMan\GenralValidator\CountryCodeValidator;
use MacroMan\GenralValidator\LengthValidator;
use MacroMan\GenralValidator\PasswordValidator;

class ValidatorTest extends TestCase {

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

		$this->assertTrue($validator->validate("very.common@example.com"));
		$this->assertNull($validator->getError());
	}

	public function testUrl() {
		$validator = new UrlValidator();

		$this->assertTrue($validator->validate("ftp://ftp.is.co.za.example.org/rfc/rfc1808.txt"));
		$this->assertTrue($validator->validate("gopher://spinaltap.micro.umn.example.edu/00/Weather/California/Los%20Angeles"));
		$this->assertTrue($validator->validate("http://www.math.uio.no.example.net/faq/compression-faq/part1.html"));
		$this->assertTrue($validator->validate("mailto:mduerst@ifi.unizh.example.gov"));
		$this->assertTrue($validator->validate("news:comp.infosystems.www.servers.unix"));
		$this->assertTrue($validator->validate("telnet://melvyl.ucop.example.edu/"));
		$this->assertTrue($validator->validate("http://www.ietf.org/rfc/rfc2396.txt"));
		$this->assertTrue($validator->validate("ldap://[2001:db8::7]/c=GB?objectClass?one"));
		$this->assertTrue($validator->validate("mailto:John.Doe@example.com"));
		$this->assertTrue($validator->validate("news:comp.infosystems.www.servers.unix"));
		$this->assertTrue($validator->validate("telnet://192.0.2.16:80/"));

		$this->assertFalse($validator->validate("tel:+1-816-555-1212"));
		$this->assertFalse($validator->validate("urn:oasis:names:specification:docbook:dtd:xml:4.1.2"));
		$this->assertEquals("Invalid URL according to RFC 3986", $validator->getError());

		$this->assertTrue($validator->validate("http://www.ietf.org"));
		$this->assertNull($validator->getError());
	}

	public function testCountryCode() {
		$validator = new CountryCodeValidator();

		$this->assertTrue($validator->validate("DM")); // My fav holiday destination
		$this->assertEquals("Dominica", $validator->getValue());
		$this->assertNull($validator->getError());

		$this->assertTrue($validator->validate("HK"));
		$this->assertEquals("Hong Kong", $validator->getValue());

		$this->assertTrue($validator->validate("CL"));
		$this->assertEquals("Chile", $validator->getValue());

		$this->assertFalse($validator->validate("UX"));
		$this->assertEquals("Invalid Country Code according to ISO 3166-1", $validator->getError());
		$this->assertNull($validator->getValue());

		$this->assertTrue($validator->validate("GB"));
		$this->assertEquals("United Kingdom of Great Britain and Northern Ireland", $validator->getValue());
		$this->assertNull($validator->getError());
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
		$validator = new PasswordValidator(3);
		$this->assertFalse($validator->validate("123")); // 0
		$this->assertEquals(0, $validator->getValue());

		$this->assertFalse($validator->validate("password")); // 0
		$this->assertFalse($validator->validate("#t~-")); // 1
		$this->assertEquals(1, $validator->getValue());
		$this->assertEquals("Password less than the minimum score", $validator->getError());

		$this->assertTrue($validator->validate("my-very-long-password-is-good")); // 4
		$this->assertNull($validator->getError());

		$this->assertTrue($validator->validate("m?sWu4re")); // 3

		$validator->setMinScore(4);
		$this->assertTrue($validator->validate("gUY3Geyab@Sw")); // 4
		$this->assertFalse($validator->validate("m?sWu4re")); // 3
	}

	public function testLoad() {
		$this->assertTrue(EmailValidator::load()->validate("prettyandsimple@example.com"));
		$this->assertFalse(EmailValidator::load()->validate("Abc.example.com"));

		$this->assertTrue(UrlValidator::load()->validate("ftp://ftp.is.co.za.example.org/rfc/rfc1808.txt"));
		$this->assertFalse(UrlValidator::load()->validate("tel:+1-816-555-1212"));

		$this->assertTrue(CountryCodeValidator::load()->validate("DM"));
		$this->assertFalse(CountryCodeValidator::load()->validate("UX"));
	}

}
