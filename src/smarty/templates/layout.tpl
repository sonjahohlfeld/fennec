<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Fennec</title>

        <!-- Startbootstrap SBAdmin2 template -->
        <!-- Bootstrap Core CSS -->
        <link href="{#$WebRoot#}/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="{#$WebRoot#}/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="{#$WebRoot#}/bower_components/startbootstrap-sb-admin-2/dist/css/timeline.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{#$WebRoot#}/bower_components/startbootstrap-sb-admin-2/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="{#$WebRoot#}/bower_components/morrisjs/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="{#$WebRoot#}/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        
        <!-- Custom Fonts -->
        <link href="{#$WebRoot#}/css/panels.css" rel="stylesheet" type="text/css">
        
        {#block name='head'#}{#/block#}
    </head>
    <body>
        {#block name='body'#}{#/block#}

        <!-- jQuery -->
        <script src="{#$WebRoot#}/bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{#$WebRoot#}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="{#$WebRoot#}/bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Morris Charts JavaScript -->
        <script src="{#$WebRoot#}/bower_components/raphael/raphael-min.js"></script>
        <script src="{#$WebRoot#}/bower_components/morrisjs/morris.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{#$WebRoot#}/bower_components/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js"></script>
        
        <script>
            new Morris.Line({
                element: 'simpleLineChart',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                  { year: '2008', value: 20 },
                  { year: '2009', value: 10 },
                  { year: '2010', value: 5 },
                  { year: '2011', value: 5 },
                  { year: '2012', value: 20 }
                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Value'],
                lineColors: ['#037203']
            });
            
            Morris.Bar({
                element: 'simpleBarChart',
                data: [
                  { y: '2006', a: 100, b: 90 },
                  { y: '2007', a: 75,  b: 65 },
                  { y: '2008', a: 50,  b: 40 },
                  { y: '2009', a: 75,  b: 65 },
                  { y: '2010', a: 50,  b: 40 },
                  { y: '2011', a: 75,  b: 65 },
                  { y: '2012', a: 100, b: 90 }
                ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Series A', 'Series B'],
                barColors: ['#A17F20', '#c4a03f']
            });
            
            Morris.Donut({
                element: 'simpleDonutChart',
                data: [
                    {label: "Download Sales", value: 12},
                    {label: "In-Store Sales", value: 30},
                    {label: "Mail-Order Sales", value: 20}
                ], 
                colors: ['#8F0404', '#6A0000', '#AF1b1b']
            });
        </script>
    </body>
</html>
