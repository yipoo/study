<?php
/**
 * Created by PhpStorm.
 * User: dingzong
 * Date: 2017/11/11
 * Time: 19:30
 */

namespace AppBundle\Form;


use AppBundle\Entity\Post;
use AppBundle\Form\Type\DateTimePickerType;
use AppBundle\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.title',
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'label.summary',
            ])
            ->add('content', null, [
                'attr'      => ['rows' => 20],
                'label'     => 'label.content',
            ])
            ->add('publishedAt', DateTimePickerType::class,[
                'label' => 'label.published_at',
            ])
            ->add('tags', TagsInputType::class, [
                'label'     => 'label.tags',
                'required'  => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault([
            'data_class'    => Post::class,
        ]);
    }
}