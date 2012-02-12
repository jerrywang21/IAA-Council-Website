jQuery(document).ready(function() {

jQuery('#colorpicker').ColorPicker({
	color: '0000ff',
	onShow: function (colpkr) {
		jQuery(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		jQuery(colpkr).fadeOut(500);
		return false;
	},
        onSubmit: function (hsb, hex, rgb) {
                jQuery('#colorpicker').val(hex);
        },
	onChange: function (hsb, hex, rgb) {
		jQuery('#colorpicker').val(hex);
	}

});

});
