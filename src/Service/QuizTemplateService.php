<?php


namespace Quizapp\Service;


use Framework\Controller\AbstractController;
use Quizapp\Entity\QuizTemplate;

class QuizTemplateService extends AbstractService
{
    public function getQuizzesCount()
    {
        $id = $this->session->get('id');

        return $this->entityRepo->countBy($id);
    }

    public function getQuizzesCountSearch(string $search) : int
    {
        $id = $this->session->get('id');

        return $this->entityRepo->countBySearch($id, $search);
    }

    public function getQuizzes (int $page = 1, int $limit = 5) : array
    {
        $id = $this->session->get('id');

        return $this->entityRepo->findBy(['userid' => $id], ['id' => 'ASC'], ($page-1)*$limit, $limit);
    }

    public function getQuizzesSearch(string $text, int $page = 1, int $limit = 5) {
        $id = $this->session->get('id');

        return $this->entityRepo->findBySearch(['userid' => $id], ['id' => 'ASC'], ($page-1)*$limit, $limit, $text);
    }

    public function addQuiz (array $data) {
        $quiz= new QuizTemplate();
        $quiz->setText($data['text']);
        $quiz->setName($data['name']);

        $questions = $data['questions'];


        $this->entityRepo->insertOnDuplicateKeyUpdate($quiz);
        $this->entityRepo->setForeignKey($this->session->get('id'), $quiz);
        $this->entityRepo->insertOnLinkTable($quiz, $questions);
    }

    public function getAllQuestions() {
        return $this->entityRepo->getAllQuestions();
    }

    public function getQuiz(int $id) {
        return $this->entityRepo->find($id);
    }

    public function editQuiz(int $id, array $data) {
        $quiz = $this->getQuiz($id);
        $quiz->setText($data['text']);
        $quiz->setName($data['name']);

        $questions = $data['questions'];

        $this->entityRepo->insertOnDuplicateKeyUpdate($quiz);
        $this->entityRepo->setForeignKey($this->session->get('id'), $quiz);
        $this->entityRepo->insertOnLinkTable($quiz, $questions);

    }

}