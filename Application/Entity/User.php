<?php

namespace Application\Entity;

class User extends Entity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $forename;

    /**
     * @var string
     */
    protected $surname;

    /**
     * @var string
     */
    protected $created;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function setId( $id )
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail( $email )
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * @param string $forename
     *
     * @return User
     */
    public function setForename( $forename )
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     *
     * @return User
     */
    public function setSurname( $surname )
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $created
     *
     * @return User
     */
    public function setCreated( $created )
    {
        $this->created = $created;

        return $this;
    }

}