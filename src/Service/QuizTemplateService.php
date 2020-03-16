<?php


namespace Quizapp\Service;


use Framework\Controller\AbstractController;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\User;

class QuizTemplateService extends AbstractService
{
    /**
     * @param int|null $id
     * @param string|null $className
     * @param string|null $search
     * @param string $searchColumn
     * @return int
     */
    public function getQuizzesCount(int $id = null, string $className = null, string $search = null, string $searchColumn = 'name') : int
    {
        return $this->entityRepo->count($id, User::class, $search, $searchColumn);
    }

    /**
     * @param int|null $id
     * @param array $sorts
     * @param int $page
     * @param int $limit
     * @param string|null $search
     * @param string $searchColumn
     * @return array|\ReallyOrm\Entity\EntityInterface[]
     */
    public function getQuizzes(int $id = null, array $sorts = [], int $page = 1, int $limit = 5, string $search = null, string $searchColumn = 'name')
    {
        if ($id) {
            return $this->entityRepo->findBy(['userid' => $id], $sorts, ($page-1)*$limit, $limit, $search, $searchColumn);
        }

        return $this->entityRepo->findBy([], $sorts, ($page-1)*$limit, $limit, $search, $searchColumn);
    }

    /**
     * @param int $userID
     * @param array $data
     */
    public function addQuiz (int $userID, array $data) {
        $quizTemplate = new QuizTemplate();
        $quizTemplate->setText($data['text']);
        $quizTemplate->setName($data['name']);

        $questions = $data['questions'];

        $this->repoManager->register($quizTemplate);
        $quizTemplate->save();

        $this->entityRepo->setForeignID($userID, User::class, $quizTemplate);
        $this->entityRepo->insertOnLinkTable($quizTemplate, $questions);
    }

    /**
     * @return mixed
     */
    public function getAllQuestions() {
        return $this->entityRepo->getAllQuestions();
    }

    /**
     * @param int $id
     * @return QuizTemplate|null
     */
    public function getQuiz(int $id) : ?QuizTemplate {
        return $this->entityRepo->find($id);
    }

    /**
     * @param int $userid
     * @param int $id
     * @param array $data
     */
    public function editQuiz(int $userid, int $id, array $data) {
        $quiz = $this->getQuiz($id);
        $quiz->setText($data['text']);
        $quiz->setName($data['name']);
        $this->entityRepo->deleteRelation($id);
        $questions = $data['questions'];

        $this->repoManager->register($quiz);
        $quiz->save();

        $this->entityRepo->setForeignID($userid, User::class, $quiz);
        $this->entityRepo->insertOnLinkTable($quiz, $questions);

    }

    /**
     * @param int $id
     */
    public function deleteQuiz(int $id) {
        $quiz = $this->entityRepo->find($id);
        $this->entityRepo->deleteRelation($id);
        $this->entityRepo->delete($quiz);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getSelectedQuestions(int $id) {
        return $this->entityRepo->getSelectedQuestions($id);
    }

}