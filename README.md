## Synopsis
General string validation. Currently Email address, string length, password, url and country code validation. Easily extendable with others.

## Code Example
    $validator = PasswordValidator(3); // Minimum password score 3
    if ($validator->validate("my-very-long-password-is-good")) {
    	// Good, your password is strong enough
    }

## API Reference
Classes:

    Validator() // Parent for all classes
    EmailValidator()
    UrlValidator()
    CountryCodeValidator()
    LengthValidator(int $minLength, int $maxLength)
    PasswordValidator(int $minScore) // Score from 0 (weakest) to 4 (strongest)


Methods:

    Validator::validate(string) // Pass in the test string and returns bool
    Validator::load() // Shorthand class loader
    Validator::getValue() // Returns the output of the validator, null if no output
    Validator::getError() // Returns the error from the validator, null if no error
    StringValidator::setMin(int) // Update the min string length
    StringValidator::setMax(int) // Update the max string length
    PasswordValidator::setMinScore(int) // Update the min password score required for a pass```

## Tests

Tests are provided in `test/ValidatorTest.php` and can be run with PHPUnit

## License

BSD 2-Clause License - See LICENSE