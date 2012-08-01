<?php $_extends = 'app/templates/layout.php' ?>

<h1><?php echo $_title ?></h1>

<?php if (isset($errors)): ?>
    <div class="errors">
        <?php echo $errors ?>
    </div>
 <?php endif; ?>


<div id="login-form">
    <form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" required="required" name="username" id="username" />
    <label for="password">Password:</label>
    <input type="password" required="required" name="password" id="password" />

    <input type="submit" name="login-submit" value="Submit" />
    </form>
</div>
