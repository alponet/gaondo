<?php
/**
 * Author: tom
 * Date: 21.11.17
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction("/c/")
            ->add('text', TextType::class, ['label' => 'main.comment'])
            ->add('save', SubmitType::class, [
            	'label' => 'main.submit',
	            'attr' => [
	            	'class' => 'button-red'
	            ]
            ])
            ->add('close', ButtonType::class, [
            	'label' => 'X',
	            'attr' => [
	            	'class' => 'button-red close-commentForm'
	            ]
            ]);
    }
}