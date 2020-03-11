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

    public function findBySearch(array $filters, array $sorts, int $from, int $size, string $search): array
    {
        $search = '%'.$search.'%';
        $sql = 'SELECT * FROM '.$this->getTableName().' WHERE ';
        if ($filters) {
            foreach ($filters as $fieldName => $value) {
                $sql .= $fieldName . ' = :' . $fieldName . ' AND ';
            }
        }

        $sql .= ' name LIKE :search ';

        if ($sorts) {
            $sql .= ' ORDER BY ';

            foreach ($sorts as $fieldName => $direction) {
                $dir = 'ASC';
                if (preg_match('/DESC/', $direction)) {
                    $dir = 'DESC';
                }
                $sql .= $fieldName . ' ' . $dir . ' ';
            }
        }

        $sql .= ' LIMIT :size OFFSET :from';

        //var_dump($sql);

        $stm = $this->pdo->prepare($sql);

        foreach ($filters as $fieldName => $value) {
            $stm->bindValue(':' . $fieldName, $value);
        }

        $stm->bindParam(':search', $search);
        $stm->bindParam(':size', $size);
        $stm->bindParam(':from', $from);

        $stm->execute();
        $data = $stm->fetchAll();
        $entities = array();

        foreach ($data as $datum) {
            $entities[] = $this->hydrator->hydrate($this->entityName, $datum);
        }

        return $entities;

    }

}