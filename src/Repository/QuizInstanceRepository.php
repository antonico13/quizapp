<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuizInstanceRepository extends AbstractRepository
{
    public function setForeignUser(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET userid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }

    public function setForeignQuiz(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET quiztemplateid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }

    public function count () {
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName();
        $stm = $this->pdo->prepare($sql);

        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

    public function countBySearch (?int $id, string $search) {
        $search = '%'.$search.'%';
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName().' WHERE ';
        if ($id) {
            $sql .= ' userid = :userid AND ';
        }
        //var_dump($sql);

        $sql .= 'name LIKE :search';

        $stm = $this->pdo->prepare($sql);

        if ($id) {
            $stm->bindValue(':userid', $id);
        }

        $stm->bindValue(':search', $search);

        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

}