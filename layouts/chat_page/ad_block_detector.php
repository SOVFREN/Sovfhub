<div class="advmt_blk_detector_popup_wrap">
    <div class="modal fade" id="advmt_blk_detector_popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="advmt_blk_detector_popup" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo Registry::load('strings')->ad_block_detected_title ?></h5>
                </div>
                <div class="modal-body">
                    <?php echo Registry::load('strings')->ad_block_detected_description ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary shadow-none refresh_page"><?php echo Registry::load('strings')->ad_block_detected_button ?></button>
                </div>
            </div>
        </div>
    </div>
</div>