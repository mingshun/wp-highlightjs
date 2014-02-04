(function($) {
  $(function() {
    $('#hljs-style-picker .hljs-style-item').click(function() {
      setSelected(this);
      updatePreviewStyle(this);
    });

    function setSelected(obj) {
      $('#hljs-style-picker .hljs-style-item').each(function() {
        $(this).removeClass('selected');
      });
      $(obj).addClass('selected');
    }

    function updatePreviewStyle(obj) {
      var selectedStyle = $(obj).attr('title');
      $('link').each(function() {
        var styleTitle;

        styleTitle = $(this).attr('title');
        if (!styleTitle) {
          return;
        }

        if (selectedStyle == styleTitle) {
          this.disabled = false;
        } else {
          this.disabled = true;
        }
      });
    }

    var selected = $('#hljs-style-picker label.selected');
    setSelected(selected);
    updatePreviewStyle(selected);
  });
})(jQuery);