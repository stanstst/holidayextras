<?php

namespace Test\Entity\Validator;

use Application\Entity\Validator\Email;
use Test\Entity\Helper\SimpleEntity;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Email
     */
    protected $object;

    /**
     * @var \stdClass | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $dependencyMock;

    /**
     * @var SimpleEntity
     */
    protected $entity;

    public function setUp()
    {
        $this->entity = new SimpleEntity();

        $this->object = new Email( 'attribute1' );
        parent::setUp();
    }

    /**
     * @test
     */
    public function addsErrorIfRequiredAttribute1IsEmptyNotValidEmail()
    {
        $this->entity->attribute1 = 'someValue';

        $this->object->validate( $this->entity );

        $expectedErrorContainer = [
            'Test\Entity\Helper\SimpleEntity' => ['attribute1' => 'Email'],
        ];

        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }

    /**
     * @test
     */
    public function doesNotAddErrorIfRequiredAttribute1IsValidEmail()
    {
        $this->entity->attribute1 = 'someValue@abc.de';

        $this->object->validate( $this->entity );

        $expectedErrorContainer = [];

        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }

    public function chainOfTwoAddsErrorSecondAttributeIsEmpty()
    {
        $this->entity->attribute1 = 'someValue';

        $secondFilter = new Email('attribute2');
        $this->object->setNext($secondFilter);

        $this->object->validate( $this->entity );
        $expectedErrorContainer = [
            'Test\Entity\Helper\SimpleEntity' => ['attribute2' => 'Required'],
        ];
        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }

    public function chainOfTwoDoesNotAddErrorBothAttributesAreSet()
    {
        $this->entity->attribute1 = 'someValue';
        $this->entity->attribute2 = 'someValue';

        $secondFilter = new Email('attribute2');
        $this->object->setNext($secondFilter);

        $this->object->validate( $this->entity );
        $expectedErrorContainer = [];
        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }
}
