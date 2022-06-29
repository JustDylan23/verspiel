<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Novel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NovelFixtures extends Fixture
{
    public const NOVEL_1 = 'novel_1';
    public const NOVEL_2 = 'novel_2';

    public function load(ObjectManager $manager)
    {
        $novel = new Novel();
        $novel->setTitle('novel_1');
        $novel->setDescription('description_1');
        $novel->setShortDescription('description_1');
        $manager->persist($novel);
        $this->setReference(self::NOVEL_1, $novel);

        $novel = new Novel();
        $novel->setTitle('novel_2');
        $novel->setDescription('description_2');
        $novel->setShortDescription('description_2');
        $manager->persist($novel);
        $this->setReference(self::NOVEL_2, $novel);

        $manager->flush();
    }
}
