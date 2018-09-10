<?php

namespace MailPoet\Logging;

use MailPoet\Dependencies\Monolog\Processor\IntrospectionProcessor;
use MailPoet\Dependencies\Monolog\Processor\MemoryUsageProcessor;
use MailPoet\Dependencies\Monolog\Processor\WebProcessor;
use MailPoet\Models\Setting;

/**
 * Usage:
 * $logger = Logger::getLogger('logger name');
 * $logger->addDebug('This is a debug message');
 * $logger->addInfo('This is an info');
 * $logger->addWarning('This is a warning');
 * $logger->addError('This is an error message');
 *
 * By default only errors are saved but can be changed in settings to save everything or nothing
 *
 * Name is anything which will be found in the log table.
 *   We can use it for separating different messages like: 'cron', 'rendering', 'export', ...
 *
 * If WP_DEBUG is true additional information will be added to every log message.
 */
class Logger {

  /** @var \MailPoet\Dependencies\Monolog\Logger[] */
  private static $instance = [];

  /**
   * @param string $name
   * @param bool $attach_processors
   *
   * @return \MailPoet\Dependencies\Monolog\Logger
   */
  public static function getLogger($name = 'MailPoet', $attach_processors = WP_DEBUG) {
    if(!isset(self::$instance[$name])) {
      self::$instance[$name] = new \MailPoet\Dependencies\Monolog\Logger($name);

      if($attach_processors) {
        // Adds the line/file/class/method from which the log call originated
        self::$instance[$name]->pushProcessor(new IntrospectionProcessor());
        // Adds the current request URI, request method and client IP to a log record
        self::$instance[$name]->pushProcessor(new WebProcessor());
        // Adds the current memory usage to a log record
        self::$instance[$name]->pushProcessor(new MemoryUsageProcessor());
      }

      self::$instance[$name]->pushHandler(new LogHandler(self::getDefaultLogLevel()));
    }
    return self::$instance[$name];
  }

  private static function getDefaultLogLevel() {
    $settings = Setting::getValue('logging', 'errors');
    switch ($settings) {
      case 'everything':
        return \MailPoet\Dependencies\Monolog\Logger::DEBUG;
      case 'nothing':
        return \MailPoet\Dependencies\Monolog\Logger::EMERGENCY;
      default:
        return \MailPoet\Dependencies\Monolog\Logger::ERROR;
    }
  }

}