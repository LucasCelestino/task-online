<?php

namespace App\Models;

class Task extends Model
{
    protected static $safe = ['id', 'created_at', 'updated_at'];

    private static $entity = 'tb_tasks';

    public function bootstrap(int $user_id, String $title)
    {   
        $this->user_id = $user_id;
        $this->title = $title;
        $this->completed = 0;
        return $this;
    }

    public function find($user_id, $columns = '*')
    {
        $load = $this->read("SELECT {$columns} FROM ".self::$entity." WHERE user_id = :user_id AND completed = 0", "user_id={$user_id}");
        
        if($this->fail() || !$load->rowCount())
        {
            return null;
        }

        return $load->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function load($id,$user_id, $columns = '*')
    {
        $load = $this->read("SELECT {$columns} FROM ".self::$entity." WHERE id = :id AND user_id = :user_id AND completed = 0", "id={$id}&user_id={$user_id}");
        
        if($this->fail() || !$load->rowCount())
        {
            return null;
        }

        return $load->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function loadCompleted($id,$user_id, $columns = '*')
    {
        $load = $this->read("SELECT {$columns} FROM ".self::$entity." WHERE id = :id AND user_id = :user_id AND completed = 1", "id={$id}&user_id={$user_id}");
        
        if($this->fail() || !$load->rowCount())
        {
            return null;
        }

        return $load->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function findCompleted($user_id, $columns = '*')
    {
        $load = $this->read("SELECT {$columns} FROM ".self::$entity." WHERE user_id = :user_id AND completed = 1", "user_id={$user_id}");
        
        if($this->fail() || !$load->rowCount())
        {
            return null;
        }

        return $load->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function save()
    {
        if(!$this->required())
        {
            return null;
        }

        // UPDATE TASK
        if(!empty($this->id))
        {
            $taskId = $this->id;

            if($this->update(self::$entity, $this->safe(), "id=:id", "id={$taskId}"))
            {
                $this->data = $this->read("SELECT * FROM ".self::$entity." WHERE id = :id", "id={$taskId}")->fetchObject(__CLASS__);
                return $this;
            }

            if($this->fail())
            {
                return null;
            }
        }

        $taskId = $this->create(self::$entity, $this->safe());

        $this->data = $this->read("SELECT * FROM ".self::$entity." WHERE id = :id", "id={$taskId}")->fetchObject(__CLASS__);
        return $this;

    }

    public function destroy()
    {
        $destroy = $this->delete(self::$entity, "id = :id", ['id'=>$this->id]);

        if($this->fail() || $destroy == null)
        {
            return null;
        }

        return true;
    }

    public function required()
    {
        if(!$this->title)
        {
            return false;
        }

        return true;
    }
}
