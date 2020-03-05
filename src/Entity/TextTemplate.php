<?php


namespace Quizapp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class TextTemplate extends AbstractEntity
{
    /**
     * @var int
     * @ORM id
     */
    private $id;
    /**
     * @var string
     * @ORM text
     */
    private $text;
}