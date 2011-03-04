<?php

$entity_guid = get_input('entity_guid');

$entity = get_entity($entity_guid);

if (!$entity) {
	register_error("We could not find the entity specified");
	forward(REFERER);
}

if (!$entity->canAnnotate(0, 'share')) {
	register_error("You do not have permission to do that");
	forward(REFERER);
}

if ($entity->annotate("share", 1)) {
	//@todo fix this: river/entity/share???
	add_to_river('river/entity/share', 'share', elgg_get_logged_in_user_guid(), $entity->guid);
	forward(REFERER);
} else {
	register_error("Unable to share item");
}