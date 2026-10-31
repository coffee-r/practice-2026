<!DOCTYPE html>
<html lang="en">
<body>
    <form action="/posts" method="POST">
        <?= csrf_field() ?>
        <textarea name='message'></textarea>
        <button type="submit">
            投稿
        </button>
    </form>
    <?= validation_show_error('message') ?>
    <?php if(session()->has('error')): ?>
        <?= session('error') ?>
    <?php endif; ?>

    <?php foreach ($posts as $key => $post) : ?>
    <div>
        <div>
            <div>
                <div>
                    post_id : <?php echo $post->id; ?> <br/>
                    message : <?php echo $post->message; ?>
                </div>
                <form action="/posts/<?php echo $post->id; ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit">
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</body>