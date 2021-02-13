
<header class="main-header">
	<a href="" class="logo">
		<span class="logo-mini"><b>TCM</b></span>
		<span class="logo-lg"><b>Test Case MNGT</b></span>
	</a>
	<nav class="navbar navbar-static-top">
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#"  style="float: right;" class="dropdown-toggle" data-toggle="dropdown">
					<span class="hidden-xs">Welcome   {{session()->get('sess_arr')['email']}} </span></span>
					</a>
				</li>
				<li>
					<a href="/logout"><i class="fa fa-sign-out" style="font-size:18px"></i></a>
				</li>
			</ul>
		</div>
	</nav>
</header>