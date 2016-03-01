<?php
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Hydrator\Tests\Unit\Fixtures;

/**
 * Class TestObjectWithConstructor
 *
 * @package Fortuneglobe\Hydrator\Tests\Unit\Fixtures
 */
class TestObjectWithConstructor
{
	private $unit;

	private $test;

	private $string;

	public function __construct( $string )
	{
		$this->test   = 'Override by ctor';
		$this->string = $string;
	}

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