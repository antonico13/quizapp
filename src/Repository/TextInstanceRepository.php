<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class TextInstanceRepository extends AbstractRepository
{
    public function setForeignQuestion(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET questioninstanceid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }

    public function setForeignTemplate(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET texttemplateid = :fkID WHERE id = :entityID';

        echo $uid_fk;

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }
}