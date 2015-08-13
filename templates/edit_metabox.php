<?php
$metaboxes = get_meta_boxes( $_REQUEST['metabox'] );
if( !$metaboxes ){
    print_error( 'Metabox not found! Please go back' );
    return;
}
?>
<?php if(isset($_GET['updated']) && $_GET['updated'] == 1): ?>
<div id="message" class="updated"><p>Metabox has been updated Successfully</p></div>
<?php endif; ?>
<div class="wrap">
    <h1>Meta Box #<?= $_REQUEST['metabox'] ?> <a href="?page=<?= $_REQUEST['page'] ?>" class="button">Go back</a></h1>
    <form action="" method="post">
    <input type="submit" class="button-primary" name="metabox_form_update" value="Update" />
    <input type="hidden" name="metabox_id" value="<?= $_REQUEST['metabox']; ?>" />
    <div class="row">
    <?php if( isset( $metaboxes[0] ) ): ?>
        <div class="column4">
            <h3>Meta Box 1</h3>
            <input type="text" class="" name="metabox_title[]" value="<?= $metaboxes[0]['title'] ?>">
            <textarea name="metabox_text[]"><?= $metaboxes[0]['content'] ?></textarea>
        </div>
    <?php endif; ?>
    <?php if( isset( $metaboxes[1] ) ): ?>
        <div class="column4">
            <h3>Meta Box 2</h3>
            <input type="text" class="" name="metabox_title[]" value="<?= $metaboxes[1]['title'] ?>">
            <textarea name="metabox_text[]"><?= $metaboxes[1]['content'] ?></textarea>
        </div>
    <?php endif; ?>
    <?php if( isset( $metaboxes[2] ) ): ?>
        <div class="column4">
            <h3>Meta Box 3</h3>
            <input type="text" class="" name="metabox_title[]" value="<?= $metaboxes[2]['title'] ?>">
            <textarea name="metabox_text[]"><?= $metaboxes[2]['content'] ?></textarea>
        </div>
    <?php endif; ?>
    </div>
    </form>
</div>