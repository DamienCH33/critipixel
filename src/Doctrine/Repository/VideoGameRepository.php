<?php

declare(strict_types=1);

namespace App\Doctrine\Repository;

use App\List\VideoGameList\Filter;
use App\List\VideoGameList\Pagination;
use App\Model\Entity\Tag;
use App\Model\Entity\VideoGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class VideoGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGame::class);
    }

    public function getVideoGames(Pagination $pagination, Filter $filter): Paginator
    {
        $qb = $this->createQueryBuilder('vg')
            ->addSelect('t')
            ->leftJoin('vg.tags', 't')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit())
            ->orderBy(
                $pagination->getSorting()->getSql(),
                $pagination->getDirection()->getSql()
            );

        // --- Filtrage texte ---
        $search = $filter->getSearch();
        if (!empty($search)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('vg.title', ':search'),
                    $qb->expr()->like('vg.description', ':search'),
                    $qb->expr()->like('vg.test', ':search')
                )
            )->setParameter('search', '%'.$search.'%');
        }

        // --- Filtrage tags ---
        $tags = $filter->getTags();
        if (!empty($tags)) {
            $tagIds = [];
            foreach ($tags as $tag) {
                if ($tag instanceof Tag) {
                    $tagIds[] = $tag->getId();
                } elseif (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                }
            }

            $existingTags = $this->getEntityManager()
                ->getRepository(Tag::class)
                ->findBy(['id' => $tagIds]);

            if (empty($existingTags)) {
                $qb->andWhere('1 = 0');

                return new Paginator($qb, fetchJoinCollection: true);
            }

            $validTagIds = array_map(fn (Tag $t) => $t->getId(), $existingTags);

            $subQb = $this->getEntityManager()->createQueryBuilder();
            $subQb->select('vg2.id')
                ->from(VideoGame::class, 'vg2')
                ->join('vg2.tags', 't2')
                ->where('t2.id IN (:tagIds)')
                ->groupBy('vg2.id')
                ->having('COUNT(DISTINCT t2.id) = :tagCount');

            $qb->andWhere($qb->expr()->in('vg.id', $subQb->getDQL()))
                ->setParameter('tagIds', $validTagIds)
                ->setParameter('tagCount', count($validTagIds));
        }

        return new Paginator($qb, fetchJoinCollection: true);
    }
}
