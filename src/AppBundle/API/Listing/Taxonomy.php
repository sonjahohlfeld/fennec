<?php

namespace AppBundle\API\Listing;

use AppBundle\Entity\Data\TaxonomyNode;
use AppBundle\Service\DBVersion;

/**
 * Web Service.
 * Returns Taxonomy information for a given organism_id
 */
class Taxonomy
{

    private $manager;

    /**
     * Taxonomy constructor.
     * @param $dbversion
     */
    public function __construct(DBVersion $dbversion)
    {
        $this->manager = $dbversion->getDataEntityManager();
    }


    /**
     * @param $fennec_id
     * @returns array of taxonomy information for a given organism id
     * array('lineage' => [grandgrandparent, grandparent, parent])
     */
    public function execute($fennec_id)
    {
        $result = array();
        $taxonomy_databases = $this->manager->getRepository(TaxonomyNode::class)->getDatabases($fennec_id);
        foreach($taxonomy_databases as $name => $taxonomy_node_id){
            $result[$name] = $this->manager->getRepository(TaxonomyNode::class)->getLineage($taxonomy_node_id);
        }
        return $result;
    }
}
