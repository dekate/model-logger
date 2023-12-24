<?php

namespace Dekate\ModelLogger;

use Illuminate\Database\Eloquent\Model;

class ModelLogger extends Model {

  protected $fillable = [
    "date",
    "user_id",
    "model",
    "reference",
    "operation",
    "before",
    "after",
  ];

  protected $cast = [
    "date" => "datetime",
  ];
}
