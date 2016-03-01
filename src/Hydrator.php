<?php
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Hydrator;

use Fortuneglobe\Hydrator\Exceptions\InvalidClassName;

/**
 * Class Hydrator
 *
 * @package Fortuneglobe\Hydrator
 */
class Hydrator
{
	/** @var \ReflectionClass */
	private $reflectionClass;

	/**
	 * @param string $className
	 *
	 * @throws InvalidClassName
	 */
	public function __construct( $className )
	{
		$this->guardClassNameIsValid( $className );

		$this->reflectionClass = new \ReflectionClass( $className );
	}

	/**
	 * @param string $className
	 *
	 * @throws InvalidClassName
	 */
	private function guardClassNameIsValid( $className )
	{
		$classNameParts = $this->getClassNameParts( $className );

		if ( empty($classNameParts) )
		{
			throw ( new InvalidClassName() )->withClassName( $className );
		}
		else
		{
			foreach ( $classNameParts as $classNamePart )
			{
				if ( !$this->isValidClassNamePart( $classNamePart ) )
				{
					throw ( new InvalidClassName() )->withClassName( $className );
				}
			}
		}
	}

	/**
	 * @param string $className
	 *
	 * @return array
	 */
	private function getClassNameParts( $className )
	{
		return preg_split( '#\\\\#', $className, -1, PREG_SPLIT_NO_EMPTY );
	}

	/**
	 * @param string $classNamePart
	 *
	 * @return bool
	 */
	private function isValidClassNamePart( $classNamePart )
	{
		if ( preg_match( "#^[a-zA-Z_][a-zA-Z0-9_]*$#", $classNamePart ) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param array $record
	 * @param array $ctorArgs
	 *
	 * @return object
	 */
	public function fromRecord( array $record, array $ctorArgs = [ ] )
	{
		$object = $this->getObjectWithoutCallingConstructor();

		foreach ( $this->getProperties( $this->reflectionClass ) as $property )
		{
			$this->setPropertyValueIfPossible( $object, $property, $record );
		}

		$this->invokeConstructorIfPossible( $object, $ctorArgs );

		return $object;
	}

	/**
	 * @return object
	 */
	private function getObjectWithoutCallingConstructor()
	{
		return $this->reflectionClass->newInstanceWithoutConstructor();
	}

	/**
	 * @param \ReflectionClass $reflectionClass
	 *
	 * @return array|\ReflectionProperty[]
	 */
	private function getProperties( \ReflectionClass $reflectionClass )
	{
		$properties = $reflectionClass->getProperties();
		$parent     = $reflectionClass->getParentClass();

		if ( $parent !== false )
		{
			$properties = array_merge( $properties, $this->getProperties( $parent ) );
		}

		return $properties;
	}

	/**
	 * @param object              $object
	 * @param \ReflectionProperty $property
	 * @param array               $record
	 */
	private function setPropertyValueIfPossible( $object, \ReflectionProperty $property, array $record )
	{
		$member = $property->getName();

		if ( isset($record[ $member ]) )
		{
			$property->setAccessible( true );
			$property->setValue( $object, $record[ $member ] );
		}
	}

	/**
	 * @param object $object
	 * @param array  $ctorArgs
	 */
	private function invokeConstructorIfPossible( $object, array $ctorArgs )
	{
		$constructor = $this->reflectionClass->getConstructor();
		if ( $constructor instanceof \ReflectionMethod )
		{
			$constructor->invokeArgs( $object, $ctorArgs );
		}
	}
}