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
    /**
     * @var string
     * @ORM text
     */
    private $text;
    /**
     * @var string
     * @ORM name
     */
    private $name;
    /**
     * @var bool
     * @ORM issaved
     */
    private $issaved;



    public function setUserID($userId) {
        $this->getRepository()->setForeignUser($userId, $this);
    }

    public function setQuizID($quizId) {
        $this->getRepository()->setForeignQuiz($quizId, $this);
    }

    public function getQuestions() {
        return $this->getRepository()->getForeignEntities(QuestionInstance::class, $this);
    }

    public function getUser() {
        return $this->getRepository()->getForeignEntity(User::class, $this);
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore(int $score)
    {
        $this->score = $score;
    }

    public function setText(string $text) {
        $this->text = $text;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function setSaved(bool $value) {
        $this->issaved = $value;
    }
}