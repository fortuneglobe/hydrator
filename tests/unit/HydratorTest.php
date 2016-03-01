<?php
/**
 *
 * @author hollodotme
 */

namespace Fortuneglobe\Hydrator\Tests\Unit;

use Fortuneglobe\Hydrator\Exceptions\InvalidClassName;
use Fortuneglobe\Hydrator\Hydrator;
use Fortuneglobe\Hydrator\Tests\Unit\Fixtures\ExtendingTestObject;
use Fortuneglobe\Hydrator\Tests\Unit\Fixtures\TestObject;
use Fortuneglobe\Hydrator\Tests\Unit\Fixtures\TestObjectWithConstructor;

class HydratorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param string $className
	 *
	 * @dataProvider invalidClassNameProvider
	 * @expectedException \Fortuneglobe\Hydrator\Exceptions\InvalidClassName
	 */
	public function testConstructionFailsWithInvalidClassNames( $className )
	{
		new Hydrator( $className );
	}

	public function invalidClassNameProvider()
	{
		return [
			[ '' ],
			[ '0Class' ],
			[ '|Class' ],
			[ 'Class with whitespace' ],
		];
	}

	/**
	 * @param string $className
	 *
	 * @dataProvider invalidClassNameProvider
	 */
	public function testCanGetClassNameFromException( $className )
	{
		try
		{
			new Hydrator( $className );
		}
		catch ( InvalidClassName $e )
		{
			$this->assertEquals( $className, $e->getClassName() );
		}
	}

	public function testCanHydrateAllMemberTypesFromRecord()
	{
		$record = [
			'unit'   => 'Hello',
			'test'   => 'Real',
			'string' => 'World',
		];

		$hydrator = new Hydrator( TestObject::class );

		/** @var TestObject $object */
		$object = $hydrator->fromRecord( $record );

		$this->assertEquals( 'Hello', $object->getUnit() );
		$this->assertEquals( 'Real', $object->getTest() );
		$this->assertEquals( 'World', $object->getString() );
	}

	public function testCanLeaveMemberNullWhenNotSetInRecord()
	{
		$record = [
			'unit' => 'Hello',
			'test' => 'Real',
		];

		$hydrator = new Hydrator( TestObject::class );

		/** @var TestObject $object */
		$object = $hydrator->fromRecord( $record );

		$this->assertEquals( 'Hello', $object->getUnit() );
		$this->assertEquals( 'Real', $object->getTest() );
		$this->assertNull( $object->getString() );
	}

	public function testRecordCanHaveMoreValuesThanMembersExist()
	{
		$record = [
			'unit'     => 'Hello',
			'test'     => 'Real',
			'string'   => 'World',
			'one_more' => 'This one does not exists',
		];

		$hydrator = new Hydrator( TestObject::class );

		/** @var TestObject $object */
		$object = $hydrator->fromRecord( $record );

		$this->assertEquals( 'Hello', $object->getUnit() );
		$this->assertEquals( 'Real', $object->getTest() );
		$this->assertEquals( 'World', $object->getString() );
	}

	public function testRecordCanBeEmpty()
	{
		$record = [ ];

		$hydrator = new Hydrator( TestObject::class );

		/** @var TestObject $object */
		$object = $hydrator->fromRecord( $record );

		$this->assertNull( $object->getUnit() );
		$this->assertNull( $object->getTest() );
		$this->assertNull( $object->getString() );
	}

	public function testConstructorIsCalledAfterHydration()
	{
		$record = [
			'unit'   => 'Hello',
			'test'   => 'Real',
			'string' => 'World',
		];

		$hydrator = new Hydrator( TestObjectWithConstructor::class );

		/** @var TestObjectWithConstructor $object */
		$object = $hydrator->fromRecord( $record, [ 'Override by ctor_args' ] );

		$this->assertEquals( 'Hello', $object->getUnit() );
		$this->assertEquals( 'Override by ctor', $object->getTest() );
		$this->assertEquals( 'Override by ctor_args', $object->getString() );
	}

	public function testCanHydrateMembersOfParentClasses()
	{
		$record = [
			'unit'      => 'Hello',
			'test'      => 'Real',
			'string'    => 'World',
			'extending' => 'In extending class',
		];

		$hydrator = new Hydrator( ExtendingTestObject::class );

		/** @var ExtendingTestObject $object */
		$object = $hydrator->fromRecord( $record );

		$this->assertEquals( 'Hello', $object->getUnit() );
		$this->assertEquals( 'Real', $object->getTest() );
		$this->assertEquals( 'World', $object->getString() );
		$this->assertEquals( 'In extending class', $object->getExtending() );
	}
}