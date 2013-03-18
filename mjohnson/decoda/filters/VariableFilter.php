<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\FilterAbstract;

/**
 * Provides tags for emails. Will obfuscate emails against bots.
 *
 * @package	mjohnson.decoda.filters
 */
class VariableFilter extends FilterAbstract {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'encrypt' => true
	);

    /**
     * Value of variables
     */
    protected $_values = array();

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'v' => array(
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => '/^[a-z][a-z0-9_]+$/i',
			'escapeAttributes' => false,
			'attributes' => array(
				'default' => self::ALPHA
			)
		),
		'var' => array(
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => '/^[a-z][a-z0-9_]+$/i',
			'escapeAttributes' => false,
			'attributes' => array(
				'default' => self::ALPHA
			)
		)
	);

    /**
     * Set values
     *
     * @access public
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->_values = array_merge($this->_values, $values);
        return $this;
    }

    /**
     * Set a value
     *
     * @access public
     * @param string $value
     */
    public function setValue($name, $value)
    {
        $this->_values[$name] = $value;
        return $this;
    }

    /**
     * Get a value
     *
     * @access public
     * @param string $value
     */
    public function getValue($name)
    {
        if ( array_key_exists($name, $this->_values) )
        {
            return $this->_values[$name];
        }
        return '';
    }

	/**
	 * Parse the variables within tags.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		$var = trim($content);

		// Return an invalid variable name
        if ( !filter_var($var, FILTER_VALIDATE_REGEXP,
                array('options' => array('regexp' => '/^[a-z][a-z0-9_]+$/i'))) )
        {
            $varTag = $tag['tag'];
            return '[' . $varTag . ']' . $content . '[/' . $varTag . ']';
        }

        return $this->getValue($var);
	}
}