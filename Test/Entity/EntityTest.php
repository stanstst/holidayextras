<?php

namespace Test\UseCase\User;

use PHPUnit_Framework_TestCase;
use Test\Entity\Helper\SimpleEntity;

class EntityTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var SimpleEntity
     */
    protected $object;

    public function setUp()
    {
        $this->object = new SimpleEntity();
        parent::setUp();
    }

    /**
     * @test
     */
    public function setsAttributesInMassiveWay()
    {
        $this->object->setAttributes( [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
        ] );

        $this->assertEquals( $this->object->attribute1, 'value1' );
        $this->assertEquals( $this->object->attribute2, 'value2' );
    }


    /**
     * @test
     */
    public function returnsAttributesInMassiveWay()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
        ];
        $expectedAttributesFromGetAttributes = $attributes;

        $this->object->setAttributes( $attributes );

        $this->assertEquals( $expectedAttributesFromGetAttributes, $this->object->getAttributes() );

    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function throwsExceptionIfSetAttributesIsGivenNonExistingAttribute()
    {
        $this->object->setAttributes( [
            'attribute1' => 'value1',
            'attribute333' => 'value333',
        ] );

    }

}
