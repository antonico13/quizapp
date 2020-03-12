<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;

class QuestionTemplate extends AbstractEntity
{
    /**
     * @var int
     * @ORM id
     * @UID
     */
    private $id;
    /**
     * @var string
     * @ORM type
     */
    private $type;
    /**
     * @var string
     * @ORM text
     */
    private $text;

    public function setText($text) {
        $this->text = $text;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setUserID(int $userID) {
        $this->getRepository()->setForeignID($userID, User::class, $this);
    }

    public function getAnswers() {
        return $this->getRepository()->getForeignEntities(TextTemplate::class, $this);
    }

    public function getText()
    {
        return $this->text;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }
}
