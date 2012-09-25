function reorder(model_name) {
	function fix_zebra() {
		$('list' + model_name + '_reorder_list_body').getChildren().each(function(element, index) {
			if (index % 2)
				element.addClass('even');
			else
				element.removeClass('even');
		})
	}
	
	function make_sortable() {
		if ($('list' + model_name + '_reorder_list_body')) {
			$('list' + model_name + '_reorder_list_body').makeListSortable('reorder_onSetOrders', 'sort_order', 'id', 'sort_handle');
			$('list' + model_name + '_reorder_list_body').addEvent('dragComplete', fix_zebra);
		}
	}
	
	window.addEvent('domready', function() {
		if ($('list' + model_name + '_reorder_list_body')) {
			$('list' + model_name + '_reorder_list_body').addEvent('listUpdated', make_sortable)
			make_sortable();
		}
	});
}