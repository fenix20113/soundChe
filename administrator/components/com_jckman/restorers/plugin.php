<?php
 
 /*
 * Modified for use as the J plugin installer
 * AW
 */


// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

define( 'JCK_PATH', JPATH_PLUGINS.DS.'editors'.DS.'jckeditor' );
define( 'JCK_PLUGINS', JCK_PATH.DS.'plugins' );

require_once( JPATH_COMPONENT .DS. 'tables' .DS. 'plugin.php' );
require_once(CKEDITOR_LIBRARY.DS . 'toolbar.php');

jckimport('helper');


/**
 * Plugin installer
 *
 * @package		Joomla.Framework
 * @subpackage	Installer
 * @since		1.5
 * Renamed JInstallerPlugin to JCKRestorerPlugin
 */
  
  
 
class JCKRestorerPlugin extends JObject
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JInstaller instance]
	 * @return	void
	 * @since	1.5
	 */
	 
	 
	 
	 
	
	function __construct(&$parent)
	{
		$this->parent = $parent;
	}
	
	/**
	 * Custom install method
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 * Minor alteration - see below
	 */
	function install()
	{
		// Get a database connector object
		$db = $this->parent->getDBO();

		// Get the extension manifest object
		
	
					
		$manifest = $this->parent->getManifest();
		$this->manifest =  $manifest;//$manifest->document;
		
		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */
			 

		// Set the component name
		$name = '';
				
		if($this->manifest->name)
		{
			$name = $this->manifest->name;
			$this->set('name', $name->data());
		}
		else
			$this->set('name','');
		
		// Get the component description
		$description = $this->manifest->description;
		if (is_a($description, 'JXMLElement')) {
			$this->parent->set('message', $description->data());
		} else {
			$this->parent->set('message', '' );
		}
		
		$element = $this->manifest->files;

		// Plugin name is specified
		$pname  = (string) $this->manifest->attributes()->plugin;
		

		if (!empty ($pname)) {
			// ^ Use JCK_PLUGINS defined path
			$this->parent->setPath('extension_root', JCK_PLUGINS .DS. $pname);
		} else {
			$this->parent->abort('Extension Install: '.JText::_('COM_JCKMAN_ADAPTER_NO_PLUGIN_SPECIFIED'));
			return false;
		}
		
		
		if ((string)$manifest->scriptfile)
		{
			$manifestScript = (string)$manifest->scriptfile;
			$manifestScriptFile = $this->parent->getPath('source').DS.$manifestScript;
			if (is_file($manifestScriptFile))
			{
				// load the file
				include_once $manifestScriptFile;
			}
			// Set the class name
			$classname = 'plgJCK'.$pname.'InstallerScript';
			
			if (class_exists($classname))
			{
				// create a new instance
				$this->parent->manifestClass = new $classname($this);
				// and set this so we can copy it later
				$this->set('manifest_script', $manifestScript);
				// Note: if we don't find the class, don't bother to copy the file
			}
				
			// run preflight if possible 
			ob_start();
			ob_implicit_flush(false);
			if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'preflight'))
			{
				if($this->parent->manifestClass->preflight('install', $this) === false)
				{
					// Install failed, rollback changes
					$this->parent->abort(JText::_('COM_JCKMAN_ADAPTER_CUSTOM_ABORT'));
					return false;
				}
			}
			ob_end_clean();
		}
		
		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the extension directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::sprintf('COM_JCKMAN_ADAPTER_PLUGIN_FAILED_CREATE_DIRECTORY',$this->parent->getPath('extension_root')));
				return false;
			}
		}

		/*
		 * If we created the extension directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}
	
		// Parse optional tags -- language files for plugins
		$this->parent->parseLanguages($this->manifest->languages, 0);
		
		// If there is an install file, lets copy it.
		$installScriptElement = $this->manifest->installfile;
		if (is_a($installScriptElement, 'JXMLElement')) {
			// Make sure it hasn't already been copied (this would be an error in the xml install file)
			if (!file_exists($this->parent->getPath('extension_root').DS.$installScriptElement->data()))
			{
				$path['src']	= $this->parent->getPath('source').DS.$installScriptElement->data();
				$path['dest']	= $this->parent->getPath('extension_root').DS.$installScriptElement->data();
				if (!$this->parent->copyFiles(array ($path))) {
					// Install failed, rollback changes
					$this->parent->abort(JText::_('COM_JCKMAN_ADAPTER_COMPONENT').' '.JText::_('COM_JCKMAN_ADAPTER_INSTALL').': '.JText::_('COM_JCKMAN_ADAPTER_COULD_NOT_COPY_INSTALL_FILE'));
					return false;
				}
			}
			$this->set('install.script', $installScriptElement->data());
		}

		// If there is an uninstall file, lets copy it.
		$uninstallScriptElement = $this->manifest->uninstallfile;
		if (is_a($uninstallScriptElement, 'JXMLElement')) {
			// Make sure it hasn't already been copied (this would be an error in the xml install file)
			if (!file_exists($this->parent->getPath('extension_root').DS.$uninstallScriptElement->data()))
			{
				$path['src']	= $this->parent->getPath('source').DS.$uninstallScriptElpement->data();
				$path['dest']	= $this->parent->getPath('extension_root').DS.$uninstallScriptElement->data();
				if (!$this->parent->copyFiles(array ($path))) {
					// Install failed, rollback changes
					$this->parent->abort(JText::_('COM_JCKMAN_ADAPTER_COMPONENT').' '.JText::_('COM_JCKMAN_ADAPTER_INSTALL').': '.JText::_('COM_JCKMAN_ADAPTER_COULD_NOT_COPY_INSTALL_FILE'));
					return false;
				}
			}
		}

			/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Check to see if a plugin by the same name is already installed
		// ^ Altered db query for #__JCK_PLUGINS
		$query = 'SELECT `id`' .
				' FROM `#__jckplugins`' .
				' WHERE name = '.$db->Quote($pname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort('Plugin Install: '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a module already installed with the same name?
		if ($id) {
			
    		$row = JTable::getInstance('plugin', 'JCKTable');
			$row->load($id);
			
		} else {
			
			$icon 		= $this->manifest->icon;
	
			// ^ Changes to plugin parameters. Use JCK Plugins Table class. 
			$row = JTable::getInstance('plugin', 'JCKTable');
			$row->title 			= $this->get('name');
			$row->name			= $pname;
			$row->type 			= 'plugin';
			$row->row	 		= 4;
			$row->published 		= 1;
			$row->editable 		= 1;
			$row->icon 			= ($icon ? $icon->data() : '');
			$row->iscore 			= 0;
			$row->params 		= $this->parent->getParams();
				
			
			if($this->manifest->attributes()->parent)
			{
				$parentName = (string) $this->manifest->attributes()->parent;
				$row->setParent($parentName);
			}
				

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort('Plugin Install: '.$db->stderr(true));
				return false;
			}
						
			// Since we have created a plugin item, we add it to the installation step stack
			// so that if we have to rollback the changes we can undo it.
			$this->parent->pushStep(array ('type' => 'plugin', 'id' => $row->id));
		}
		
		
		
		/* -------------------------------------------------------------------------------------------
		 * update editor plugin config file    AW 
		 * -------------------------------------------------------------------------------------------
		*/ 
		  
		  $config = JCKHelper::getEditorPluginConfig();
		  
		 if($config->getValue($pname,false) === false)
		 {
			$config->setValue($pname,1);
            
			$cfgFile = CKEDITOR_LIBRARY.DS . 'plugins' . DS . 'toolbarplugins.php'; 
    		  
           
			// Get the config registry in PHP class format and write it to configuation.php
			if (!JFile::write($cfgFile, $config->toString('PHP',array('class' => 'JCKToolbarPlugins extends JCKPlugins')))) { 	  
						
				JError::raiseWarning(100,'Failed to publish '. $pname. ' jckeditor plugin');
			}
           
			$config = JCKHelper::getEditorPluginConfig();  			
  		 }
		 
 	
		/**
		 * ---------------------------------------------------------------------------------------------
		 * Custom Installation Script Section
		 * ---------------------------------------------------------------------------------------------
		 */

		/*
		 * If we have an install script, lets include it, execute the custom
		 * install method, and append the return value from the custom install
		 * method to the installation message.
		 */
		if ($this->get('install.script')) {
			if (is_file($this->parent->getPath('extension_root').DS.$this->get('install.script'))) {
				ob_start();
				ob_implicit_flush(false);
				require_once ($this->parent->getPath('extension_root').DS.$this->get('install.script'));
				if (function_exists('com_install')) {
					if (com_install() === false) {
						$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('COM_JCKMAN_ADAPTER_PLUGIN_CUSTOM_INSTALL_ROUTINE_FAILURE'));
						return false;
					}
				}
				$msg = ob_get_contents();
				ob_end_clean();
				if ($msg != '') {
					$this->parent->set('extension.message', $msg);
				}
			}
		}
		/**
		 * ---------------------------------------------------------------------------------------------
		 * Finalization and Cleanup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest(-1)) {
			// Install failed, rollback changes
			$this->parent->abort('Plugin Install: '.JText::_('COM_JCKMAN_ADAPTER_NOT_COPY_SETUP_FILE'));
			return false;
		}
		
		// And now we run the postflight
		ob_start();
		ob_implicit_flush(false);
		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'postflight'))
		{
			$this->parent->manifestClass->postflight('install', $this);
		}
		ob_end_clean();
	
		return true;
	}

	/**
	 * Custom rollback method
	 * 	- Roll back the plugin item
	 *
	 * @access	public
	 * @param	array	$arg	Installation step to rollback
	 * @return	boolean	True on success
	 * @since	1.5
	 * Minor changes to the db query
	 */
	function _rollback_plugin($arg)
	{
		// Get database connector object
		$db = $this->parent->getDBO();
		
		
		// Remove the entry from the #__JCK_PLUGINS table
			
		$query = 'DELETE' .
				' FROM `#__jckplugins`' .
				' WHERE id='.(int)$arg['id'];
		$db->setQuery($query);
		return ($db->query() !== false);
	}
}