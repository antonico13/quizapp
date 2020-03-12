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

        if (!$user) {
            return null;
        }
        if ($password !== $user->getPassword()) {
            return null;
        }

        return [$user->getRole(), $user->getId(), $user->getName()];
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
        if ($this->entityRepo->findOneBy(['email'=>$user->getEmail()])) {
            return null;
        }
        $user->setRole($data['role']);
        $user->setPassword(hash('sha256', $data['password']));

        $this->repoManager->register($user);
        $user->save();

        return true;
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
        if ($user->getEmail() != $data['email']) {
            if ($this->entityRepo->findOneBy(['email'=>$data['email']])) {
                return null;
            }
        }
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['email']);
        $user->setRole($data['role']);
        $this->repoManager->register($user);
        $user->save();

        return true;
    }

    public function getUserCount(array $filters) : int
    {
        return $this->entityRepo->Count(null, null, null, null, $filters);
    }

    public function getUsers(array $filters, array $sorts, int $page, int $limit = 5) : array
    {
        return $this->entityRepo->findBy($filters, $sorts, ($page-1)*$limit, $limit);
    }

}