<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getForeignEntities (string $className, EntityInterface $target) : array
    {
        $entityTable = $this->getEntityTableName($className);
        $thisTable = $this->getTableName();
        $targetId = $target->getId();
        $sql = 'SELECT '.$entityTable.'.id, '.$entityTable.'.name '.'FROM '.$entityTable.' INNER JOIN '.$thisTable.' ON '.$thisTable.'.id = '.$entityTable.'.'.$thisTable.'id WHERE '.$thisTable.'.id = :targetID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':targetID', $targetId);
        $stm->execute();
        $row = $stm->fetchAll();

        return $this->hydrator->hydrateMany($className, $row);
    }

}