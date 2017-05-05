<?php
/**
 * Created by PhpStorm.
 * User: igorek
 * Date: 12.04.17
 * Time: 2:16
 */

namespace Directory;

class MetaFile
{
    /** @var string номер устройства */
    public $dev;
    /** @var string номер  inode * */
    public $ino;
    /** @var string режим защиты inode */
    public $mode;
    /** @var string количество ссылок */
    public $nlink;
    /** @var string userid владельца * */
    public $uid;
    /** @var string groupid владельца * */
    public $gid;
    /** @var string тип устройства, если устройство inode */
    public $rdev;
    /** @var string размер в байтах */
    public $size;
    /** @var string время последнего доступа (временная метка Unix) */
    public $atime;
    /** @var string время последней модификации (временная метка Unix) */
    public $mtime;
    /** @var string время последнего изменения inode (временная метка Unix) */
    public $ctime;
    /** @var string размер блока ввода-вывода файловой системы ** */
    public $blksize;
    /** @var string количество используемых 512-байтных блоков ** */
    public $blocks;

}