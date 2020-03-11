<?php


namespace Quizapp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class TextTemplate extends AbstractEntity
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

    public function setText(string $text) {
        $this->text = $text;
    }

    public function getText() {
        return $this->text;
    }

    public function getID() {
        return $this->id;
    }

    public function setQuestionID(int $id) {
        $this->getRepository()->setForeignKey($id, $this);
    }

    public function findBy(int $id) {
        return $this->getRepository()->findOneBy(['questiontemplateid' => $id]);
    }
}