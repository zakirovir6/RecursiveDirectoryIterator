<?php
/**
 * Created by PhpStorm.
 * User: igorek
 * Date: 12.04.17
 * Time: 1:08
 */

namespace Directory;


use Interfaces\Directory\Iterator as IIterator;

class IteratorRecursive implements IIterator
{
    /** @var  string */
    private $_path;
    /** @var  int */
    private $_maxDepth;

    /** @var  int */
    private $_pos = 0;

    /** @var Iterator[] */
    private $_iteratorStack = [];

    /** @var  Iterator */
    private $_currentIterator;

    /** @var  string */
    private $_currentFile;

    /** @var string[] */
    private $_errors = [];

    public function __construct( $path, $maxDepth = PHP_INT_MAX )
    {
        $this->_path = $path;
        $this->_maxDepth = (int)$maxDepth;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (bool)count( $this->_errors );
    }

    /**
     * @return string
     */
    public function current()
    {
        return $this->_currentFile;
    }

    /**
     * @return void
     */
    public function next()
    {
        if ( is_dir( $this->_currentFile ) && ( count( $this->_iteratorStack ) < $this->_maxDepth - 1 ) )
        {
            $this->_iteratorStack[] = $this->_currentIterator;
            $this->_currentIterator = new Iterator( $this->_currentFile );
            $this->_currentIterator->rewind();
            $this->_pos++;
        }
        else
        {
            $this->_currentIterator->next();
        }

        while ( ! $this->_currentIterator->valid() && count( $this->_iteratorStack ) )
        {
            if ( $this->_currentIterator->hasErrors() )
                $this->_errors = array_merge( $this->_errors, $this->_currentIterator->getErrors() );

            $this->_currentIterator = array_pop( $this->_iteratorStack );
            $this->_currentIterator->next();
        }

        $this->_currentFile = $this->_getFileFromIterator( $this->_currentIterator );
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->_pos . '-' . $this->_currentIterator->key();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->_currentFile !== null;
    }

    public function rewind()
    {
        $this->_currentIterator = new Iterator( $this->_path );
        $this->_currentIterator->rewind();
        $this->_currentFile = $this->_getFileFromIterator( $this->_currentIterator );

        if ( $this->_currentIterator->hasErrors() )
            $this->_errors = array_merge( $this->_errors, $this->_currentIterator->getErrors() );
    }

    /**
     * @param Iterator $it
     *
     * @return string
     */
    private function _getFileFromIterator( Iterator $it )
    {
        if ( $it->valid() )
            return $it->current();

        return null;
    }


}