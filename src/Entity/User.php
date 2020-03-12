<?php

namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;

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

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRole() : string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getQuizes() : array {
        return $this->getRepository()->getForeignEntities(QuizTemplate::class, $this);
    }

    public function setPassword(string $hash) {
        $this->password = $hash;
    }

}