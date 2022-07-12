<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chapter;
use App\Entity\Novel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChapterFixtures extends Fixture implements DependentFixtureInterface
{
    public const CHAPTER_1 = 'chapter_1';

    public function load(ObjectManager $manager)
    {
        /** @var Novel $novel */
        $novel = $this->getReference(NovelFixtures::NOVEL_1);

        foreach (range(1, 30) as $i) {
            $chapter = new Chapter();
            $chapter->setTitle('Title '.$i);
            $chapter->setContent(file_get_contents(__DIR__.'/LoremIpsum.txt'));
            $chapter->setNumber($i);
            $chapter->setCreatedAt(new \DateTime(31 - $i.' days ago'));
            $novel->addChapter($chapter);
            if (1 === $i) {
                $this->setReference(self::CHAPTER_1, $chapter);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NovelFixtures::class,
        ];
    }
}
