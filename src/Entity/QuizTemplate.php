<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;

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

    public function getText () {
        return $this->text;
    }

    public function getName () {
        return $this->name;
    }

    public function getId () {
        return $this->id;
    }

    public function setText (string $text) {
        $this->text = $text;
    }

    public function setName (string $name) {
        $this->name = $name;
    }

    public function getQuestionNumber () {
        return $this->getRepository()->countQuestions($this->getId());
    }

    public function getQuestions(int $id) {
        return $this->getRepository()->getQuestions($id);
    }
}