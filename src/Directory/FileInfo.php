<?php
/**
 * Created by PhpStorm.
 * User: igorek
 * Date: 12.04.17
 * Time: 3:23
 */

namespace Directory;


class FileInfo
{
    const S_IFMT   = 0170000;  /* type of file */
    const S_IFIFO  = 0010000;  /* named pipe (fifo) */
    const S_IFCHR  = 0020000;  /* character special */
    const S_IFDIR  = 0040000;  /* directory */
    const S_IFBLK  = 0060000;  /* block special */
    const S_IFREG  = 0100000;  /* regular */
    const S_IFLNK  = 0120000;  /* symbolic link */
    const S_IFSOCK = 0140000;  /* socket */
    const S_IFWHT  = 0160000;  /* whiteout */

    /** @var string */
    private $_errors = [];

    /**
     * @param string $fileName
     *
     * @return null|MetaFile
     */
    public function Get( $fileName )
    {
        $stat = @stat( $fileName );
        if ( $stat === false )
        {
            $this->_errors[] = print_r( error_get_last(), true );
            return null;
        }

        $file = new MetaFile();
        $reflection = new \ReflectionClass( $file );
        foreach ( $reflection->getProperties( \ReflectionProperty::IS_PUBLIC ) as $prop )
        {
            if ( isset( $stat[ $prop->getName() ] ) )
                $file->{ $prop->getName() } = $stat[ $prop->getName() ];
        }

        return $file;
    }

    public function isDirectory( MetaFile $metaFile )
    {
        return (bool) ( $metaFile->mode & self::S_IFDIR );
    }

    public function isFile( MetaFile $metaFile )
    {
        return (bool) ( $metaFile->mode & self::S_IFREG );
    }

    public function isSymlink( MetaFile $metaFile )
    {
        return (bool) ( $metaFile->mode & self::S_IFLNK );
    }

    public function getErrors()
    {
        return print_r( $this->_errors, true );
    }

    public function FlushErrors()
    {
        $this->_errors = [];
    }
}