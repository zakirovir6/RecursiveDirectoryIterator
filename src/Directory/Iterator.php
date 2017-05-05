<?php
/**
 * Created by PhpStorm.
 * User: igorek
 * Date: 10.04.17
 * Time: 23:54
 */

namespace Directory;


use Interfaces\Directory\Iterator as IIterator;

class Iterator implements IIterator
{
    /** @var  string */
    private $_path;

    /** @var  resource */
    private $_dirHandle;

    /** @var  int */
    private $_key = 0;

    /** @var  string */
    private $_currentFile;

    /** @var string[] */
    private $_errors = [];

    /**
     * Iterator constructor.
     *
     * @param string $path
     */
    public function __construct( $path )
    {
        $this->_path = $path;

        $this->_dirHandle = @opendir( $path );

        if ( ! $this->_dirHandle )
            $this->_errors[] = print_r( error_get_last(), true );
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (bool)count( $this->_errors );
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    private function _checkDirHandle( $dirHandle )
    {
        return $dirHandle !== false;
    }

    /**
     * @return string
     */
    public function current()
    {
        return $this->_getFullFilename( $this->_currentFile );
    }

    /**
     * @return void
     */
    public function next()
    {
        if ( ! $this->_checkDirHandle( $this->_dirHandle ) )
            return;

        $this->_key++;
        $this->_currentFile = $this->_readdir( $this->_dirHandle );
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if ( ! $this->_checkDirHandle( $this->_dirHandle ) )
            return false;

        return false !== $this->_currentFile;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        if ( ! $this->_checkDirHandle( $this->_dirHandle ) )
            return;

       rewinddir( $this->_dirHandle );
       $this->_key = 0;

       $this->_currentFile = $this->_readdir( $this->_dirHandle );
    }

    /**
     * @param $dirHandle
     *
     * @return bool|string
     */
    private function _readdir( $dirHandle )
    {
        while( false !== ( $currentFile = readdir( $dirHandle ) ) )
        {
            if ( $currentFile === '.' || $currentFile === '..' )
                continue;

            return $currentFile;
        }

        return false;
    }

    /**
     * @param $currentFile
     *
     * @return string
     */
    private function _getFullFilename( $currentFile )
    {
        return $this->_path . '/' . $currentFile;
    }

}