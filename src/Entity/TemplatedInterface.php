<?php


namespace Quizapp\Entity;


use ReallyOrm\Entity\EntityInterface;

interface TemplatedInterface
{

    public static function createFromTemplate (EntityInterface $template) : EntityInterface;

}