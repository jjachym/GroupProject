<!DOCTYPE html>

<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
        * {
        box-sizing: border-box;
        }
        
        body {
        font-family: Arial, Helvetica, sans-serif;
        }
        
        /* Float four columns side by side */
        .column {
        float: left;
        width: 33%;
        padding: 0 10px;
        }
        
        /* Remove extra left and right margins, due to padding */
        .row {margin: 0 -5px;}
        
        /* Clear floats after the columns */
        .row:after {
        content: "";
        display: table;
        clear: both;
        }
        
        /* Responsive columns */
        @media screen and (max-width: 600px) {
        .column {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }
        }
        
        /* Style the counter cards */
        .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        padding: 16px;
        text-align: center;
        background-color: #f1f1f1;
        }
        </style>
    </head>

    <body>
        
        <iframe src="Master.html" width = "100%" height = "72" style="border:none;"></iframe>
        
        <h1>Get Recipe Recommendations</h1>
        <p>Using our AI algorithm we will provide you with a unique top 3 recipe recommendations based on previous recipes you rated highly.</p>

        <hr>
            <p>We do not need any extra information from you, below are your top 3 recipe recommendations!</p>
        </hr>

        <!--RECIPE NAME IS WHERE YOU SHOULD PUT NAME OF THE RECIPE-->
        <!--RECIPE INFO IS WHERE THE VARIOUS RECIPE SPECIFIC DETAILS WILL BE DISPLAYED-->
        <div class="row">
        <div class="column">
            <div class="card">
            <h3>Recipe Name 1</h3>
            <p>Description:</p>
            <p>Recipe Ingridents:</p>
            <p>Instructinos:</p>
            <p>Tag:</p>
            </div>
        </div>

        <div class="column">
            <div class="card">
            <h3>Recipe Name 2</h3>
            <p>Description:</p>
            <p>Recipe Ingridents:</p>
            <p>Instructinos:</p>
            <p>Tag:</p>
            </div>
        </div>
        
        <div class="column">
            <div class="card">
            <h3>Recipe Name 3</h3>
            <p>Description:</p>
            <p>Recipe Ingridents:</p>
            <p>Instructinos:</p>
            <p>Tag:</p>
            </div>
        </div>
        </div>
 
    </body>

</html>