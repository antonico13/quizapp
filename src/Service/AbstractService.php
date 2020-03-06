<?php


namespace Quizapp\Service;


use Framework\Contracts\SessionInterface;
use Quizapp\Contracts\ServiceInterface;
use ReallyOrm\Repository\RepositoryInterface;

class AbstractService implements ServiceInterface
{
    protected $entityRepo;
    protected $session;

    public function __construct(RepositoryInterface $entityRepo, SessionInterface $session)
    {
        $this->entityRepo = $entityRepo;
        $this->session = $session;
    }

}