<?php

namespace Test\UseCase\User;

use Application\Entity\Validator\Email;
use Application\Entity\Validator\Required;
use Application\Entity\ValidatorFactory;

class ValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ValidatorFactory
     */
    protected $object;

    public function setUp()
    {

        $this->object = new ValidatorFactory();
        parent::setUp();
    }

    /**
     * @test
     */
    public function createsValidatorChain()
    {
        $expectedValidatorChain = new Required( 'email' );
        $validator2 = new Email( 'email' );
        $validator3 = new Required( 'forename' );
        $expectedValidatorChain->setNext( $validator2 )
                               ->setNext( $validator3 );

        $actualValidatorChain = $this->object->create( [
            'email'    => ['Required', 'Email'],
            'forename' => ['Required'],
        ] );

        $this->assertEquals($expectedValidatorChain, $actualValidatorChain);
    }
}
