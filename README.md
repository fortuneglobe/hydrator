# Hydrator

This class is meant to be used to fetch data from an array into an entity.

## Basic example

First create an entity that should represent your data.

```php
<?php

class MyEntity 
{
    /** @var int */
    private $id;
    
    /** @var string */
    private $name;
}
```

Then hydrate this entity with your data.

```php
<?php

use Fortuneglobe\Hydrator\Hydrator;

$record = [
    'id'    => 12345,
    'name'  => 'My entity',
];

$hydrator = new Hydrator( MyEntity::class );

$entity = $hydrator->fromRecord( $record );

print_r( $entity );
```

Prints:

```
MyEntity Object
(
    [id:MyEntity:private] => 12345
    [name:MyEntity:private] => My entity
)
```

## Hydrating derived classes

The hydration also works if you apply it to derived classes.

```php

class MyDerivedEntity extends MyEntity
{
    /** @var string */
    private $createdAt;
}

$record = [
    'id'        => 12345,
    'name'      => 'My entity',
    'createdAt' => '2015-03-30 12:13:14',
];

$hydrator = new Hydrator( MyDerivedEntity::class );

$entity = $hydrator->fromRecord( $record );

print_r( $entity );
```

Prints:

```
MyDerivedEntity Object
(
    [createdAt:MyDerivedEntity:private] => 2015-03-30 12:13:14
    [id:MyEntity:private] => 12345
    [name:MyEntity:private] => My entity
)
```

## The entity constructor

As of the Hydrator class is meant to copy the behaviour of Php's `PDOStatement::fetchObject`, __the constructor is called after hydrating the object members!__

Let's translate the date string into a `\DateTimeImmutable` object.

```php
class MyDerivedEntity extends MyEntity
{
    /** @var \DateTimeImmutable */
    private $createdAt;
    
    public function __construct()
    {
        if (!is_null($this->createdAt))
        {
            $this->createdAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->createdAt);
        }
    }
}

$record = [
    'id'        => 12345,
    'name'      => 'My entity',
    'createdAt' => '2015-03-30 12:13:14',
];

$hydrator = new Hydrator( MyDerivedEntity::class );

$entity = $hydrator->fromRecord( $record );

print_r( $entity );
```

Prints:

```
MyDerivedEntity Object
(
    [createdAt:MyDerivedEntity:private] => DateTimeImmutable Object
        (
            [date] => 2015-03-30 12:13:14
            [timezone_type] => 3
            [timezone] => Europe/Berlin
        )

    [id:MyEntity:private] => 12345
    [name:MyEntity:private] => My entity
)
```

## Providing constructor arguments

If your entity's constructor expects arguments, you can provide them as array to the `Hydrator->fromRecord()`` method as the second parameter.

```php
class MyDerivedEntity extends MyEntity
{
    /** @var \DateTimeImmutable */
    private $createdAt;
    
    /** @var string */
    private $type;
    
    public function __construct( $type )
    {
        $this->type = $type;
        
        if (!is_null($this->createdAt))
        {
            $this->createdAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->createdAt);
        }
    }
}

$record = [
    'id'        => 12345,
    'name'      => 'My entity',
    'createdAt' => '2015-03-30 12:13:14',
];

$hydrator = new Hydrator( MyDerivedEntity::class );

$entity = $hydrator->fromRecord( $record, ['Derived class'] );

print_r( $entity );
```

Prints:

```
MyDerivedEntity Object
(
    [createdAt:MyDerivedEntity:private] => DateTimeImmutable Object
        (
            [date] => 2015-03-30 12:13:14
            [timezone_type] => 3
            [timezone] => Europe/Berlin
        )

    [type:MyDerivedEntity:private] => Derived class
    [id:MyEntity:private] => 12345
    [name:MyEntity:private] => My entity
)
```

---

## 0. Installing tools

```bash
$ vagrant ssh
$ cd /vagrant
$ sh build/tools/update_tools.sh
```
## 1. Running composer

```bash
$ vagrant ssh
$ cd /vagrant
$ php build/tools/composer.phar update -o -v
```

## 2. Generating phpdox API documentation

```bash
$ vagrant ssh
$ cd /vagrant
$ php build/tools/phpdox.phar -f build/phpdox.xml
```

## 3. Running php mass detector

```bash
$ vagrant ssh
$ cd /vagrant
$ php build/tools/phpmd.phar src text build/phpmd.xml
```

## 4. Generating Unit-Test with Codeception

```bash
$ vagrant ssh
$ cd /vagrant
$ php build/tools/codecept.phar generate:test unit {CLASSNAME}
```

## 5. Running Tests with Codeception

```bash
$ vagrant ssh
$ cd /vagrant
$ php build/tools/codecept.phar run [optional:unit|functional|acceptance] [optional:CLASSNAME]
```

## 6. Generating Coverage-Data with Codeception

```bash
$ vagrant ssh
$ cd /vagrant
$ php build/tools/codecept.phar run unit --coverage [optional:--coverage-xml] [optional:--coverage-html] [optional:--xml=junit.xml]
```
