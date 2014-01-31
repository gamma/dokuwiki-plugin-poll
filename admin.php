<?php
/**
 * Poll Plugin: allows to create simple polls
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     i-net software <tools@inetsoftwrae.de>
 * @author     Gerry Weissbach <gweissbach@inetsoftware.de>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'admin.php');
 
/**
 * All DokuWiki plugins to extend the admin function
 * need to inherit from this class
 */
class admin_plugin_poll extends DokuWiki_Admin_Plugin {

    /**
	* Constructor
	*/
    function admin_plugin_siteexport(){
        $this->setupLocale();
    }

    /**
	* return some info
	*/
    function getInfo(){
         return array_merge(confToHash(dirname(__FILE__).'/info.txt'), array(
				'name' => 'Poll Admin Component',
		));
   }
 
    /**
	* return sort order for position in admin menu
	*/
    function getMenuSort() {
        return 300;
    }
 
    /**
	 * handle user request
	 */
    function handle() {
    }
 
    /**
	 * output appropriate html
	 */
    function html() {
		global $conf;
		
		if (!$pollplugin =& plugin_load('syntax', 'poll')) {
			print $this->plugin_locale_xhtml('install-poll');
			return;
		}

	
        print $this->plugin_locale_xhtml('intro');
		
		$votes = $this->__get_votes();
		foreach ( $votes as $poll ) {
			print '<fieldset class="poll">';
			print '<legend>'.$poll['title'].'</legend>';

			print $pollplugin->_pollResults($poll, true);
			
			print '</fieldset>';
		}
		
    }

 	
	function __get_votes() {
		global $conf;
		
		$data = array();
		require_once (DOKU_INC.'inc/search.php');
		search($data, $conf['metadir'], 'search_media', array(), '', 0);
		
		$return = array();
		
		for ( $i=0; $i < count($data); $i++ ) {
			if ( substr( $data[$i]['id'], -4 ) == 'poll' ) {
				$return[] = unserialize(@file_get_contents($conf['metadir'] . '/' . $data[$i]['file']));
			}
		}
		
		return $return;
	}
}
//Setup VIM: ex: et ts=4 enc=utf-8 :
