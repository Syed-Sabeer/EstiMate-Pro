<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ \App\Helpers\Helper::getCompanyName() }} - @yield('title')</title>
		@include('layouts.meta')
		@include('layouts.css')
		@yield('css')
	</head>
	<body>
		<!-- Loader starts-->
		<div class="loader-wrapper">
			<div class="loader-index"><span></span></div>
			<svg>
				<defs></defs>
				<filter id="goo">
					<fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
					<fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">    </fecolormatrix>
				</filter>
			</svg>
		</div>
		<!-- Loader ends-->
		<!-- page-wrapper Start-->
		@yield('content')		
		<!-- latest jquery-->
		@include('layouts.script')    
	</body>
</html>