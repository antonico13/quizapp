<?php


namespace Quizapp\Entity;


use ReallyOrm\Entity\EntityInterface;

interface TemplatedInterface
{
    /**
     * @param EntityInterface $template
     * @return EntityInterface
     */
    public static function createFromTemplate (EntityInterface $template) : EntityInterface;

}