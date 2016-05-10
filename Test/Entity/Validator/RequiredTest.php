<?php

namespace Test\Entity\Validator;

use Application\Entity\Validator\Required;
use Test\Entity\Helper\SimpleEntity;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Required
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

        $this->object = new Required( 'attribute1' );
        parent::setUp();
    }

    /**
     * @test
     */
    public function addsErrorIfRequiredAttributeIsEmpty()
    {

        $this->object->validate( $this->entity );

        $expectedErrorContainer = [
            'Test\Entity\Helper\SimpleEntity' => ['attribute1' => 'Required'],
        ];

        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }

    /**
     * @test
     */
    public function returnsEmptyErrorArrayIfRequiredAttributeIsPopulated()
    {
        $this->entity->attribute1 = 'someValue';

        $this->object->validate( $this->entity );
        $this->assertEquals( [], $this->object->getErrors() );
    }

    /**
     * @test
     */
    public function chainOfTwoAddsErrorSecondAttributeIsEmpty()
    {
        $this->entity->attribute1 = 'someValue';

        $secondFilter = new Required('attribute2');
        $this->object->setNext($secondFilter);

        $this->object->validate( $this->entity );
        $expectedErrorContainer = [
            'Test\Entity\Helper\SimpleEntity' => ['attribute2' => 'Required'],
        ];
        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }
    /**
     * @test
     */
    public function chainOfTwoDoesNotAddErrorBothAttributesAreSet()
    {
        $this->entity->attribute1 = 'someValue';
        $this->entity->attribute2 = 'someValue';

        $secondFilter = new Required('attribute2');
        $this->object->setNext($secondFilter);

        $this->object->validate( $this->entity );
        $expectedErrorContainer = [];
        $this->assertEquals( $expectedErrorContainer, $this->object->getErrors() );
    }
}
