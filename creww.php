<?php


/*
Plugin Name: Creww
Plugin URI: https://creww.io
Description: Creww is a platform where marketers and HR professionals can capitalize their team members’ reach without nagging them. And, team members earn Creww Coins which they can convert into cash, gift cards and experiences. With this plugin users will be able to add content directly to their Creww account and get analytics of the posts.
Version: 1.0.0
License: GPL2

Creww is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.	
 
Creww is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Creww. If not, see <http://www.gnu.org/licenses/>.

*/

if (! defined('ABSPATH'))
{
	die;
}


add_action( 'admin_menu', 'creww_menu' );


function creww_menu() {
    add_options_page( 'Creww Options', 'Creww Details', 'manage_options', 'creww', 'creww_options' );
}


function creww_options() {

	require_once plugin_dir_path(__FILE__).'templates/api_key.php';	


}

function register_settings() {  
        register_setting('settings-group','api_key_creww');
        wp_enqueue_script('jquery');

        wp_register_script('prefix_bootstrap', plugins_url('Creww/assets/bootstrap.min.js'));
	    wp_enqueue_script('prefix_bootstrap');

	   

    	// CSS
    	wp_register_style('prefix_bootstrap_css', plugins_url('Creww/assets/bootstrap.min.css'));
    	wp_enqueue_style('prefix_bootstrap_css');

        
    }

add_action( 'admin_init', 'register_settings' );

class creww_plugin
{

	public $plugin;

	function __construct()
	{
		
		$this->plugin = plugin_basename(__FILE__);

	}

	

	function register()
	{
		add_action('admin_menu', array($this, 'add_admin_pages'));

		

		add_filter( 'manage_posts_columns' , array($this, 'add_sticky_column') ); // to add a column in posts

		

		add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));

		add_action( 'admin_init', array($this, 'register_settings_internal') );
	}

	public function register_settings_internal()
	{
		wp_register_script('prefix_morris', plugins_url('Creww/assets/morris.min.js'));
		wp_enqueue_script('prefix_morris');

		wp_register_script('prefix_raphael', plugins_url('Creww/assets/raphael-min.js'));
		wp_enqueue_script('prefix_raphael');

		wp_register_script('prefix_semantic', plugins_url('Creww/assets/semantic.min.js'));
		wp_enqueue_script('prefix_semantic');

		wp_register_style('prefix_morris_css', plugins_url('Creww/assets/morris.css'));
    	wp_enqueue_style('prefix_morris_css');

    	wp_register_style('prefix_semantic_css', plugins_url('Creww/assets/semantic.min.css'));
    	wp_enqueue_style('prefix_semantic_css');
	}

	public function settings_link( $links )
	{
		//test
		$settings_link = '<a href="options-general.php?page=creww">Settings</a>';
		array_push($links, $settings_link);
		return $links;
	}

	public function add_admin_pages()
	{
		add_menu_page('Dashboard', 'Creww', 'manage_options', 'creww_dashboard', array($this, 'dashboard_index'), 'dashicons-store', 110 ); 
		add_submenu_page( 'creww_dashboard','Creww Plugin', 'Posts', 'manage_options', 'creww_plugin', array($this, 'admin_index'));

		
	}

	

	public function dashboard_index()
	{
		require_once plugin_dir_path(__FILE__).'templates/dashboard.php';	
	}

	public function admin_index()
	{
		require_once plugin_dir_path(__FILE__).'templates/posts.php';
	}

	function activate()
	{

		flush_rewrite_rules(); 
	}

	function deactivate()
	{
		flush_rewrite_rules(); 
		
	}

	function uninstall()
	{

	}

	
}

if (class_exists ('creww_plugin'))
{

	$creww_plugin = new creww_plugin();
	$creww_plugin->register();

}



register_activation_hook(__FILE__, array($creww_plugin, 'activate'));

register_deactivation_hook(__FILE__, array($creww_plugin, 'deactivate'));



?>