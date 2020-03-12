<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    /**
     * @param int $questionTemplateID
     */
    public function deleteRelation(int $questionTemplateID) {
        $sql = 'DELETE FROM quizquestion WHERE questiontemplateid = :questionid';
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':questionid', $questionTemplateID);
        $stm->execute();
    }
}