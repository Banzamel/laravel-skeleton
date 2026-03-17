<?php

namespace FileManager\Enums;

enum StoragesEnum: string
{
    /**
     * @case string 'local' Local storage disk
     */
    case local = 'local';
    /**
     * @case string 'public' Public Local storage disk
     */
    case public = 'public';
    /**
     * @case string 'aws' Amazon S3 storage disk
     */
    case aws = 'aws';
}
