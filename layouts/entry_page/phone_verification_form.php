<form id="phone_verification_form" class="phone_verification_form form_element d-none">

    <div class="d-none">
        <input type="hidden" name="update" value="phone_verification_status" />
    </div>

    <div class="d-none">
        <input type="hidden" class="phone_verify_user_id" name="user_id" />
    </div>
    <div class="field">
        <label><?php echo Registry::load('strings')->enter_otp ?></label>
        <input type="number" name="one_time_pin" />
    </div>

    <div class="captcha_validation">
        <?php include 'layouts/entry_page/captcha_validation.php'; ?>
    </div>

</form>
</form>