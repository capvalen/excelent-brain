<template>
	<div class="modal fade" id="modalFacturacion" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" >
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Facturación Electrónica</h5>
					        <button type="button" id="cerrModalEstado" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<div class="row row-cols-2">
						<div class="col">
							<div class="card impresionComprobante" @click="imprimirTicket()">
								<div class="card-body">
									<div class="">
										<div class="text-center">
											<img class="mx-auto" :src="require('/img/pdf_logo.jpg')" style="width: 35px" alt="">
											<p class="mb-0">Ticket Interno</p>
											<p class="mb-0"><small>Impresión en PDF</small></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="card impresionComprobante" id="imprimirSunat" v-if="pago?.voucher" @click="imprimirComprobante()">
								<div class="card-body">
									<div class="text-center">
										<img class="mx-auto" :src="require('/img/sunat_logo.webp')" style="width: 35px" alt="">
										<p class="mb-0">Comprobante SUNAT</p>
										<p class="mb-0"><small>Impresión Física</small></p>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="mt-3" v-if="!pago?.voucher">
						<p >Datos principales para facturación:</p>
						<div class="mb-2">
							<label class="form-label">Tipo de Comprobante</label>
							<select class="form-select" id="sltTipo" v-model="facturacion.tipoEmision" @change="cambiarEmision()">
								<option value="01">Factura electrónica</option>
								<option value="03">Boleta electrónica</option>
							</select>
						</div>
						<div class="mb-2 d-none">
							<label class="form-label">Fecha del documento</label>
							<input type="date" class="form-control" disabled :value="hoy" >
						</div>

						<div class="row g-2 mb-2">
							<div class="col-8">
								<label class="form-label">Tipo de documento</label>
								<select class="form-select" v-model="facturacion.tipoDocumento" @change="cambioCliente()">
									<option value="1">DNI</option>
									<option value="6">RUC</option>
									<option value="0">Cliente Simple</option>
									<option value="4">Carnet de extranjería</option>
								</select>
							</div>
							<div class="col-4 d-flex align-items-end justify-content-center">
								<button v-if="facturacion.tipoDocumento === '1' || facturacion.tipoDocumento === '4'" class="btn btn-outline-primary" @click="buscarReniec()"><span><img :src="require('/img/reniec_logo.webp')" style="width: 15px" alt=""></span> RENIEC</button>
								<button v-if="facturacion.tipoDocumento === '6' " class="btn btn-outline-primary" @click="buscarSunat()"><span><img :src="require('/img/sunat_logo.webp')" style="width: 15px" alt=""></span> SUNAT</button>
							</div>
						</div>

						<div class="mb-2" v-if="facturacion.tipoDocumento !== '0'">
							<label class="form-label"># de Documento</label>
							<input type="text" class="form-control" placeholder="" v-model="facturacion.ruc" @keypress="preventSpace" maxlength="11">
						</div>
						<div class="mb-2" v-if="facturacion.tipoDocumento !== '0'">
							<label class="form-label">Razón social</label>
							<input type="text" class="form-control" placeholder="" v-model="facturacion.razonSocial">
						</div>
						<div class="mb-2" v-if="facturacion.tipoDocumento !== '0'">
							<label class="form-label">Dirección</label>
							<input type="text" class="form-control" placeholder="" v-model="facturacion.direccion">
						</div>
						<div class="mb-2">
							<label class="form-label">Apoderado</label>
							<input type="text" class="form-control" placeholder="" v-model="facturacion.apoderado">
						</div>
						<div class="mb-2">
							<label class="form-label">Concepto del pago</label>
							<input type="text" class="form-control" placeholder="" v-model="facturacion.conceptoPago">
						</div>

						<div class="mb-2">
							<label class="form-label">Monto total a pagar (S/.)</label>
							<input type="text" class="form-control" placeholder="" v-model="facturacion.monto" @input="soloNumeros($event)">
						</div>

						<div class="mb-2" v-show="facturacion.tipoEmision=='01'">
							<label class="form-label">Tipo de pago</label>
							<select class="form-select" v-model="facturacion.tipoPago" @change="cambiarContado()">
								<option>Contado</option>
								<option>Crédito</option>
							</select>
						</div>

						<div v-if="facturacion.tipoPago === 'Crédito' && facturacion.tipoEmision=='01'"> <!-- solo facturas pueden programar fechas -->
							<div class="d-flex justify-content-between mt-3">
								<p class="mb-1 ">Cronograma de fechas y montos</p>
								<button class="btn btn-outline-secondary btn-sm" @click="agregarMontoFecha()"><i class="fa-solid fa-plus"></i></button>
							</div>
							<template v-if="facturacion.montosFechas.length > 0">
								<div class="card" >
									<div class="card-body">
										<div class="row g-2 align-items-end mb-2" v-for="(item, index) in facturacion.montosFechas" :key="index">
											<div class="col-6">
												<label class="form-label"><i class="fa-regular fa-calendar"></i> Fecha (cuota {{index+1}})</label>
												<input type="date" class="form-control" v-model="item.fecha" :min="hoy">
											</div>
											<div class="col-6">
												<label class="form-label">Monto</label>
												<input type="number" class="form-control" step="1.0" placeholder="0.00" v-model.number="item.monto">
											</div>
										</div>
									</div>
								</div>
							</template>
							
						</div>

						<div class="d-flex mt-3">
							<button class="btn btn-block btn-primary" @click="generarComprobante()"><img :src="require('/img/sunat_logo.webp')" style="width: 15px" alt=""> Generar comprobante</button>
						</div>
					
					</div>
					<div class="mt-3 d-flex justify-content-center" v-if="pago?.voucher" @click="irPanelBaja()">
						<button class="btn btn-outline-danger"><i class="fa-solid fa-download"></i> ¿Dar de baja al comprobante?</button>
					</div>
				</div>

				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import moment from 'moment'
