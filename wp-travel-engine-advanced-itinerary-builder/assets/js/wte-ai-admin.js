(function ($) {
  $(function () {
    $('.wte-ai-fields-wrap').sortable();
    /**
     * Check all checkbox for meals type
     */

    $(document).on('click', '.etaeai-check-all', function () {
      var checkBoxes = $(this).parent().find("input[type='checkbox']");

      if (checkBoxes.prop("checked") === true) {
        checkBoxes.prop("checked", false);
        $(this).val('Tick All');
      } else {
        checkBoxes.prop("checked", true);
        $(this).val('Untick All');
      }
    });
    var file_frame;
    var allowed_filetype = ["image/jpeg", "image/png"];
    $(document).on('click', 'a.feat-itinerary-img-gallery-add', function (e) {
      $this = $(this);
      e.preventDefault();
      var curent_section_id = $(this).closest('.itinerary-row').attr('data-id');
      if (file_frame) file_frame.close();
      file_frame = wp.media.frames.file_frame = wp.media({
        title: $(this).data('uploader-title'),
        button: {
          text: $(this).data('uploader-button-text')
        },
        library: {
          type: allowed_filetype
        },
        multiple: true
      });
      index_max_count = parseInt($this.parent().find('.wte-max-img-count').val());
      file_frame.on('select', function () {
        selection = file_frame.state().get('selection');
        selection.map(function (attachment, i) {
          attachment = attachment.toJSON(), index = index_max_count + (i + 1);

          if (attachment.sizes) {
            if (attachment.sizes.thumbnail !== undefined) url_image = attachment.sizes.thumbnail.url; else if (attachment.sizes.medium !== undefined) url_image = attachment.sizes.medium.url; else url_image = attachment.sizes.full.url;
          } else {
            url_image = '';
          }

          $this.parent().find('#feat-itinerary-img-gallery-metabox-list').append('<li><input type="hidden" name="wte_advanced_itinerary[advanced_itinerary][itinerary_image][' + curent_section_id + '][' + index + ']" value="' + attachment.id + '"><img class="image-preview" src="' + url_image + '"><div class="wteai-field-action-wrap">  <button data-uploader-button-text="Replace Image" data-uploader-title="Replace image" class="wpte-change wteai-custom-change-image"></button><button class="wpte-delete wteai-remove-image"></button></div></li>');
          $this.parent().find('.wte-max-img-count').val(parseInt(index));
          wtea_itinerary_gallery_sortable();
        });
      });
      file_frame.open();
    });
    $(document).on('click', 'button.wteai-custom-change-image', function (e) {
      e.preventDefault();
      var that = $(this);
      if (file_frame) file_frame.close();
      file_frame = wp.media.frames.file_frame = wp.media({
        title: $(this).data('uploader-title'),
        button: {
          text: $(this).data('uploader-button-text')
        },
        library: {
          type: allowed_filetype
        },
        multiple: false
      });
      file_frame.on('select', function () {
        attachment = file_frame.state().get('selection').first().toJSON();
        that.closest('li').find('input:hidden').attr('value', attachment.id);
        that.closest('li').find('img.image-preview').attr('src', attachment.sizes.thumbnail.url);
      });
      file_frame.open();
    });

    function resetIndex(cur_sec_id_to_reset) {
      $('#feat-itinerary-img-gallery-metabox-list li').each(function (i) {
        $(this).find('input:hidden').attr('name', 'wte_advanced_itinerary[itinerary][' + cur_sec_id_to_reset + '][itinerary_image][' + i + ']');
      });
    }

    function wtea_itinerary_gallery_sortable() {
      if ($('.feat-itinerary-img-gallery-metabox-list').length) {
        $('.feat-itinerary-img-gallery-metabox-list').sortable({
          opacity: 0.6,
          stop: function stop() {//resetIndex();
          }
        });
      }
    }

    $(document).on('click', '.wte-ai-add-itinerary', function (e) {
      e.preventDefault();
      var maximum = 0;
      $('.itinerary-row').each(function () {
        var value = $(this).attr('data-id');

        if (!isNaN(value)) {
          value = parseInt(value);
          maximum = value > maximum ? value : maximum;
        }
      });
      maximum++;
      var newField = $('#wte-ai-itinerary-template').clone();
      newField.html(function (i, oldHTML) {
        return oldHTML.replace(/{{aiindex}}/g, maximum);
      });
      newField.find('.itinerary-content').addClass('show');
      newField.find('.itinerary-content').slideDown('slow');
      newField.find('.itinerary-content').css('height', 'auto');
      $('#itinerary-holder').before(newField.html());
      wtea_itinerary_custom_toggle_types();
    });
    $(document).on('click', '#feat-itinerary-img-gallery-metabox-list button.wteai-remove-image', function (e) {
      e.preventDefault();
      var cur_sec_id_to_reset = $(this).closest('.itinerary-row').attr('data-id');
      $(this).closest('li').animate({
        opacity: 0
      }, 200, function () {
        $(this).remove(); //resetIndex(cur_sec_id_to_reset);
      });
    });
    wtea_itinerary_gallery_sortable();

    function wtea_itinerary_custom_toggle_types() {
      $(document).on('change', '.field-type', function (e) {
        if ($(this).find('select option:selected').val() == 'select') {
          $(this).siblings('.select-options').fadeIn('slow');
        } else {
          $(this).siblings('.select-options').hide();
        }

        if ($(this).find('select option:selected').val() == 'text' || $(this).find('select option:selected').val() == 'number' || $(this).find('select option:selected').val() == 'textarea') {
          $(this).siblings('.input-placeholder').fadeIn('slow');
        } else {
          $(this).siblings('.input-placeholder').hide();
        }
      });
    }

    $(document).on('click', '#wte_itinerary_add_remove_field', function (e) {
      e.preventDefault();
      var len = 0;
      $('.wte-ai-trip-itinerary-sleep-mode').each(function () {
        var value = $(this).attr('data-id');

        if (!isNaN(value)) {
          value = parseInt(value);
          len = value > len ? value : len;
        }
      });
      len++;
      var newinput = $('#itinerary_sleep_mode_inner_template').clone();
      newinput.html(function (i, oldHTML) {
        return oldHTML.replace(/{{tripfactsindex}}/g, len);
      });
      $(this).closest('.wpte-advanced-itinerary-settings').find('.wte-ai-fields-wrap').append(newinput.html());
      $('.wte-ai-fields-wrap').sortable();
    });
    $(document).on('click', '.wte-ai-del-li', function (e) {
      e.preventDefault();
      $(this).closest('li').animate({
        opacity: 0
      }, 200, function () {
        $(this).remove();
      });
    });
    $(document).on('keyup', '.itinerary_days_label_field', function (e) {
      $(this).closest('li').find(".itinerary-section-header").text(this.value);
    });
  });
})(jQuery);

(function ($) {
  $(function () {
    jQuery(document).ready(function ($) {
      $('.wte-ai-fields-wrap').sortable();
      $(document).on('click', '.wte-ai-input-wrap', function (e) {
        var curpointer = $(this);
        var this_id = $(this).find('textarea.wte-ai-editorarea').attr('id');

        if (curpointer.hasClass('delay')) {
          curpointer.find('.wte-ai-editor-notice').remove();
          curpointer.removeClass('delay');
          init_editor(this_id);
        }
      });
    });

    function init_editor(this_id) {
      var initialize = wp.editor.initialize || wp.oldEditor.initialize
      initialize(this_id, {
        tinymce: {
          wpautop: true,
          plugins: 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
          toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
          toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
        },
        quicktags: true,
        mediaButtons: true
      });
    }
  });
})(jQuery);

window.addEventListener('load', function () {
  document.addEventListener('click', function (e) {
    if (Array.from(e.target.classList).indexOf('wte-itinerary-on-chart') > -1) { }
  });
});
