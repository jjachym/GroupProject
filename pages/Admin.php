<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<?php
  require_once '../models/DBHandler.php';
  include '../models/ErrorHandler.php';
  include '../models/Recipe.php';
  include 'Master.php';
  
  $e = new DBHandler();
  $pdo = $e->getInstance();
  
  echo "<div class = 'col-lg-1'></div>";
  echo "<a href='UserPage.php' class='btn btn-primary'>Back to User Page</a>";
  
  if (isset($_POST['button-accept'])) {
    if ($_POST['button-accept'] == 'add' or $_POST['button-accept'] == 'remove') {
      $pdo->beginTransaction();
      
      if ($_POST['button-accept'] == 'add') {
        $newRecipe = new Recipe();
        $newRecipe->__construct($_POST['name'],$_POST['ingredient'],$_POST['description'],$_POST['instruction'],$_POST['tags'],3);
        $newRecipe->save(); 
      }
      
      try {
        $delete = $pdo->prepare("delete from Suggestions where suggestionName = ? and suggestionIngredients = ? and suggestionDescription = ? and suggestionInstructions = ? and suggestionTags = ?");
        $delete->execute([$_POST['name'],$_POST['ingredient'],$_POST['description'],$_POST['instruction'],$_POST['tags']]);
      } catch (PDOException $e) {
        $_SESSION['error'][] = "DB Error";
        $pdo->rollBack();
      }
      
      $pdo->commit();

    }
  }
              
  $getSuggestions = $pdo->query("select * from Suggestions LIMIT 10");
  $suggestionsReturned = $getSuggestions->rowCount();
  
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
  
  $suggestion = $getSuggestions->fetch();
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
        echo "<form action='Admin.php' method='post'>";
          echo "<input type='hidden' name='name' value='$suggestion[suggestionName]'>";
          echo "<input type='hidden' name='ingredient' value='$suggestion[suggestionIngredients]'>";
          echo "<input type='hidden' name='description' value='$suggestion[suggestionDescription]'>";
          echo "<input type='hidden' name='instruction' value='$suggestion[suggestionInstructions]'>";
          echo "<input type='hidden' name='tags' value='$suggestion[suggestionTags]'>";
          echo "<div class='btn-group' role = 'group'>";
          echo "<button type='submit' name='button-accept' value='add' class='btn btn-success'><span class='glyphicon glyphicon-ok'> Approve</button>";
          echo "<button type='submit' name='button-accept' value='remove' class='btn btn-danger'><span class='glyphicon glyphicon-remove'> Reject</button>";
          echo "</div>";
        echo "</form>";
      echo "</div>";
    echo "</div>";
  echo "</div>";
?>
</body>
</html>
