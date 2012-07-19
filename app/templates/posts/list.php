<?php $_extends = 'app/templates/layout.php' ?>

<h1><?php echo $title ?></h1>
<ul>
    <?php foreach ($posts as $post): ?>
        <li>
            <a href="/show/<?php echo $post['slug'] ?>"> <?php echo $post['title'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
