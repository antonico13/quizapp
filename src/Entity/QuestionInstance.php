<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;

class QuestionInstance extends AbstractEntity
{
    /**
     * @var int
     * @ORM id
     * @UID
     */
    private $id;
    /**
     * @var string
     * @ORM text
     */
    private $text;
    /**
     * @var string
     * @ORM type
     */
    private $type;
    /**
     * @var bool
     * @ORM isAnswered
     */
    private $isAnswered;
}