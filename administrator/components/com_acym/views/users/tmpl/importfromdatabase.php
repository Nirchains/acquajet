<?php
defined('_JEXEC') or die('Restricted access');
?><div id="acym__users__import__from_database" class="grid-x padding-vertical-2 padding-horizontal-2">
	<div class="cell large-3"></div>
    <?php
    $userFields = acym_getColumns('user');
    ?>
	<div class="cell large-6 grid-x">

		<label for="acym__users__import__from_database__field--tablename">Table Name</label>
        <?php
        array_unshift($data["tables"], acym_translation('ACYM_SELECT_TABLE'));
        echo acym_select($data["tables"], 'tablename', null, '', 'value', 'name', 'acym__users__import__from_database__field--tablename');
        ?>
        <?php
        if (!empty($userFields)) {
            foreach ($userFields as $oneUserField) {
                if (!in_array($oneUserField, ["id", "key", "automation"])) {
                    echo "<label class='cell' for='acym__users__import__from_database__field--".$oneUserField."'>".$oneUserField."</label>";
                    echo "<select class='cell acym__users__import__from_database__fields' name='fields[".$oneUserField."]' id='acym__users__import__from_database__field--".$oneUserField."'></select>";
                }
            }
        }
        if ($this->config->get('require_confirmation')) { ?>
			<div class="cell grid-x">
                <?php echo acym_switch('import_confirmed_database', 1, acym_translation('ACYM_IMPORT_USERS_AS_CONFIRMED')); ?>
			</div>
        <?php } ?>

		<div class="cell grid-x">
			<div class="cell medium-auto"></div>
			<button type="button" class="button cell medium-shrink" data-open="acym__user__import__add-subscription__modal" data-from="database"><?php echo acym_translation('ACYM_IMPORT'); ?></button>
			<button id="submit_import_database" class="acym__import__submit is-hidden" data-from="database"></button>
		</div>
	</div>
	<div class="cell large-3"></div>
</div>

