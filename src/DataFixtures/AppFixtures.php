<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Liior\Faker\Prices;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger, private UserPasswordHasherInterface $encoder)
    {
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $admin = new User();
        $hashedPassword = $this->encoder->hashPassword($admin, 'password');
        $admin->setEmail('admin@admin.com')
            ->setUserName('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($hashedPassword);
        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hashedPassword = $this->encoder->hashPassword($user, 'password');
            $user->setEmail("user$u@admin.com")
                ->setUserName($faker->userName())
                ->setPassword($hashedPassword);
            $manager->persist($user);
        }
        for ($c = 0; $c < 3; $c++) {
            $category = new Category;

            $category->setName($faker->department())
                ->setSlug(strtolower($this->slugger->slug($category->getName())->toString()));
            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;

                $product->setName($faker->productName())
                    ->setPrice($faker->price(200, 20000, true, true, false))
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())->toString()))
                    ->setCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
