<?php


namespace App\Form;


use App\Form\DataTransformer\TagsTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsType extends AbstractType
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getParent() {
        return TextType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CollectionToArrayTransformer(), true);
        $builder->addModelTransformer(new TagsTransformer($this->manager), true);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*
        $resolver
            ->setDefault('required', false)
            ->setDefault('attr', ['class' => 'tag-input']);
        */
    }
}