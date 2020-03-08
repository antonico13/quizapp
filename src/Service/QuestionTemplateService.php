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

    public function getQuestions(string $text) : array
    {
        $id = $this->session->get('id');

        return $this->entityRepo->findBy(['userid' => $id], ['id' => 'ASC'], 0, 5);
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