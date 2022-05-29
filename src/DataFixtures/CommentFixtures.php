<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\Novel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Novel $novel */
        $novel = $this->getReference(NovelFixtures::NOVEL_1);
        $commentSection = $novel->getCommentSection();

        $comment = new Comment();
        $comment->setAuthor($this->getReference(UserFixtures::USER_ADMIN));
        $comment->setContent('comment_admin');
        $commentSection->addComment($comment);

        foreach (range(1, 10) as $i) {
            $reply = new Comment();
            $reply->setAuthor($this->getReference(UserFixtures::USER_ADMIN));
            $reply->setContent('reply_' . $i);
            $reply->setReplyTo($comment);
            $commentSection->addComment($reply);
        }

        foreach (range(1, 30) as $i) {
            $comment = new Comment();
            $comment->setAuthor($this->getReference(UserFixtures::USER_NORMAL));
            $comment->setContent('comment_' . $i);
            $commentSection->addComment($comment);
        }

        /** @var Chapter $chapter */
        $chapter = $this->getReference(ChapterFixtures::CHAPTER_1);
        $commentSection = $chapter->getCommentSection();

        foreach (range(1, 10) as $i) {
            $comment = new Comment();
            $comment->setAuthor($this->getReference(UserFixtures::USER_NORMAL));
            $comment->setContent('comment_' . $i);
            $commentSection->addComment($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NovelFixtures::class,
            ChapterFixtures::class,
            UserFixtures::class,
        ];
    }
}
