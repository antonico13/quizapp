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

    /**
     * @param $userId
     */
    public function setUserID($userId) {
        $this->getRepository()->setForeignID($userId,User::class, $this);
    }

    /**
     * @param $quizId
     */
    public function setQuizID($quizId) {
        $this->getRepository()->setForeignID($quizId, QuizTemplate::class, $this);
    }

    /**
     * @return mixed
     */
    public function getQuestions() {
        return $this->getRepository()->getForeignEntities(QuestionInstance::class, $this);
    }

    /**
     * @return User
     */
    public function getUser() : User {
        return $this->getRepository()->getForeignEntity(User::class, $this);
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score)
    {
        $this->score = $score;
    }

    public function setText(string $text) {
        $this->text = $text;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @param bool $value
     */
    public function setSaved(bool $value) {
        $this->issaved = $value;
    }
}