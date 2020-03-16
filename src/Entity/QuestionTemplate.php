<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

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

    /**
     * @param $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @param $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @param int $userID
     */
    public function setUserID(int $userID) {
        $this->getRepository()->setForeignID($userID, User::class, $this);
    }

    public function getUser() : EntityInterface {
        return $this->getRepository()->getForeignEntity(User::class, $this);
    }

    /**
     * @return mixed
     */
    public function getAnswers() {
        return $this->getRepository()->getForeignEntities(TextTemplate::class, $this);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
