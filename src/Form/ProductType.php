<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Product name', 'attr' => ['placeholder' => 'Enter product name']])
            ->add('mainPicture', UrlType::class, ['label' => 'Picture url', 'attr' => ['placeholder' => 'Enter product image']])
            ->add('price', MoneyType::class, ['html5' => false, 'currency' => 'EUR', 'divisor' => 100, 'label' => 'Product price', 'attr' => ['placeholder' => 'Enter price in €']])
            ->add('category', EntityType::class, [
                'label' => 'Product category',
                'placeholder' => '-- Select a category --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
            ])
            ->add('shortDescription', TextareaType::class, ['label' => 'Short description', 'attr' => ['placeholder' => 'Please decribe your product details']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
