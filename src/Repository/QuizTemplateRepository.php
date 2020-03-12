<?php


namespace Quizapp\Repository;

use Quizapp\Entity\QuestionTemplate;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    /**
     * @param EntityInterface $quiz
     * @param array $ids
     */
    public function insertOnLinkTable(EntityInterface $quiz, array $ids) {
        $quizid = $quiz->getId();
        foreach ($ids as $id) {
            $sql = 'INSERT INTO quizquestion (quiztemplateid, questiontemplateid) VALUES (:quizid, :questionid)';
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':quizid', $quizid);
            $stm->bindValue(':questionid', $id);

            $stm->execute();
        }
    }

    /**
     * @return mixed
     */
    public function getAllQuestions() {
        $sql = 'SELECT * FROM questiontemplate';

        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        return $this->hydrator->hydrateMany(QuestionTemplate::class, $stm->fetchAll());
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function countQuestions(int $id) {
        $sql = 'SELECT COUNT(*) FROM quizquestion WHERE quiztemplateid = :quizid';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':quizid', $id);
        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getQuestions(int $id) {
        $sql = 'SELECT * FROM quizquestion WHERE quiztemplateid = :quizid';
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':quizid', $id);
        $stm->execute();

        return $stm->fetchAll();
    }

    /**
     * @param int $quizTemplateID
     * @return array
     */
    public function getSelectedQuestions(int $quizTemplateID) {
        $sql = 'SELECT questiontemplateid FROM quizquestion WHERE quiztemplateid = :quizid';
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':quizid', $quizTemplateID);
        $stm->execute();

        return $stm->fetchAll();
    }

    /**
     * @param int $quizTemplateID
     */
    public function deleteRelation(int $quizTemplateID) {
        $sql = 'DELETE FROM quizquestion WHERE quiztemplateid = :quizid';
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':quizid', $quizTemplateID);
        $stm->execute();
    }

}