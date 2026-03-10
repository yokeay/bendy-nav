<?php


namespace app\model;


use DOMDocument;
use Exception;
use think\Model;

class NoteModel extends Model
{
    protected $name = 'note';
    protected $pk = 'id';

    function getTextAttr($value): string
    {
        return modifyImageUrls($value, request()->root(true));
    }

    function setTextAttr($htmlContent)
    {
        return removeImagesUrls($htmlContent, request()->root(true));
    }

}