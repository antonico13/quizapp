<?php


namespace Quizapp\Service;


use Quizapp\Entity\QuestionTemplate;

class QuestionTemplateService extends AbstractService
{

    public function addQuestion (array $data) {
        $question = new QuestionTemplate();
        $question->setText($data['text']);
        $question->setType($data['type']);
        $question->setUserID($this->session->get('id'));

        $this->entityRepo->insertOnDuplicateKeyUpdate($question);
    }

    public function getQuestions(int $page = 1, int $limit = 5) : array
    {
        $id = $this->session->get('id');

        return $this->entityRepo->findBy(['userid' => $id], ['id' => 'ASC'], ($page-1)*$limit, $limit);
    }

    public function getQuestionsCount() : int
    {
        $id = $this->session->get('id');

        return $this->entityRepo->countBy($id);
    }

    public function getQuestionsCountSearch(string $search) : int
    {
        $id = $this->session->get('id');

        return $this->entityRepo->countBySearch($id, $search);
    }

    public function getQuestionsSearch(string $text, int $page = 1, int $limit = 5) : array
    {
        $id = $this->session->get('id');

        return $this->entityRepo->findBySearch(['userid' => $id], ['id' => 'ASC'], ($page-1)*$limit, $limit, $text);
    }

    public function deleteQuestion (int $id)
    {
        $question = $this->entityRepo->find($id);
        $this->entityRepo->delete($question);
    }

    public function getQuestion (int $id) {
        return $this->entityRepo->find($id);
    }

    public function editQuestion (int $id, array $data) {
        $question = $this->getQuestion($id);
        $question->setText($data['text']);
        $question->setType($data['type']);

        $this->entityRepo->insertOnDuplicateKeyUpdate($question);
    }

}