import alertify from 'alertifyjs'

export default{
	name: 'ModalFacturacion',
	data(){ return{
		hoy: moment().format('YYYY-MM-DD'),
		facturacion:{
			tipoEmision:'03',
			fecha: '',
			tipoDocumento: '1',
			ruc: '',
			razonSocial: '',
			direccion: '',
			conceptoPago: '',
			monto:0,
			tipoPago: 'Contado',
			apoderado: '',
			montosFechas: [
			]
		}
	}},
	props:{
		idSede:null, pago:null
	},
	methods:{
		agregarMontoFecha(){
			event.preventDefault()
			let ultimo = this.facturacion.montosFechas[this.facturacion.montosFechas.length - 1]
			console.log(ultimo)
			//validar que el ultimo dato tenga fecha y valor mayor a 0, si fuera undefined se omite la validación
			if(ultimo && (ultimo.fecha === '' || ultimo.monto <= 0)) return
			
			
			this.facturacion.montosFechas.push({
				fecha: '',
				monto: 0
			})
		},
		cambioCliente(){
			if(this.facturacion.tipoDocumento === '0'){
				this.facturacion.ruc = '00000000'
				this.facturacion.razonSocial = 'Cliente Simple'
				this.facturacion.direccion = '-'
			}else{
				this.facturacion.ruc = ''
				this.facturacion.razonSocial = ''
				this.facturacion.direccion = ''
			}
		},
		preventSpace(event) {
      // Si la tecla presionada es espacio (código 32)
      if (event.keyCode === 32 || event.key === ' ') {
        event.preventDefault(); // Evita que se ingrese el espacio
      }
    },
		async buscarReniec(){
			if(this.facturacion.ruc == ''){
				alertify.notify('<i class="fa-solid fa-circle-check"></i> ' + 'Campo DNI/RUC está vacío', 'danger', 10);
				return
			}
			this.$swal.fire({
				title: 'Buscando paciente',
				timer: 1000, //1 seg
				timerProgressBar: true,
				didOpen: () => {
					timerProgressBar: true,
					this.$swal.showLoading()
				},
			})
			
			let resp = await this.axios.get("/api/buscarPacienteSoloDNI/"+this.facturacion.ruc+'?token='+localStorage.getItem('token'))
			if(resp.data && resp.data !== ""){ //esta en la BD local
				this.facturacion.ruc = resp.data.dni.trim()
				this.facturacion.razonSocial = resp.data.name.trim() + ' '+ resp.data.nombres.trim()
				this.facturacion.direccion = resp.data.address?.address.trim().replace('null', '-')
			}else{ //Busca en la BD externa (RENIEC)
				let respReniec = await this.axios.get("/api/buscarDni/"+this.facturacion.ruc+'?token='+localStorage.getItem('token'))
				//console.log('se entrega', respReniec.status === 200,respReniec.data)
				if(respReniec.status === 200 && respReniec.data){
					this.facturacion.razonSocial = respReniec.data.apellido_paterno.trim() + ' ' + respReniec.data.apellido_materno.trim() + ' ' + respReniec.data.nombres.trim()
					this.facturacion.direccion = '-'
					return
				}else{
					this.facturacion.ruc = ''
					this.facturacion.razonSocial = ''
					this.facturacion.direccion = ''
					this.$swal.fire({
						title: 'DNI no ubicado',
						timer: 1000, //1 seg
					})
				}
			}
			this.facturacion.razonSocial = this.facturacion.razonSocial.replace(/\s+/g, ' ')
		},
		async buscarSunat(){
			if(this.facturacion.ruc.length < 11) {
				this.$swal.fire({
					icon: "error",
					title: 'RUC Inválido',
					timer: 1500, //1 seg
				})
				return false
			}
			this.$swal.fire({
				title: 'Buscando RUC en Sunat...',
				timer: 1000, //1 seg
				timerProgressBar: true,
				didOpen: () => {
					timerProgressBar: true,
					this.$swal.showLoading()
				},
			})
			
			let resp = await this.axios.get("/api/buscarRUC/"+this.facturacion.ruc )
			if(resp.status === 200 ){ //esta en la BD local
				let datos = resp.data
				this.facturacion.ruc = datos.ruc.trim()
				this.facturacion.razonSocial = datos.razonSocial.trim()
				this.facturacion.direccion = datos.direccion.trim()
				this.facturacion.razonSocial = this.facturacion.razonSocial.replace(/\s+/g, ' ')
				return

			}
			this.$swal.fire({
				title: 'RUC no ubicado',
				timer: 1000, //1 seg
			})
		},
		cambiarContado(){
			if(this.facturacion.tipoPago === 'Contado'){
				this.facturacion.montosFechas = []
				return
			}
			this.agregarMontoFecha()
		},
		imprimirTicket(){
			const url = `/api/pdfExtraCupon/${this.pago.id}?token=${localStorage.getItem('token')}`;
			window.open(url, '_blank');
		},
		soloNumeros(event) {
        let valor = event.target.value.replace(/[^0-9.]/g, '');
        
        // Un solo punto
        const puntos = valor.match(/\./g);
        if (puntos && puntos.length > 1) {
            valor = valor.substring(0, valor.lastIndexOf('.'));
        }
        
        event.target.value = valor;
        this.facturacion.monto = valor;
    },
		generarComprobante(){
			if( !this.validaciones() ) return
			let sede = this.idSede == 1 ? 'eltambo' : 'sancarlos' //1= el tambo, 2 = san carlos
			let url = null
			let empresa = null
			let cliente = null
			let jsonProductos = []
			
			if( process.env.NODE_ENV === 'production' ) url = 'https://apps.infocatsoluciones.com/excelentemente/'+sede+'/php/insertarBoleta_v4.php'
			else url = 'http://localhost/pluginSunat/php/insertarBoleta_v4.php'


			let comprobantes = { '01': 'FE01', '03': 'BE01', '0': '', '-1': '' }
			

			if(this.idSede == 1) {//El Tambo
				empresa = {
					ruc: '20601862329',
					crearArchivo: 0,
					facturador: "",
					sucursal: "001"
				}
				
			}
			if(this.idSede == 2) {//San carlos
				empresa = {
					ruc: '20614202476',
					crearArchivo: 0,
					facturador: "",
					sucursal: "001"
				}
			}

			cliente = {dni: this.facturacion.ruc, razon: this.facturacion.razonSocial, direccion:this.facturacion.direccion,
				contado: this.facturacion.tipoPago == 'Contado' ? 1 : 2,
				fechaCredito : this.facturacion.montosFechas,
				adelanto: 0,
				montoCredito: this.facturacion.montosFechas.reduce((acc, item) => acc+item.monto, 0 ),
				descuentos: 0,
				observaciones: this.facturacion.apoderado
			}
			
			jsonProductos.push({
				id: 1, //libre
				nombre: this.facturacion.conceptoPago,
				cantidad: 1, //es servicios
				unidad: 'Und.',
				unidadSunat: 'ZZ', //servicios
				precio: this.facturacion.monto,
				descuento: 0,
				afecto: '1', //gravado
				subTotal: this.facturacion.monto
			})
			
			let cabecera = { tipo: this.facturacion.tipoEmision, serie: comprobantes[this.facturacion.tipoEmision], fecha: this.hoy }

			this.$swal.fire({
				title: 'Enviando Comprobante Electrónico',
				timerProgressBar: true,
				didOpen: () => {
					timerProgressBar: true,
					this.$swal.showLoading()
				},
			})

			
			this.axios.post(url, { empresa, cliente, cabecera, jsonProductos })
			.then(resp => {
				console.log(resp.data)
				this.registrarSerie(resp.data.cliente.serie+'-'+resp.data.cliente.correlativo)
				this.$swal.close();
			})
			.catch(error=>{
				this.$swal.close()
				this.$swal.fire({
					icon: "error",
					title: 'Hubo un error al enviar el comprobante',
					timer: 1500, //1 seg
				})
			})

		},
		validaciones(){
			let valor = true

			//validaciones generales:
			if( this.facturacion.ruc == '' || this.facturacion.razonSocial == '' ){
				alertify.notify('<i class="fa-solid fa-circle-check"></i> ' + 'Falta rellenar datos: DNI, Razón Social', 'danger', 10);
				valor = false
			}
			if( this.facturacion.monto <= 0 ){
				alertify.notify('<i class="fa-solid fa-circle-check"></i> ' + 'Monto incorrecto', 'danger', 10);
				valor = false
			}
			if( this.facturacion.conceptoPago == '' ){
				alertify.notify('<i class="fa-solid fa-circle-check"></i> ' + 'Se debe rellenar un concepto', 'danger', 10);
				valor = false
			}

			if( this.facturacion.tipoEmision == '01' ){ //facturas

			}
			if( this.facturacion.tipoEmision == '03' ){ //boletas
				//if( (this.facturacion.tipoDocumento == 1 || this.facturacion.tipoDocumento == 6) )
			

					//

			}
			return valor
		},
		registrarSerie(serie){
			//registrar en pagos de BD consultorio
			this.pago.voucher = serie
			this.axios.patch('/api/paymentExtra/'+this.pago.id, {voucher: this.pago.voucher})
			alertify.notify('<i class="fa-solid fa-circle-check"></i> ' + 'Comprobante registrado exitosamente', 'success', 10);
		},
		async imprimirComprobante(){
			let sede = this.idSede == 1 ? 'eltambo' : 'sancarlos'
			let url = null
			if( process.env.NODE_ENV === 'production' ){
				url = 'https://apps.infocatsoluciones.com/excelentemente/'+sede+'/php/apiComprobantePorCorrelativo.php'}
			else url = 'http://127.0.0.1/pluginSunat/php/apiComprobantePorCorrelativo.php'

			
			let resp = await this.axios.post( url , {correlativo:this.pago.voucher })
			let datos = await resp.data
			console.log(datos)

			let cuerpo = {
				// Datos del comprobante
				rucEmisor: datos.empresa.ruc,
				tipoComprobante: `0${datos.cabecera.factTipoDocumento}`,           // 01=Factura, 03=Boleta, etc.
				serie: datos.cabecera.factSerie,
				correlativo: datos.cabecera.factCorrelativo,
				totalFinal: datos.cabecera.totalFinal,
				fecha: datos.cabecera.fechaEmision,
				fechaLat: moment(datos.cabecera.fechaEmision).format('DD/MM/YYYY'),          // Fecha en formato latino
				tipoCliente: datos.cabecera.factTipoDocumento,                // 6=RUC, 1=DNI, etc.
				docClient: datos.cabecera.dniRUC,
				cliente: datos.cabecera.razonSocial,
				direccion: '-',
				esServicio: 1,                   // 1=Servicio, 0=Producto (cambia el header)
				
				// Tipo de documento
				queEs: datos.cabecera.queDoc,
				ticketera: 'CAJA',
				
				// Como es 1 solo servicio, solo abregamos 1 opción
				productos: [
					{
						descripcion: datos.detalles[0].descripcionItem.replace(/\|/g, ' '),
						cantidad: 1, //porque es servicio
						undCorto: datos.detalles[0].codUnidadMedida,
						preProducto: datos.detalles[0].mtoPrecioVenta, // Precio unitario
						precio: datos.detalles[0].mtoPrecioVenta, // Subtotal (cantidad * precio unitario)
					},
					/* {
						descipcion: 'Producto B',
						cantidad: 1,
						undCorto: 'UND',
						preProducto: 18.00,
						precio: 18.00
					} */
				],
				
				exonerado: '0.00',
				igvFinal: datos.cabecera.IGVFinal,
				costoFinal: datos.cabecera.costoFinal,            // Subtotal sin IGV
				descuento: 0,                    // Descuento global (opcional)
				monedas: datos.cabecera.desLeyenda
			}

			fetch('http://127.0.0.1/pluginSunat/printComprobante.php',{
				method:'POST', headers: { 'Content-Type': 'application/json'},
				body: JSON.stringify(cuerpo)
			})
			.then(response =>{
				console.log('Impresión exitosa:', response.text());
			})
			.catch(error => {
				console.error('Error en impresión:', error);
			});
		},
		irPanelBaja(){
			let sede = this.idSede == 1 ? 'eltambo' : 'sancarlos'
			window.open('https://apps.infocatsoluciones.com/excelentemente/'+sede+'/php/accesoFast.php?token='+process.env.FACTURACION_TOKEN, '_blank');
		},
		cambiarEmision(){
			if( this.facturacion.tipoEmision == '03'){ //boletas, seteamos al contado
				this.facturacion.tipoPago = 'Contado'
			}

		}
	},
	watch:{
		'pago': function(newVal){
			if(newVal){
				if(newVal.voucher) return;
				this.facturacion.ruc = newVal.dniCliente
				this.buscarReniec()
				if( parseInt(newVal.idMembresia) >0 )
					this.facturacion.conceptoPago = 'Pago de membresía'
				else{
					const servicio = {"0":"Certificado","1":"Paquete Membresía","2":"Paquete Kurame","3":"Informe","4":"Otros","5":"Pago de cita","7":"Pago de membresía","8":"Adelanto de cita","15":"Pago de membresía","16":"Revaluación gratuita"}
					this.facturacion.conceptoPago = servicio[newVal.type]+' '+ newVal.detalle.replace(/\//g, '-');

				}
				this.facturacion.monto = newVal.price
			}
		}
	}
}
</script>
<style>
.impresionComprobante{
	cursor:pointer;
}
.form-select{
	color: #6e707e;
}
</style>