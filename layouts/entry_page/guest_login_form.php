<?php
$columns = $where = null;
$columns = ['custom_fields.string_constant(field_name)', 'custom_fields.field_type', 'custom_fields.required'];
$where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0, 'custom_fields.show_on_guest_login' => 1];
$where["ORDER"] = ["custom_fields.field_id" => "ASC"];
$custom_fields = DB::connect()->select('custom_fields', $columns, $where);
?>

<form class="guest_login_form form_element d-none" id="guest_login_form">
    <div class="d-none">
        <input type="hidden" name="add" value="guest_user" />

        <?php if (isset($_GET['redirect'])) {
            ?>
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']) ?>" />
            <?php
        } ?>

    </div>
    <div class="field">
        <label><?php echo Registry::load('strings')->nickname ?></label>
        <input type="text" name="nickname" />
    </div>

    <?php

    foreach ($custom_fields as $custom_field) {
        $field_name = $custom_field['field_name'];

        if ($custom_field['field_type'] === 'short_text' || $custom_field['field_type'] === 'link') {

            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <input type="text" name="<?php echo $field_name; ?>" />
            </div>

            <?php
        } else if ($custom_field['field_type'] === 'long_text') {
            ?>

            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <textarea rows="6" name="<?php echo $field_name; ?>"></textarea>
            </div>

            <?php
        } else if ($custom_field['field_type'] === 'date') {

            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <input type="date" name="<?php echo $field_name; ?>" class="icon-calendar" />
            </div>

            <?php

        } else if ($custom_field['field_type'] === 'number') {

            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <input type="number" name="<?php echo $field_name; ?>" />
            </div>

            <?php

        } else if ($custom_field['field_type'] === 'dropdown') {

            $dropdownoptions = $field_name.'_options';

            if (isset(Registry::load('strings')->$dropdownoptions)) {
                $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
            }
            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <select name="<?php echo $field_name; ?>">
                    <option value=''>--------</option>
                    <?php foreach ($field_options as $field_option_value => $field_option) {
                        ?>
                        <option value='<?php echo $field_option_value ?>'><?php echo $field_option ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <?php

        }
    }
    ?>

    <div class="field checkbox">
        <label>
            <input type="checkbox" name="terms_agreement" value="agreed">
            <span class="checkmark"></span>
            <span class="text"><?php echo Registry::load('strings')->signup_agreement ?>
                <span class="load_page" page_id="<?php echo Registry::load('settings')->site_terms_conditions ?>">[<?php echo Registry::load('strings')->read_terms ?>]</span>
            </span>
        </label>
    </div>

    <div class="captcha_validation">
        <?php include 'layouts/entry_page/captcha_validation.php'; ?>
    </div>
</form>