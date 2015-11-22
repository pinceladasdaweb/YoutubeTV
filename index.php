<!doctype html>
<html>
    <head>
        <title>Youtube Tv</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Montserrat:400,700">
        <link rel="stylesheet" type="text/css" href="public/css/style.css">
    </head>
    <body>
        <header>
            <img src="public/css/img/youtube.png" alt="Youtube Tv" title="Youtube Tv">
        </header>

        <main id="content" class="content">
            <div class="loading">
                <img class="loading-svg" src="public/css/img/loading.svg" alt="Loading">
            </div>
        </main>

        <footer>
            <p>Powered by <a href="https://developers.google.com/youtube/v3/getting-started" title="Youtube API">Youtube API</a></p>
        </footer>

        <script src="public/js/youtubetv.js" type="text/javascript"></script>
        <script type="text/javascript">
            new YoutubeTv();
        </script>
    </body>
</html>
