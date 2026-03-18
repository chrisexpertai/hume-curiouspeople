<?php

// Initialize the filter globals.
require( dirname( __FILE__ ) . '/pikseraHook.php' );

/** @var pikseraHook[] $piksera_filter */
global $piksera_filter, $piksera_actions, $piksera_current_filter;

if ( $piksera_filter ) {
	$piksera_filter = pikseraHook::build_preinitialized_hooks( $piksera_filter );
} else {
	$piksera_filter = array();
}

if ( ! isset( $piksera_actions ) ) {
	$piksera_actions = array();
}

if ( ! isset( $piksera_current_filter ) ) {
	$piksera_current_filter = array();
}

/**
 * @param $tag
 * @param $function_to_add
 * @param int $priority
 * @param int $accepted_args
 * @return bool
 */
function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	global $piksera_filter;
	if ( ! isset( $piksera_filter[ $tag ] ) ) {
		$piksera_filter[ $tag ] = new pikseraHook();
	}
	$piksera_filter[ $tag ]->add_filter( $tag, $function_to_add, $priority, $accepted_args );
	return true;
}

/**
 * @param $tag
 * @param bool $function_to_check
 * @return bool|int
 */
function has_filter( $tag, $function_to_check = false ) {
	global $piksera_filter;

	if ( ! isset( $piksera_filter[ $tag ] ) ) {
		return false;
	}

	return $piksera_filter[ $tag ]->has_filter( $tag, $function_to_check );
}

/**
 * @param $tag
 * @param $value
 * @return mixed
 */
function apply_filters( $tag, $value ) {
	global $piksera_filter, $piksera_current_filter;

	$args = func_get_args();

	// Do 'all' actions first.
	if ( isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
		_piksera_call_all_hook( $args );
	}

	if ( ! isset( $piksera_filter[ $tag ] ) ) {
		if ( isset( $piksera_filter['all'] ) ) {
			array_pop( $piksera_current_filter );
		}
		return $value;
	}

	if ( ! isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
	}

	// Don't pass the tag name to pikseraHook.
	array_shift( $args );

	$filtered = $piksera_filter[ $tag ]->apply_filters( $value, $args );

	array_pop( $piksera_current_filter );

	return $filtered;
}

/**
 * @param $tag
 * @param $args
 * @return mixed
 */
function apply_filters_ref_array( $tag, $args ) {
	global $piksera_filter, $piksera_current_filter;

	// Do 'all' actions first
	if ( isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
		$all_args            = func_get_args();
		_piksera_call_all_hook( $all_args );
	}

	if ( ! isset( $piksera_filter[ $tag ] ) ) {
		if ( isset( $piksera_filter['all'] ) ) {
			array_pop( $piksera_current_filter );
		}
		return $args[0];
	}

	if ( ! isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
	}

	$filtered = $piksera_filter[ $tag ]->apply_filters( $args[0], $args );

	array_pop( $piksera_current_filter );

	return $filtered;
}

/**
 * @param $tag
 * @param $function_to_remove
 * @param int $priority
 * @return bool
 */
function remove_filter( $tag, $function_to_remove, $priority = 10 ) {
	global $piksera_filter;

	$r = false;
	if ( isset( $piksera_filter[ $tag ] ) ) {
		$r = $piksera_filter[ $tag ]->remove_filter( $tag, $function_to_remove, $priority );
		if ( ! $piksera_filter[ $tag ]->callbacks ) {
			unset( $piksera_filter[ $tag ] );
		}
	}

	return $r;
}

/**
 * @param $tag
 * @param bool $priority
 * @return bool
 */
function remove_all_filters( $tag, $priority = false ) {
	global $piksera_filter;

	if ( isset( $piksera_filter[ $tag ] ) ) {
		$piksera_filter[ $tag ]->remove_all_filters( $priority );
		if ( ! $piksera_filter[ $tag ]->has_filters() ) {
			unset( $piksera_filter[ $tag ] );
		}
	}

	return true;
}

/**
 * @return mixed
 */
function current_filter() {
	global $piksera_current_filter;
	return end( $piksera_current_filter );
}

/**
 * @return string
 */
function current_action() {
	return current_filter();
}

/**
 * @param null $filter
 * @return bool
 */
