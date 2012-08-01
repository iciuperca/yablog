<?php $_extends = 'app/templates/layout.php' ?>

<h1><?php echo $_title ?></h1>

<?php if (isset($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p class="error"><?php echo $error ?></p>
        <?php endforeach ?>
    </div>
<?php endif; ?>

<form method="post" action="" id="create-post-form" >
    <label for="title">Title*</label>
    <input type="text" name="title" id="title" required="required" value="<?php if (isset($title)) echo $title; ?>" />
    <label for="content">*Content</label>
    <textarea name="content" id="content" required="required" cols="20" rows="10" ><?php if (isset($content)) echo $content; ?></textarea>
    <label for="is_published">Published</label>
    <input type="checkbox" name="is_published" id="is_published" value="true"  <?php if (isset($is_published) && $is_published) echo 'checked="checked"'; ?> />

    <input type="submit" name="submit" id="submit" value="Submit"  />
</form>

