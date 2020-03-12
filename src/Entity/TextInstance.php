<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

class TextInstance extends AbstractEntity implements TemplatedInterface
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

    public function isCodeAnswer() {
        /**
         * @var QuestionInstance $question
         */
        $question = $this->getRepository()->getForeignEntity(QuestionInstance::class, $this);

        return $question->getType() == 'Code';
    }

    public function getID() {
        return $this->id;
    }

    public static function createFromTemplate(EntityInterface $template) : EntityInterface {
        $obj = new self();
        $obj->setText($template->getText());
        return $obj;
    }

    public function setQuestionID(int $questionInstanceID) {
        $this->getRepository()->setForeignID($questionInstanceID, QuestionInstance::class, $this);
    }

    public function setTemplateID(int $answerTemplateID) {
        $this->getRepository()->setForeignID($answerTemplateID, TextTemplate::class, $this);
    }
}