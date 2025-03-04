<?php
use Medoo\Medoo;
$columns = $join = $where = null;
$columns = [
    'groups.group_id', 'groups.name', 'groups.slug', 'groups.description',
    'groups.group_picture', 'groups.group_cover_pic', 'groups.total_members'
];
$where["groups.suspended"] = 0;

if (isset($_POST['group_category_id'])) {
    $_POST['group_category_id'] = filter_var($_POST["group_category_id"], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($_POST['group_category_id'])) {
        $where["groups.group_category_id"] = $_POST['group_category_id'];
    }
}

$where["AND"] = [
    "OR" => [
        "groups.password(password_null)" => null,
        "groups.password(password_empty)" => '',
        "groups.password(password_zero)" => "0"
    ],
    "groups.secret_group" => "0"
];
$where["ORDER"] = ["groups.pin_group" => "DESC", "groups.updated_on" => "DESC"];
$where["LIMIT"] = Registry::load('settings')->no_of_groups_landing_page;

$groups = DB::connect()->select('groups', $columns, $where);
foreach ($groups as $group) {
    $group_image = get_img_url(['from' => 'groups/icons', 'image' => $group['group_picture']]);
    $group_cover_pic = get_img_url(['from' => 'groups/cover_pics', 'image' => $group['group_cover_pic']]);
    $group_url = Registry::load('config')->site_url;
    $group_id = $group['group_id'];

    if (Registry::load('settings')->show_group_members_count === 'enable') {
        $group_members_stats = abbreviateNumber($group['total_members']);

        $where = $join = null;
        $where = ["group_id" => $group_id, "currently_browsing" => 1, "site_users.online_status[!]" => 0];
        $join['[>]site_users'] = ['group_members.user_id' => 'user_id'];

        $online_members = DB::connect()->select('group_members', $join, ['group_member_id'], $where);

        $online_members = abbreviateNumber(count($online_members));

        if (!empty($online_members)) {
            $group_members_stats = $online_members.'/'.$group_members_stats;
        }
    }

    if (!empty($group['slug'])) {
        $group_url .= $group['slug'].'/';
    } else {
        $group_url .= 'group/'.$group['group_id'].'/';
    }

    ?>
    <div class="col">
        <a href="<?php echo $group_url; ?>">
            <div class="card h-100">
                <div class="group_cover_pic">
                    <img src="<?php echo $group_cover_pic; ?>" />
                </div>
                <div class="card-body">
                    <?php
                    if (Registry::load('settings')->show_group_members_count === 'enable') {
                        ?>
                        <div class="group_members">
                            <div>
                                <span><?php echo $group_members_stats; ?></span>
                            </div>
                        </div>
                        <?php
                    } ?>
                    <div class="group_image">
                        <img src="<?php echo $group_image; ?>" />
                    </div>
                    <h5 class="card-title"><?php echo $group['name']; ?></h5>
                    <p class="card-text">
                        <?php echo $group['description']; ?>
                    </p>
                </div>
                <div class="card-footer">
                    <div class="button">
                        <span><?php echo Registry::load('strings')->view_group; ?></span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <?php
}
?>;