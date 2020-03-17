<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";

    require_once "inc/header.php";

    Utils\Page::ForceLogin();

    $user = Users\User::getCurrentUser();

    $userPic = ($user['profile_img'] != null) ? __PATH__ . 'uploads/' . $user['profile_img'] : __PATH__ . 'inc/img/default-avatar.png';
    
?>

    <div class="container">
        <div class="row">
        <form class="js-post-add" autocomplete="on">
        <h1>Create Post</h1>
            <fieldset>
            <div class="form-group">
                <label for="first-name">Blog Title</label>
                <input type="text" class="form-control" id="post-title" name="post-title" size="50" required='required' placeholder="First Name..." style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACIUlEQVQ4EX2TOYhTURSG87IMihDsjGghBhFBmHFDHLWwSqcikk4RRKJgk0KL7C8bMpWpZtIqNkEUl1ZCgs0wOo0SxiLMDApWlgOPrH7/5b2QkYwX7jvn/uc//zl3edZ4PPbNGvF4fC4ajR5VrNvt/mo0Gr1ZPOtfgWw2e9Lv9+chX7cs64CS4Oxg3o9GI7tUKv0Q5o1dAiTfCgQCLwnOkfQOu+oSLyJ2A783HA7vIPLGxX0TgVwud4HKn0nc7Pf7N6vV6oZHkkX8FPG3uMfgXC0Wi2vCg/poUKGGcagQI3k7k8mcp5slcGswGDwpl8tfwGJg3xB6Dvey8vz6oH4C3iXcFYjbwiDeo1KafafkC3NjK7iL5ESFGQEUF7Sg+ifZdDp9GnMF/KGmfBdT2HCwZ7TwtrBPC7rQaav6Iv48rqZwg+F+p8hOMBj0IbxfMdMBrW5pAVGV/ztINByENkU0t5BIJEKRSOQ3Aj+Z57iFs1R5NK3EQS6HQqF1zmQdzpFWq3W42WwOTAf1er1PF2USFlC+qxMvFAr3HcexWX+QX6lUvsKpkTyPSEXJkw6MQ4S38Ljdbi8rmM/nY+CvgNcQqdH6U/xrYK9t244jZv6ByUOSiDdIfgBZ12U6dHEHu9TpdIr8F0OP692CtzaW/a6y3y0Wx5kbFHvGuXzkgf0xhKnPzA4UTyaTB8Ph8AvcHi3fnsrZ7Wore02YViqVOrRXXPhfqP8j6MYlawoAAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
            </div>
            <div class="form-group">
                <label for="bio">Content</label>
                <textarea id="post-content" name="content" class="form-control" rows="10" cols="10" placeholder="Enter Your Content" ></textarea>
            </div>
            <div class="js-error" style="display:none;"></div>

            <button type="submit" class="btn btn-primary">Publish</button>
            </fieldset>
        </form>
        </div>
    </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>