<?php


namespace Quizapp\Service;


use Framework\Contracts\SessionInterface;
use Quizapp\Contracts\ServiceInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class AbstractService implements ServiceInterface
{
    protected $repoManager;
    protected $entityRepo;

    public function __construct(RepositoryManagerInterface $repoManager, RepositoryInterface $entityRepo)
    {
        $this->repoManager = $repoManager;
        $this->entityRepo = $entityRepo;
    }

}