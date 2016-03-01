<?php
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Hydrator\Tests\Unit\Fixtures;

/**
 * Class ExtendingTestObject
 *
 * @package Fortuneglobe\Hydrator\Tests\Unit\Fixtures
 */
class ExtendingTestObject extends TestObject
{
	private $extending;

	public function getExtending()
	{
		return $this->extending;
	}
}