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

    /**
     * @param string $text
     */
    public function setText(string $text) {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @param int $questionTemplateID
     */
    public function setQuestionID(int $questionTemplateID) {
        $this->getRepository()->setForeignID($questionTemplateID, QuestionTemplate::class, $this);
    }

    /**
     * @param int $id
     * @return \ReallyOrm\Entity\EntityInterface|null
     */
    public function findBy(int $id) {
        return $this->getRepository()->findOneBy(['questiontemplateid' => $id]);
    }
}