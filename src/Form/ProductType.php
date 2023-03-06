<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\DataFixtures\CentTransformer;
use App\Form\Type\PriceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductType extends AbstractType
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Product name', 'required' => false, 'attr' => ['placeholder' => 'Enter product name']])
            ->add('mainPicture', UrlType::class, ['label' => 'Picture url', 'attr' => ['placeholder' => 'Enter product image']])
            ->add('price', PriceType::class, ['label' => 'Product price', 'required' => false, 'attr' => ['placeholder' => 'Enter price in €']])
            ->add('shortDescription', TextareaType::class, ['label' => 'Short description', 'attr' => ['placeholder' => 'Please decribe your product details']]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var Product */
            $product = $event->getData();
            if (!empty($product->getName())) {
                $product->setSlug(strtolower($this->slugger->slug($product->getName())));
            }
        });

        // $builder->get('price')
        //     ->addModelTransformer(new CallbackTransformer(
        //         function ($value) {
        //             return $value;
        //         },
        //         function ($value) {
        //             return $value;
        //         },
        //     ));

        // $builder->get('price')->addModelTransformer(new CentTransformer);

        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     /**@var Product $product*/
        //     $product = $event->getData();
        //     if (!is_null($product->getPrice())) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });


        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();

        //     /**@var Product $product */
        //     $product = $event->getData();
        //     if (!is_null($product->getPrice())) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }

        //     if (!empty($product->getName())) {
        //         $product->setSlug(strtolower($this->slugger->slug($product->getName())));
        //     }

        //     if (is_null($product->getName())) {
        $builder->add('category', EntityType::class, [
            'label' => 'Product category',
            'placeholder' => '-- Select a category --',
            'class' => Category::class,
            'choice_label' => function (Category $category) {
                return strtoupper($category->getName());
            }
        ]);
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
