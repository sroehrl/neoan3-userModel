<?php
/* Generated by neoan3-cli */

namespace Neoan3\Model;


use Neoan3\Apps\Db;
use Neoan3\Apps\DbException;
use Neoan3\Apps\Ops;

/**
 * Class UserModel
 * @package Neoan3\Model
 */
class UserModel extends IndexModel
{
    /**
     * @param $id
     *
     * @return array
     * @throws DbException
     */
    static function byId($id)
    {
        try {
            $user = Db::easy('user.*', ['id' => '$' . $id]);
        } catch (DbException $e) {
            return [];
        }

        if (!empty($user)) {
            $user = $user[0];
            $user['email'] = parent::first(Db::easy('user_email.email user_email.confirm_date user_email.id user_email.delete_date',
                ['^delete_date', 'user_id' => '$' . $id]));
            if ($user['image_id']) {
                $user['image'] = ImageModel::undeletedById($user['image_id']);
            }
            $tables = ['profile' => false, 'role' => true];
            foreach ($tables as $table => $multiple) {
                $q = Db::easy('user_' . $table . '.*', ['^delete_date', 'user_id' => '$' . $id]);
                if ($multiple) {
                    $user[$table . 's'] = $q;
                } else {
                    $user[$table] = isset($q[0]) ? $q[0] : $q;
                }

            }
        }
        return $user;
    }

    /**
     * @param array $condition
     *
     * @return array
     * @throws DbException
     */
    static function find($condition = [])
    {
        if (!isset($condition['user.delete_date'])) {
            $condition['user.delete_date'] = '';
        }
        try {
            $ids = Db::easy('user.id user_email.email', $condition);
        } catch (DbException $e) {
            $ids = [];
        }
        $users = [];
        foreach ($ids as $id) {
            $users[] = self::byId($id['id']);
        }
        return $users;
    }

    /**
     * Register using encrypted or hashed password
     *
     * @param      $email
     * @param      $password
     * @param bool $hashed
     *
     * @return array
     * @throws DbException
     */
    static function register($email, $password, $hashed = false)
    {
        $id = Db::uuid()->uuid;
        $confirm_code = Ops::hash(28);
        $insertPassword = $hashed ? '=' . password_hash($password, PASSWORD_DEFAULT) : Ops::encrypt($password,
            $password);
        Db::ask('user', ['id' => '$' . $id, 'user_type' => 'user']);
        Db::ask('user_email', ['user_id' => '$' . $id, 'email' => $email, 'confirm_code' => $confirm_code]);
        Db::ask('user_password', ['user_id' => '$' . $id, 'password' => $insertPassword, 'confirm_code' => $confirm_code, 'confirm_date' => '.']);
        return ['model' => self::byId($id), 'confirm_code' => $confirm_code];
    }

}
