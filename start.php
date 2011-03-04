<?php

function share_init() {
	$actions = dirname(__FILE__) . '/actions';

	elgg_register_action('annotation/share/add', "$actions/annotation/share/add.php");

	elgg_register_plugin_hook_handler('register', 'menu:metadata', 'share_menu_metadata_hook_handler');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'share_menu_river_hook_handler');

	elgg_register_plugin_hook_handler('permissions_check:annotate', 'all', 'share_annotation_permissions_check_handler');
}

function share_annotation_permissions_check_handler($hook, $type, $resutlt, $params) {
	if ($params['annotation_name'] !== 'share') {
		return NULL;
	}

	if ($type !== 'object') {
		return false;
	}

	$object = $params['entity'];

	if ($object->owner_guid == elgg_get_logged_in_user_guid()) {
		return false;
	}

	return $object->access_id == ACCESS_PUBLIC || $object->access_id == ACCESS_LOGGED_IN;
}

function share_menu_metadata_hook_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];

	if ($entity->canAnnotate(0, 'share')) {
		$items[] = array(
			'name' => 'share',
			'href' => "action/annotation/share/add?entity_guid=$entity->guid",
			'is_action' => true,
			'text' => 'Share',
		);

		return $items;
	}
}

function share_menu_river_hook_handler($hook, $type, $items, $params) {
	$item = $params['item'];

	$object = get_entity($item->object_guid);

	if ($object->canAnnotate(0, 'share')) {
		$items[] = array(
			'name' => 'share',
			'href' => "action/annotation/share/add?entity_guid=$object->guid",
			'is_action' => true,
			'text' => 'Share',
		);
	}
}

elgg_register_event_handler('init', 'system', 'share_init');