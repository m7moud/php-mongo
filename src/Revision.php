<?php

/**
 * This file is part of the PHPMongo package.
 *
 * (c) Dmytro Sokil <dmytro.sokil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sokil\Mongo;

class Revision extends \Sokil\Mongo\Document
{
    protected $_data = array(
        '__date__' => null,
    );
    
    /**
     *
     * @var \Sokil\Mongo\Collection
     */
    private $baseCollection;

    public function __construct(Collection $revisionsCollection, array $data = null, array $options = array())
    {
        parent::__construct($revisionsCollection, $data, $options);

        $baseCollectionName = substr(
            $revisionsCollection->getName(),
            0,
            -1 * strlen(Document::REVISION_COLLECTION_SUFFIX)
        );

        $this->baseCollection = $revisionsCollection
            ->getDatabase()
            ->getCollection($baseCollectionName);
    }
    
    /**
     * Set document data
     * 
     * @param array $document
     * @return \Sokil\Mongo\Revision
     */
    public function setDocumentData(array $document)
    {
        $this->set('__date__', new \MongoDate);
        $this->set('__documentId__', $document['_id']);
        
        unset($document['_id']);
        
        $this->merge($document);
        
        
        return $this;
    }
    
    /**
     * Get document instance
     * 
     * @return \Sokil\Mongo\Document
     */
    public function getDocument()
    {
        $data = $this->toArray();
        
        // restore document id
        $data['_id'] = $data['__documentId__'];
        
        // unset meta fields
        unset($data['__date__'], $data['__documentId__']);
        
        return $this
            ->baseCollection
            ->getStoredDocumentInstanceFromArray($data);
    }
    
    /**
     * Get date
     * 
     * @return \MongoDate
     */
    public function getDate($format = null)
    {
        if(!$format) {
            return $this->get('__date__')->sec;
        }
        
        return date($format, $this->get('__date__')->sec);
    }
}