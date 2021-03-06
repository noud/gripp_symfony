<?php

namespace App\Rpc\v3;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Timiki\Bundle\RpcServerBundle\Mapping as Rpc;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Rpc\Method("tag.create")
 * @ Rpc\Roles({
 *   "ROLE_NAME"
 * })
 * @Rpc\Cache(lifetime=3600)
 */
class TagCreate
{
    /**
     * @Rpc\Param()
     * @Assert\NotBlank()
     */
    protected $param;
    
    /**
     * @var Serializer
     */
    private $serializer;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var TagRepository
     */
    private $tagRepository;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository
    ) {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $encoder = new JsonEncoder();
        $this->serializer = new Serializer([$normalizer], [$encoder]);
        
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }
    
    /**
     * @Rpc\Execute()
     */
    public function execute()
    {
        $serializedTag = json_encode($this->param);
        $tag = $this->serializer->deserialize($serializedTag, Tag::class, 'json');
        $this->tagRepository->add($tag);
        $this->entityManager->flush();
        return true;
    }
}
