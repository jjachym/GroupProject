<!DOCTYPE html>

<head>
  <iframe src="Master.html" width = "100%" height = "72" style="border:none;"></iframe>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<?php
  
  function buttonHandle() {
    if ($_POST['button-accept']) {
      $pdo->prepare("insert into recipes() values()");
    }

    $pdo->prepare("delete from suggestions where ");
  } 
  
  require_once '../models/DBHandler.php';
  include '../models/ErrorHandler.php';
  include '../models/Recipe.php';
  
  $e = new DBHandler();
  $pdo = $e->getInstance();
  
  $getSuggestions = $pdo->query("select * from Suggestions LIMIT 10");
  $suggestionsReturned = $getSuggestions->rowCount();
  
  if (isset($_POST['button-accept']) || isset($_POST['button-remove'])) {
    
  }
  
  if ($suggestionsReturned == 0) {
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-header'>";
    echo "<h2>No suggestions</h2>"; 
    echo "</div>";
    echo "<div class='panel-body'>";
    echo "<p>";
    echo "No suggestions to approve.";
    echo "</p>" ;
    echo "</div>";
  } 
  
  $i = 0;
  while ($suggestion = $getSuggestions->fetch()) {
    echo "<div class='container'>";
    echo "<h2>Recipe Name: $suggestion[suggestionName]</h2>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-body'>";
    echo "<p>";
    echo "Ingredients: $suggestion[suggestionIngredients]\n";
    echo "</p>";
    echo "<p>";
    echo "Description: $suggestion[suggestionDescription]\n";
    echo "</p>";
    echo "<p>";
    echo "Instructions: $suggestion[suggestionInstructions]\n";
    echo "</p>";
    echo "<p>";
    echo "Tags: $suggestion[suggestionTags]\n";
    echo "</p>";
    echo "</div>";
    echo "<div class='panel-footer'>";
    echo "<div class='btn btn-success' id='button-accept' href='Admin.php'><span class='glyphicon glyphicon-ok'></div>";
    echo "<div class='btn btn-danger' id='button-remove' href='Admin.php'><span class='glyphicon glyphicon-remove'></div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    $i++;
  }
?>
</body>
</html>