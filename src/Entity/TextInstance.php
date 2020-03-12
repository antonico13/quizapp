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
     * @return bool
     */
    public function isCodeAnswer() {
        /**
         * @var QuestionInstance $question
         */
        $question = $this->getRepository()->getForeignEntity(QuestionInstance::class, $this);

        return $question->getType() == 'Code';
    }

    /**
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @param EntityInterface $template
     * @return EntityInterface
     */
    public static function createFromTemplate(EntityInterface $template) : EntityInterface {
        $obj = new self();
        $obj->setText($template->getText());
        return $obj;
    }

    /**
     * @param int $questionInstanceID
     */
    public function setQuestionID(int $questionInstanceID) {
        $this->getRepository()->setForeignID($questionInstanceID, QuestionInstance::class, $this);
    }

    /**
     * @param int $answerTemplateID
     */
    public function setTemplateID(int $answerTemplateID) {
        $this->getRepository()->setForeignID($answerTemplateID, TextTemplate::class, $this);
    }
}