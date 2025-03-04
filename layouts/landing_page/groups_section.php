<section class="groups_list" id="groups">

    <div>
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <h3><?php echo Registry::load('strings')->landing_page_groups_section_heading; ?></h3>
                        <p>
                            <?php echo nl2br(Registry::load('strings')->landing_page_groups_section_description); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="groups">
            <div class="container">

                <?php
                if (Registry::load('settings')->categorize_groups === 'yes') {
                    $columns = $join = $where = null;
                    $columns = [
                        'group_categories.group_category_id',
                        'group_categories.category_name',
                        'group_categories.group_category_image'
                    ];
                    $where["group_categories.disabled[!]"] = 1;

                    $where["ORDER"] = ["group_categories.category_order" => "ASC"];

                    $group_categories = DB::connect()->select('group_categories', $columns, $where);
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="groups_categories_list">
                                <?php if (isset($group_categories[0])) {
                                    ?>
                                    <div class="active load_groups">
                                        <h6><?php echo Registry::load('strings')->all; ?></h6>
                                    </div>
                                    <?php
                                } ?>
                                <?php foreach ($group_categories as $group_category) {
                                    $category_img = get_img_url(['from' => 'group_categories', 'image' => $group_category['group_category_image']]);
                                    ?>
                                    <div class="load_groups" category_id="<?php echo $group_category['group_category_id']; ?>">
                                        <span><img src="<?php echo $category_img; ?>" /></span>
                                        <h6><?php echo $group_category['category_name']; ?></h6>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                } ?>
                <div class="row row-cols-1 row-cols-md-4 g-4 landing_page_groups">
                    <?php include('layouts/landing_page/groups.php'); ?>
                </div>

                <?php
                $site_advert = DB::connect()->rand("site_advertisements",
                    ['site_advertisements.site_advert_min_height', 'site_advertisements.site_advert_max_height',
                        'site_advertisements.site_advert_content'],
                    ["site_advertisements.site_advert_placement" => 'landing_page_groups_section', "site_advertisements.disabled[!]" => 1, "LIMIT" => 1]
                );
                if (isset($site_advert[0])) {
                    $site_advert = $site_advert[0];
                    $advert_css = 'max-height:'.$site_advert['site_advert_max_height'].'px;';

                    if (!empty($site_advert['site_advert_min_height'])) {
                        $advert_css .= 'min-height:'.$site_advert['site_advert_min_height'].'px;';
                    }

                    ?>

                    <div class="site_advert_block" style="<?php echo $advert_css; ?>">
                        <div>
                            <?php echo $site_advert['site_advert_content']; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>

    </div>
</section>