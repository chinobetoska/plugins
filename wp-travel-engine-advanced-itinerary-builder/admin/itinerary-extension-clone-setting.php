<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<div id="itinerary_sleep_mode_inner_template">
    <li id="trip_facts_template-{{tripfactsindex}}" data-id="{{tripfactsindex}}" class="trip_facts wte-ai-trip-itinerary-sleep-mode wpte-sortable">
        <div class="form-builder">
            <div class="wpte-field wpte-field-gray wpte-text"> 
                <input type="text" name="wp_travel_engine_settings[wte_advance_itinerary][itinerary_sleep_mode_fields][{{tripfactsindex}}][field_text]" placeholder="<?php _e('E.g. Hotel Stay, Tent Stay..','wte-advanced-itinerary');?>" required>
            </div>
        </div>
        <a href="#" class="wte-ai-del-li"><i class="far fa-trash-alt"></i></a>
    </li>
</div>
<style>
    #itinerary_sleep_mode_inner_template {
        display: none !important;
    }
</style>