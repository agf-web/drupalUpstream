<?php

namespace Drupal\Tests\telephone_validation\Unit;

use Drupal\Core\Locale\CountryManagerInterface;
use Drupal\telephone_validation\Validator;
use Drupal\Tests\UnitTestCase;
use libphonenumber\PhoneNumberFormat;

/**
 * @coversDefaultClass  Drupal\telephone_validation\Validator
 * @group Telephone
 */
class ValidatorTest extends UnitTestCase {

  /**
   * Country manager service mock.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected $countryManager;

  /**
   * Validator.
   *
   * @var \Drupal\telephone_validation\Validator
   */
  protected $validator;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $mock = $this->createMock(CountryManagerInterface::class);
    $mock->expects($this->any())
      ->method('getList')
      ->withAnyParameters()
      ->willReturn(['NO' => 'Norway', 'CA' => 'Canada', 'US' => 'United States']);
    $this->countryManager = $mock;

    // Instantiate validator.
    $this->validator = new Validator($this->countryManager);
  }

  /**
   * Tests get country list.
   *
   * ::covers getCountryList.
   *
   * @dataProvider dataCountryList
   */
  public function testCountryList($countryCode, $label) {
    $validator = new Validator($this->countryManager);
    $list = $validator->getCountryList();
    self::assertEquals($label, $list[$countryCode]);
  }

  /**
   * Tests phone number validation.
   *
   * ::covers isValid.
   *
   * @dataProvider dataPhoneNumbers
   */
  public function testIsValid($countryCode, $countryPrefix, $number) {

    // Test if number passes if format is National and number matches allowed
    // country.
    self::assertTrue($this->validator->isValid($number, PhoneNumberFormat::NATIONAL, [$countryCode]));

    // Test if number fails if country is not supported.
    self::assertFalse($this->validator->isValid($number, PhoneNumberFormat::NATIONAL, ['XYZ']));

    // Test if number fails if country doesn't match.
    self::assertFalse($this->validator->isValid($number, PhoneNumberFormat::NATIONAL, ['UA']));

    // Test if number fails if format is wrong.
    self::assertFalse($this->validator->isValid($number, PhoneNumberFormat::INTERNATIONAL, [$countryCode]));
    self::assertFalse($this->validator->isValid($number, PhoneNumberFormat::E164, [$countryCode]));

    // Test if number passes if we add country prefix.
    self::assertTrue($this->validator->isValid($countryPrefix . $number, PhoneNumberFormat::E164, [$countryCode]));

    // Test if number passes if country is not defined.
    self::assertTrue($this->validator->isValid($countryPrefix . $number, PhoneNumberFormat::E164, []));

    // Test if number fails if it's prefix doesn't belong to one of the
    // countries from white-list.
    self::assertFalse($this->validator->isValid($countryPrefix . $number, PhoneNumberFormat::E164, ['UA']));
  }

  /**
   * Provides test data to testCountryList().
   *
   * @return array
   */
  public function dataCountryList() {
    return [
      ['NO', 'Norway - +47'],
      ['CA', 'Canada - +1'],
      ['US', 'United States - +1'],
    ];
  }

  /**
   * Provides test data to dataPhoneNumbers().
   *
   * @return array
   */
  public function dataPhoneNumbers() {
    return [
      ['CA', '+1', '2507638884'],
      ['NO', '+47', '98765432'],
      ['DK', '+45', '55555555'],
      ['PL', '+48', '748111111'],
    ];
  }

}
