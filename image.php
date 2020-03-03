<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";

    Page::ForceLogin();

    require_once "inc/header.php";
?>


    <div class="container">
        <div class="row">
        <form class="js-image" autocomplete="on" enctype="multipart/form-data">
        <h2>Image Upload</h2>
            <fieldset>
            <div class="form-group">
                <label for="image-title">Image Title</label>
                <input type="text" class="form-control" id="image-title" name="image-title"size="50" required='required' placeholder="Enter the image title" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
            </div>
            <div class="form-group">
                <label for="file">File</label>
                <input type="file" class="form-control file-upload" id="file" name="file" required='required'>
            </div>

            <div class="js-error" style="display:none;"></div>
            
            <button type="submit" class="btn btn-primary">Submit</button>
            </fieldset>
        </form>
        </div>
        <div class="display" style="display:none;"></div>
    </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>