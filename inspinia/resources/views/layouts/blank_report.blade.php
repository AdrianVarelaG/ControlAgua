<!DOCTYPE html>
<html lang="en">

    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">        
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        
        <style type="text/css">  
            body {
                font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
                font-size: 10px;}
            @page {
                margin-top: 0.3em;
            }
            @font-face {
                font-family: 'fontawesome';
                src: url('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/fonts/fontawesome-webfont.ttf?v=4.6.1') format('truetype');
                font-weight: normal;
                font-style: normal; 
            }
            .fa {
                display: inline-block;
                font: normal normal normal 14px/1 fontawesome;
                text-rendering: auto;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
    </head>

    <body class="white-bg">
        
        <div id="wrapper">        
                
            @stack('stylesheets')
                
            @yield('content')

                <footer>    
                    <script type="text/php">
                        $now = new DateTime();
                        $y = $pdf->get_height() - 24;
                        $x = $pdf->get_width() - 100;
                        $font_size = 8;         
                        $text_right = $now->format('d/m/Y H:i:s');    
                        $text_center = 'Copyright '.date("Y").' {{ Session::get('app_name') }}. Todos los derechos reservados.';
                        $text_left = 'Pag: {PAGE_NUM} / {PAGE_COUNT}';
                        $pdf->page_text($x, $y, $text_left, null, $font_size);
                        $pdf->page_text(200, $y, $text_center, null, $font_size);
                        $pdf->page_text(50, $y, $text_right, null, $font_size);
                    </script>
                </footer>
            
        </div>

        @stack('scripts')

    </body>
</html>