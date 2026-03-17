<?php

namespace FileManager\Enums;

enum EntityTypeEnum: string
{
    /**
     * @case string 'file' Type File
     */
    case file = 'file';
    /**
     * @case string 'dir' Type Directory
     */
    case dir = 'dir';
}
