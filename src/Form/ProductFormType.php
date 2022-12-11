<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\ProductType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('link', TextType::class)
            ->add('subscribers')
            ->add('description')
            ->add('incomeMonth', MoneyType::class, [
                'divisor' => 100,
                'currency' => 'USD',
                'required' => false
            ])
            ->add('expenseMonth', MoneyType::class, [
                'divisor' => 100,
                'currency' => 'USD',
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100,
                'currency' => 'USD',
                'required' => true
            ])
            ->add('productType', EntityType::class, [
                'class' => ProductType::class,
                'choice_label' => 'name'
            ])
            ->add('productCategory', EntityType::class, [
                'class' => ProductCategory::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
