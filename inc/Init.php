<?php


/**
 * @package MhPlugin
 */

namespace Inc;


final class Init
{

  /**
   * Store all the classes inside an array
   *
   * @return array full list of classes
   */
  public static function get_services()
  {
    return [

      Pages\Dashboard::class,

      Base\Enqueue::class,
      Base\SettingsLinks::class,
      Base\CustomPostTypeController::class,
      Base\TaxonomyController::class,
      Base\WidgetsController::class,
      Base\GalleryController::class,
      Base\TestimonialController::class,
      Base\TemplatesController::class,
      Base\AuthController::class,
      Base\MembershipController::class,
      Base\ChatController::class,
      
      Api\SettingsApi::class,
    ];
  }

  /**
   * Loop  through the classes, initialize them, and call the register() method if it exists.
   *
   * @return void
   */
  public static function register_services()
  {
    foreach (self::get_services() as $class) {
      $service = self::instantiate($class);
      if (method_exists($service, 'register')) {
        $service->register();
      }
    }
  }

  /**
   * Initialize the class
   *
   * @param [class] $class - class from the service array
   * @return class instance - new instance of the class
   */
  private static function instantiate($class)
  {
    $service = new $class(); //this line can be written as $service = new Pages\Admin::class or new Admin();
    return $service;
  }
}
