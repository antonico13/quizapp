<?php


namespace Quizapp\Service;


use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\TextTemplate;
use ReallyOrm\Entity\EntityInterface;

class QuestionTemplateService extends AbstractService
{

    public function addQuestion (array $data, int $userid) {
        $question = new QuestionTemplate();
        $question->setText($data['text']);
        $question->setType($data['type']);
        $this->repoManager->register($question);
        $question->save();

        $question->setUserID($userid);

        $answer = new TextTemplate();
        $answer->setText($data['answer']);
        $this->repoManager->register($answer);
        $answer->save();

        $answer->setQuestionID($question->getID());

    }

    public function getQuestions(int $userid, int $page = 1, int $limit = 5) : array
    {

        return $this->entityRepo->findBy(['userid' => $userid], ['id' => 'ASC'], ($page-1)*$limit, $limit);
    }

    public function getQuestionsCount(int $userid) : int
    {
        return $this->entityRepo->countBy($userid);
    }

    public function getQuestionsCountSearch(int $userid, string $search) : int
    {
        return $this->entityRepo->countBySearch($userid, $search);
    }

    public function getQuestionsSearch(int $userid, string $text, int $page = 1, int $limit = 5) : array
    {
        return $this->entityRepo->findBySearch(['userid' => $userid], ['id' => 'ASC'], ($page-1)*$limit, $limit, $text);
    }

    public function deleteQuestion (int $id)
    {
        $question = $this->entityRepo->find($id);
        $this->entityRepo->deleteRelation($id);
        $this->entityRepo->delete($question);
    }

    public function getQuestion (int $id) : ?EntityInterface {
        return $this->entityRepo->find($id);
    }

    public function editQuestion (int $id, array $data) {
        $question = $this->getQuestion($id);
        $question->setText($data['text']);
        $question->setType($data['type']);
        $this->repoManager->register($question);
        $question->save();

        $answer = new TextTemplate();
        $this->repoManager->register($answer);
        $answer = $answer->findBy($question->getId());
        $answer->setText($data['answer']);
        $answer->save();
    }

}