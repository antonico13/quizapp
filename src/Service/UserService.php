<?php


namespace Quizapp\Service;

use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\User;
use ReallyOrm\Entity\EntityInterface;

class UserService extends AbstractService
{
    public function login (string $email, string $password)
    {
        $user = $this->entityRepo->findOneBy(['email' => $email]);
        if ($password === $user->getPassword()) {
            return [$user->getRole(), $user->getId(), $user->getName()];
        }

        return null;
    }

    public function getQuizzes(int $id) : array
    {
        $user = $this->entityRepo->find($id);

        return $this->entityRepo->getQuizzes(QuizTemplate::class, $user);
    }

    public function addUser(array $data) {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);
        $user->setPassword(hash('sha256', $data['password']));

        $this->repoManager->register($user);
        $user->save();
    }

    public function getUserCount() {
        return $this->entityRepo->count();
    }

    public function getUsers (int $page = 1, int $limit = 5) {

        return $this->entityRepo->findBy([], ['id' => 'ASC'], ($page-1)*$limit, $limit);
    }

    public function deleteUser (int $id)
    {
        $user = $this->entityRepo->find($id);
        $this->entityRepo->delete($user);
    }

    public function getUser (int $id) {

        return $this->entityRepo->find($id);
    }

    public function editUser (int $id, array $data) {
        $user = $this->getUser($id);
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['email']);
        $user->setRole($data['role']);
        $this->repoManager->register($user);
        $user->save();
    }

    public function getUsersCountSearch(array $filters) : int
    {
        return $this->entityRepo->findByCount($filters);
    }

    public function getUsersSearch(array $filters, int $page, int $limit = 5) : array
    {
        return $this->entityRepo->findBy($filters, ['id' => 'ASC'], ($page-1)*$limit, $limit);
    }

}