<?php


namespace Quizapp\Repository;


use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getQuizzes (string $className, EntityInterface $target) : array
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

    public function getQuestions (string $className, EntityInterface $target) : array
    {
        $entityTable = $this->getEntityTableName($className);
        $thisTable = $this->getTableName();
        $targetId = $target->getId();
        $sql = 'SELECT '.$entityTable.'.id, '.$entityTable.'.type, '.$entityTable.'.text '.'FROM '.$entityTable.' INNER JOIN '.$thisTable.' ON '.$thisTable.'.id = '.$entityTable.'.'.$thisTable.'id WHERE '.$thisTable.'.id = :targetID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':targetID', $targetId);
        $stm->execute();
        $row = $stm->fetchAll();

        return $this->hydrator->hydrateMany($className, $row);
    }

    public function count () {
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName();
        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

    public function findByCount(array $filters): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName().' WHERE ';
        foreach ($filters as $fieldName => $value) {
            $sql .= $fieldName .' = :' . $fieldName . ' AND ';
        }
        $sql = substr($sql, 0, -5);

        $stm = $this->pdo->prepare($sql);

        foreach ($filters as $fieldName => $value) {
            $stm->bindValue(':' . $fieldName, $value);
        }

        $stm->execute();
        return $stm->fetch()['COUNT(*)'];

    }

}