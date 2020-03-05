<?php


namespace App\Form\DataTransformer;


use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsTransformer implements DataTransformerInterface
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function transform($value): string
    {
        return implode(",", $value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value): array
    {
        $names =
            array_unique(
                array_filter(
                    array_map('trim', explode(",", $value))));
        $tags = [];
        $tags = $this->manager->getRepository(Tag::class)->findBy(["name" => $names]);
        $newNames = array_diff($names, $tags);
        foreach ($newNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }
        return $tags;
    }
}