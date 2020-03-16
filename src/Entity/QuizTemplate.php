<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

class QuizTemplate extends AbstractEntity
{
    /**
     * @var int
     * @ORM id
     * @UID
    }
     */
    private $id;
    /**
     * @var string
     * @ORM text
     */
    private $text;
    /**
     * @var string
     * @ORM name
     */
    private $name;

    /**
     * @return string
     */
    public function getText () {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getName () {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId () {
        return $this->id;
    }

    /**
     * @param string $text
     */
    public function setText (string $text) {
        $this->text = $text;
    }

    /**
     * @param string $name
     */
    public function setName (string $name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuestionNumber () {
        return $this->getRepository()->countQuestions($this->getId());
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getQuestions(int $id) {
        return $this->getRepository()->getQuestions($id);
    }

    /**
     * @return User
     */
    public function getUser() : User {
        return $this->getRepository()->getForeignEntity(User::class, $this);
    }
}