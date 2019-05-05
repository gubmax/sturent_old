<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Страница не найдена | STURENT</title>

		<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&amp;subset=cyrillic" rel="stylesheet">

    <style>
      * {
        margin: 0;
        padding: 0;
        border: 0;
        outline: 0;
      }
      html,
      body,
      form {
        height: 100%;
      }
      body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
				font-family: 'Roboto', verdana, helvetica, arial, sans-serif;
        background: #fbfbfd;
        color: #4c5d6e;
      }
      .page-404__logo {
        flex-grow: 1;
        margin-top: 48px;
        font-size: 28px;
        line-height: 28px;
      }
      .page-404__logo > span {
        color: #3f51b5;
        font-weight: bold;
      }
      .page-404__block {
        flex-grow: 2;
        box-sizing: border-box;
        padding: 0 16px;
        text-align: center;
      }
      .page-404__code {
        font-size: 100px;
        line-height: 100px;
        margin-bottom: 20px;
      }
      .page-404__title {
      	font-size: 32px;
      	line-height: 32px;
      	margin-bottom: 58px;
      }
			.page-404__link {
				position: relative;
			  display: block;
			  width: 100%;
			  color: #fff;
				background: linear-gradient(40deg, #4d67d2, #7258a2);
				font-size: 13px;
				font-weight: 500;
			  line-height: 40px;
			  text-align: center;
			  text-decoration: none;
				text-transform: uppercase;
			  border-radius: 4px;
			  cursor: pointer;
		    box-shadow: 0 1px 8px rgba(72, 88, 105, .4);
			}
			.page-404__link:before {
				content: '';
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				border-radius: inherit;
				opacity: 0;
				transition: opacity .4s;
				background-color: rgba(255, 255, 255, 0.4);
    		overflow: hidden;
			}
			.page-404__link:active:before {
				opacity: 1;
				animation: ripple .2s;
			}
			@keyframes ripple {
				from {
					transform: scale(0, 1);
				}
			  to {
					transform: scale(1, 1);
				}
			}

    </style>
  </head>

  <body>
    <div class="page-404__logo"><span>STU</span>RENT</div>
  	<div class="page-404__block">
      <p class="page-404__code">404</p>
      <p class="page-404__title">Страница не найдена</p>
      <a href="{{ url('/') }}" class="page-404__link">Вернуться на сайт</a>
    </div>
  </body>
</html>
