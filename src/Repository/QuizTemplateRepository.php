<?php


namespace Quizapp\Repository;

use Quizapp\Entity\QuestionTemplate;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
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
        $sql .= ' userid = :userid AND name LIKE :search';

        //var_dump($sql);

        $stm = $this->pdo->prepare($sql);

        $stm->bindValue(':userid', $id);
        $stm->bindValue(':search', $search);

        $stm->execute();

        return $stm->fetch()['COUNT(*)'];
    }

    public function findBySearch(array $filters, array $sorts, int $from, int $size, string $search): array
    {
        $search = '%'.$search.'%';
        $sql = 'SELECT * FROM '.$this->getTableName().' WHERE ';
        foreach ($filters as $fieldName => $value) {
            $sql .= $fieldName .' = :' . $fieldName . ' AND ';
        }

        $sql = substr($sql, 0, -5);
        $sql .= ' AND name LIKE :search ';
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

    public function insertOnLinkTable(EntityInterface $quiz, array $ids) {
        $quizid = $quiz->getId();
        foreach ($ids as $id) {
            $sql = 'INSERT INTO quizquestiontemplate (quiztemplateid, questiontemplateid) VALUES (:quizid, :questionid)';
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':quizid', $quizid);
            $stm->bindValue(':questionid', $id);

            $stm->execute();
        }
    }
    //^^^generalize

    public function getAllQuestions() {
        $sql = 'SELECT * FROM questiontemplate';

        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        return $this->hydrator->hydrateMany(QuestionTemplate::class, $stm->fetchAll());
    }

    public function setForeignKey(int $uid_fk, EntityInterface $target): bool
    {
        $uid = $this->hydrator->getId($target);

        $sql = 'UPDATE '.$this->getTableName().' SET userid = :fkID WHERE '.$uid[0].' = :entityID';

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':fkID', $uid_fk);
        $stm->bindParam(':entityID', $uid[1]);

        return $stm->execute();
    }

}