<?php


namespace Quizapp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    public function findBySearch(array $filters, array $sorts, int $from, int $size, string $search): array
    {
        $search = '%'.$search.'%';
        $sql = 'SELECT * FROM '.$this->getTableName().' WHERE ';
        foreach ($filters as $fieldName => $value) {
            $sql .= $fieldName .' = :' . $fieldName . ' AND ';
        }

        $sql = substr($sql, 0, -5);
        $sql .= ' AND text LIKE :search ';
        $sql .= ' ORDER BY ';

        foreach ($sorts as $fieldName => $direction) {
            $dir = 'ASC';
            if (preg_match('/DESC/', $direction)) {
                $dir = 'DESC';
            }
            $sql.= $fieldName . ' ' . $dir;
        }

        $sql = substr($sql, 0, -3);
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

    public function countBy (int $id) {
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName().' WHERE ';
        $sql .= ' userid = :userid';

        //var_dump($sql);

        $stm = $this->pdo->prepare($sql);

        $stm->bindValue(':userid', $id);

        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

    public function countBySearch (int $id, string $search) {
        $search = '%'.$search.'%';
        $sql = 'SELECT COUNT(*) FROM '.$this->getTableName().' WHERE ';
        $sql .= ' userid = :userid AND text LIKE :search';

        //var_dump($sql);

        $stm = $this->pdo->prepare($sql);

        $stm->bindValue(':userid', $id);
        $stm->bindValue(':search', $search);

        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

}