<?php

/**
 * Load Pico plugins from composer
 *
 * @author Ruben Verweij
 * @link http://rubenverweij.nl
 * @license http://opensource.org/licenses/MIT
 */
class Pico_Load_Plugins_Composer {

	private $oPlugins;
	private $sRootDir;
	
	public function __construct()
	{
		if(defined('ROOT_DIR')){
			$this->sRootDir = ROOT_DIR;
		}else{
			$this->sRootDir = realpath(dirname(__FILE__)) .'/../';
		}
	}

	public function config_loaded(&$settings)
	{	
		if(empty($settings['composer_plugins']['path'])){
			$settings['composer_plugins']['path'] = 'vendor';
		}
	
		if(!empty($settings['composer_plugins']) 
			&& !empty($settings['composer_plugins']['plugins'])){
			// Load the plugins
			$this->loadPlugins($settings['composer_plugins']['plugins'], $settings['composer_plugins']['path']);
			
			// Run config_loaded hook
			$this->run_hooks('config_loaded', array(&$settings));
		}
	}
	
	/**
	 * Load the plugins
	 */
	protected function loadPlugins($aPlugins, $sPath)
	{
		$this->plugins = array();
		if(!empty($aPlugins)){
			foreach($aPlugins as $aPlugin){
				// Include the plugin
				include_once($this->sRootDir.$sPath.'/'.$aPlugin['author'].'/'.$aPlugin['repository'].'/'.$aPlugin['name'].'.php');
				
				if(class_exists($aPlugin['name'])){
					$oObj = new $aPlugin['name'];
					$this->oPlugins[] = $oObj;
				}
			}
		}
		
		$this->run_hooks('plugins_loaded');
	}
	
	/**
	 * Processes any hooks for custom plugins and runs them
	 *
	 * @param string $hook_id the ID of the hook
	 * @param array $args optional arguments
	 */
	protected function run_hooks($hook_id, $args = array())
	{
		if(!empty($this->oPlugins)){
			foreach($this->oPlugins as $oPlugin){
				if(is_callable(array($oPlugin, $hook_id))){
					call_user_func_array(array($oPlugin, $hook_id), $args);
				}
			}
		}
	}
	
	/**
	* All hooks
	*/
	
	public function request_url(&$url)
	{
		$this->run_hooks('request_url', array(&$url));
	}
	
	public function before_load_content(&$file)
	{
		$this->run_hooks('before_load_content', array(&$file));
	}
	
	public function after_load_content(&$file, &$content)
	{
		$this->run_hooks('after_load_content', array(&$file, &$content));
	}
	
	public function before_404_load_content(&$file)
	{
		$this->run_hooks('before_404_load_content', array(&$file));
	}
	
	public function after_404_load_content(&$file, &$content)
	{
		$this->run_hooks('after_404_load_content', array(&$file, &$content));
	}
	
	public function before_read_file_meta(&$headers)
	{
		$this->run_hooks('before_read_file_meta', array(&$headers));
	}
	
	public function file_meta(&$meta)
	{
		$this->run_hooks('file_meta', array(&$meta));
	}

	public function before_parse_content(&$content)
	{
		$this->run_hooks('before_parse_content', array(&$content));
	}
	
	public function after_parse_content(&$content)
	{
		$this->run_hooks('after_parse_content', array(&$content));
	}
	
	public function get_page_data(&$data, $page_meta)
	{
		$this->run_hooks('get_page_data', array(&$data, $page_meta));
	}
	
	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page)
	{
		$this->run_hooks('get_pages', array(&$pages, &$current_page, &$prev_page, &$next_page));
	}
	
	public function before_twig_register()
	{
		$this->run_hooks('before_twig_register', array());
	}
	
	public function before_render(&$twig_vars, &$twig, &$template)
	{
		$this->run_hooks('before_render', array(&$twig_vars, &$twig, &$template));
	}
	
	public function after_render(&$output)
	{
		$this->run_hooks('after_render', array(&$output));
	}
	
}
