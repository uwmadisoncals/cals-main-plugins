jQuery("#ppm-tabs").collapse({
  accordion: true,
  open: function() {
	this.addClass("open");
	this.css({ height: this.children().outerHeight() });
  },
  close: function() {
	this.css({ height: "0px" });
	this.removeClass("open");
  }
});