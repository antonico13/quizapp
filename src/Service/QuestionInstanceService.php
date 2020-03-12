<?php


namespace Quizapp\Service;


use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizInstance;

class QuestionInstanceService extends AbstractService
{
    /**
     * @param int $qtid
     * @param int $offset
     * @param int $qid
     * @return QuestionInstance
     */
    public function addQuestion (int $qtid, int $offset, int $qid) {
        $question = new QuestionInstance();
        $templateid = $this->repoManager->getRepository(QuestionTemplate::class)->findByForeignID($qtid, $offset);
        $template = $this->repoManager->getRepository(QuestionTemplate::class)->find($templateid);
        $this->repoManager->register($question);
        $question->setText($template->getText());
        $question->setType($template->getType());
        $question->save();
        $question->setQuizID($qid);
        $question->setTemplateID($templateid);

        return $question;
    }

    /**
     * @param int $quizInstanceID
     * @param int $offset
     * @return mixed|\ReallyOrm\Entity\EntityInterface
     */
    public function getQuestion (int $quizInstanceID, int $offset) {
        return $this->entityRepo->findBy(['quizinstanceid' => $quizInstanceID], [], $offset, 1)[0];
    }

    /**
     * @param int $quizInstanceID
     * @return mixed
     */
    public function count(int $quizInstanceID) {
        return $this->entityRepo->count($quizInstanceID, QuizInstance::class);
    }

}