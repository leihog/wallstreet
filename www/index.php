<html>
    <head>
        <title>Wallstreet</title>
        <link rel='stylesheet'
              href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext'>

        <style>
           * {
                margin:0px;
                padding: 0px;
            }

            body {
                font-family: "Lato";
                font-style: normal;

                margin: 0px 60px 0px 60px;
                height: 100%;
            }

            #footer {
                position: absolute;
                height: 60px;
                width: 100%;
                bottom: 0;

                color: #999999;
            }

            #footer .ip {
                float: right;
            }

        </style>
    </head>
    <body>
        <div id="container">
            <div id="content">



            </div>

            <div id="footer">
                <div>
                <span class="datetime"><?php setlocale(LC_TIME, "sv_SE"); echo strftime("%A %d %B %Y"); ?></span>
                    <span class="ip">
                    <?php
                        // Get the real IP of the server
                        echo exec('ip addr|awk \'/inet /{sub(/\/.*$/,"",$2); nics[$NF] = $2} END{ split("wlan0 eth1 eth0 lo", pref, " "); for(i in pref){ if ("" != nics[pref[i]]){ print nics[pref[i]]; exit } } }\'');
                    ?>
                    </span>
                </div>
            </div>
        </div>

        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
        <script>
            $(function() {

                // pubsub
                var conn = new ab.Session('ws://wallstreet.gomitech.dev:9000',
                    function() {
                        conn.subscribe('wallstreet', function(topic, data) {
                            console.log('Got "' + topic + '" : ' + data);
                        });
                    },
                    function() {
                        console.warn('WebSocket connection closed');
                    },
                    {'skipSubprotocolCheck': true}
                );
            });
        </script>
    </body>
</html>
