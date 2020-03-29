<section id="gamespace" hidden>
    <h1>Games!</h1>
    <h2>Lives Available: </h2>
    <?php
        $username = $_SESSION['username'];
        $user_level = "SELECT \"level\" FROM \"user\" WHERE username= '$username'";
        $result = $db->prepare($user_level);
        $result->execute();
        $level = $result->fetch();
        $level = $level[0];
        echo "Level Selection:";
        echo "<form>";
        foreach (range(1, $level) as $number) {
            echo "<input type=\"button\" id='$number' name='level_choice' value='$number' onclick='runGame($number)'> ";
            //echo "<label for='$number'>$number</label>&nbsp &nbsp";
        }
        echo "</form>";
    ?>
    <div align="center">
    <html>
    <head>
    </head>
    <h1>Run for it!</h1>
    <h2 id="score_board">Score: 0</h2>
        <body>
        <div id="game" style="margin:0 auto;"></div>
        <script type="text/javascript" src="https://rawgithub.com/craftyjs/Crafty/release/dist/crafty-min.js"></script>
        <script>
            var x;
            function runGame(level_number){
                var screenWidth = window.screen.width/2;
                var screenHeight = 300;
                var score = 0;

                Crafty.init(screenWidth,300, document.getElementById('game'));

                Crafty.e('Floor, 2D, Canvas, Color')
                    .attr({x: 0, y: 250, w: screenWidth, h: 10})
                    .color('green');

                var player1 =
                    Crafty.e('Player, Canvas, Color, Twoway, Gravity')
                        .attr({x: 0, y: 250, w: 50, h: 50})
                        .color('#F00')
                        .twoway(200)
                        .gravityConst(700)
                        .gravity('Floor');

                function drop()
                {
                    //document.getElementById("timer").innerHTML = "+2500 XP. Great Job!";
                    var randomy = Math.floor((Math.random() * 250) +50);
                    Crafty.e('Drop, 2D, Canvas, Color, Solid, Gravity, Collision')
                        .attr({x: screenWidth, y: randomy, w: 50, h: 10})
                        .color('#ff3300')
                        .checkHits('Player')
                        .bind("EnterFrame", function() {
                            if (this.x < 0){
                                this.destroy();
                                score = score + 1;
                                document.getElementById("score_board").innerHTML = "Score: " + String(score)

                             }
                             else{
                                 this.x = this.x - (10 * 1 + level_number);

                             }
                        })
                        .bind("HitOn", function(){
                            this.destroy();
                            player1.x = 0;
                            score = 0;
                        });
                }

                Crafty.bind("EnterFrame", function(){
                    var arr = Crafty("2D").get();
                    if (arr.length-2 < level_number){
                        drop();
                    }
                });
            }
        </script>
        </body>
    </html>
    </div>
</section>