<?php

namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Test\Entity\Quiz;

class User extends AbstractEntity
{
    /**
     * @var string
     * @ORM name
     */
    private $name;
    /**
     * @var string
     * @ORM email
     */
    private $email;
    /**
     * @var string
     * @ORM role
     */
    private $role;
    /**
     * @var string
     * @ORM password
     */
    private $password;
    /**
     * @var int
     * @UID
     * @ORM id
     */
    private $id;

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setRole(string $role)
    {
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getRole() : string
    {
        return $this->role;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getQuizes() : array {
        return $this->getRepository()->getForeignEntities(Quiz::class, $this);
    }

    public function setPassword(string $hash) {
        $this->password = $hash;
    }

}