<?php

namespace Dekate\ModelLogger;

trait LogModel
{
  // CUD in a string without space
  protected $disableLog = "";

  public function disableLogAction() {
    return str_split($this->disableLog);
  }

  public static function bootLogModel()
  {
    static::created(function ($model) {
      $disableLog = $model->disableLogAction();
      if (!in_array('C', config('model-logger.ignores')) && !in_array('C', $disableLog) && get_class($model) !== 'Dekate\\ModelLogger\\ModelLogger') {
        $key = $model->getKeyName();
        ModelLogger::create([
          "date" => now(),
          "user_id" => auth()->id(),
          "model" => get_class($model),
          "reference" => $key ? $model->$key : null,
          "operation" => 'C',
          "before" => null,
          "after" => json_encode($model),
        ]);
      }
    });

    static::updated(function ($model) {
      $disableLog = $model->disableLogAction();
      if (!in_array('U', config('model-logger.ignores')) && !in_array('U', $disableLog) && get_class($model) !== 'Dekate\\ModelLogger\\ModelLogger') {
        $oldValues = [];
        foreach ($model->getChanges() as $key => $value) {
          $oldValues[$key] = $model->getRawOriginal($key);
        }
        $key = $model->getKeyName();
        ModelLogger::create([
          "date" => now(),
          "user_id" => auth()->id(),
          "model" => get_class($model),
          "reference" => $key ? $model->$key : null,
          "operation" => 'U',
          "before" => json_encode($oldValues),
          "after" => json_encode($model),
        ]);
      }
    });

    static::deleting(function ($model) {
      $disableLog = $model->disableLogAction();
      if (!in_array('D', config('model-logger.ignores')) && !in_array('D', $disableLog) && get_class($model) !== 'Dekate\\ModelLogger\\ModelLogger') {
        $key = $model->getKeyName();
        ModelLogger::create([
          "date" => now(),
          "user_id" => auth()->id(),
          "model" => get_class($model),
          "reference" => $key ? $model->$key : null,
          "operation" => 'D',
          "before" => json_encode($model),
          "after" => null,
        ]);
      }
    });
  }

  public function syncWithRelation($relation, $ids, $detaching = true)
  {
    $old = [$relation => $this->$relation()->get()];
    $changes = $this->$relation()->sync($ids, $detaching);
    if (!in_array('U', config('model-logger.ignores')) && get_class($this) !== 'Dekate\\ModelLogger\\ModelLogger') {
      $new = [$relation => $this->$relation()->get()];
      $key = $this->getKeyName();
      ModelLogger::create([
        "date" => now(),
        "user_id" => auth()->id(),
        "model" => get_class($this),
        "reference" => $key ? $this->$key : null,
        "operation" => 'U',
        "before" => json_encode($old),
        "after" => json_encode($new),
      ]);
    }
    return $changes;
  }
}
