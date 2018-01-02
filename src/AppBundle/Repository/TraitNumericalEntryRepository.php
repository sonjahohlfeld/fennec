<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TraitNumericalEntryRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class TraitNumericalEntryRepository extends EntityRepository
{
    public function getNumber(): int {
        $query = $this->getEntityManager()->createQuery('SELECT COUNT(t.id) FROM AppBundle\Entity\TraitNumericalEntry t WHERE t.deletionDate IS NULL ');
        return $query->getSingleScalarResult();
    }

    /**
     * @param $trait_type_id
     * @param $fennec_ids
     * @return array values of specific trait
     */
    public function getValues($trait_type_id, $fennec_ids){
        $qb = $this->getEntityManager()->createQueryBuilder();
        if($fennec_ids !== null){
            $qb->select('IDENTITY(t.fennec) AS id', 't.value')
                ->from('AppBundle\Entity\TraitNumericalEntry', 't')
                ->where('IDENTITY(t.traitType) = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->andWhere('t.deletionDate IS NOT NULL')
                ->add('where', $qb->expr()->in('t.fennec', $fennec_ids));
        } else {
            $qb->select('IDENTITY(t.fennec) AS id', 't.value')
                ->from('AppBundle\Entity\TraitNumericalEntry', 't')
                ->where('IDENTITY(t.traitType) = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->expr()->isNotNull('t.deletionDate');
        }
        $query = $qb->getQuery();
        $result = $query->getResult();
        $values = array();
        for($i=0;$i<sizeof($result);$i++) {
            if(!array_key_exists($result[$i]['id'], $values)){
                $values[$result[$i]['id']] = array();
            }
            $values[$result[$i]['id']][] = $result[$i]['value'];
        }
        return $values;
    }
}
