pico-load-plugins-composer
==========================

Automatically load Pico plugins from the Composer `vendor` directory. This avoids unneccesary cluttering of the `plugins` directory and loads the plugins via a helper plugin based on your config settings.

## Step 1: add this repository to your composer.json
    "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/rbnvrw/pico-load-plugins-composer"
      }
    ],
    "require": {
      "rbnvrw/pico-load-plugins-composer": "dev-master"
    }
    
Save the file and run `composer update`.
    
## Step 2: add a loader file to the plugins directory
For this plugin to work, add a file `pico_load_plugins_composer.php` to the `plugins` directory with the following contents:

    <?php
    
    include_once(ROOT_DIR.'vendor/rbnvrw/pico-load-plugins-composer/pico_load_plugins_composer.php');
    
This will load the plugin, which in turn will load other plugins from the Composer `vendor` directory.

## Step 3: edit the `config.php` file to add plugins
    $config['composer_plugins'] = array(
    	// Path to vendor directory relative to the Pico root directory
    	// Default: vendor
    	'path' => 'vendor',
    	
    	// List of all the plugins you'd like to add
    	'plugins' => array(
    		array(
    		  // Name of the class
    			'name' => 'adv_meta',
    			// Name of the repository
    			'repository' => 'adv-meta',
    			// Author
    			'author' => 'rbnvrw'
    		),
    		array(
    			'name' => 'pico_multilanguage',
    			'repository' => 'pico_multilanguage',
    			'author' => 'rbnvrw'
    		),
    		array(
    			'name' => 'pico_placing',
    			'repository' => 'Pico-Placing',
    			'author' => 'rbnvrw'
    		)
    	)
    );

