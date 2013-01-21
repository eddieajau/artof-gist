<?php
/**
 * @package     NewLifeInIT
 * @subpackage  plg_content_artofgist
 * @copyright   Copyright (C) 2005 - 2013 New Life in IT Pty Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later when included with or used in the Joomla CMS.
 * @license     MIT when not included with or used in the Joomla CMS.
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * ArtofGist content plugin for display gists.
 *
 * @package     NewLifeInIT
 * @subpackage  plg_content_artofgist
 * @link        http://gist.github.com
 * @since       1.0
 */
class PlgContentArtofgist extends JPlugin
{
	/**
	 * Listener for the 'onContentPrepare' event.
	 *
	 * This method replaces {{gist
	 *
	 * @param   string   $context  The context for the content passed to the plugin.
	 * @param   object   $article  The content object.  Note $article->text is also available.
	 * @param   object   $params   The content params.
	 * @param   integer  $page     The 'page' number (zero is the first page).
	 *
	 * @return  boolean  True is successful, false some problem occurred.
	 *
	 * @since   1.0
	 */
	public function onContentPrepare($context, $article, $params, $page = 0)
	{
		$gistScript = '<script src="https://gist.github.com/%1$s.js"></script>';

		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		// Simple performance check to determine whether plugin should process further.
		if (strpos($article->text, '{{gist') === false)
		{
			return true;
		}

		$regex = '/\{\{gist(&nbsp;|\s)+(.*?)\}\}/i';

		// $matches[0] is full pattern match, $matches[2] is the gist id.
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		if ($matches)
		{
			foreach ($matches as $match)
			{
				// Sanitise the gist - people do crazy things.
				$gistId = preg_replace('/[^a-z0-9]/i', '', $match[2]);
				$article->text = preg_replace('|' . $match[0] . '|', sprintf($gistScript, $gistId), $article->text, 1);
			}
		}

		return true;
	}
}
