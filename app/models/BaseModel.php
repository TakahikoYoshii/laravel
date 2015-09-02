<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function toArray(array $properties = null)
    {
        if ($properties === null) {
            return parent::toArray();
        }

        $dates = $this->getDates();
        $array = [];
        foreach ($properties as $p) {
            if (in_array($p, $dates)) {
                $array[$p] = (string)$this->asDateTime($this->$p);
            } else {
                $array[$p] = $this->$p;
            }
        }
        return $array;
    }
}
