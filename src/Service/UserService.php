<?php


namespace Quizapp\Service;

use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\User;
use ReallyOrm\Entity\EntityInterface;

class UserService extends AbstractService
{
    public function logout () {
        $this->session->destroy();
    }

    public function findUser (string $email) : EntityInterface
    {
        return $this->entityRepo->findOneBy(['email' => $email]);
    }

    public function getName ()
    {
        return $this->session->get('name');
    }

    public function login (string $email, string $password)
    {
        $user = $this->findUser($email);
        if ($password === $user->getPassword()) {
            $this->session->set('name', $user->getName());
            $this->session->set('email', $email);
            $this->session->set('role', $user->getRole());
            $this->session->set('id', $user->getId());

            return $this->session->get('role');
        }
    }

    public function getQuizzes() : array
    {
        $email = $this->session->get('email');
        $user = $this->findUser($email);

        return $this->entityRepo->getQuizzes(QuizTemplate::class, $user);
    }

    public function addUser(array $data) {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);
        $user->setPassword(hash('sha256', $data['password']));

        $this->entityRepo->insertOnDuplicateKeyUpdate($user);
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

        $this->entityRepo->insertOnDuplicateKeyUpdate($user);
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