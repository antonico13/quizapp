<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class TextTemplateRepository extends AbstractRepository
{
    public function setForeignKey(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET questiontemplateid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }
}