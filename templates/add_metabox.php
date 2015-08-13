<?php if(isset($_GET['updated']) && $_GET['updated'] == 1): ?>
<div id="message" class="updated"><p>Metabox has been updated Successfully</p></div>
<?php endif; ?>
<div class="wrap">
    <h1>Meta Box <a href="?page=<?= $_REQUEST['page'] ?>" class="button">Go back</a></h1>
    <form action="" method="post">
    <input type="submit" class="button-primary" name="metabox_form_submit" value="Add" />
    <div class="row">
        <div class="column4">
            <h3>Meta Box 1</h3>
            <input type="text" class="" name="metabox_title[]">
            <textarea name="metabox_text[]"></textarea>
        </div>
        <div class="column4">
            <h3>Meta Box 2</h3>
            <input type="text" class="" name="metabox_title[]">
            <textarea name="metabox_text[]"></textarea>
        </div>
        <div class="column4">
            <h3>Meta Box 3</h3>
            <input type="text" class="" name="metabox_title[]">
            <textarea name="metabox_text[]"></textarea>
        </div>
    </div>
    </form>
</div>