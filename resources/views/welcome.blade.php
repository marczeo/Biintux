@extends('layouts.app')

@section('scriptsTop')
<style type="text/css">
	body{
		background-image: url('http://3.bp.blogspot.com/-AjXqje7n7YI/U6xjNlR30UI/AAAAAAAAEXg/wnquRfvDVsY/s1600/pmr.jpg');
	}
</style>
@endsection

@section('content')
<div class="jumbotron-fluid">
    <div id="map" style="height: 800px;"></div>
</div>
<!--<div class="jumbotron">
	<div class="container-fluid">
		<div class="col-md-2">
			<div class="btn-group-vertical col-md-12">
				<button class="btn col-md-12 btn-info">Ciclovia</button>
				<button class="btn col-md-12 btn-info">MiBici</button>
				<button class="btn col-md-12 btn-info">Camiones</button>
			</div>
		</div>
		<div class="col-md-10">
			<div id="map" class="container" style="height: 750px;">
			</div>
		</div>
	</div>
</div>-->
@endsection
@section('scriptsBottom')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
    <script src="/js/map_index.js"></script>
@endsection