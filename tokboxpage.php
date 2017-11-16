<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Tokbox Room</title>
</head>
<body>
    <?php
    $roomid= $_GET['roomid'];?>
    <div id="otEmbedContainer" style="width:800px; height:640px"></div>
    <script src=https://tokbox.com/embed/embed/ot-embed.js?embedId=1628e43f-7a31-456e-839b-1b47dc7a0926&room='<?php echo $roomid ?>'></script>

</body>
</html>