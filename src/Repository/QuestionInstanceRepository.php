<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    public function setForeignTemplate(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET questiontemplateid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }

    public function setForeignQuiz(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET quizinstanceid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }

    public function count (int $quizInstanceID) {
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName().' WHERE quizinstanceid = :quizID';
        $stm = $this->pdo->prepare($sql);

        $stm->bindValue(':quizID', $quizInstanceID);

        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

}