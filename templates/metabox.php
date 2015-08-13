<div class="tz_mb_container">
    <div class="tz_mb_row center" style="margin-top:20px">
        <?php foreach ($metaboxes as $k=>$metabox): ?>
            <div class="tz_mb_column <?= metabox_col( count( $metaboxes ) ); ?> <?= metabox_col_sec( $k ); ?> tz_mb_border-top">
                <h2 class="tz_mb_header"><?= $metabox['title'] ?></h2>
                <p><?= $metabox['content'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>