<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TraitCategoricalEntryRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class TraitCategoricalEntryRepository extends EntityRepository
{
    public function getNumber(): int
    {
        $query = $this->getEntityManager()->createQuery('SELECT COUNT(t.id) FROM AppBundle\Entity\TraitCategoricalEntry t WHERE t.deletionDate IS NULL ');
        return $query->getSingleScalarResult();
    }

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
            $qb->select('IDENTITY(t.fennec) AS id', 'value.value')
                ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
                ->innerJoin('AppBundle\Entity\TraitCategoricalValue', 'value', 'WITH', 't.traitCategoricalValue = value.id')
                ->where('t.traitType = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->expr()->isNotNull('t.deletionDate');
        }
        $query = $qb->getQuery();
        $result = $query->getResult();

        $values = array();
        for($i=0;$i<sizeof($result);$i++) {
            if(!array_key_exists($result[$i]['value'], $values)){
                $values[$result[$i]['value']] = array();
            }
            $values[$result[$i]['value']][] = $result[$i]['id'];
        }

        foreach ($values as $key => $value){
            $values[$key] = array_values(array_unique($value));
        }
        return $values;
    }

    /**
     * @param $trait_type_id
     * @param $fennec_ids
     * @return integer number of organisms which have this trait
     */
    public function getNumberOfOrganisms($trait_type_id, $fennec_ids){
        $qb = $this->getEntityManager()->createQueryBuilder();
        if($fennec_ids !== null){
            $qb->select('count(IDENTITY(t.fennec))')
                ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
                ->where('IDENTITY(t.traitType) = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->andWhere('t.deletionDate IS NOT NULL')
                ->add('where', $qb->expr()->in('t.fennec', $fennec_ids));
        } else {
            $qb->select('count(IDENTITY(t.fennec))')
                ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
                ->where('t.traitType = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->expr()->isNotNull('t.deletionDate');
        }
        $query = $qb->getQuery();
        $result = $query->getSingleResult();
        return $result['1'];
    }

    /**
     * @param $trait_type_id
     * @param $fennec_ids
     * @return array citations by fennec_id
     */
    public function getCitations($trait_type_id, $fennec_ids){
        $qb = $this->getEntityManager()->createQueryBuilder();
        if($fennec_ids !== null){
            $qb->select('IDENTITY(t.fennec) AS id', 'traitCitation.citation', 'traitValue.value')
                ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
                ->innerJoin('AppBundle\Entity\TraitCitation', 'traitCitation', 'WITH', 't.traitCitation = traitCitation.id')
                ->innerJoin('AppBundle\Entity\TraitCategoricalValue', 'traitValue', 'WITH', 't.traitCategoricalValue = traitValue.id')
                ->where('t.traitType = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->add('where', $qb->expr()->in('t.fennec', $fennec_ids))
                ->expr()->isNotNull('t.deletionDate');
        } else {
            $qb->select('IDENTITY(t.fennec) AS id', 'traitCitation.citation', 'traitValue.value')
                ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
                ->innerJoin('AppBundle\Entity\TraitCitation', 'traitCitation', 'WITH', 't.traitCitation = traitCitation.id')
                ->innerJoin('AppBundle\Entity\TraitCategoricalValue', 'traitValue', 'WITH', 't.traitCategoricalValue = traitValue.id')
                ->where('t.traitType = :trait_type_id')
                ->setParameter('trait_type_id', $trait_type_id)
                ->expr()->isNotNull('t.deletionDate');
        }
        $query = $qb->getQuery();
        $result = $query->getSingleResult();
        $citations = array();
        for($i=0;$i<sizeof($result);$i++) {
            if(!array_key_exists($result[$i]['id'], $citations)){
                $citations[$result[$i]['id']] = array();
            }
            $citations[$result[$i]['id']][] = [
                "citation" => $result[$i]['citation'],
                "value" => $result[$i]['value']
            ];
        }

        return $citations;
    }

    public function getTraits($search, $limit){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('IDENTITY(t.traitType) AS traitTypeId', 'traitType.type')
            ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
            ->innerJoin('AppBundle\Entity\TraitType', 'traitType', 'WITH', 't.traitType = traitType.id')
            ->where('lower(traitType.type) LIKE lower(:search)')
            ->groupBy('t.traitType', 'traitType.type')
            ->setParameter('search', $search)
            ->setMaxResults($limit);
        $query = $qb->getQuery();
        $result = $query->getResult();
        $this->getEntityManager()->clear();

        for($i=0;$i<sizeof($result);$i++){
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('COUNT(IDENTITY(t.traitType)) AS count')
                ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
                ->where('t.traitType = :traitTypeId')
                ->setParameter('traitTypeId', $result[$i]['traitTypeId'])
                ->groupBy('t.traitType');
            $query = $qb->getQuery();
            $data = $query->getSingleResult();
            $result[$i]['frequency'] = $data['count'];
            $this->getEntityManager()->clear();
        }
        return $result;
    }

    public function getTraitEntry($traitEntryIds){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.id', 'IDENTITY(t.fennec) AS fennec', 't.originUrl', 'traitValue.value AS valueName', 'traitValue.ontologyUrl AS valueDefinition', 'traitType.type AS typeName', 'traitType.unit AS unit', 'traitType.ontologyUrl AS typeDefinition', 'traitCitation.citation')
            ->from('AppBundle\Entity\TraitCategoricalEntry', 't')
            ->innerJoin('AppBundle\Entity\TraitCategoricalValue', 'traitValue', 'WITH', 't.traitCategoricalValue = traitValue.id')
            ->innerJoin('AppBundle\Entity\TraitType', 'traitType', 'WITH', 't.traitType = traitType.id')
            ->innerJoin('AppBundle\Entity\TraitCitation', 'traitCitation', 'WITH', 't.traitCitation = traitCitation.id')
            ->add('where', $qb->expr()->in('t.id', $traitEntryIds));
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }
}
