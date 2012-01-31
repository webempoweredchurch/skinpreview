jQuery(document).ready(function() {
	jQuery('select#skinSelectorDropdown').change( function() {
		jQuery('form#skinSelectorForm').trigger('submit');
	});
});
