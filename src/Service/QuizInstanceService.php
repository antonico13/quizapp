<?php


namespace Quizapp\Service;


use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizInstance;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\TextInstance;
use Quizapp\Entity\TextTemplate;

class QuizInstanceService extends AbstractService
{
    public function addQuiz(int $userid, int $quizid) {
        $quiz = new QuizInstance();
        $template = $this->repoManager->getRepository(QuizTemplate::class)->find($quizid);
       // $this->repoManager->register($quiz);
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

    public function findQuiz(int $quizInstanceID) {
        return $this->entityRepo->find($quizInstanceID);
    }

    public function save(int $quizInstanceID) {
        $quizInstance = $this->findQuiz($quizInstanceID);
        $quizInstance->setSaved(true);
        $quizInstance->save();
    }

    public function saveScore(int $quizInstanceID, int $score) {
        $quiz = $this->findQuiz($quizInstanceID);
        $quiz->setScore($score);
        $quiz->save();
    }

    public function getQuizzesCount()
    {
        return $this->entityRepo->count();
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
}