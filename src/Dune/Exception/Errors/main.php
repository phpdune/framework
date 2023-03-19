<?php http_response_code($code); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <style>
 @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
*{
  font-family: 'Poppins',sans-serif;
}
body {
  background-image: url('https://imgtr.ee/images/2023/03/16/qvwVl.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  display: flex;
}
.exception{
   position: absolute;
   padding-top: 50%;
   padding-bottom: 50%;
   padding-left: 30px;
	 color: #fff;
	 background: rgba(0,0,0,0.1);
	 border-radius: 20px;
	 font-weight: bold;
}
.exception:hover{
  background: rgba(0,0,0,0.3);
  transition-duration: 0.5;
}
.title {
  margin-left: 30px;
  margin-top: 130px;
  color: #fff;
}
    </style>
    <h1 class="title">Error</h1>
  <h2 class="exception"><?= $message ?></h2>
  </body>
</html>