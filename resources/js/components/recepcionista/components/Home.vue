<template>
    <main>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Citas</h1>
        <div class="d-flex align-items-center">
        </div>
    </div>
    <table-citas :nombreUser="nombreUser" :idSede="idSede"></table-citas>
    </main>
</template>

<script>
export default{
	props:['nombreUser', 'idSede']
}</script>