<?php

namespace MailPoet\Models;

use MailPoet\WP\Functions as WPFunctions;

if(!defined('ABSPATH')) exit;

class ScheduledTask extends Model {
  public static $_table = MP_SCHEDULED_TASKS_TABLE;
  const STATUS_COMPLETED = 'completed';
  const STATUS_SCHEDULED = 'scheduled';
  const STATUS_PAUSED = 'paused';
  const PRIORITY_HIGH = 1;
  const PRIORITY_MEDIUM = 5;
  const PRIORITY_LOW = 10;

  function subscribers() {
    return $this->hasManyThrough(
      __NAMESPACE__.'\Subscriber',
      __NAMESPACE__.'\ScheduledTaskSubscriber',
      'task_id',
      'subscriber_id'
    );
  }

  function pause() {
    $this->set('status', self::STATUS_PAUSED);
    $this->save();
    return ($this->getErrors() === false && $this->id() > 0);
  }

  function resume() {
    $this->setExpr('status', 'NULL');
    $this->save();
    return ($this->getErrors() === false && $this->id() > 0);
  }

  function complete() {
    $this->processed_at = WPFunctions::currentTime('mysql');
    $this->set('status', self::STATUS_COMPLETED);
    $this->save();
    return ($this->getErrors() === false && $this->id() > 0);
  }

  function save() {
    // set the default priority to medium
    if(!$this->priority) {
      $this->priority = self::PRIORITY_MEDIUM;
    }
    parent::save();
    return $this;
  }
}
