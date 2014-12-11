<?php
namespace In2code\Powermail\Tests\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Div Tests
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class DivTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	/**
	 * @var \In2code\Powermail\Utility\Div
	 */
	protected $generalValidatorMock;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->generalValidatorMock = $this->getAccessibleMock('\In2code\Powermail\Utility\Div', array('dummy'));
		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
		$this->generalValidatorMock->_set('objectManager', $objectManager);
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->generalValidatorMock);
	}

	/**
	 * Dataprovider getSenderNameFromArgumentsReturnsString()
	 *
	 * @return array
	 */
	public function getSenderNameFromArgumentsReturnsStringDataProvider() {
		return array(
			array(
				array(
					'Alex',
					'Kellner'
				),
				NULL,
				'Alex Kellner'
			),
			array(
				array(
					'Prof. Dr.',
					'Müller'
				),
				'abc',
				'Prof. Dr. Müller'
			),
			array(
				NULL,
				'Fallback Name',
				'Fallback Name'
			),
			array(
				NULL,
				NULL,
				'No Sendername'
			),
			array(
				array(
					// test multivalue (e.g. checkbox)
					array(
						'Prof.',
						'Dr.'
					),
					'Max',
					'Muster'
				),
				'xyz',
				'Prof. Dr. Max Muster'
			),
		);
	}

	/**
	 * Test for getSenderNameFromArguments()
	 *
	 * @param array $values
	 * @param string $fallback
	 * @param string $expectedResult
	 * @return void
	 * @dataProvider getSenderNameFromArgumentsReturnsStringDataProvider
	 * @test
	 */
	public function getSenderNameFromArgumentsReturnsString($values, $fallback, $expectedResult) {
		$mail = new \In2code\Powermail\Domain\Model\Mail;
		if (is_array($values)) {
			foreach ($values as $value) {
				$answer = new \In2code\Powermail\Domain\Model\Answer;
				$field = new \In2code\Powermail\Domain\Model\Field;
				$field->setType('input');
				$field->setSenderName(TRUE);
				$answer->setValue($value);
				$answer->setValueType((is_array($value) ? 1 : 0));
				$answer->setField($field);
				$mail->addAnswer($answer);
			}
		}

		$result = $this->generalValidatorMock->_callRef('getSenderNameFromArguments', $mail, $fallback);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Dataprovider getSenderMailFromArgumentsReturnsString()
	 *
	 * @return array
	 */
	public function getSenderMailFromArgumentsReturnsStringDataProvider() {
		return array(
			array(
				array(
					'no email',
					'abc@def.gh'
				),
				NULL,
				'abc@def.gh'
			),
			array(
				array(
					'alexander.kellner@in2code.de',
					'abc@def.gh'
				),
				NULL,
				'alexander.kellner@in2code.de'
			),
			array(
				array(
					'no email'
				),
				'test@email.org',
				'test@email.org'
			),
			array(
				array(
					'abc',
					'def',
					'ghi'
				),
				'abc@email.org',
				'abc@email.org'
			)
		);
	}

	/**
	 * Test for getSenderMailFromArguments()
	 *
	 * @param array $values
	 * @param string $fallback
	 * @param string $expectedResult
	 * @return void
	 * @dataProvider getSenderMailFromArgumentsReturnsStringDataProvider
	 * @test
	 */
	public function getSenderMailFromArgumentsReturnsString($values, $fallback, $expectedResult) {
		$mail = new \In2code\Powermail\Domain\Model\Mail;
		if (is_array($values)) {
			foreach ($values as $value) {
				$answer = new \In2code\Powermail\Domain\Model\Answer;
				$field = new \In2code\Powermail\Domain\Model\Field;
				$field->setType('input');
				$field->setSenderEmail(TRUE);
				$answer->setValue($value);
				$answer->setField($field);
				$mail->addAnswer($answer);
			}
		}

		$result = $this->generalValidatorMock->_callRef('getSenderMailFromArguments', $mail, $fallback);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Test for getStoragePage()
	 *
	 * @return void
	 * @test
	 */
	public function getStoragePageReturnsInt() {
		$result = \In2code\Powermail\Utility\Div::getStoragePage(123);
		$this->assertSame($result, 123);
	}

	/**
	 * Dataprovider getVariablesWithMarkersReturnsArray()
	 *
	 * @return array
	 */
	public function getVariablesWithMarkersReturnsArrayDataProvider() {
		return array(
			array(
				array(
					array(
						'marker',
						'value'
					),
				),
				array(
					'marker' => 'value'
				),
			),
			array(
				array(
					array(
						'firstname',
						'Alex'
					),
					array(
						'lastname',
						'Kellner'
					),
					array(
						'email',
						'alex@in2code.de'
					),
				),
				array(
					'firstname' => 'Alex',
					'lastname' => 'Kellner',
					'email' => 'alex@in2code.de'
				),
			),
			array(
				array(
					array(
						'checkbox',
						array(
							'red',
							'blue'
						)
					),
					array(
						'firstname',
						'Alex'
					),
				),
				array(
					'checkbox' => 'red, blue',
					'firstname' => 'Alex'
				),
			),
		);
	}

	/**
	 * Test for getVariablesWithMarkers()
	 *
	 * @param array $values
	 * @param string $expectedResult
	 * @return void
	 * @dataProvider getVariablesWithMarkersReturnsArrayDataProvider
	 * @test
	 */
	public function getVariablesWithMarkersReturnsArray($values, $expectedResult) {
		$mail = new \In2code\Powermail\Domain\Model\Mail;
		if (is_array($values)) {
			foreach ($values as $markerValueMix) {
				$answer = new \In2code\Powermail\Domain\Model\Answer;
				$field = new \In2code\Powermail\Domain\Model\Field;
				$field->setMarker($markerValueMix[0]);
				$answer->setValue($markerValueMix[1]);
				$answer->setField($field);
				$answer->setValueType((is_array($markerValueMix[1]) ? 1 : 0));
				$mail->addAnswer($answer);
			}
		}

		$result = $this->generalValidatorMock->_callRef('getVariablesWithMarkers', $mail);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Dataprovider getLabelsAttachedToMarkersReturnsArray()
	 *
	 * @return array
	 */
	public function getLabelsAttachedToMarkersReturnsArrayDataProvider() {
		return array(
			array(
				array(
					array(
						'marker',
						'title'
					),
				),
				array(
					'label_marker' => 'title'
				),
			),
			array(
				array(
					array(
						'firstname',
						'Firstname'
					),
					array(
						'lastname',
						'Lastname'
					),
					array(
						'email',
						'Email Address'
					),
				),
				array(
					'label_firstname' => 'Firstname',
					'label_lastname' => 'Lastname',
					'label_email' => 'Email Address'
				),
			),
		);
	}

	/**
	 * Test for getLabelsAttachedToMarkers()
	 *
	 * @param array $values
	 * @param string $expectedResult
	 * @return void
	 * @dataProvider getLabelsAttachedToMarkersReturnsArrayDataProvider
	 * @test
	 */
	public function getLabelsAttachedToMarkersReturnsArray($values, $expectedResult) {
		$mail = new \In2code\Powermail\Domain\Model\Mail;
		if (is_array($values)) {
			foreach ($values as $markerTitleMix) {
				$answer = new \In2code\Powermail\Domain\Model\Answer;
				$field = new \In2code\Powermail\Domain\Model\Field;
				$field->setMarker($markerTitleMix[0]);
				$field->setTitle($markerTitleMix[1]);
				$answer->setField($field);
				$mail->addAnswer($answer);
			}
		}

		$result = $this->generalValidatorMock->_callRef('getLabelsAttachedToMarkers', $mail);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Dataprovider getVariablesWithLabelsReturnsArray()
	 *
	 * @return array
	 */
	public function getVariablesWithLabelsReturnsArrayDataProvider() {
		return array(
			array(
				array(
					array(
						'title',
						'value',
						123
					),
				),
				array(
					array(
						'label' => 'title',
						'value' => 'value',
						'uid' => 123
					)
				),
			),
			array(
				array(
					array(
						'First Name',
						'Alex',
						1
					),
					array(
						'Last Name',
						'Kellner',
						2
					),
					array(
						'Email address',
						'alex@test.de',
						3
					),
				),
				array(
					array(
						'label' => 'First Name',
						'value' => 'Alex',
						'uid' => 1
					),
					array(
						'label' => 'Last Name',
						'value' => 'Kellner',
						'uid' => 2
					),
					array(
						'label' => 'Email address',
						'value' => 'alex@test.de',
						'uid' => 3
					)
				),
			),
		);
	}

	/**
	 * Test for getVariablesWithLabels()
	 *
	 * @param array $values
	 * @param string $expectedResult
	 * @return void
	 * @dataProvider getVariablesWithLabelsReturnsArrayDataProvider
	 * @test
	 */
	public function getVariablesWithLabelsReturnsArray($values, $expectedResult) {
		$mail = new \In2code\Powermail\Domain\Model\Mail;
		if (is_array($values)) {
			foreach ($values as $titleValueUidMix) {
				$answer = new \In2code\Powermail\Domain\Model\Answer;
				$field = new \In2code\Powermail\Domain\Model\Field;
				$field->setTitle($titleValueUidMix[0]);
				$field->_setProperty('uid', $titleValueUidMix[2]);
				$answer->setField($field);
				$answer->setValue($titleValueUidMix[1]);
				$mail->addAnswer($answer);
			}
		}

		$result = $this->generalValidatorMock->_callRef('getVariablesWithLabels', $mail);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Dataprovider getDataTypeFromFieldTypeReturnsInt()
	 *
	 * @return array
	 */
	public function getDataTypeFromFieldTypeReturnsIntDataProvider() {
		return array(
			array(
				'captcha',
				0
			),
			array(
				'check',
				1
			),
			array(
				'date',
				2
			),
			array(
				'file',
				3
			),
			array(
				'select',
				1
			),
			array(
				'input',
				0
			)
		);
	}

	/**
	 * Test for getDataTypeFromFieldType()
	 *
	 * @param \string $value
	 * @param \int $expectedResult
	 * @return void
	 * @dataProvider getDataTypeFromFieldTypeReturnsIntDataProvider
	 * @test
	 */
	public function getDataTypeFromFieldTypeReturnsInt($value, $expectedResult) {
		$result = \In2code\Powermail\Utility\Div::getDataTypeFromFieldType($value);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Dataprovider optionArrayReturnsArray()
	 *
	 * @return array
	 */
	public function optionArrayReturnsArrayDataProvider() {
		return array(
			array(
				'abc',
				array(
					array(
						'label' => 'abc',
						'value' => 'abc',
						'selected' => 0
					),
				)
			),
			array(
				"red\nblue\nyellow",
				array(
					array(
						'label' => 'red',
						'value' => 'red',
						'selected' => 0
					),
					array(
						'label' => 'blue',
						'value' => 'blue',
						'selected' => 0
					),
					array(
						'label' => 'yellow',
						'value' => 'yellow',
						'selected' => 0
					),
				)
			),
			array(
				"please choose...|\nred\nblue|blue|*",
				array(
					array(
						'label' => 'please choose...',
						'value' => '',
						'selected' => 0
					),
					array(
						'label' => 'red',
						'value' => 'red',
						'selected' => 0
					),
					array(
						'label' => 'blue',
						'value' => 'blue',
						'selected' => 1
					),
				)
			),
			array(
				"||*\nred|red shoes",
				array(
					array(
						'label' => '',
						'value' => '',
						'selected' => 1
					),
					array(
						'label' => 'red',
						'value' => 'red shoes',
						'selected' => 0
					),
				)
			),
		);
	}

	/**
	 * Test for optionArray()
	 *
	 * @param string $value
	 * @param array $expectedResult
	 * @return void
	 * @dataProvider optionArrayReturnsArrayDataProvider
	 * @test
	 */
	public function optionArrayReturnsArray($value, $expectedResult) {
		$result = \In2code\Powermail\Utility\Div::optionArray($value, '');
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Test for createHash()
	 *
	 * @return void
	 * @test
	 */
	public function createHashReturnsString() {
		$value = 'abc';
		$result = $this->generalValidatorMock->_callRef('createHash', $value);
		$this->assertFalse(($value == $result));
	}

	/**
	 * Dataprovider getSubFolderOfCurrentUrlReturnsString()
	 *
	 * @return array
	 */
	public function getSubFolderOfCurrentUrlReturnsStringDataProvider() {
		return array(
			array(
				TRUE,
				TRUE,
				'http://www.in2code.de',
				'http://www.in2code.de/',
				'/'
			),
			array(
				FALSE,
				TRUE,
				'http://www.in2code.de',
				'http://www.in2code.de/',
				'/'
			),
			array(
				TRUE,
				FALSE,
				'http://www.in2code.de',
				'http://www.in2code.de/',
				'/'
			),
			array(
				FALSE,
				FALSE,
				'http://www.in2code.de',
				'http://www.in2code.de/',
				''
			),
			array(
				TRUE,
				TRUE,
				'http://www.in2code.de',
				'http://www.in2code.de/subfolder/',
				'/subfolder/'
			),
			array(
				FALSE,
				TRUE,
				'http://www.in2code.de',
				'http://www.in2code.de/subfolder/',
				'subfolder/'
			),
			array(
				TRUE,
				FALSE,
				'http://www.in2code.de',
				'http://www.in2code.de/subfolder/',
				'/subfolder'
			),
			array(
				FALSE,
				FALSE,
				'http://www.in2code.de',
				'http://www.in2code.de/subfolder/',
				'subfolder'
			),
		);
	}

	/**
	 * Test for getSubFolderOfCurrentUrl()
	 *
	 * @param bool $leadingSlash will be prepended
	 * @param bool $trailingSlash will be appended
	 * @param string $host
	 * @param string $url
	 * @param string $expectedResult
	 * @return void
	 * @dataProvider getSubFolderOfCurrentUrlReturnsStringDataProvider
	 * @test
	 */
	public function getSubFolderOfCurrentUrlReturnsString($leadingSlash, $trailingSlash, $host, $url, $expectedResult) {
		$result = \In2code\Powermail\Utility\Div::getSubFolderOfCurrentUrl($leadingSlash, $trailingSlash, $host, $url);
		$this->assertSame($result, $expectedResult);
	}

	/**
	 * Data Provider for createRandomStringAlwaysReturnsStringsOfGivenLength
	 *
	 * @return array
	 */
	public function createRandomStringAlwaysReturnsStringsOfGivenLengthDataProvider() {
		return array(
			'default params' => array(
				32,
				TRUE,
			),
			'default length lowercase' => array(
				32,
				FALSE,
			),
			'60 length' => array(
				60,
				TRUE,
			),
			'60 length lowercase' => array(
				60,
				FALSE,
			),
		);
	}

	/**
	 * createRandomStringAlwaysReturnsStringsOfGivenLength Test
	 *
	 * @param int $length
	 * @param bool $uppercase
	 * @dataProvider createRandomStringAlwaysReturnsStringsOfGivenLengthDataProvider
	 * @return void
	 * @test
	 */
	public function createRandomStringAlwaysReturnsStringsOfGivenLength($length, $uppercase) {
		for ($i = 0; $i < 10; $i++) {
			$string = \In2code\Powermail\Utility\Div::createRandomString($length, $uppercase);

			if ($uppercase) {
				$regex = '~[a-zA-Z0-9]{' . $length . '}~';
			} else {
				$regex = '~[a-z0-9]{' . $length . '}~';
			}

			$this->assertSame(1, preg_match($regex, $string));

		}
	}

	/**
	 * Data Provider for isJsonArrayReturnsBool()
	 *
	 * @return array
	 */
	public function isJsonArrayReturnsBoolDataProvider() {
		return array(
			array(
				json_encode(array('a')),
				TRUE
			),
			array(
				json_encode('a,b:c'),
				FALSE
			),
			array(
				json_encode(array('object' => 'a')),
				TRUE
			),
			array(
				json_encode(array(array('title' => 'test2'), array('title' => 'test2'))),
				TRUE
			),
			array(
				'a,b:c',
				FALSE
			),
		);
	}

	/**
	 * isJsonArray Test
	 *
	 * @param string $value
	 * @param bool $expectedResult
	 * @dataProvider isJsonArrayReturnsBoolDataProvider
	 * @return void
	 * @test
	 */
	public function isJsonArrayReturnsBool($value, $expectedResult) {
		$this->assertSame(
			\In2code\Powermail\Utility\Div::isJsonArray($value),
			$expectedResult
		);
	}

	/**
	 * Data Provider for isBackendAdminReturnsBool()
	 *
	 * @return array
	 */
	public function isBackendAdminReturnsBoolDataProvider() {
		return array(
			array(
				1,
				TRUE
			),
			array(
				0,
				FALSE
			),
		);
	}

	/**
	 * isBackendAdmin Test
	 *
	 * @param string $value
	 * @param bool $expectedResult
	 * @dataProvider isBackendAdminReturnsBoolDataProvider
	 * @return void
	 * @test
	 */
	public function isBackendAdminReturnsBool($value, $expectedResult) {
		$GLOBALS['BE_USER']->user['admin'] = $value;
		$this->assertSame(
			\In2code\Powermail\Utility\Div::isBackendAdmin(),
			$expectedResult
		);
	}

	/**
	 * Data Provider for getDomainFromUriReturnsString()
	 *
	 * @return array
	 */
	public function getDomainFromUriReturnsStringDataProvider() {
		return array(
			array(
				'http://subdomain.domain.org/folder/file.html',
				'subdomain.domain.org'
			),
			array(
				'ftp://domain.org',
				'domain.org'
			),
			array(
				'https://www.domain.co.uk/',
				'www.domain.co.uk'
			),
		);
	}

	/**
	 * getDomainFromUri Test
	 *
	 * @param string $value
	 * @param string $expectedResult
	 * @dataProvider getDomainFromUriReturnsStringDataProvider
	 * @return void
	 * @test
	 */
	public function getDomainFromUriReturnsString($value, $expectedResult) {
		$this->assertSame(
			\In2code\Powermail\Utility\Div::getDomainFromUri($value),
			$expectedResult
		);
	}

	/**
	 * Data Provider for getCountryFromIpReturnsString()
	 *
	 * @return array
	 */
	public function getCountryFromIpReturnsStringDataProvider() {
		return array(
			array(
				'217.72.208.133',
				'Germany'
			),
			array(
				'27.121.255.4',
				'Japan'
			),
			array(
				'5.226.31.255',
				'Spain'
			),
			array(
				'66.85.131.18',
				'United States'
			),
			array(
				'182.118.23.7',
				'China'
			),
		);
	}

	/**
	 * getCountryFromIp Test
	 *
	 * @param string $ip
	 * @param string $expectedResult
	 * @dataProvider getCountryFromIpReturnsStringDataProvider
	 * @return void
	 * @test
	 */
	public function getCountryFromIpReturnsString($ip, $expectedResult) {
		$this->assertSame(
			\In2code\Powermail\Utility\Div::getCountryFromIp($ip),
			$expectedResult
		);
	}

	/**
	 * Data Provider for getImageSourceFromTagReturnsString()
	 *
	 * @return array
	 */
	public function getImageSourceFromTagReturnsStringDataProvider() {
		return array(
			array(
				array(
					'<img class="tx-srfreecap-image" id="tx_srfreecap_captcha_image_6ac99" ',
					'src="http://powermail.localhost.de/index.php?eID=sr_freecap_EidDispatcher&amp;',
					'id=111&amp;extensionName=SrFreecap&amp;pluginName=ImageGenerator&amp;controllerName=ImageGenerator',
					'&amp;actionName=show&amp;formatName=png&amp;set=6ac99" alt="CAPTCHA-Bild zum Spam-Schutz "/>',
					'<span class="tx-srfreecap-cant-read">Wenn Sie das Wort nicht lesen können, ',
					'<a href="#" onclick="this.blur();SrFreecap.newImage(\'6ac99\', \'Entschuldigung, wir können nicht ',
					'automatisch ein neues Bild zeigen. Schicken Sie das Formular ab und ein neues Bild wird geladen.\');',
					'return false;">bitte hier klicken</a>.</span>'
				),
				array(
					'http://powermail.localhost.de/index.php?eID=sr_freecap_EidDispatcher&amp;',
					'id=111&amp;extensionName=SrFreecap&amp;pluginName=ImageGenerator&amp;controllerName=ImageGenerator',
					'&amp;actionName=show&amp;formatName=png&amp;set=6ac99'
				)
			),
			array(
				array(
					'abcd <img src="/abc/pic.png" /> adsa ',
				),
				array(
					'/abc/pic.png'
				)
			),
			array(
				array(
					'<b> <img src="http://d.org/pic.bmp" /> ads <img src="xyz.php" /> </b>adsa',
				),
				array(
					'http://d.org/pic.bmp'
				)
			),
		);
	}

	/**
	 * getImageSourceFromTag Test
	 *
	 * @param array $html
	 * @param array $expectedResult
	 * @dataProvider getImageSourceFromTagReturnsStringDataProvider
	 * @return void
	 * @test
	 */
	public function getImageSourceFromTagReturnsString($html, $expectedResult) {
		$this->assertSame(
			\In2code\Powermail\Utility\Div::getImageSourceFromTag(implode('', $html)),
			implode('', $expectedResult)
		);
	}
}