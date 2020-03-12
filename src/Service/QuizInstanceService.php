<?php


namespace Quizapp\Service;


use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizInstance;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\TextInstance;
use Quizapp\Entity\TextTemplate;
use ReallyOrm\Test\Entity\User;

class QuizInstanceService extends AbstractService
{
    /**
     * @param int $userid
     * @param int $quizid
     * @return int|null
     */
    public function addQuiz(int $userid, int $quizid) {
        $quiz = new QuizInstance();
        $template = $this->repoManager->getRepository(QuizTemplate::class)->find($quizid);
        if ($template == null) {
            return null;
        }
        $quiz->setText($template->getText());
        $quiz->setName($template->getName());
        $this->repoManager->register($quiz);
        $quiz->save();
        $quiz->setUserID($userid);
        $quiz->setQuizID($quizid);

        $data = $this->repoManager->getRepository(QuizTemplate::class)->getQuestions($quizid);

        foreach ($data as $datum) {
            $questionTemplate = $this->repoManager->getRepository(QuestionTemplate::class)->find($datum['questiontemplateid']);
            $questionInstance = QuestionInstance::createFromTemplate($questionTemplate);
            $this->repoManager->register($questionInstance);
            $questionInstance->save();
            $questionInstance->setQuizID($quiz->getID());
            $questionInstance->setTemplateID($questionTemplate->getID());
            $answerTemplates = $questionTemplate->getAnswers();
            foreach ($answerTemplates as $answerTemplate) {
                /**
                 * @var TextTemplate $answerTemplate
                 */
                $answerInstance = TextInstance::createFromTemplate($answerTemplate);
                $this->repoManager->register($answerInstance);
                $answerInstance->save();
                $answerInstance->setQuestionID($questionInstance->getID());
                $answerInstance->setTemplateID($answerTemplate->getID());
            }
        }

        return $quiz->getID();
    }

    /**
     * @param int $quizInstanceID
     * @return array
     */
    public function getAllQuestions(int $quizInstanceID) {
        $quiz = $this->entityRepo->find($quizInstanceID);
        $questions = $quiz->getQuestions();
        $answers = [];
        foreach($questions as $question) {
            /**
             * @var QuestionInstance $question
             */
            $answers[] = $question->getAnswers();
        }
        return ['questions' => $questions, 'answers' => $answers];
    }

    /**
     * @param int $quizInstanceID
     * @return \ReallyOrm\Entity\EntityInterface|null
     */
    public function findQuiz(int $quizInstanceID) {
        return $this->entityRepo->find($quizInstanceID);
    }

    /**
     * @param int $quizInstanceID
     */
    public function save(int $quizInstanceID) {
        $quizInstance = $this->findQuiz($quizInstanceID);
        $quizInstance->setSaved(true);
        $quizInstance->save();
    }

    /**
     * @param int $quizInstanceID
     * @param int $score
     */
    public function saveScore(int $quizInstanceID, int $score) {
        $quiz = $this->findQuiz($quizInstanceID);
        $quiz->setScore($score);
        $quiz->save();
    }

    /**
     * @param int|null $id
     * @param string|null $className
     * @param string|null $search
     * @param string $searchColumn
     * @return int
     */
    public function getQuizzesCount(int $id = null, string $className = null, string $search = null, string $searchColumn = 'name') : int
    {
        return $this->entityRepo->count($id, $className, $search, $searchColumn);
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
}