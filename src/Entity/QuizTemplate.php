<?php


namespace Quizapp\Entity;

use ReallyOrm\Entity\AbstractEntity;

class QuizTemplate extends AbstractEntity
{
    /**
     * @var int
     * @ORM id
     * @UID    public function edit (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->questionService->editQuestion($id, $data);

        $location = "Location: http://local.quizapp.com/admin/question";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }
     */
    private $id;
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

    public function getText () {
        return $this->text;
    }

    public function getName () {
        return $this->name;
    }

    public function getId () {
        return $this->id;
    }

    public function setText (string $text) {
        $this->text = $text;
    }

    public function setName (string $name) {
        $this->name = $name;
    }

    public function getQuestionNumber () {
        return $this->getRepository()->countQuestions($this->getId());
    }

}