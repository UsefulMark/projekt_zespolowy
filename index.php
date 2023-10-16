<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>YourFavouriteApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"><link rel="stylesheet" href="./style.css">

</head>
<body>
  <!-- Check for the "login" session variable and redirect accordingly -->
  <?php
  session_start();

  // Check if the "login" session variable exists
  if (isset($_SESSION['login'])) {
    // If it exists, redirect the user to "panel.php"
    header("Location: panel.php");
    exit();
  } else {
    // If it doesn't exist, redirect the user to "form_log.html"
    header("Location: form_log.html");
    exit();
  }
  ?>


<!-- partial:index.partial.html -->
<!---- Hidden Open / Close Toggles ---->
<input type="radio" id="nav-close" name="nav-toggle" value="nav-close">
<input type="radio" id="nav-toggle" name="nav-toggle" value="nav-toggle">
<!---- END Hidden Toggles ---->

<!---- Visual Open Menu Toggle ---->
<a href="javascript:void(0);" class="nav-toggle">

	<label for="nav-toggle"></label>

	<figure></figure>
	<figure></figure>
	<figure></figure>

</a>
<!---- END Visual Open Menu Toggle ---->

<!---- Visual Close Menu Toggle ----->
<a href="javascript:void(0);" class="nav-close">

	<label for="nav-close"></label>

	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
	</svg>

</a>
<!---- END Visual Close Menu Toggle ---->

<!----- Main Navigation ---->
<nav>

	<ul>

		<li><a href="main.html" title="Home" rel="noopener">Home</a></li>

		<li><a href="javascript:void(0);" title="About" rel="noopener">About</a></li>

		<li><a href="javascript:void(0);" title="Services" rel="noopener">Services</a></li>

	</ul>

</nav>
<!---- END Main Navigation ---->
<!-- partial -->
  
</body>
</html>
