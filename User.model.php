<?php
/* Generated by neoan3-cli */

namespace Neoan3\Model;

use Neoan3\Apps\Transformer;

/**
 * Class UserModel
 * @package Neoan3\Model
 * @method static create($array)
 * @method static find($array)
 * @method static get($id)
 * @method static update($array)
 */
class UserModel extends IndexModel
{
    static function __callStatic($name, $arguments)
    {
        return Transformer::addMagic($name,$arguments);
    }

}
