<?php


namespace Quizapp\Service;


class TextInstanceService extends AbstractService
{
    public function save (int $id, string $text) {
        $answer = $this->entityRepo->find($id);
        $answer->setText($text);
        $answer->save();
    }
}