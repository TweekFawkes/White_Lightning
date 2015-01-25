// Morris.js Charts sample data for SB Admin template
function get_info_bar() {
	$.ajax({
		url: '../../../n/includes/index_bargraph_data.php',
		success:function(html) {
			Morris.Bar({
				element: 'morris-bar-chart',
				data: eval(html),
				xkey: 'browser',
				ykeys: ['hits'],
				labels: ['Hits'],
				barRatio: 'auto',
				xLabelAngle: 'auto',
				hideHover: 'auto',
				resize: true
			});
		}
	});
	$.ajax({
		url: '../../../n/includes/index_donutgraph_data.php',
		success:function(html) {
			Morris.Donut({
				element: 'morris-donut-chart',
				data: eval(html),
				resize: true
			});
		}
	});
}


$(function() {
	get_info_bar();
});
