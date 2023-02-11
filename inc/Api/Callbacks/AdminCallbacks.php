<?php

/**
 * @package MhPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
  public function adminDashboard()
  {
    return require_once("$this->plugin_path/templates/admin/admin.php");
  }

  public function adminCPT()
  {
    return require_once("$this->plugin_path/templates/admin/subpages/cpt.php");
  }
  public function adminTaxonomies()
  {
    return require_once("$this->plugin_path/templates/admin/subpages/taxonomies.php");
  }
  public function adminWidgets()
  {
    return require_once("$this->plugin_path/templates/admin/subpages/widgets.php");
  }

  public function adminGallery()
	{
		echo "<h1>Gallery Manager</h1>";
	}

	public function adminTestimonial()
	{
		echo "<h1>Testimonial Manager</h1>";
	}

	public function adminTemplates()
	{
		echo "<h1>Templates Manager</h1>";
	}

	public function adminAuth()
	{
		echo "<h1>Auth Manager</h1>";
	}

	public function adminMembership()
	{
		echo "<h1>Membership Manager</h1>";
	}

	public function adminChat()
	{
		echo "<h1>Chat Manager</h1>";
	}


  public function storeDashboard()
  {
    return require_once("$this->plugin_path/templates/store/index.php");
  }

  public function storeCPT()
  {
    return require_once("$this->plugin_path/templates/store/subpages/cpt.php");
  }

  public function mhOptionsGroup($input)
  {
    /**
     * validate input here before retunrning 
     */

    return $input;
  }

  public function mhAdminSection()
  {
    echo 'Check this beautiful section';
  }

  public function mhTextExample()
  {
    $value = esc_attr(get_option('text_example'));
    echo '<input type="text" class="regular-text" , name="text_example" value="' . $value . '" placeholder="Write something here..."/>';
  }
  public function mhAddFirstName()
  {
    $value = esc_attr(get_option('first_name'));
    echo '<input type="text" class="regular-text" name="text_example" value="' . $value . '" placeholder="Your first name..."/>';
  }
}
