<?php


namespace Quizapp\Service;


use HighlightLib\CodeHighlight;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class TextInstanceService extends AbstractService
{
    private $codeHighlight;

    public function __construct(RepositoryManagerInterface $repoManager, RepositoryInterface $entityRepo, CodeHighlight $codeHighlight)
    {
        parent::__construct($repoManager, $entityRepo);
        $this->codeHighlight = $codeHighlight;
    }

    public function save (int $id, string $text) {
        $answer = $this->entityRepo->find($id);
        $answer->setText($text);
        if ($answer->isCodeAnswer()) {
            $answer->setText($this->codeHighlight->highlight($text));
        }
        $answer->save();
    }
}