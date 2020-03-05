<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;

class QuizInstance extends AbstractEntity
{
    /**
     * @var int
     * @ORM id
     * @UID
     */
    private $id;
    /**
     * @var int
     * @ORM score
     */
    private $score;
}