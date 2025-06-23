<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Theme style -->
   <link rel="stylesheet" href=" {{ asset('assets/dist/css/adminlte.min.css') }}">
    <title>BevCount</title>
    <style>
        .logo{
            text-align:center; 
            padding:15px;          
        }
        .download-app-container{
            box-shadow: 0 1px 4px rgba(0,0,0,.3);
            border-radius: 1em;
            border-color: rgba(255,255,255,.3);
        }
        .download-app{
            margin-top:15px;
            font-size: 14px;
            padding: 0.6em 20px;
            min-width: 0.75em;
            display: block;
            position: relative;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            zoom: 1;
            
        }
        .download-app:hover{
            background:#e7e1e1;;
            border-radius: 1em;
            
        }
        .download-app:focus{
          box-shadow: inset 0 0 3px #387bbe, 0px 0 9px #387bbe;
          border-radius: 1em;
        }
        .center{
         text-align: center;
        }
        .wrapper{
         padding:10px;
        }
    </style>
</head>
<body>
    <div class="logo">
    <img src="{{asset('images/accuflo.png')}}" alt="beverage logo" srcset="">
     
   
    </div>
    <div class="wrapper">
      <P>
         <b>Ver 1.1</b><br>
         Mar 17, 2023
         <ul>
            <li>Fixed submit to cloud</li>
         </ul>
      </P>
      <div class="center download-app-container">
         <a href="{{asset('app apk/app-release.apk')}}" class="button download-app">Download BevCount</a>
       </div>
    </div>
    
    
</body>
</html>