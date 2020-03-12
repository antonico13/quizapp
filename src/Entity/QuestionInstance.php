<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

class QuestionInstance extends AbstractEntity implements TemplatedInterface
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
     * @ORM isanswered
     */
    private $isanswered;

    public function setText(string $text) {
        $this->text = $text;
    }

    public function getText() {
        return $this->text;
    }

    public function getID() {
        return $this->id;
    }

    public function setType(string $type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setTemplateID($templateId) {
        $this->getRepository()->setForeignId($templateId,QuestionTemplate::class, $this);
    }

    public function setQuizID($qId) {
        $this->getRepository()->setForeignId($qId, QuizInstance::class, $this);
    }

    public static function createFromTemplate(EntityInterface $template) : EntityInterface {
        $obj = new self();
        $obj->setText($template->getText());
        $obj->setType($template->getType());
        return $obj;
    }

    public function getAnswers() {
        return $this->getRepository()->getForeignEntities(TextInstance::class, $this);
    }
}