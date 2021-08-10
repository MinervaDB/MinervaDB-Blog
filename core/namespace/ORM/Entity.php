<?php
namespace ORM;

abstract class Entity implements EntityInterface {
	protected $id;
	protected $time_insert;
	protected $time_update;

	# Modified attributes
	private $_modified = [];

	#===============================================================================
	# Get attribute
	#===============================================================================
	public function get(string $attribute) {
		return $this->{$attribute} ?? NULL;
	}

	#===============================================================================
	# Set attribute
	#===============================================================================
	public function set(string $attribute, $value) {
		if($this->{$attribute} !== $value) {
			!in_array($attribute, $this->_modified) &&
				array_push($this->_modified, $attribute);
		}

		return $this->{$attribute} = $value;
	}

	#===============================================================================
	# Return ID
	#===============================================================================
	final public function getID(): int {
		return $this->id;
	}

	#===============================================================================
	# Get all attributes
	#===============================================================================
	public function getAll(array $exclude = []): array {
		$attributes = get_object_vars($this);

		return array_filter($attributes, function($attribute) use($exclude) {
			return !in_array($attribute, $exclude);
		}, ARRAY_FILTER_USE_KEY);
	}

	#===============================================================================
	# Get an array of modified attribute keys
	#===============================================================================
	public function getModifiedKeys(): array {
		return $this->_modified;
	}
}
