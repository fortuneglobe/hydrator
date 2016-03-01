<?php
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Hydrator\Exceptions;

/**
 * Class InvalidClassName
 *
 * @package Fortuneglobe\Hydrator\Exceptions
 */
final class InvalidClassName extends HydratorException
{
	/** @var string */
	private $className;

	/**
	 * @param string $className
	 *
	 * @return $this
	 */
	public function withClassName( $className )
	{
		$this->className = $className;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}
}