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
    /**
     * @var int
     * @ORM userid
     */
    private $userid;

    public function setText($text) {
        $this->text = $text;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setUserID($userId) {
        $this->userid = $userId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getID()
    {
        return $this->id;
    }
}
