<?php
/**
 * Created by PhpStorm.
 * User: brtrnd
 * Date: 09/02/16
 * Time: 01:16
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('quantity')
            ->add('category', CategoryType::class, [
                'class' => 'AppBundle:Categorie',
                'choice_label' => 'label',
                'attr' => ['class' => 'form-control'],
                'label' => 'Catégorie :'
            ]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Item'
        ));
    }


}