<?php
/**
 * Created by PhpStorm.
 * User: brtrnd
 * Date: 15/03/16
 * Time: 22:11
 */

namespace AppBundle\Form;


class CategorySearchType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('keyword', 'text', array('label' => 'Mot-clé'));
    }

    public function getName()
    {
        return 'searchedCategory';
    }
}

