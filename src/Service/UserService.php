<?php


namespace Quizapp\Service;

use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizTemplate;
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


}