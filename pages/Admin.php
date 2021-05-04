<?php
  if (!$_SESSION['user']->admin) {
    $_SESSION['errors'][] = "Unauthorised account attempting to access Admin page.";
    header("Location: UserPage.php");
  }
?>

<!DOCTYPE html>

<head>
  <iframe src="Master.php" width = "100%" height = "72" style="border:none;"></iframe>
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
  
  $e = new DBHandler();
  $pdo = $e->getInstance();
  
  function buttonHandle() {
    $pdo->beginTransaction();
  
    if ($state == add) {
      $addRecipe = new Recipe();
      $addRecipe->__construct($_POST['name'], $_POST['ingredient'], $_POST['description'], $_POST['instruction'], $_POST['tags'], 3);
      $addRecipe->save();
    }
    try {
      $removeSuggestion = $pdo->prepare("delete from suggestions where suggestionName =:name, suggestionIngredients =:ing, suggestionDescription =:desc, suggestionInstructions =:inst, suggestionTags=:tags");
      
      $removeSuggestion->bindValue(':name',$_POST['name']);
      $removeSuggestion->bindValue(':ing',$_POST['ingredient']);
      $removeSuggestion->bindValue(':desc',$_POST['description']);
      $removeSuggestion->bindValue(':inst',$_POST['instruction']);
      $removeSuggestion->bindValue(':tags',$_POST['tags']);
      $removeSuggestion->execute();
      $pdo->commit();
    } catch (PDOException $e){
      $pdo->rollback();
    }
  } 


  echo "<div class = 'col-lg-1'></div>";
  echo "<a href='UserPage.php' class='btn btn-primary'>Back to User Page</a>";
            
  $getSuggestions = $pdo->query("select * from Suggestions LIMIT 10");
  $suggestionsReturned = $getSuggestions->rowCount();
  
  if (isset($_POST['button-accept'])) {
    $state = $_POST['button-accept'];
  }
  
  if (!empty($_POST['name'])) {
    $name = $_POST['name'];
  }
  
  if (!empty($_POST['ingredient'])) {
    $ingredients = $_POST['ingredient'];
  }
  
  if (!empty($_POST['description'])) {
    $description = $_POST['description'];
  }
  
  if (!empty($_POST['instruction'])) {
    $instruction = $_POST['instruction'];
  }
  
  if (!empty($_POST['tags'])) {
    $tags = $_POST['tags'];
  }
  
  if ($state == 'add' || $state == 'remove') {
    buttonHandle();
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
