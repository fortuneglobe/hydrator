<?php
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Hydrator\Tests\Unit\Fixtures;

/**
 * Class TestObject
 *
 * @package Fortuneglobe\Hydrator\Tests\Unit\Fixtures
 */
class TestObject
{
	private   $unit;

	protected $test;

	public    $string;

	public function getUnit()
	{
		return $this->unit;
	}

	public function getTest()
	{
		return $this->test;
	}

	public function getString()
	{
		return $this->string;
	}
}