function doing_filter( $filter = null ) {
	global $piksera_current_filter;

	if ( null === $filter ) {
		return ! empty( $piksera_current_filter );
	}

	return in_array( $filter, $piksera_current_filter );
}

/**
 * @param null $action
 * @return bool
 */
function doing_action( $action = null ) {
	return doing_filter( $action );
}

/**
 * @param $tag
 * @param $function_to_add
 * @param int $priority
 * @param int $accepted_args
 * @return true
 */
function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	return add_filter( $tag, $function_to_add, $priority, $accepted_args );
}

/**
 * @param $tag
 * @param mixed ...$arg
 */
function do_action( $tag, ...$arg ) {
	global $piksera_filter, $piksera_actions, $piksera_current_filter;

	if ( ! isset( $piksera_actions[ $tag ] ) ) {
		$piksera_actions[ $tag ] = 1;
	} else {
		++$piksera_actions[ $tag ];
	}

	// Do 'all' actions first
	if ( isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
		$all_args            = func_get_args();
		_piksera_call_all_hook( $all_args );
	}

	if ( ! isset( $piksera_filter[ $tag ] ) ) {
		if ( isset( $piksera_filter['all'] ) ) {
			array_pop( $piksera_current_filter );
		}
		return;
	}

	if ( ! isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
	}

	if ( empty( $arg ) ) {
		$arg[] = '';
	} elseif ( is_array( $arg[0] ) && 1 === count( $arg[0] ) && isset( $arg[0][0] ) && is_object( $arg[0][0] ) ) {
		// Backward compatibility for PHP4-style passing of `array( &$this )` as action `$arg`.
		$arg[0] = $arg[0][0];
	}

	$piksera_filter[ $tag ]->do_action( $arg );

	array_pop( $piksera_current_filter );
}

/**
 * @param $tag
 * @return int
 */
function did_action( $tag ) {
	global $piksera_actions;

	if ( ! isset( $piksera_actions[ $tag ] ) ) {
		return 0;
	}

	return $piksera_actions[ $tag ];
}

/**
 * @param $tag
 * @param $args
 */
function do_action_ref_array( $tag, $args ) {
	global $piksera_filter, $piksera_actions, $piksera_current_filter;

	if ( ! isset( $piksera_actions[ $tag ] ) ) {
		$piksera_actions[ $tag ] = 1;
	} else {
		++$piksera_actions[ $tag ];
	}

	// Do 'all' actions first
	if ( isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
		$all_args            = func_get_args();
		_piksera_call_all_hook( $all_args );
	}

	if ( ! isset( $piksera_filter[ $tag ] ) ) {
		if ( isset( $piksera_filter['all'] ) ) {
			array_pop( $piksera_current_filter );
		}
		return;
	}

	if ( ! isset( $piksera_filter['all'] ) ) {
		$piksera_current_filter[] = $tag;
	}

	$piksera_filter[ $tag ]->do_action( $args );

	array_pop( $piksera_current_filter );
}

/**
 * @param $tag
 * @param bool $function_to_check
 * @return false|int
 */
function has_action( $tag, $function_to_check = false ) {
	return has_filter( $tag, $function_to_check );
}

/**
 * @param $tag
 * @param $function_to_remove
 * @param int $priority
 * @return bool
 */
function remove_action( $tag, $function_to_remove, $priority = 10 ) {
	return remove_filter( $tag, $function_to_remove, $priority );
}

/**
 * @param $tag
 * @param bool $priority
 * @return true
 */
function remove_all_actions( $tag, $priority = false ) {
	return remove_all_filters( $tag, $priority );
}

/**
 * @param $args
 */
function _piksera_call_all_hook( $args ) {
	global $piksera_filter;

	$piksera_filter['all']->do_all_hook( $args );
}

function _piksera_filter_build_unique_id( $tag, $function, $priority ) {
	global $piksera_filter;
	static $filter_id_count = 0;

	if ( is_string( $function ) ) {
		return $function;
	}

	if ( is_object( $function ) ) {
		// Closures are currently implemented as objects
		$function = array( $function, '' );
	} else {
		$function = (array) $function;
	}

	if ( is_object( $function[0] ) ) {
		// Object Class Calling
		return spl_object_hash( $function[0] ) . $function[1];
	} elseif ( is_string( $function[0] ) ) {
		// Static Calling
		return $function[0] . '::' . $function[1];
	}
}
