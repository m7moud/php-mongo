<?php

/**
 * This file is part of the PHPMongo package.
 *
 * (c) Dmytro Sokil <dmytro.sokil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sokil\Mongo\Document;

use Sokil\Mongo\Document;

/**
 * Throws on validation process when document is invalid
 *
 * @author Dmytro Sokil <dmytro.sokil@gmail.com>
 */
class InvalidDocumentException extends \Sokil\Mongo\Exception
{
    /**
     *
     * @var \Sokil\Mongo\Document
     */
    private $_document;

    public function setDocument(Document $document)
    {
        $this->_document = $document;
        return $this;
    }

    public function getDocument()
    {
        return $this->_document;
    }
}