<form id="forgot_password_form" class="forgot_password_form form_element d-none">

    <div class="d-none">
        <input type="hidden" name="add" value="access_token" />
    </div>
    <div class="field">
        <label><?php echo Registry::load('strings')->email_username ?></label>
        <input type="text" name="user" />
    </div>

    <div class="captcha_validation">
        <?php include 'layouts/entry_page/captcha_validation.php'; ?>
    </div>

</form>
</form>