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
     * @param string $type
     */
    public function setType(string $type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param $templateId
     */
    public function setTemplateID($templateId) {
        $this->getRepository()->setForeignId($templateId,QuestionTemplate::class, $this);
    }

    /**
     * @param $qId
     */
    public function setQuizID($qId) {
        $this->getRepository()->setForeignId($qId, QuizInstance::class, $this);
    }

    /**
     * @param EntityInterface $template
     * @return EntityInterface
     */
    public static function createFromTemplate(EntityInterface $template) : EntityInterface {
        $obj = new self();
        $obj->setText($template->getText());
        $obj->setType($template->getType());
        return $obj;
    }

    /**
     * @return mixed
     */
    public function getAnswers() {
        return $this->getRepository()->getForeignEntities(TextInstance::class, $this);
    }
}