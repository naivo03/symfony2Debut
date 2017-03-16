<?php 

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateUpdateFormType extends AbstractType
{
	public function BuildForm(FormBuilderInterface $builder, array $options)
	 {
	 	$builder
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
	 }
}