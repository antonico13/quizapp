<?php


namespace Quizapp\Service;


use Framework\Controller\AbstractController;
use Quizapp\Entity\QuizTemplate;

class QuizTemplateService extends AbstractService
{
    public function getQuizzesCount($id = null)
    {
        return $this->entityRepo->countBy($id);
    }

    public function getQuizzesCountSearch(?int $id, string $search) : int
    {

        return $this->entityRepo->countBySearch($id, $search);
    }

    public function getQuizzes (?int $id, int $page = 1, int $limit = 5) : array
    {
        if ($id) {
            return $this->entityRepo->findBy(['userid' => $id], ['id' => 'ASC'], ($page - 1) * $limit, $limit);
        }

        return $this->entityRepo->findBy([], ['id' => 'ASC'], ($page-1)*$limit, $limit);
    }

    public function getQuizzesSearch(?int $id, string $text, int $page = 1, int $limit = 5)
    {
        if ($id) {
            return $this->entityRepo->findBySearch(['userid' => $id], ['id' => 'ASC'], ($page-1)*$limit, $limit, $text);
        }

        return $this->entityRepo->findBySearch([], ['id' => 'ASC'], ($page-1)*$limit, $limit, $text);
    }

    public function addQuiz (int $id, array $data) {
        $quiz= new QuizTemplate();
        $quiz->setText($data['text']);
        $quiz->setName($data['name']);

        $questions = $data['questions'];

        $this->repoManager->register($quiz);
        $quiz->save();

        $this->entityRepo->setForeignKey($id, $quiz);
        $this->entityRepo->insertOnLinkTable($quiz, $questions);
    }

    public function getAllQuestions() {
        return $this->entityRepo->getAllQuestions();
    }

    public function getQuiz(int $id) {
        return $this->entityRepo->find($id);
    }

    public function editQuiz(int $userid, int $id, array $data) {
        $quiz = $this->getQuiz($id);
        $quiz->setText($data['text']);
        $quiz->setName($data['name']);

        $questions = $data['questions'];

        $this->repoManager->register($quiz);
        $quiz->save();

        $this->entityRepo->setForeignKey($userid, $quiz);
        $this->entityRepo->insertOnLinkTable($quiz, $questions);

    }

    public function deleteQuiz(int $id) {
        $quiz = $this->entityRepo->find($id);
        $this->entityRepo->deleteRelation($id);
        $this->entityRepo->delete($quiz);
    }

}