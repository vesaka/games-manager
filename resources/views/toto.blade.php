<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
  </head>
  <body class="d-flex flex-col m-0">
    <div id="app" class="w-100"></div>
    @vite([$appJs])
  </body>
</html>