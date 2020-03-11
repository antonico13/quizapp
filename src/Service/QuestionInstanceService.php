<?php


namespace Quizapp\Service;


use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;

class QuestionInstanceService extends AbstractService
{
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

    public function getQuestion (int $quizInstanceID, int $offset) {
        return $this->entityRepo->findBy(['quizinstanceid' => $quizInstanceID], [], $offset, 1)[0];
    }

    public function count(int $quizInstanceID) {
        return $this->entityRepo->count($quizInstanceID);
    }

}