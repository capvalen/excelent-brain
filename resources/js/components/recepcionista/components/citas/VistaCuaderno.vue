<template>
	<div class="container-fluid p-2">
		<div class="row">
			<div class="col-12 col-sm-3">
				<input type="date" class="form-control shadow-sm" v-model="fecha">
			</div>
		</div>
	</div>
</template>
<script>
	import moment from 'moment'
	
	export default{
		name: 'VistaCuaderno',
		data(){ return{
			fecha: moment().format('YYYY-MM-DD')
		}},

	}
</script>